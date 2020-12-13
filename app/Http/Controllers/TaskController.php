<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Project;
use Auth;

class TaskController extends Controller
{
    public function index()
    {
        $projects = Project::where('user_id', Auth::id())
            ->orderBy('priority')
            ->get();

        $tasks = Task::with('project')
            ->where('project_id', $projects->first()->id)
            ->orderBy('priority')
            ->get();

        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer'
        ]);
        
        $tasks = Task::with('project')
            ->where('project_id', $request->project_id)
            ->orderBy('priority')
            ->get();

        $html = view('tasks.partials.table', compact('tasks'))->render();
        
        return response()->json(compact('html'));
    }

    public function create()
    {   
        $projects = Project::where('user_id', Auth::id())
            ->orderBy('priority')
            ->get();

        $amount = Task::with('project')
            ->where('project_id', $projects->first()->id)
            ->count();

        $totalTasks = $amount + 1;

        return view('tasks.create', compact('totalTasks', 'projects'));
    }

    public function store(StoreTaskRequest $request)
    {
        Task::create($request->all());

        return redirect()->route('tasks');
    }

    public function edit(Task $task)
    {   
        $totalTasks = Task::where('project_id', $task->project_id)->count();

        return view('tasks.edit', compact('task', 'totalTasks'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->except('project_id'));

        return redirect()->route('tasks');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return back();
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array'
        ]);

        foreach($request->items as $index => $id) {
            DB::table('tasks')
                ->where('id', $id)
                ->update([
                    'priority' => $index + 1
                ]);
        }
        
        return response()->json("OK", 200);
    }

    public function updateSelectPriority(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer'
        ]);
        
        $totalTasks = Task::where('project_id', $request->project_id)
            ->count() + 1;

        $html = view('tasks.partials.options', compact('totalTasks'))->render();
        
        return response()->json(compact('html'));
    }
}
