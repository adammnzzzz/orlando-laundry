@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 bg-indigo-50 text-sm font-bold leading-5 text-indigo-700 rounded-xl transition duration-150 ease-in-out'
            : 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold leading-5 text-slate-500 hover:text-indigo-600 hover:bg-slate-50 rounded-xl transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
