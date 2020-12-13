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