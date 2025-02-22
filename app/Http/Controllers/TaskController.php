<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = cache()->remember('homepage-tasks-page-' . request()->page, 60 * 60 * 24, function () {
            return Task::with('taskable')->with('user')->paginate(10);
        });
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::all()->map->only(['id','title'])->values()->toArray();
        $clients = Client::all()->map->only(['id','company'])->values()->toArray();
        $users = User::all();
        return view('tasks.create',compact('projects','users','clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'taskable_type' => 'required',
            'taskable_id' => 'required', 
            'status' => 'required',
            'priority' => 'required'
        ]);
        Task::create($validated);
        return back()->with('status', 'task created successfuly');
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
    public function edit(Task $task)
    {
        $projects = Project::all()->map->only(['id','title'])->values()->toArray();
        $clients = Client::all()->map->only(['id','company'])->values()->toArray();
        $users = User::all();
        return view('tasks.edit',compact('users','projects','clients','task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'taskable_type' => 'required',
            'taskable_id' => 'required', 
            'status' => 'required',
            'priority' => 'required'
        ]);
        $task->update($request->all());
        return back()->with('status','task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('status','task deleted successfully');
    }
}
