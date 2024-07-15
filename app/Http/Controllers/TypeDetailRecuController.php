<?php

namespace App\Http\Controllers;

use App\Models\TypeDetailRecu;
use App\Http\Requests\StoreTypeDetailRecuRequest;
use App\Http\Requests\UpdateTypeDetailRecuRequest;

class TypeDetailRecuController extends Controller
{
    protected $typedetailrecus;

    public function __construct(TypeDetailRecu $typedetailrecus){
        $this->typedetailrecus = $typedetailrecus;
    }


    public function index()
    {
        $typedetailrecus = $this->typedetailrecus->orderBy('libelle')->get();
        return view('typedetailrecus.index', compact('typedetailrecus'));
    }


    public function create()
    {
        return  view('typedetailrecus.create');
    }



    public function store(StoreTypeDetailRecuRequest $request)
    {
        $typedetailrecus = TypeDetailRecu::create([
            'libelle' => strtoupper($request->libelle),
        ]);

        if($typedetailrecus){
            Session()->flash('message', 'Type détail réçu ajouté avec succès!');
            return redirect()->route('typedetailrecus.index');
        }
    }



    public function show(TypeDetailRecu $typedetailrecus)
    {
        //
    }



    public function edit($id)
    {
        $typedetailrecus = $this->typedetailrecus->findOrFail($id);

        return  view('typedetailrecus.edit', compact('typedetailrecus'));
    }



    public function update(UpdateTypeDetailRecuRequest $request)
    {
        $typedetailrecus = $this->typedetailrecus->findOrFail($request->id);

        $typedetailrecus = $typedetailrecus->update([
            'libelle' => strtoupper($request->libelle),
        ]);

        if($typedetailrecus){
            Session()->flash('message', 'Type détail réçu modifié avec succès!');
            return redirect()->route('typedetailrecus.index');
        }
    }


    public function delete($id)
    {
        $typedetailrecus = $this->typedetailrecus->findOrFail($id);

        return  view('typedetailrecus.delete', compact('typedetailrecus'));
    }



    public function destroy($id)
    {
        $typedetailrecus = $this->typedetailrecus->findOrFail($id)->delete();

        if($typedetailrecus){
            Session()->flash('message', 'Type détail réçu supprimé avec succès!');
            return redirect()->route('typedetailrecus.index');
        }
    }
}
