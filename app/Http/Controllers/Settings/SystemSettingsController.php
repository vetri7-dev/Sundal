<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\StorageConfigService;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends Controller
{
    /**
     * Update the system settings.
     *
     * Handles system-wide configuration including:
     * - Language and localization settings
     * - Date/time formats and timezone
     * - Email verification requirements
     * - Landing page enable/disable toggle
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'defaultLanguage' => 'required|string',
                'dateFormat' => 'required|string',
                'timeFormat' => 'required|string',
                'calendarStartDay' => 'required|string',
                'defaultTimezone' => 'required|string',
                'emailVerification' => 'boolean',
                'landingPageEnabled' => 'boolean',
            ]);

            $user = auth()->user();
            $workspaceId = null;
            
            if ($user->type === 'company') {
                $workspaceId = $user->current_workspace_id;
            }   
            
            foreach ($validated as $key => $value) {
                $convertedValue = is_bool($value) ? ($value ? '1' : '0') : $value;
                updateSetting($key, $convertedValue, $user->id, $workspaceId);
            }

            return redirect()->back()->with('success', __('System settings updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to update system settings: :error', ['error' => $e->getMessage()]));
        }
    }
    
    /**
     * Update the brand settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBrand(Request $request)
    {
        try {
            $validated = $request->validate([
                'settings' => 'required|array',
                'settings.logoDark' => 'nullable|string',
                'settings.logoLight' => 'nullable|string',
                'settings.favicon' => 'nullable|string',
                'settings.titleText' => 'nullable|string|max:255',
                'settings.footerText' => 'nullable|string|max:500',
                'settings.themeColor' => 'nullable|string|in:blue,green,purple,orange,red,custom',
                'settings.customColor' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'settings.sidebarVariant' => 'nullable|string|in:inset,floating,minimal',
                'settings.sidebarStyle' => 'nullable|string|in:plain,colored,gradient',
                'settings.layoutDirection' => 'nullable|string|in:left,right',
                'settings.themeMode' => 'nullable|string|in:light,dark,system',
            ]);

            // Check if application is in demo mode
            if (config('app.is_demo')) {
                // Demo mode: store ONLY theme settings in cookies (exclude logos and text)
                $themeOnlySettings = [
                    'themeColor' => $validated['settings']['themeColor'] ?? 'green',
                    'customColor' => $validated['settings']['customColor'] ?? '#10b981',
                    'sidebarVariant' => $validated['settings']['sidebarVariant'] ?? 'inset',
                    'sidebarStyle' => $validated['settings']['sidebarStyle'] ?? 'plain',
                    'layoutDirection' => $validated['settings']['layoutDirection'] ?? 'left',
                    'themeMode' => $validated['settings']['themeMode'] ?? 'light',
                ];
                // Store as single cookie that frontend expects
                cookie()->queue(cookie('brandSettings', json_encode($themeOnlySettings), 60 * 24 * 30)); // 30 days
                
                return redirect()->back()->with('success', __('Theme settings saved successfully!'));
            } else {
                // Normal mode: store ONLY in database (no cookies)
                $user = auth()->user();
                $workspaceId = null;
                
                if ($user->type === 'company') {
                    $workspaceId = $user->current_workspace_id;
                }
                
                foreach ($validated['settings'] as $key => $value) {
                    updateSetting($key, $value, $user->id, $workspaceId);
                }
                return redirect()->back()->with('success', __('Brand settings updated successfully.'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to update brand settings: :error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Update the recaptcha settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRecaptcha(Request $request)
    {
        try {
            $validated = $request->validate([
                'recaptchaEnabled' => 'boolean',
                'recaptchaVersion' => 'required|in:v2,v3',
                'recaptchaSiteKey' => 'required|string',
                'recaptchaSecretKey' => 'required|string',
            ]);
            
            $user = auth()->user();
            $workspaceId = null;
            
            if ($user->type === 'company') {
                $workspaceId = $user->current_workspace_id;
            }
            
            foreach ($validated as $key => $value) {
                $convertedValue = is_bool($value) ? ($value ? '1' : '0') : $value;
                updateSetting($key, $convertedValue, $user->id, $workspaceId, isSaasMode() ? false: true);
            }

            return redirect()->back()->with('success', __('ReCaptcha settings updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to update ReCaptcha settings: :error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Update the chatgpt settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateChatgpt(Request $request)
    {
        try {
            $validated = $request->validate([
                'chatgptKey' => 'required|string',
                'chatgptModel' => 'required|string',
            ]);
            
            $user = auth()->user();
            $workspaceId = null;
            
            if ($user->type === 'company') {
                $workspaceId = $user->current_workspace_id;
            }
            
            foreach ($validated as $key => $value) {
                updateSetting($key, $value, $user->id, $workspaceId);
            }

            return redirect()->back()->with('success', __('Chat GPT settings updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to update Chat GPT settings: :error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Update the storage settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStorage(Request $request)
    {
        try {
            $validated = $request->validate([
                'storage_type' => 'required|in:local,aws_s3,wasabi',
                'allowedFileTypes' => 'required|string',
                'maxUploadSize' => 'required|numeric|min:1',
                'awsAccessKeyId' => 'required_if:storage_type,aws_s3|string',
                'awsSecretAccessKey' => 'required_if:storage_type,aws_s3|string',
                'awsDefaultRegion' => 'required_if:storage_type,aws_s3|string',
                'awsBucket' => 'required_if:storage_type,aws_s3|string',
                'awsUrl' => 'required_if:storage_type,aws_s3|string',
                'awsEndpoint' => 'required_if:storage_type,aws_s3|string',
                'wasabiAccessKey' => 'required_if:storage_type,wasabi|string',
                'wasabiSecretKey' => 'required_if:storage_type,wasabi|string',
                'wasabiRegion' => 'required_if:storage_type,wasabi|string',
                'wasabiBucket' => 'required_if:storage_type,wasabi|string',
                'wasabiUrl' => 'required_if:storage_type,wasabi|string',
                'wasabiRoot' => 'required_if:storage_type,wasabi|string',
            ]);

            $settings = [
                'storage_type' => $validated['storage_type'],
                'storage_file_types' => $validated['allowedFileTypes'],
                'storage_max_upload_size' => $validated['maxUploadSize'],
            ];

            if ($validated['storage_type'] === 'aws_s3') {
                $settings['aws_access_key_id'] = $validated['awsAccessKeyId'];
                $settings['aws_secret_access_key'] = $validated['awsSecretAccessKey'];
                $settings['aws_default_region'] = $validated['awsDefaultRegion'];
                $settings['aws_bucket'] = $validated['awsBucket'];
                $settings['aws_url'] = $validated['awsUrl'];
                $settings['aws_endpoint'] = $validated['awsEndpoint'];
            }

            if ($validated['storage_type'] === 'wasabi') {
                $settings['wasabi_access_key'] = $validated['wasabiAccessKey'];
                $settings['wasabi_secret_key'] = $validated['wasabiSecretKey'];
                $settings['wasabi_region'] = $validated['wasabiRegion'];
                $settings['wasabi_bucket'] = $validated['wasabiBucket'];
                $settings['wasabi_url'] = $validated['wasabiUrl'];
                $settings['wasabi_root'] = $validated['wasabiRoot'];
            }
            
            $user = auth()->user();
            $workspaceId = null;
            
            if ($user->type === 'company') {
                $workspaceId = $user->current_workspace_id;
            }
            
            foreach ($settings as $key => $value) {
                updateSetting($key, $value, $user->id, $workspaceId);
            }

            StorageConfigService::clearCache();

            return redirect()->back()->with('success', __('Storage settings updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to update storage settings: :error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Update the cookie settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCookie(Request $request)
    {
        try {
            $validated = $request->validate([
                'enableLogging' => 'required|boolean',
                'strictlyNecessaryCookies' => 'required|boolean',
                'cookieTitle' => 'required|string|max:255',
                'strictlyCookieTitle' => 'required|string|max:255',
                'cookieDescription' => 'required|string',
                'strictlyCookieDescription' => 'required|string',
                'contactUsDescription' => 'required|string',
                'contactUsUrl' => 'required|url',
            ]);
            
            $user = auth()->user();
            $workspaceId = null;
            
            if ($user->type === 'company') {
                $workspaceId = $user->current_workspace_id;
            }
            
            foreach ($validated as $key => $value) {
                updateSetting($key, is_bool($value) ? ($value ? '1' : '0') : $value, $user->id, $workspaceId, isSaasMode() ? false: true);
            }

            return redirect()->back()->with('success', __('Cookie settings updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to update cookie settings: :error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Store cookie consent data as CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCookieConsent(Request $request)
    {
        try {
            $validated = $request->validate([
                'ip' => 'required|string',
                'date' => 'required|string',
                'time' => 'required|string',
                'acceptedCookies' => 'required|string',
                'deviceType' => 'required|string',
                'browserLanguage' => 'required|string',
                'browserName' => 'required|string',
                'osName' => 'required|string',
                'country' => 'required|string',
                'region' => 'required|string',
                'regionName' => 'required|string',
                'city' => 'required|string',
                'zipcode' => 'required|string',
                'lat' => 'required|string',
                'lon' => 'required|string',
            ]);

            $csvData = [
                $validated['ip'],
                $validated['date'],
                $validated['time'],
                $validated['acceptedCookies'],
                $validated['deviceType'],
                $validated['browserLanguage'],
                $validated['browserName'],
                $validated['osName'],
                $validated['country'],
                $validated['region'],
                $validated['regionName'],
                $validated['city'],
                $validated['zipcode'],
                $validated['lat'],
                $validated['lon']
            ];

            $filename = 'cookie-consents-' . date('Y-m-d') . '.csv';
            $filepath = storage_path('app/' . $filename);

            $fileExists = file_exists($filepath);
            $file = fopen($filepath, 'a');

            if (!$fileExists) {
                fputcsv($file, ['IP', 'Date', 'Time', 'Accepted-cookies', 'Device type', 'Browser language', 'Browser name', 'OS Name', 'Country', 'Region', 'RegionName', 'City', 'Zipcode', 'Lat', 'Lon']);
            }

            fputcsv($file, $csvData);
            fclose($file);

            return response()->json(['success' => true, 'message' => 'Cookie consent stored successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to store cookie consent: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the SEO settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSeo(Request $request)
    {
        try {
            $validated = $request->validate([
                'metaKeywords' => 'required|string|max:255',
                'metaDescription' => 'required|string|max:160',
                'metaImage' => 'required|string',
            ]);
            
            $user = auth()->user();
            $workspaceId = null;
            
            if ($user->type === 'company') {
                $workspaceId = $user->current_workspace_id;
            }
            
            foreach ($validated as $key => $value) {
                updateSetting($key, $value, $user->id, $workspaceId, isSaasMode() ? false: true);
            }

            return redirect()->back()->with('success', __('SEO settings updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to update SEO settings: :error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Clear application cache.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            \Artisan::call('optimize:clear');

            return redirect()->back()->with('success', __('Cache cleared successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to clear cache: :error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Get email notification settings.
     */
    public function getEmailNotifications()
    {
        $userId = auth()->id();
        $templates = \App\Models\EmailTemplate::select('id', 'name')->get();
        $settings = [];

        foreach ($templates as $template) {
            $userTemplate = \App\Models\UserEmailTemplate::where('user_id', $userId)
                ->where('template_id', $template->id)
                ->first();

            $settings[$template->name] = $userTemplate ? $userTemplate->is_active : false;
        }

        return response()->json($settings);
    }

    /**
     * Get available email notifications.
     */
    public function getAvailableEmailNotifications()
    {
        $templates = \App\Models\EmailTemplate::select('id', 'name')->get();
        $notifications = [];

        foreach ($templates as $template) {
            $notifications[] = [
                'name' => $template->name,
                'label' => str_replace(' ', ' ', $template->name)
            ];
        }

        return response()->json($notifications);
    }

    /**
     * Update email notification settings.
     */
    public function updateEmailNotifications(Request $request)
    {
        try {
            $userId = auth()->id();
            $availableTemplates = \App\Models\EmailTemplate::pluck('name', 'id')->toArray();

            $rules = [];
            foreach ($availableTemplates as $templateId => $templateName) {
                $rules[$templateName] = 'boolean';
            }

            $validated = $request->validate($rules);

            foreach ($availableTemplates as $templateId => $templateName) {
                if (isset($validated[$templateName])) {
                    \App\Models\UserEmailTemplate::updateOrCreate(
                        ['user_id' => $userId, 'template_id' => $templateId],
                        ['is_active' => $validated[$templateName]]
                    );
                }
            }

            return redirect()->back()->with('success', __('Email notification settings updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to update email notification settings: :error', ['error' => $e->getMessage()]));
        }
    }
}   