@extends('app')

@section('title')
    Play
@endsection

@section('content')
    @include('partials.header')
    @livewire('play')
@endsection

@push('styles')
    <style>
        #play {
            cursor: pointer;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateY(-50%) translateX(-50%);
        }
        .stroke-dotted {
            opacity: 0;
            stroke-dasharray: 4,5;
            stroke-width: 1px;
            transform-origin: 50% 50%;
            animation: spin 4s infinite linear;
            transition: opacity 1s ease,
            stroke-width 1s ease;
        }
        .stroke-solid {
            stroke-dashoffset: 0;
            stroke-dashArray: 300;
            stroke-width: 4px;
            transition: stroke-dashoffset 1s ease,
            opacity 1s ease;
        }
        #play:hover .stroke-dotted {
            stroke-width: 4px;
            opacity: 1;
        }
        #play:hover .stroke-solid {
            opacity: 0;
            stroke-dashoffset: 300;
        }
        #play:hover .icon {
            transform: scale(1.05);
        }
        .icon {
            transform-origin: 50% 50%;
            transition: transform 200ms ease-out;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
@endpush
