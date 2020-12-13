<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-link href="{{ URL::route('projects.create') }}">New Project</x-link>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full table-auto">
                        <thead class="justify-between">
                        <tr class="bg-gray-800">
                            <th class="px-16 py-2">
                            <span class="text-gray-300">Id</span>
                            </th>
                            <th class="px-16 py-2">
                            <span class="text-gray-300">Name</span>
                            </th>
                            <th class="px-16 py-2">
                            <span class="text-gray-300">Priority</span>
                            </th>
                            <th class="px-16 py-2">
                            <span class="text-gray-300">Date</span>
                            </th>

                            <th class="px-16 py-2">
                            <span class="text-gray-300">Time</span>
                            </th>

                            <th class="px-16 py-2">
                            <span class="text-gray-300">Actions</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-gray-200" id="sortable">
                        @foreach($projects as $project)
                            <tr class="bg-white border-4 border-gray-200 hover:bg-gray-300">
                                <td class="px-16 py-2 items-center">
                                    {{ $project->id }}
                                </td>
                                <td>
                                    <span class="text-center font-semibold">
                                        {{ $project->name }}
                                    </span>
                                </td>
                                <td class="px-16 py-2 text-center">
                                    {{ $project->priority }}
                                </td>
                                <td class="px-16 py-2">
                                    <span>
                                        {{ $project->created_at->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="py-2 text-center">
                                    {{ $project->created_at->format('h:i A') }}
                                </td>
                                <td class="py-2 flex items-center">
                                    <a href="{{ URL::route('projects.edit', $project->id) }}" class="mr-2 bg-indigo-500 text-white px-3 rounded-md hover:bg-indigo-700 hover:text-black ">
                                        Edit
                                    </a>
                                    <form class="form-button" action="{{ URL::route('projects.destroy', $project->id) }}" method="post" onsubmit="confirm('Are you sure?');">
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
    $( "#sortable" ).sortable({
        update: function( event, ui ) {
            const _token = '{{ csrf_token() }}';
            let projectIds = [];
    
            $(this).children().each(function(index, item) {
                projectIds.push($(this).find('td:nth-child(1)').text());
            });

            projectIds = projectIds.map(id => parseInt(id));

            fetch('{{ route('projects.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({_token, items: projectIds})
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
  } );
  </script>
