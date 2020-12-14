@extends('app')

@section('title')
    Play
@endsection

@section('content')
    @include('partials.header')
    @livewire('play')
@endsection
