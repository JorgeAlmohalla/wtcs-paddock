<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the driver's SimRacing information.
     */
    public function updateDriverInfo(Request $request): RedirectResponse
    {
        // Validamos SOLO los campos de este formulario
        $validated = $request->validate([
            'steam_id' => ['required', 'string', 'max:20', Rule::unique(User::class)->ignore($request->user()->id)],
            'nationality' => ['required', 'string', 'size:2'],
        ]);

        // Guardamos los datos
        $request->user()->fill([
            'steam_id' => $validated['steam_id'],
            'nationality' => strtoupper($validated['nationality']),
            'driver_number' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $validated = $request->validate([
            'steam_id' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($request->user()->id)],
            'nationality' => ['required', 'string', 'size:2'],
            'bio' => ['nullable', 'string', 'max:1000'],     // <--- NUEVO
            'equipment' => ['required', 'in:wheel,pad,keyboard'], // <--- NUEVO
        ]);

        $request->user()->fill([
            'steam_id' => $validated['steam_id'],
            'nationality' => strtoupper($validated['nationality']),
            'bio' => $validated['bio'],
            'equipment' => $validated['equipment'],
            'driver_number' => $validated['driver_number'] ?? null, 
        ]);

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'driver-info-updated');
    }
}
