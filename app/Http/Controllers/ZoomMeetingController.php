<?php

namespace App\Http\Controllers;

use App\Models\ZoomMeeting;
use App\Models\Project;
use App\Models\User;
use App\Models\Setting;
use App\Services\ZoomService;
use App\Traits\HasPermissionChecks;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;


class ZoomMeetingController extends Controller
{
    use HasPermissionChecks;

    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    public function index(Request $request)
    {
        $this->authorizePermission('zoom_meeting_view_any');

        $user = auth()->user();
        $workspace = $user->currentWorkspace;

        if (!$workspace) {
            abort(404, __('No workspace found. Please select a workspace.'));
        }

        $query = ZoomMeeting::with(['project', 'creator', 'workspace', 'members'])
            ->forWorkspace($workspace->id);
            
        $userWorkspaceRole = $workspace->getMemberRole($user);
        
        // Access control based on workspace role - same as ProjectController
        if ($userWorkspaceRole !== 'owner') {
            // Non-owners: Only assigned meetings
            $query->whereHas('members', function($memberQuery) use ($user) {
                $memberQuery->where('user_id', $user->id);
            });
        }
        // Filter by search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by project
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $perPage = in_array($request->get('per_page', 12), [12, 24, 48]) ? $request->get('per_page', 12) : 12;
        $meetings = $query->orderBy('start_time', 'desc')->paginate($perPage);



        // Get projects for filters and form
        $projects = Project::forWorkspace($workspace->id)->get(['id', 'title']);

        // Get workspace members for meeting assignment
        $members = User::whereHas('workspaces', function ($q) use ($workspace) {
            $q->where('workspace_id', $workspace->id)
              ->where('status', 'active');
        })->get(['id', 'name', 'email']);

        // Check if Zoom is configured (now works for all users by checking workspace-level settings)
        $hasZoomConfig = $this->zoomService->hasValidCredentials();
        return Inertia::render('zoom-meetings/Index', [
            'meetings' => $meetings,

            'projects' => $projects,
            'members' => $members,
            'hasZoomConfig' => $hasZoomConfig,
            'filters' => $request->only(['search', 'status', 'project_id']),
            'permissions' => $this->getModuleCrudPermissions('zoom_meeting')
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizePermission('zoom_meeting_create');

        $user = auth()->user();
        $workspace = $user->currentWorkspace;

        if (!$workspace) {
            return back()->withErrors(['error' => __('No workspace found. Please select a workspace.')]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:15|max:480',
            'timezone' => 'required|string',
            'password' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
        ]);

        $startTime = Carbon::parse($request->start_time);

        $meetingData = [
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $startTime->toISOString(),
            'duration' => $request->duration,
            'timezone' => $request->timezone,
            'password' => $request->password,
        ];

        try {
            // Check if Zoom credentials are configured
            $hasCredentials = $this->zoomService->hasValidCredentials();
            
            if ($hasCredentials) {
                // Create meeting via Zoom API
                $zoomResponse = $this->zoomService->createMeeting($meetingData);

                if ($zoomResponse['success']) {
                    $zoomData = $zoomResponse['data'];
                    $meetingId = $zoomData['id'];
                    $startUrl = $zoomData['start_url'];
                    $joinUrl = $zoomData['join_url'];
                    $password = $zoomData['password'] ?? $request->password;
                } else {
                    // Fallback to local meeting if API fails
                    $meetingId = 'local_' . time();
                    $startUrl = null;
                    $joinUrl = null;
                    $password = $request->password;
                }
            } else {
                // Create local meeting when no Zoom credentials
                $meetingId = 'local_' . time();
                $startUrl = null;
                $joinUrl = null;
                $password = $request->password;
            }

            $meeting = ZoomMeeting::create([
                'title' => $request->title,
                'description' => $request->description,
                'workspace_id' => $workspace->id,
                'zoom_meeting_id' => $meetingId,
                'project_id' => $request->project_id,
                'start_time' => $startTime,
                'end_time' => $startTime->copy()->addMinutes($request->duration),
                'duration' => $request->duration,
                'timezone' => $request->timezone,
                'start_url' => $startUrl,
                'password' => $password,
                'join_url' => $joinUrl,
                'status' => 'scheduled',
                'user_id' => $user->id,
            ]);

            // Attach members to the meeting
            if (!empty($request->member_ids)) {
                $meeting->members()->attach($request->member_ids);
            }

            $message = $startUrl ? __('Zoom meeting created successfully!') : __('Meeting created successfully! Zoom API failed - please check your app activation and scopes in Zoom Marketplace.');
            
            return redirect()->route('zoom-meetings.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('Error creating meeting: ') . $e->getMessage()]);
        }
    }

    public function getProjectMembers($projectId)
    {
        $this->authorizePermission('zoom_meeting_view_any');

        $user = auth()->user();
        $workspace = $user->currentWorkspace;

        if (!$workspace) {
            return response()->json(['error' => 'No workspace found'], 403);
        }

        $project = Project::find($projectId);
        
        if (!$project || $project->workspace_id != $workspace->id) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        // Get all project users (members with roles) and clients
        $projectUsers = $project->users()->get();
        $projectClients = $project->clients()->get();
        
        $allUsers = collect();
        
        // Add project users (members/managers with their roles)
        $projectUsers->each(function ($user) use ($allUsers) {
            $allUsers->push([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->pivot->role
            ]);
        });
        
        // Add clients
        $projectClients->each(function ($client) use ($allUsers) {
            $allUsers->push([
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'role' => 'client'
            ]);
        });

        return response()->json($allUsers->values());
    }

    public function destroy(ZoomMeeting $zoomMeeting)
    {
        $this->authorizePermission('zoom_meeting_delete');

        $user = auth()->user();
        $workspace = $user->currentWorkspace;

        if (!$workspace || $zoomMeeting->workspace_id !== $workspace->id) {
            abort(403, 'Meeting not found in current workspace.');
        }

        try {
            // Only delete from Zoom if it's a real Zoom meeting (not local)
            if ($zoomMeeting->zoom_meeting_id && !str_starts_with($zoomMeeting->zoom_meeting_id, 'local_')) {
                $zoomResponse = $this->zoomService->deleteMeeting($zoomMeeting->zoom_meeting_id);
                
                if (!$zoomResponse['success']) {
                    return back()->withErrors(['error' => __('Failed to delete Zoom meeting: ') . $zoomResponse['error']]);
                }
            }

            $zoomMeeting->delete();

            return redirect()->route('zoom-meetings.index')
                ->with('success', __('Meeting deleted successfully!'));
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('Error deleting meeting: ') . $e->getMessage()]);
        }
    }

    public function join(ZoomMeeting $zoomMeeting)
    {
        $user = auth()->user();
        $workspace = $user->currentWorkspace;

        if (!$workspace || $zoomMeeting->workspace_id !== $workspace->id) {
            abort(403, 'Meeting not found in current workspace.');
        }

        if (!$zoomMeeting->join_url) {
            return back()->withErrors(['error' => __('Zoom credentials not configured. Please configure Zoom settings to enable video conferencing.')]);
        }

        return redirect()->away($zoomMeeting->join_url);
    }

    public function start(ZoomMeeting $zoomMeeting)
    {
        $user = auth()->user();
        $workspace = $user->currentWorkspace;

        if (!$workspace || $zoomMeeting->workspace_id !== $workspace->id) {
            abort(403, 'Meeting not found in current workspace.');
        }

        if (!$zoomMeeting->start_url) {
            return back()->withErrors(['error' => __('Zoom credentials not configured. Please configure Zoom settings to enable video conferencing.')]);
        }

        $zoomMeeting->update(['status' => 'started']);

        return redirect()->away($zoomMeeting->start_url);
    }

    public function show(ZoomMeeting $zoomMeeting)
    {
        $this->authorizePermission('zoom_meeting_view');

        $user = auth()->user();
        $workspace = $user->currentWorkspace;

        if (!$workspace || $zoomMeeting->workspace_id !== $workspace->id) {
            abort(403, 'Meeting not found in current workspace.');
        }

        $zoomMeeting->load(['user', 'project', 'workspace', 'members']);

        // Get projects for edit modal
        $projects = Project::forWorkspace($workspace->id)->get(['id', 'title']);
        
        // Get workspace members
        $members = User::whereHas('workspaces', function ($q) use ($workspace) {
            $q->where('workspace_id', $workspace->id)
              ->where('status', 'active');
        })->get(['id', 'name', 'email']);

        return Inertia::render('zoom-meetings/Show', [
            'meeting' => $zoomMeeting,
            'projects' => $projects,
            'members' => $members,
            'permissions' => $this->getModuleCrudPermissions('zoom_meeting')
        ]);
    }

    public function update(Request $request, ZoomMeeting $zoomMeeting)
    {
        $this->authorizePermission('zoom_meeting_update');

        $user = auth()->user();
        $workspace = $user->currentWorkspace;

        if (!$workspace || $zoomMeeting->workspace_id !== $workspace->id) {
            abort(403, 'Meeting not found in current workspace.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:15|max:480',
            'timezone' => 'required|string',
            'password' => 'nullable|string',
            'member_ids' => 'nullable|array',
            'member_ids.*' => 'exists:users,id',
        ]);

        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes($request->duration);

        $meetingData = [
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $startTime->toISOString(),
            'duration' => $request->duration,
            'timezone' => $request->timezone,
            'password' => $request->password,
        ];

        try {
            // Update meeting in Zoom
            if ($zoomMeeting->zoom_meeting_id) {
                $zoomResponse = $this->zoomService->updateMeeting($zoomMeeting->zoom_meeting_id, $meetingData);

                if (!$zoomResponse['success']) {
                    return back()->withErrors(['error' => __('Failed to update Zoom meeting: ') . $zoomResponse['error']]);
                }
            }

            // Update in database
            $zoomMeeting->update([
                'title' => $request->title,
                'description' => $request->description,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'timezone' => $request->timezone,
                'duration' => $request->duration,
                'password' => $request->password,
            ]);

            // Update members
            if ($request->has('member_ids')) {
                $zoomMeeting->members()->sync($request->member_ids ?? []);
            }

            return redirect()->route('zoom-meetings.index')
                ->with('success', __('Zoom meeting updated successfully!'));
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => __('Error updating meeting: ') . $e->getMessage()]);
        }
    }

    public function calendar()
    {
        $user = auth()->user();
        $workspace = $user->currentWorkspace;

        if (!$workspace) {
            return response()->json([]);
        }

        $meetings = ZoomMeeting::with(['project', 'user'])
            ->forWorkspace($workspace->id)
            ->get()
            ->map(function ($meeting) {
                return [
                    'id' => $meeting->id,
                    'title' => $meeting->title,
                    'start' => $meeting->start_time->toISOString(),
                    'end' => $meeting->end_time->toISOString(),
                    'backgroundColor' => '#1976d2',
                    'borderColor' => '#1976d2',
                    'extendedProps' => [
                        'type' => 'zoom_meeting',
                        'status' => $meeting->status,
                        'join_url' => $meeting->join_url,
                        'start_url' => $meeting->start_url,
                        'project' => $meeting->project?->title,
                        'description' => $meeting->description,
                        'duration' => $meeting->duration,
                        'timezone' => $meeting->timezone,
                    ]
                ];
            });

        return response()->json($meetings);
    }
}