<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Employer;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployerRequest;
use App\Http\Requests\UpdateEmployerRequest;
use Exception;

class EmployerController extends Controller
{
    public function index()
    {
        $employers = Employer::with('departement')->paginate(10);
        return view('employers.index', compact('employers'));
    }

    public function create()
    {
        $departements = Departement::all();
        return view('employers.create', compact('departements'));
    }

    public function edit(Employer $employer)
    {
        $departements = Departement::all();
        return view('employers.edit', compact('employer', 'departements'));
    }

    public function store(StoreEmployerRequest $request)
    {
        try {
            $employer = Employer::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'contact' => $request->contact,
                'departement_id' => $request->departement_id,
                'montant_journalier' => $request->montant_journalier
            ]);

            if ($employer) {
                return redirect()->route('employer.index')
                    ->with('success', 'Employé ajouté avec succès.');
            } else {
                return redirect()->back()
                    ->with('error', "Échec lors de l’ajout de l’employé.");
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur serveur : ' . $e->getMessage());
        }
    }


    public function update(UpdateEmployerRequest $request, Employer $employer)
    {
        try {
            $success = $employer->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'contact' => $request->contact,
                'departement_id' => $request->departement_id,
                'montant_journalier' => $request->montant_journalier,
            ]);

            if ($success) {
                return redirect()->route('employer.index')
                    ->with('success', "Les informations ont été mises à jour.");
            } else {
                return redirect()->back()
                    ->with('error', "Échec de la mise à jour.");
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur serveur : ' . $e->getMessage());
        }
    }



    public function delete(Employer $employer)
    {
        try {
            $employer->delete();

            return redirect()->route('employer.index')
                ->with('success', 'Employé supprimé avec succès.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
