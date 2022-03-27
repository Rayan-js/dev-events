@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')



<div class="col-md-10 offset-md-1 dashboard-title-container">
    <h1>Meus Eventos</h1>
</div>

<div class="col-md-10 offset-md-1 dashboard-events-container">
    <h1>Meus Eventos</h1>
</div>

@if($event->isEmpty())
        
    <h1>Você não possui nenhum evento, <a href="/events/create">Criar Evento</a></h1>

@endif

@endsection
