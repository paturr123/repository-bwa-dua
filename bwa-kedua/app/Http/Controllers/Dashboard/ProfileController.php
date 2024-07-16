<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use App\Http\Requests\Dashboard\Profile\UpdateDetailUserRequest;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\DetailUser;
use App\Models\ExperienceUser;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $experience_user = ExperienceUser::where('detail_user_id', $user->detail_user->id)            
            ->orderBy('id', 'asc')
            ->get();

        return view('pages.dashboard.profile', compact('user', 'experience_user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        $detail_user = DetailUser::where('user_id', $user->id)->first();
        $detail_user->phone = $request->input('phone');
        $detail_user->address = $request->input('address');
        $detail_user->save();

        return redirect()->route('dashboard.profile.index')->with('success', 'Profile updated successfully');
    }

    /**
     * Delete the specified resource from storage.
     */
    public function deleteExperience(Request $request)
    {
        $experience_user = ExperienceUser::find($request->input('id'));
        if ($experience_user) {
            $experience_user->delete();
            return redirect()->route('dashboard.profile.index')->with('success', 'Experience deleted successfully');
        } else {
            return redirect()->route('dashboard.profile.index')->with('error', 'Experience not found');
        }
    }
}