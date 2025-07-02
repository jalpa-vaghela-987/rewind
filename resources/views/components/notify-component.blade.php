@props(['id', 'maxWidth'])

@php
    $id = $id ?? md5($attributes->wire('model'));

    $maxWidth = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth ?? '2xl'];
@endphp
    <!-- x-on:keydown.escape.window="show = false" -->
<style>
    @media only screen and (max-width: 992px) {
        .pop-modal {
            transform: none !important;
            min-width: 100vw !important;
            left: 0px;
        }
    }
    .pop-modal{
        top: 0px!important;
        bottom: 0px!important;
        left: 20px!important;
        border-radius: 16px;
        border: 0px solid;
        min-width: 600px;
        z-index: 100000;
        position:absolute!important;
        inset: 0px auto auto 0px;
        margin: 0px;
        transform: translate(198px, 0px);
    }
    .jetstream-modal .popover-arrow {
        display: block;
        width: 1rem;
        height: .5rem;
        position: absolute;
        top: calc(100vh - 140px);
        left: 209px;

    }

    .jetstream-modal .popover-arrow:before,.jetstream-modal .popover-arrow:after {
        position: absolute;
        display: block;
        content: "";
        border-color: transparent;
        border-style: solid;
        border-width: 0;
    }
    .jetstream-modal>.popover-arrow:before {
        left: 0;
        border-right-color: rgba(0,0,0,.175);
    }

    .jetstream-modal>.popover-arrow:before,  .jetstream-modal>.popover-arrow:after {
        border-width: calc(1rem * .5) .5rem calc(1rem * .5) 0;
    }

    .jetstream-modal>.popover-arrow:after {
        left: 1px;
        border-right-color:  #fff;
    }

    .pop-modal .scrollable-content {
        overflow: auto;
        max-height: calc(100vh - 100px);
    }

    .pop-modal .day {
        margin-bottom: 24px;
    }

    .pop-modal .date {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 8px;
        color: #000;
    }

    .pop-modal .info {
        font-weight: 200;
        font-size: 14px;
        /*line-height: 35px;*/
        color: #000;
    }

    .pop-modal .info a{
        text-decoration: none;
        color: #1d7db2;
        font-weight: 900;
    }

    @media (max-width: 989px) {
        .pop-modal{
            left:0px !important;
            top: 37px!important;
        }
        .jetstream-modal .popover-arrow {
            display: none;
        }
    }

</style>
<div
    x-data="{ show: @entangle($attributes->wire('model')).defer }"
    x-on:close.stop="show = false"
    x-show="show"
    id="{{ $id }}"
    class="jetstream-modal fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 pop-modal-1"
    style="display: none;"
>


    <!-- x-on:click="show = false" -->
    <div x-show="show" class="fixed inset-0 transform transition-all" x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
    <div class="popover-arrow opacity-100" style=""></div>
    <div x-show="show"
         class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto pop-modal"
         x-trap.inert.noscroll="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 ">

        {{ $slot }}
    </div>
</div>
