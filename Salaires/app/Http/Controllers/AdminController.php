<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeAdminRequest;
use App\Http\Requests\updateAdminRequest;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Notifications\AdminAfterRegistrationNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::paginate(10);
        return view('admins/index', compact('admins'));
    }
    public function create()
    {
        return view('admins/create');
    }

    public function edit(User $user)
    {
        return view('admins/edit', compact('user'));
    }

    public function store(StoreAdminRequest $request)
    {
        try {
            // Création de l'utilisateur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('default'), // à remplacer par une vraie logique plus tard
            ]);

            // Nettoyage des anciens codes
            ResetCodePassword::where('email', $user->email)->delete();

            // Génération du nouveau code
            $code = rand(1000, 4000);

            ResetCodePassword::create([
                'code' => $code,
                'email' => $user->email,
            ]);

            // Envoi de la notification
            $user->notify(new AdminAfterRegistrationNotification($code, $user->email));

            return redirect()
                ->route('admin.index')
                ->with('success', 'Administrateur créé avec succès et notification envoyée.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function update(updateAdminRequest $request, user $user)
    {
        try {

            $user->update($request->validated());
            return redirect()->route('admin.index')->with('success', 'Administrateur mis à jour avec succès.');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function delete(User $user)
    {
        try {
            $connectedAdminId = Auth::id();

            // Empêche un admin de se supprimer lui-même
            if ($connectedAdminId === $user->id) {
                return back()->with('error_message', 'Vous ne pouvez pas supprimer votre propre compte.');
            }

            // Suppression
            $user->delete();

            return back()->with('success_message', 'Administrateur supprimé avec succès.');
        } catch (Exception $e) {
            // Log technique pour debug
            Log::error("Erreur suppression administrateur : " . $e->getMessage());

            return back()->with('error_message', 'Une erreur est survenue lors de la suppression.');
        }
    }
}