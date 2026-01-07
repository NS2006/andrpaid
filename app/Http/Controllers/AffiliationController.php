<?php

namespace App\Http\Controllers;

use App\Models\Affiliation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliationController extends Controller
{
    public function indexRequests($profileId)
    {
        $user = Auth::user();

        if ($user->profileId !== $profileId || !$user->university) {
            abort(403, 'Access denied. Only University accounts can view this page.');
        }

        $requests = Affiliation::with(['lecturer.user', 'lecturer.province'])
            ->where('university_id', $user->university->id)
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('pages.university.requests', [
            'user' => $user,
            'navbarProfileData' => $user, 
            'requests' => $requests
        ]);
    }

    public function approveRequest(Request $request)
    {
        $affiliation = Affiliation::findOrFail($request->affiliation_id);
       
        if ($affiliation->university_id !== Auth::user()->university->id) {
            abort(403);
        }

        $affiliation->status = 'accepted';
        $affiliation->save();

        return back()->with('success', 'Affiliation approved. Lecturer is now linked.');
    }

    public function rejectRequest(Request $request)
    {
        $affiliation = Affiliation::findOrFail($request->affiliation_id);

        if ($affiliation->university_id !== Auth::user()->university->id) {
            abort(403);
        }

        $affiliation->status = 'rejected';
        $affiliation->save();

        return back()->with('success', 'Request rejected.');
    }

    public function indexOpportunities($profileId)
    {
        $user = Auth::user();

        if ($user->profileId !== $profileId || !$user->lecturer) {
            abort(403, 'Only Lecturers can view this page.');
        }

        $lecturer = $user->lecturer;

        $currentAffiliation = Affiliation::with('university.user')
            ->where('lecturer_id', $lecturer->id)
            ->latest() 
            ->first();

        $universities = User::whereHas('university')->get();

        return view('pages.lecturer.grants', [
            'user' => $user,
            'navbarProfileData' => $user,
            'currentAffiliation' => $currentAffiliation,
            'universities' => $universities
        ]);
    }

    public function sendRequest(Request $request)
    {
        $user = Auth::user();
        $lecturer = $user->lecturer;

        if (empty($lecturer->nidn)) {
            return redirect("/settings")
                ->with('error', 'Please update your profile with your NIDN before applying for grants.');
        }

        $request->validate([
            'university_id' => 'required|exists:universities,id',
        ]);

        $exists = Affiliation::where('lecturer_id', $lecturer->id)
            ->where('university_id', $request->university_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Request already pending or accepted.');
        }

        Affiliation::create([
            'lecturer_id'   => $lecturer->id,
            'university_id' => $request->university_id,
            'nidn'          => $lecturer->nidn, 
            'status'        => 'pending',
        ]);

        return back()->with('success', 'Grant request sent successfully!');
    }
}