@for ($i = 1; $i <= $totalTasks; $i++)
    <option value="{{ $i }}" {{ $i == $totalTasks ? 'selected' : '' }}>{{ $i }}</option>
@endfor