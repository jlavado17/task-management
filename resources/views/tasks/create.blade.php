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
                    <form action="/tasks" class="flex flex-col space-y-8" method="POST">
                        @csrf
                        <div>
                            <h3 class="text-1xl font-semibold">New Task</h3>
                            <hr>
                        </div>
                        <div class="form-item">
                            <label class="text-md">Name</label>
                            <input type="text" name="name" class="w-full appearance-none text-black text-opacity-70 rounded shadow py-1 px-2  mr-2 focus:outline-none focus:shadow-outline focus:border-blue-200" required>
                        </div>
                        <div class="form-item">
                            <label class="text-md">Project</label>
                            <select id="project_id" name="project_id" class="w-full appearance-none text-black text-opacity-70 rounded shadow py-1 px-2  mr-2 focus:outline-none focus:shadow-outline focus:border-blue-200" required>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" {{ $project->priority == 1 ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-item">
                            <label class="text-md">Priority</label>
                            <select id="priority" name="priority" class="w-full appearance-none text-black text-opacity-70 rounded shadow py-1 px-2  mr-2 focus:outline-none focus:shadow-outline focus:border-blue-200">
                                @for ($i = 1; $i <= $totalTasks; $i++)
                                    <option value="{{ $i }}" {{ $i == $totalTasks ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="p-6 ">
                            <button type="submit" class="p-4 bg-green-400 hover:bg-green-500 w-full rounded-lg shadow text-xs uppercase text-white">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
$( function() {
    const _token = '{{ csrf_token() }}';

    $("#project_id").change(function() {
        fetch('{{ route('tasks.updateSelectPriority') }}', {
            method: 'POST',
            headers: {  
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({_token, project_id: $(this).val() })
        })
        .then(response => response.json())
        .then(data => {
            $("#priority").html(data.html);
        })
        .catch((error) => {
            console.log(error);
        })
    })

});
</script>