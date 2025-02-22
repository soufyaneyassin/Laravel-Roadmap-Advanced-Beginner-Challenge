<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!auth()->user()->can('view_clients'),403);
        $clients = Client::active()->withCount('projects')->paginate(10);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!auth()->user()->can('create_clients'),403);
        $projects = Project::all();
        return view('clients.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('create_clients'),403);
        $validated = $request->validate([
            'company' => 'required|string',
            'vat' => 'required|numeric',
            'status' => 'required|boolean'
        ]);
        $validated['description'] = isset($request->description) ? $request->description : "no description";
        $client = Client::create($validated);
        if (isset($request->projects))
            $client->projects()->attach($request->projects);
        return back()->with('status', "client successfully added");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        abort_if(!auth()->user()->can('edit_clients'),403);
        $projects = Project::all();
        return view('clients.edit', compact('client', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        abort_if(!auth()->user()->can('edit_clients'),403);
        $request->validate([
            'company' => 'required|string',
            'vat' => 'required|numeric',
            'status' => 'required|boolean'
        ]);
        $client->update($request->all());
        $client->projects()->sync($request->projects);
        return back()->with('status','client updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        abort_if(!auth()->user()->can('delete_clients'),403);
        $client->projects()->detach();
        $client->delete();
        return back()->with('status', 'client deleted successfully');
    }
}
