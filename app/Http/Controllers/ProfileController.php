<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Peserta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $peserta = null;

        if ($user->role === 'peserta') {
            $peserta = Peserta::where('user_id', $user->id)->first();
        }

        return view('profile.edit', [
            'user' => $user,
            'peserta' => $peserta,
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

        // Update Data Peserta jika user adalah peserta
        if ($request->user()->role === 'peserta') {
            $request->validate([
                'no_hp' => ['nullable', 'string', 'max:20'],
                'instansi' => ['nullable', 'string', 'max:255'],
            ]);

            Peserta::updateOrCreate(
                ['user_id' => $request->user()->id],
                [
                    'no_hp' => $request->input('no_hp'),
                    'instansi' => $request->input('instansi'),
                ]
            );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
