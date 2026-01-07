<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class FindController extends Controller
{
    public function index(Request $request)
    {
        $query = Lecturer::with(['user', 'province', 'affiliation.university.user', 'papers']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('region')) {
            $query->where('province_id', $request->region);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->join('users', 'lecturers.user_id', '=', 'users.id')
                          ->orderBy('users.name', 'asc')
                          ->select('lecturers.*'); 
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->latest(); 
        }

        $lecturers = $query->paginate(12)->withQueryString();

        $provinces = Province::orderBy('name')->get();

        return view('pages.find', [
            'lecturers' => $lecturers,
            'provinces' => $provinces,
            'navbarProfileData' => Auth::user(),
            'user' => Auth::user()
        ]);
    }
}