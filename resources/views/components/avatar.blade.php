@props(['src'=>null])
<div {{$attributes->merge(['class'=>"shrink-0 inline-flex items-center justify-center "])}}>
    @if ($src)
    <img class="shrink-0 w-full h-full object-cover object-center rounded-full"
        src="{{ $src }}" 
    />
    @endif

    @if (!$src)

    <svg width="16" height="16" fill="currentColor" class="bi bi-person-fill shrink-0 w-full h-full text-gray-300 bg-gray-100 darck:bg-gray-600 rounded-full" viewBox="1 0 14 14">
        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
    </svg>
    @endif
</div>