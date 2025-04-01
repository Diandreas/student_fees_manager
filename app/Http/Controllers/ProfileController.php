<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Affiche la page de profil de l'utilisateur.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Affiche le formulaire d'édition du profil de l'utilisateur.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Met à jour les informations personnelles de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'job_title' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Traitement de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprime l'ancien avatar s'il existe
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Stocke le nouvel avatar
            $avatarName = 'user_' . time() . '_' . uniqid() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('avatars', $avatarName, 'public');
            $user->avatar = $avatarName;
        }

        // Mise à jour des autres informations
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->job_title = $request->job_title;
        $user->address = $request->address;
        $user->bio = $request->bio;
        $user->save();

        return redirect()->route('profile.index')->with('profile_success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Met à jour le mot de passe de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Le mot de passe actuel est incorrect.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index', ['#security'])->with('password_success', 'Votre mot de passe a été modifié avec succès.');
    }

    /**
     * Met à jour les préférences de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'language' => ['required', 'string', 'in:fr,en'],
            'theme' => ['required', 'string', 'in:light,dark,auto'],
        ]);

        $user = Auth::user();
        $user->language = $request->language;
        $user->theme = $request->theme;
        $user->email_notifications = $request->has('email_notifications');
        $user->browser_notifications = $request->has('browser_notifications');
        $user->save();

        // Mettre à jour la session pour refléter les changements immédiatement
        session(['locale' => $request->language]);

        return redirect()->route('profile.index', ['#preferences'])->with('preferences_success', 'Vos préférences ont été mises à jour avec succès.');
    }
} 