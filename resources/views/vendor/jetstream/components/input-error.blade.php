@props(['for'])

@error($for)
    <span {{ $attributes->merge(['class' => 'error-message mt-2 d-flex align-items-center']) }}>
        <svg class="icon icon-Attention me-2" width="16" height="16">
            <use href="{{asset('img/icons.svg#icon-Attention')}}"></use>
        </svg>
        {{ $message }}
    </span>
@enderror
