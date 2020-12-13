<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function creating(Task $task)
    {
        if($task->priority == Task::where('project_id', $task->project_id)->count() + 1) {
            return;
        }

        $tasksToReOrder = Task::query()
            ->where('project_id', $task->project_id)
            ->where('priority', '>=', $task->priority)
            ->get();

        foreach($tasksToReOrder as $task) {
            $task->priority++;
            $task->saveQuietly();
        }
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updating(Task $task)
    {
        if($task->isClean('priority')){
            return;
        }

        if($task->getOriginal('priority') < $task->priority) {
            $tasksToReOrder = Task::query()
                ->where('project_id', $task->project_id)
                ->whereBetween('priority', [$task->getOriginal('priority') + 1, $task->priority])
                ->get();
            
            foreach($tasksToReOrder as $task) {
                $task->priority--;
                $task->saveQuietly();
            }
        } else {
            $tasksToReOrder = Task::query()
                ->where('project_id', $task->project_id)
                ->whereBetween('priority', [$task->priority, $task->getOriginal('priority') - 1])
                ->get();
            
            foreach($tasksToReOrder as $task) {
                $task->priority++;
                $task->saveQuietly();
            }
        }
    }

    /**
     * Handle the Task "deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $tasksToReOrder = Task::query()
            ->where('project_id', $task->project_id)
            ->where('priority', '>', $task->priority)
            ->get();

        foreach($tasksToReOrder as $task) {
            $task->priority--;
            $task->saveQuietly();
        }
    }

    /**
     * Handle the Task "restored" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }
}
