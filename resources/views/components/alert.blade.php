<div class="mt-2 block text-sm text-red-600 bg-red-200 border border-red-400 h-12 flex items-center p-4 rounded-sm relative">
    <h2 class="text-red-700">{{ $slot }}</h2>
    <button type="button" data-dismiss="alert" aria-label="Close" onclick="this.parentElement.remove();">
        <span class="absolute top-0 bottom-0 right-0 text-2xl px-3 py-1 hover:text-red-900" aria-hidden="true">×</span>
    </button>
</div>