<?php

namespace App\Http\Controllers;

use App\Http\Requests\saveDepartementRequest;
use App\Models\Departement;
use Exception;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index()
    {
        $departements = Departement::paginate(10);
        return view('departements.index', compact('departements'));
    }

    public function create()
    {
        return view('departements.create');
    }

    public function edit(Departement $departement)
    {
        return view('departements.edit', compact('departement'));
    }


    //interraction avec la BD

    public function store(saveDepartementRequest $request)
{
    try {
        $departement = new Departement();
        $departement->name = $request->name;
        $departement->save();

        return redirect()->route('departement.index')->with('success_message', 'Département ajouté avec succès.');
    } catch (Exception $e) {
        report($e); // meilleure pratique
        return back()->with('error_message', 'Une erreur est survenue lors de l\'ajout.');
    }
}


    public function update(Departement $departement, saveDepartementRequest $request)
{
    try {
        $departement->name = $request->name;
        $departement->save(); // `save()` suffit, pas besoin de `update()`

        return redirect()->route('departement.index')->with('success_message', 'Département mis à jour avec succès.');
    } catch (Exception $e) {
        report($e);
        return back()->with('error_message', 'Échec de la mise à jour du département.');
    }
}


    public function delete(Departement $departement)
{
    try {
        $departement->delete();

        return redirect()->route('departement.index')->with('success_message', 'Département supprimé avec succès.');
    } catch (Exception $e) {
        report($e);
        return back()->with('error_message', 'Suppression du département échouée.');
    }
}

    // API Methods
    public function apiIndex()
    {
        try {
            $departements = Departement::paginate(15);
            return response()->json([
                'success' => true,
                'data' => $departements,
                'message' => 'Liste des départements récupérée avec succès'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des départements: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiShow(Departement $departement)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $departement,
                'message' => 'Département récupéré avec succès'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du département: ' . $e->getMessage()
            ], 500);
        }
    }

}