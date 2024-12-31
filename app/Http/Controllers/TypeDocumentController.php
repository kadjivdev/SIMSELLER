<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class TypeDocumentController extends Controller
{
    public function index()
    {
        $typedocuments = TypeDocument::all();
        return view('typedocuments.index', compact('typedocuments'));
    }

    public function create()
    {
        return view('typedocuments.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', 'unique:type_documents'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('typedocuments.index')->withErrors($validator->errors())->withInput();
            }

            $typedocuments = TypeDocument::create([
                'libelle' => strtoupper($request->libelle),
            ]);

            if ($typedocuments) {
                Session()->flash('message', 'Type document ajouté avec succès!');
                return redirect()->route('typedocuments.index');
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('typedocuments.index');
            }
        }
    }

    public function show(TypeDocument $typeDocument)
    {
        //
    }

    public function edit(TypeDocument $typedocument)
    {
        return view('typedocuments.edit', compact('typedocument'));
    }

    public function update(Request $request, TypeDocument $typedocument)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', Rule::unique('type_documents')->ignore($request->id)],
            ]);

            if ($validator->fails()) {
                return redirect()->route('typedocuments.edit', ['typedocument' => $typedocument->id])->withErrors($validator->errors())->withInput();
            }

            $typedocuments = $typedocument->update([
                'libelle' => strtoupper($request->libelle),
            ]);

            if ($typedocuments) {
                Session()->flash('message', 'Type document modifié avec succès!');
                return redirect()->route('typedocuments.index');
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('typedocuments.index');
            }
        }
    }

    public function delete(TypeDocument $typedocument)
    {
        return view('typedocuments.delete', compact('typedocument'));
    }

    public function destroy(TypeDocument $typedocument)
    {
        $typedocument = $typedocument->delete();
        if ($typedocument) {
            Session()->flash('message', 'Type document supprimé avec succès!');
            return redirect()->route('typedocuments.index');
        }
    }
}
