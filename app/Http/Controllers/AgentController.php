<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Http\Requests\StoreAgentRequest;
use App\Http\Requests\UpdateAgentRequest;
use App\Models\Porteuille;
use CreateAgentsTable;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as Validator;


class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agents = Agent::orderBy('id', 'desc')->get();
        return view('agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'telephone' => ['required', 'string', 'max:255'],
                'adresse' => ['required', 'string', 'max:255']

            ]);

            if ($validator->fails()) {
                return redirect()->route('agent.index')->withErrors($validator->errors())->withInput();
            }
            $agents = Agent::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse
            ]);
            if ($agents) {
                Session()->flash('message', 'Agent ajouté avec succès!');
                return redirect()->route('agent.index');
            }
        } catch (\Throwable $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('agent.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function show(Agent $agent)
    {
        try {
            return view('agents.delete', compact('agent'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Agent $agent)
    {
        return view('agents.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAgentRequest  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agent $agent)
    {

        try {
            $validator = Validator::make($request->all(), [
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'telephone' => ['required', 'string', 'max:255'],
                'adresse' => ['required', 'string', 'max:255']

            ]);

            if ($validator->fails()) {
                return redirect()->route('agent.index')->withErrors($validator->errors())->withInput();
            }
            $agents = $agent->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse
            ]);
            if ($agents) {
                Session()->flash('message', 'Agent modifié avec succès!');
                return redirect()->route('agent.index');
            }
        } catch (\Throwable $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('agent.index');
            }
        }
    }

    public function delete($id)
    {

        try {
            $agent = Agent::find($id);
            return view('agents.delete', compact('agent'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agent $agent)
    {
        $agent = $agent->delete();

        if ($agent) {
            Session()->flash('message', 'Agent supprimé avec succès!');
            return redirect()->route('agent.index');
        }
    }

    public function client_Affecter(Agent $agent)
    {
        $Porteuilles = Porteuille::join('clients', 'portefeuilles.client_id', '=', 'clients.id')
            ->join('agents', 'portefeuilles.agent_id', '=', 'agents.id')
            ->select(
                'clients.raisonSociale',
                'clients.telephone',
                'clients.email',
                'portefeuilles.statut',
                'portefeuilles.datedebut',
                'portefeuilles.datefin',
                'portefeuilles.id'
            )
            ->where('portefeuilles.agent_id', '=', $agent->id)
            ->where('portefeuilles.statut', '=', 1)
            ->orderBy('portefeuilles.id', 'desc')
            ->get();
        return view('agents.portefeuilles', compact('agent', 'Porteuilles'));
    }
}
