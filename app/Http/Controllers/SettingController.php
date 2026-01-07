<?php

namespace App\Http\Controllers;

use App\Models\Affiliation;
use App\Models\Province;
use App\Models\ResearchField;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index(){
        $user = Auth::user();

        if($user->isLecturer() || $user->isUniversity()){
            $province = $user->isLecturer() ? $user->lecturer->province : $user->university->province;
            $allProvinces = Province::all();
        }



        if($user->isLecturer()){
            $allUniversities = University::with('user')->get()->sortBy('user.name');

            $allResearchFields = ResearchField::all();

            return view("pages.settings", [
                "user"=> $user,
                "province" => $province,
                "allProvinces" => $allProvinces,
                "allUniversities" => $allUniversities,
                "allResearchFields" => $allResearchFields,
            ]);
        } else{
            if($user->isUniversity()){
                return view("pages.settings", [
                    "user"=> $user,
                    "province" => $province,
                    "allProvinces" => $allProvinces,
                ]);
            } else{
                return view("pages.settings", [
                    "user"=> $user,
                ]);
            }
        }
    }

    public function updatePublicProfile(Request $request){
        $user = Auth::user();

        if($user->isLecturer()){
            $validated = $request->validate([
                'name'          => 'required|string|max:255',
                'description'    => 'nullable|string',
                'province_id'    => 'required|string',
                'linkedin_url'    => 'nullable|string',
                'portfolio_url'    => 'nullable|string',
                'nidn' => 'string|nullable'
            ]);

            $accRole = $user->lecturer;

            $accRole->update([
                "linkedinUrl" => $validated["linkedin_url"],
                "portfolioUrl" => $validated["portfolio_url"],
                'nidn' => $validated['nidn'],
            ]);
        } else if($user->isUniversity()){
            $validated = $request->validate([
                'name'          => 'required|string|max:255',
                'description'    => 'nullable|string',
                'province_id'    => 'required|string',
                'website_url'    => 'nullable|string',
            ]);


            $accRole = $user->university;

            $accRole->update([
                "websiteUrl" => $validated["website_url"],
            ]);
        } else{
            $validated = $request->validate([
                'name'          => 'required|string|max:255',
                'description'    => 'nullable|string',
            ]);
        }

        if(!$user->isAdmin()){
            $province = Province::where("provinceId", $validated["province_id"])->first();

            $accRole->update([
                "province_id" => $province->id
            ]);

            $user->update([
                "name" => $validated["name"],
                "description"=> $validated["description"],
                "provinceId"=> $validated["province_id"],
            ]);
        } else{
            $user->update([
                "name" => $validated["name"],
                "description"=> $validated["description"],
            ]);
        }


        return redirect("/settings#profile")->with("success", "Your public profile has been updated successfully!");
    }

    public function requestAffiliation(Request $request){
        $validated = $request->validate([
            'university_id'     => 'required',
            'nidn'              => 'required'
        ]);

        $lecturer = Auth::user()->lecturer;

        Affiliation::updateOrCreate(
            ['lecturer_id' => $lecturer->id],
            [
                'university_id' => $validated['university_id'],
                'nidn' => $validated['nidn'],
                'status' => 'pending',
                'rejection_reason' => null
            ]
        );

        return redirect("/settings#academic")->with("success", "Your affiliation request has been requested successfully!");
    }

    public function updateInterests(Request $request){
        $validated = $request->validate([
            'research_fields' => 'required|array',
            'research_fields.*' => 'integer|exists:research_fields,id'
        ]);

        $user = Auth::user();
        $lecturer = $user->lecturer;

        $lecturer->researchFields()->sync($validated['research_fields']);

        return redirect("/settings#academic")->with("success", "Your interests have been updated successfully!");
    }

    public function updateEmail(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string',
        ]);

        if ($validator->fails() || !Hash::check($request->password, $user->password)) {
            return redirect("/settings#account")->with("error", "Update failed. Invalid password or unavailable email address.");
        }

        $user->update([
            "email" => $request->email,
        ]);

        return redirect("/settings#account")->with("success", "Your email has been updated successfully!");
    }

    public function updatePassword(Request $request){
        $user = Auth::user();

        $validated = $request->validate([
            'current_password'  => 'required|string|max:255',
            'new_password'      => 'required|string',
            'new_password_confirmation'    => 'required|string',
        ]);

        if (!Hash::check($validated["current_password"], $user->password) || $validated["new_password"] !== $validated["new_password_confirmation"]) {
            return redirect("/settings#account")->with("error", "Update failed. Current password incorrect or new passwords do not match.");
        }

        $user->update([
            'password' => bcrypt($validated['new_password']),
            'latest_password_updated_at' => now(),
        ]);

        return redirect("/settings#account")->with("success", "Your password has been changed successfully!");
    }

    public function deleteAccount(Request $request){
        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();


        $user->delete();

        return redirect('/');
    }
}
