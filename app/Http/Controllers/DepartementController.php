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

}