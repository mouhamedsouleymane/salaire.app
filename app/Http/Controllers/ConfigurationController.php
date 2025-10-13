<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfigRequest;
use App\Models\Configuration;
use PhpParser\Node\Stmt\TryCatch;

class ConfigurationController extends Controller
{
    public function index()
    {
        $allConfigurations = Configuration::latest()->paginate(10);
        return view('config/index', compact('allConfigurations'));
    }

    public function create()
    {
        return view('config.create');
    }

    public function store(StoreConfigRequest $request)
    {

        try {
            Configuration::create($request->all());

            return redirect()->route('configurations')->with('success_message', 'Configuration ajoute');
        } catch (Exception $e) {

            throw new Exception('Erreur lors de l\'enregistrement de la nouvelle configuration');
        }
    }

    public function edit($id)
    {
        $config = Configuration::findOrFail($id);
        return view('config.edit', compact('config'));
    }

    public function delete(Configuration $configuration)
    {
        $configuration->delete();
        return redirect()->route('configurations')->with('success_message', 'Configuration supprimée');
    }
}
