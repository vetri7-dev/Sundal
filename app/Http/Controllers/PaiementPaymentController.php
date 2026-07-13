<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class PaiementPaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $validated = validatePaymentRequest($request, [
            'transaction_id' => 'required|string',
            'status' => 'required|string',
        ]);

        try {
            $plan = Plan::findOrFail($validated['plan_id']);
            $settings = getPaymentGatewaySettings();
            
            if (!isset($settings['payment_settings']['paiement_merchant_id'])) {
                return back()->withErrors(['error' => __('Paiement Pro not configured')]);
            }

            if ($validated['status'] === 'success') {
                processPaymentSuccess([
                    'user_id' => auth()->id(),
                    'plan_id' => $plan->id,
                    'billing_cycle' => $validated['billing_cycle'],
                    'payment_method' => 'paiement',
                    'coupon_code' => $validated['coupon_code'] ?? null,
                    'payment_id' => $validated['transaction_id'],
                ]);

                return back()->with('success', __('Payment successful and plan activated'));
            }

            return back()->withErrors(['error' => __('Payment failed or cancelled')]);

        } catch (\Exception $e) {
            return handlePaymentError($e, 'paiement');
        }
    }

    public function createPayment(Request $request)
    {
        $validated = validatePaymentRequest($request, [
            'mobile_number' => 'required|string',
            'channel' => 'required|string|in:ALL,MOBILE_MONEY,CARD,BANK_TRANSFER',
        ]);
        
        try {
            $plan = Plan::findOrFail($validated['plan_id']);
            $pricing = calculatePlanPricing($plan, $validated['coupon_code'] ?? null, $validated['billing_cycle'] ?? 'monthly');
            $settings = getPaymentGatewaySettings();
            
            if (!isset($settings['payment_settings']['paiement_merchant_id'])) {
                return response()->json(['error' => __('Paiement Pro not configured')], 400);
            }

            $user = auth()->user();
            $referenceNumber = "REF-" . time();

            $data = [
                'merchantId' => $settings['payment_settings']['paiement_merchant_id'],
                'amount' => $pricing['final_price'],
                'description' => "Api PHP",
                'channel' => $validated['channel'],
                'countryCurrencyCode' => $settings['payment_settings']['currency_code'] ?? 'USD',
                'referenceNumber' => $referenceNumber,
                'customerEmail' => $user->email,
                'customerFirstName' => $user->name,
                'customerLastname' => $user->name,
                'customerPhoneNumber' => $validated['mobile_number'],
                'notificationURL' => route('paiement.callback'),
                'returnURL' => route('paiement.success'),
                'returnContext' => json_encode([
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'billing_cycle' => $validated['billing_cycle'] ?? 'monthly',
                    'coupon_code' => $validated['coupon_code'] ?? null,
                    'mobile_number' => $validated['mobile_number'],
                    'channel' => $validated['channel'],
                ]),
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.paiementpro.net/webservice/onlinepayment/init/curl-init.php");
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);



            $response = json_decode($response);
            
            if (isset($response->success) && $response->success == true) {
                return response()->json([
                    'success' => true,
                    'payment_url' => $response->url,
                    'payment_data' => [],
                    'reference_number' => $referenceNumber
                ]);
            } else {
                return response()->json(['error' => $response->message ?? __('Payment creation failed')], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => __('Payment creation failed')], 500);
        }
    }

    public function success(Request $request)
    {
        return redirect()->route('plans.index')->with('success', __('Payment completed successfully'));
    }

    public function callback(Request $request)
    {
        try {
            if ($request->responsecode == 0) {
                $returnContext = json_decode($request->returnContext, true);
                
                if ($returnContext && isset($returnContext['plan_id'], $returnContext['user_id'])) {
                    $plan = Plan::find($returnContext['plan_id']);
                    $user = User::find($returnContext['user_id']);
                    
                    if ($plan && $user) {
                        processPaymentSuccess([
                            'user_id' => $user->id,
                            'plan_id' => $plan->id,
                            'billing_cycle' => $returnContext['billing_cycle'] ?? 'monthly',
                            'payment_method' => 'paiement',
                            'coupon_code' => $returnContext['coupon_code'] ?? null,
                            'payment_id' => $request->referenceNumber ?? $request->transactionId,
                        ]);
                    }
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Callback processing failed'], 500);
        }
    }
}