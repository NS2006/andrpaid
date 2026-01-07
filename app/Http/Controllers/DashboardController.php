<?php

namespace App\Http\Controllers;

use App\Models\Affiliation;
use App\Models\CollaborationRequest;
use App\Models\Lecturer;
use App\Models\Paper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index($profileId)
    {
        $user = User::where("profileId", $profileId)->firstOrFail();

        $data = [
            'user' => $user,
            'navbarProfileData' => [
                'profileId' => $user->profileId,
                'papersCount' => $user->lecturer
                    ? \App\Models\Paper::where('lecturer_id', $user->lecturer->id)->count()
                    : ($user->university
                        ? \App\Models\Paper::whereIn('lecturer_id', \App\Models\Affiliation::where('university_id', $user->university->id)
                            ->where('status', 'accepted')
                            ->pluck('lecturer_id'))->count()
                        : 0),
                'starsCount' => 0,
                'researchersCount' => $user->university
                    ? \App\Models\Affiliation::where('university_id', $user->university->id)
                        ->where('status', 'accepted')
                        ->count()
                    : 0,
            ],
            'messageCount' => 0,    
            'unreadMessages' => 0,  
            'citations' => 0,       
        ];

        // ==========================================
        // SCENARIO A: USER IS A UNIVERSITY
        // ==========================================
        if ($user->university) {
            $university = $user->university;
            
            // 1. STATS
            $approvedLecturerIds = Affiliation::where('university_id', $university->id)
                ->where('status', 'accepted')
                ->pluck('lecturer_id');
            
            $lecturerCount = $approvedLecturerIds->count();

            $activeProjectsCount = Paper::whereIn('lecturer_id', $approvedLecturerIds)->count();

            $pendingRequestsCount = Affiliation::where('university_id', $university->id)
                ->where('status', 'pending')
                ->count();

            // 2. RECENT ACTIVITY LIST
            $activePapers = Paper::with(['lecturer.user', 'paperType'])
                ->whereIn('lecturer_id', $approvedLecturerIds)
                ->latest('created_at')
                ->take(5)
                ->get();

            // 3. RECOMMENDATIONS (Other Universities to partner with?)
            $recommendations = User::whereHas('university')
                ->where('id', '!=', $user->id)
                ->inRandomOrder()
                ->take(3)
                ->get();

            $data['isUniversity'] = true;
            $data['activeProjectsCount'] = $activeProjectsCount;
            $data['pendingRequestsCount'] = $pendingRequestsCount;
            $data['lecturerCount'] = $lecturerCount; 
            $data['activePapers'] = $activePapers;
            $data['recommendations'] = $recommendations; 
            $data['citations'] = 8500; 
        }

        // ==========================================
        // SCENARIO B: USER IS A LECTURER
        // ==========================================
        elseif ($user->lecturer) {
            $lecturer = $user->lecturer;

            // 1. STATS
            $activeProjectsCount = Paper::where('lecturer_id', $lecturer->id)
                ->orWhereHas('collaborations', function ($q) use ($lecturer) {
                    $q->where('lecturer_id', $lecturer->id);
                })->count();

            $pendingRequestsCount = Affiliation::where('lecturer_id', $lecturer->id)
                ->where('status', 'pending')
                ->count();

            // 2. ACTIVE COLLABORATIONS LIST
            $activePapers = Paper::with(['lecturer.user', 'paperType'])
                ->where('lecturer_id', $lecturer->id)
                ->orWhereHas('collaborations', function ($q) use ($lecturer) {
                    $q->where('lecturer_id', $lecturer->id);
                })
                ->latest('updated_at')
                ->take(5)
                ->get();

            // 3. RECOMMENDATIONS (Other Lecturers)
            $recommendations = Lecturer::with(['user', 'affiliation.university.user'])
                ->where('id', '!=', $lecturer->id)
                ->inRandomOrder()
                ->take(3)
                ->get();

            $data['isUniversity'] = false;
            $data['activeProjectsCount'] = $activeProjectsCount;
            $data['pendingRequestsCount'] = $pendingRequestsCount;
            $data['activePapers'] = $activePapers;
            $data['recommendations'] = $recommendations;
            $data['citations'] = 1240; 
        } 
        
        else {
            abort(403, 'Unauthorized role');
        }

        return view('pages.dashboard', $data);
    }
}