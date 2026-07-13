<?php

namespace App\Services;

use App\Models\TelegramSetting;

class TelegramService
{
    public static function send($templateName, $data = [], $userId = null, $workspaceId = null)
    {        
        $userId = $userId ?: auth()->id();
        $workspaceId = $workspaceId ?: auth()->user()->current_workspace_id;
                
        $telegramSettings = TelegramSetting::getUserSettings($userId, $workspaceId);
        
        // Check if Telegram integration is enabled
        if (!($telegramSettings['telegram_enabled'] ?? false)) {
            return false;
        }
                
        $templateKey = strtolower(str_replace(' ', '_', $templateName));
        
        $notifications = $telegramSettings['telegram_notifications'];
        if (is_string($notifications)) {
            $notifications = json_decode($notifications, true);
        }
        
        if (!($notifications[$templateKey] ?? false)) {
            return false;
        }

        $message = self::formatMessage($templateName, $data);

        $payload = [
            'chat_id' => $telegramSettings['telegram_chat_id'],
            'text' => $message,
            'parse_mode' => 'Markdown'
        ];
        
        // dd('Sending Telegram notification via cURL', [
        //     'bot_token' => $telegramSettings['telegram_bot_token'],
        //     'payload' => $payload
        // ]);
        
        $jsonPayload = json_encode($payload);
        $ch = curl_init("https://api.telegram.org/bot{$telegramSettings['telegram_bot_token']}/sendMessage");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonPayload)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        return $httpCode === 200 && empty($curlError);
    }

    private static function formatMessage($templateName, $data)
    {
        $title = $data['title'] ?? ucfirst(str_replace('_', ' ', $templateName));
        $message = $data['message'] ?? 'New notification from Taskly';
        
        $formatted = "*{$title}*\n\n{$message}";
        
        if (isset($data['url'])) {
            $formatted .= "\n\n[View Details]({$data['url']})";
        }
        
        return $formatted;
    }

}