<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $countries = Countries::getNames('en');
        $locales = Languages::getNames('en');
        return view('dashboard.profile.edit', compact('user', 'countries', 'locales'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable', 'date', 'before:today'],
            'gender' => ['in:male,female'],
            'country' => ['required', 'string', 'size:2'],
        ]);
        
        $user = $request->user();
        $user->profile->fill($request->all())->save();

        // dd($user->profile);
        
        // if ($profile->first_name) {
        //     $user->profile->update($request->all());
        // } else {
        //     // $request->merge([
        //     //     'user_id' => $user->id,
        //     // ]);
        //     // Profile::create($request->all());
        //     $user->profile()->create($request->all());
        // }
        
        return redirect()
            ->route('dashboard.profile.edit')
            ->with('success', 'Profile updated!');
    }
}
