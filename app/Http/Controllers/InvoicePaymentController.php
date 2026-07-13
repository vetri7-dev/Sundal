<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoicePaymentController extends Controller
{
    public function show($token, Request $request)
    {
        $invoice = Invoice::where('payment_token', $token)
            ->with(['client', 'project', 'creator', 'workspace', 'items'])
            ->firstOrFail();

        // Get company information
        $company = \App\Models\User::where('id', $invoice->created_by)
            ->where('type', 'company')
            ->select('id', 'name')
            ->first();
            
        // Get favicon and app name from settings table
        $settings = \App\Models\Setting::where('user_id', $invoice->created_by)
            ->whereIn('key', ['favicon', 'app_name'])
            ->pluck('value', 'key')
            ->toArray();
            
        $favicon = $settings['favicon'] ?? null;
        $appName = $settings['app_name'] ?? 'Taskly SaaS';
        $enabledGateways = $this->getEnabledPaymentGateways($invoice->created_by, $invoice->workspace_id);

        return Inertia::render('invoice/payment', [
            'invoice' => $invoice,
            'enabledGateways' => $enabledGateways,
            'remainingAmount' => $invoice->remaining_amount,
            'company' => $company,
            'favicon' => $favicon,
            'appName' => $appName
        ]);
    }

    public function processPayment(Request $request, $token)
    {
        $invoice = Invoice::where('payment_token', $token)->firstOrFail();
        $maxAmount = $invoice->remaining_amount ?: $invoice->total_amount;
        
        $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0.01|max:' . $maxAmount
        ]);
        
        if ($request->amount > $maxAmount) {
            return back()->withErrors(['amount' => 'Payment amount cannot exceed remaining balance of ' . $maxAmount]);
        }

        $request->merge([
            'invoice_id' => $invoice->id,
            'invoice_token' => $token,
            'type' => 'invoice'
        ]);

        $paymentMethod = $request->payment_method;
        $controllerMap = [
            'bank' => '\App\Http\Controllers\BankPaymentController',
            'stripe' => '\App\Http\Controllers\StripePaymentController',
            'paypal' => '\App\Http\Controllers\PayPalPaymentController',
            'razorpay' => '\App\Http\Controllers\RazorpayController',
            'mercadopago' => '\App\Http\Controllers\MercadoPagoController',
            'paystack' => '\App\Http\Controllers\PaystackPaymentController',
            'flutterwave' => '\App\Http\Controllers\FlutterwavePaymentController',
            'paytabs' => '\App\Http\Controllers\PayTabsPaymentController',
            'skrill' => '\App\Http\Controllers\SkrillPaymentController',
            'coingate' => '\App\Http\Controllers\CoinGatePaymentController',
            'payfast' => '\App\Http\Controllers\PayfastPaymentController',
            'tap' => '\App\Http\Controllers\TapPaymentController',
            'xendit' => '\App\Http\Controllers\XenditPaymentController',
            'paytr' => '\App\Http\Controllers\PayTRPaymentController',
            'mollie' => '\App\Http\Controllers\MolliePaymentController',
            'toyyibpay' => '\App\Http\Controllers\ToyyibPayPaymentController',
            'cashfree' => '\App\Http\Controllers\CashfreeController',
            'khalti' => '\App\Http\Controllers\KhaltiPaymentController',
            'iyzipay' => '\App\Http\Controllers\IyzipayPaymentController',
            'benefit' => '\App\Http\Controllers\BenefitPaymentController',
            'ozow' => '\App\Http\Controllers\OzowPaymentController',
            'easebuzz' => '\App\Http\Controllers\EasebuzzPaymentController',
            'authorizenet' => '\App\Http\Controllers\AuthorizeNetPaymentController',
            'fedapay' => '\App\Http\Controllers\FedaPayPaymentController',
            'payhere' => '\App\Http\Controllers\PayHerePaymentController',
            'cinetpay' => '\App\Http\Controllers\CinetPayPaymentController',
            'paiement' => '\App\Http\Controllers\PaiementPaymentController',
            'yookassa' => '\App\Http\Controllers\YooKassaPaymentController',
            'aamarpay' => '\App\Http\Controllers\AamarpayPaymentController',
            'midtrans' => '\App\Http\Controllers\MidtransPaymentController',
            'paymentwall' => '\App\Http\Controllers\PaymentWallPaymentController',
            'sspay' => '\App\Http\Controllers\SSPayPaymentController'
        ];

        if (!isset($controllerMap[$paymentMethod])) {
            return back()->withErrors(['error' => 'Payment method not supported']);
        }

        try {
            $controller = app($controllerMap[$paymentMethod]);
            return $controller->processInvoicePayment($request, $invoice);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->withErrors(['error' => __('Invoice not found. Please check the link and try again.')]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('Payment processing failed. Please try again or contact support.')]);
        }
    }

    public function success($token)
    {
        $invoice = Invoice::where('payment_token', $token)
            ->with(['client', 'payments'])
            ->firstOrFail();

        // Get company information
        $company = \App\Models\User::where('id', $invoice->created_by)
            ->where('type', 'company')
            ->select('id', 'name')
            ->first();
            
        // Get favicon and app name from settings table
        $settings = \App\Models\Setting::where('user_id', $invoice->created_by)
            ->whereIn('key', ['favicon', 'app_name'])
            ->pluck('value', 'key')
            ->toArray();
            
        $favicon = $settings['favicon'] ?? null;
        $appName = $settings['app_name'] ?? 'Taskly SaaS';

        return Inertia::render('invoice/payment-success', [
            'invoice' => $invoice,
            'message' => session('success'),
            'company' => $company,
            'favicon' => $favicon,
            'appName' => $appName
        ]);
    }

    public function getEnabledPaymentGateways($userId = null, $workspaceId = null)
    {
        if (!$userId) {
            return [];
        }

        // Get company-specific payment settings only
        $settings = PaymentSetting::where('user_id', $userId)->pluck('value', 'key')->toArray();

        $gateways = [];
        $paymentGateways = [
            'bank' => ['name' => 'Bank Transfer', 'icon' => '🏦'],
            'stripe' => ['name' => 'Credit Card (Stripe)', 'icon' => '💳'],
            'paypal' => ['name' => 'PayPal', 'icon' => '🅿️'],
            'razorpay' => ['name' => 'Razorpay', 'icon' => '💰'],
            'mercadopago' => ['name' => 'Mercado Pago', 'icon' => '💳'],
            'paystack' => ['name' => 'Paystack', 'icon' => '🅿️'],
            'flutterwave' => ['name' => 'Flutterwave', 'icon' => '💳'],
            'paytabs' => ['name' => 'Paytabs', 'icon' => '🅿️'],
            'skrill' => ['name' => 'Skrill', 'icon' => '💳'],
            'coingate' => ['name' => 'Coin Gate', 'icon' => '💳'],
            'payfast' => ['name' => 'Pay Fast', 'icon' => '🅿️'],
            'tap' => ['name' => 'Tap', 'icon' => '💳'],
            'xendit' => ['name' => 'Xendit', 'icon' => '💳'],
            'paytr' => ['name' => 'PayTR', 'icon' => '🅿️'],
            'mollie' => ['name' => 'Mollie', 'icon' => '💳'],
            'toyyibpay' => ['name' => 'Toyyib Pay', 'icon' => '💳'],
            'cashfree' => ['name' => 'Cashfree', 'icon' => '💳'],
            'khalti' => ['name' => 'Khalti', 'icon' => '💳'],
            'iyzipay' => ['name' => 'Iyzipay', 'icon' => '💳'],
            'benefit' => ['name' => 'Benefit', 'icon' => '💳'],
            'ozow' => ['name' => 'Ozow', 'icon' => '💳'],
            'easebuzz' => ['name' => 'Easebuzz', 'icon' => '💳'],
            'authorizenet' => ['name' => 'Authorize.net', 'icon' => '💳'],
            'fedapay' => ['name' => 'Fedapay', 'icon' => '💳'],
            'payhere' => ['name' => 'Pay Here', 'icon' => '🅿️'],
            'cinetpay' => ['name' => 'Cinet Pay', 'icon' => '💳'],
            'paiement' => ['name' => 'Paiement Pro', 'icon' => '🅿️'],
            'yookassa' => ['name' => 'Yoo Kassa', 'icon' => '💳'],
            'aamarpay' => ['name' => 'Aamar Pay', 'icon' => '💳'],
            'midtrans' => ['name' => 'Midtrans', 'icon' => '💳'],
            'paymentwall' => ['name' => 'Payment Wall', 'icon' => '🅿️'],
            'sspay' => ['name' => 'SS Pay', 'icon' => '💳']
        ];

        foreach ($paymentGateways as $key => $config) {
            $enabledKey = "is_{$key}_enabled";
            if (($settings[$enabledKey] ?? '0') === '1') {
                $gateways[] = [
                    'id' => $key,
                    'name' => $config['name'],
                    'icon' => $config['icon']
                ];
            }
        }

        return $gateways;
    }

    public function getPaymentMethods(Invoice $invoice)
    {
        $gateways = $this->getEnabledPaymentGateways($invoice->created_by, $invoice->workspace_id);
        
        // Get payment settings for credentials
        $paymentSettings = PaymentSetting::where('user_id', $invoice->created_by)
            ->pluck('value', 'key')
            ->toArray();
            
        // Add credentials to response
        $response = [
            'gateways' => $gateways,
            'paypalClientId' => $paymentSettings['paypal_client_id'] ?? null,
            'stripeKey' => $paymentSettings['stripe_key'] ?? null,
            'razorpayKey' => $paymentSettings['razorpay_key'] ?? null,
            'mercadopagoAccessToken' => $paymentSettings['mercadopago_access_token'] ?? null,
            'paystackPublicKey' => $paymentSettings['paystack_public_key'] ?? null,
            'flutterwavePublicKey' => $paymentSettings['flutterwave_public_key'] ?? null,
            'tapPublicKey' => $paymentSettings['tap_secret_key'] ?? null,
            'xenditApiKey' => $paymentSettings['xendit_api_key'] ?? null,
            'paytrMerchantId' => $paymentSettings['paytr_merchant_id'] ?? null,
            'mollieApiKey' => $paymentSettings['mollie_api_key'] ?? null,
            'toyyibpayCategoryCode' => $paymentSettings['toyyibpay_category_code'] ?? null,
        ];
        return response()->json($response);
    }
}