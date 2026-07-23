<?php

namespace App\Services;

// Google Calendar integration is currently disabled.
// To re-enable: composer require google/apiclient and restore the full implementation.

class GoogleCalendarService
{
    public function __construct() {}

    public function isEnabled($userId, $workspaceId = null): bool
    {
        return false;
    }

    public function isAuthorized($userId, $workspaceId = null): bool
    {
        return false;
    }

    public function createEvent($item, $userId, $workspaceId = null): ?string
    {
        return null;
    }

    public function updateEvent($eventId, $item, $userId, $workspaceId = null): bool
    {
        return false;
    }

    public function deleteEvent($eventId, $userId, $workspaceId = null): bool
    {
        return false;
    }

    public function getEvents($userId, $maxResults = 100, $workspaceId = null): array
    {
        return [];
    }

    public function createMeetingEvent($meeting, $userId, $workspaceId = null): ?string
    {
        return null;
    }

    public function updateMeetingEvent($eventId, $meeting, $userId, $workspaceId = null): bool
    {
        return false;
    }

    public function createGoogleMeetingEvent($meeting, $userId, $workspaceId = null): ?string
    {
        return null;
    }
}
