<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('user_id', Auth::id())
            ->orderBy('priority')
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $totalProjects = Project::count() + 1;

        return view('projects.create', compact('totalProjects'));
    }

    public function store(StoreProjectRequest $request)
    {
        Project::create($request->all() + ['user_id' => Auth::id()]);

        return redirect()->route('projects');
    }

    public function edit(Project $project)
    {
        $totalProjects = Project::count();

        return view('projects.edit', compact('project', 'totalProjects'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->all());

        return redirect()->route('projects');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return back();
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array'
        ]);

        foreach($request->items as $index => $id) {
            DB::table('projects')
                ->where('id', $id)
                ->update([
                    'priority' => $index + 1
                ]);
        }
        
        return response()->json("OK", 200);
    }
}
