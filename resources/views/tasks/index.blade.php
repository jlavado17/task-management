<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-link href="{{ URL::route('tasks.create') }}">New Task</x-link>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <label class="text-md">Projects</label>
                    <select id="project" name="project" class="w-full appearance-none text-black text-opacity-70 rounded shadow py-1 px-2  mr-2 focus:outline-none focus:shadow-outline focus:border-blue-200">
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ $project->priority == 1 ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full table-auto">
                        <thead class="justify-between">
                        <tr class="bg-gray-800">
                            <th class="px-8 py-2">
                            <span class="text-gray-300">Id</span>
                            </th>
                            <th class="px-8 py-2">
                            <span class="text-gray-300">Name</span>
                            </th>
                            <th class="px-8 py-2">
                            <span class="text-gray-300">Priority</span>
                            </th>
                            <th class="px-8 py-2">
                            <span class="text-gray-300">Project</span>
                            </th>
                            <th class="px-8 py-2">
                            <span class="text-gray-300">Date</span>
                            </th>
                            <th class="px-8 py-2">
                            <span class="text-gray-300">Time</span>
                            </th>

                            <th class="px-8 py-2">
                            <span class="text-gray-300">Actions</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-gray-200" id="sortable">
                        @foreach($tasks as $task)
                            <tr class="bg-white border-4 border-gray-200 hover:bg-gray-300">
                                <td class="px-16 py-2 items-center">
                                    {{ $task->id }}
                                </td>
                                <td>
                                    <span class="text-center font-semibold">
                                        {{ $task->name }}
                                    </span>
                                </td>
                                <td class="px-16 py-2 text-center">
                                    {{ $task->priority }}
                                </td>
                                <td class="px-16 py-2 text-center">
                                    {{ $task->project->name }}
                                </td>
                                <td class="px-16 py-2">
                                    <span>
                                        {{ $task->created_at->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="py-2 text-center">
                                    {{ $task->created_at->format('h:i A') }}
                                </td>
                                <td class="py-2 flex items-center">
                                    <a href="{{ URL::route('tasks.edit', $task->id) }}" class="mr-1 bg-indigo-500 text-white px-3 rounded-md hover:bg-indigo-700 hover:text-black ">
                                        Edit
                                    </a>
                                    <form class="form-button" action="{{ URL::route('tasks.destroy', $task->id) }}" method="post" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 rounded-md hover:bg-red-700 hover:text-black ">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
  $( function() {

    const _token = '{{ csrf_token() }}';

    $( "#sortable" ).sortable({
        update: function( event, ui ) {
            let taskIds = [];
    
            $(this).children().each(function(index, item) {
                taskIds.push($(this).find('td:nth-child(1)').text());
            });

            taskIds = taskIds.map(id => parseInt(id));

            fetch('{{ route('tasks.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({_token, items: taskIds})
            })
            .then(response => response.json())
            .then(data => {
                $(this).children().each(function(index, item) {
                    $(this).find('td:nth-child(3)').text(index + 1);
                });
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
    });

    $( "#sortable" ).disableSelection();

    $("#project").change(function (){
        fetch('{{ route('tasks.search') }}', {
            method: 'POST',
            headers: {
                    'Content-Type': 'application/json',
            },
            body: JSON.stringify({_token, project_id: $(this).val() })
        })
        .then(response => response.json())
        .then(data => {
            $("#sortable").html(data.html);
        })
        .catch((error) => {
            console.log(error);
        });
    });
        
   
  } );
  </script>
