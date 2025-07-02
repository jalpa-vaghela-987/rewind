@php
    //this code is added, because in safari, classes is not applied on <a> tag. and working fine with <button> tag
    //if we dont mind performance issue with safari, then we can revert below code.

    $mergedAttributes = [
       'type' => 'button',
       'class' => 'inline-flex items-center px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest  active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150'
    ];

    if ($attributes->has('href')) {
       $mergedAttributes = array_merge($mergedAttributes, [
           'onclick' => "window.open('".$attributes->get('href')."', '".$attributes->get('target', '_self')."')",
       ]);
    }
@endphp

<button {{ $attributes->merge($mergedAttributes) }}>
    {{ $slot }}
</button>

