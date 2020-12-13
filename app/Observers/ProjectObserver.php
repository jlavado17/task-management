<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function creating(Project $project)
    {
        if($project->priority == Project::count() + 1) {
            return;
        }

        $projectsToReOrder = Project::
            where('priority', '>=', $project->priority)
            ->get();

        foreach($projectsToReOrder as $project) {
            $project->priority++;
            $project->saveQuietly();
        }
    }

    /**
     * Handle the Project "updated" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function updating(Project $project)
    {
        if($project->isClean('priority')){
            return;
        }

        if($project->getOriginal('priority') < $project->priority) {
            $projectsToReOrder = Project::query()
                ->whereBetween('priority', [$project->getOriginal('priority') + 1, $project->priority])
                ->get();
            
            foreach($projectsToReOrder as $project) {
                $project->priority--;
                $project->saveQuietly();
            }
        } else {
            $projectsToReOrder = Project::query()
                ->whereBetween('priority', [$project->priority, $project->getOriginal('priority') - 1])
                ->get();
            
            foreach($projectsToReOrder as $project) {
                $project->priority++;
                $project->saveQuietly();
            }
        }


    }

    /**
     * Handle the Project "deleted" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function deleted(Project $project)
    {
        $projectsToReOrder = Project::query()
                ->where('priority', '>', $project->priority)
                ->get();
        
        foreach($projectsToReOrder as $project) {
            $project->priority--;
            $project->saveQuietly();
        }
    }

    /**
     * Handle the Project "restored" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function restored(Project $project)
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    public function forceDeleted(Project $project)
    {
        //
    }
}
