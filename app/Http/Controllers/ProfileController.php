<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit');
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->update($request->safe()->only(['name', 'email']));

        Student::query()
            ->where('user_id', $user->id)
            ->first()?->update([
                'name' => $user->name,
                'email' => $user->email,
            ]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Your profile has been updated.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update($request->safe()->only(['password']));

        return redirect()
            ->route('profile.edit')
            ->with('password_success', 'Your password has been updated.');
    }
}
