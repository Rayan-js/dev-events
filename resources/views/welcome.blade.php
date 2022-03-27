@extends('layouts.main')

@section('title', 'Dev Events')

@section('content')

    <!-- <img src="/img/banner.jpg" alt="banner" class="banner"> -->

<div id="search-container" class="col-md-12">
    @if($search)
        <h1>{{ $search }}</h1>
    @else
        <h1>Busque um evento</h1>
    @endif
    <form action="/" method="GET">
        <input type="text" id="search" name="search" class="form-control" placeholder="Procurar...">
    </form>
</div>
<div id="events-container" class="col-md-12">
    @if($search)
        <h1>Buscando por {{$search}}</h1>
    @else
        <h2>Proximos eventos</h2>
    <p class="subtitle">
        Veja os eventos dos próximos dias
    </p>
    @endif
    <!-- <h2>Proximos eventos</h2>
    <p class="subtitle">
        Veja os eventos dos próximos dias
    </p> -->
    <div id="cards-container" class="row">
        @foreach($events as $event)
            <div class="card col-md-3">
                <img src="/img/events/{{ $event->image }}" alt="{{$event->title}}">
                <div class="card-body">
                    <div class="card-date">{{date('d/m/Y', strtotime($event->date))}}</div>
                    <h5 class="card-title">{{$event->title}}</h5>
                    <p class="card-participantes">{{ count($event->users)}} participantes</p>
                    <a href="/events/{{$event->id}}" class="btn btn-primary">Saber mais</a>
                </div>

            </div>
        @endforeach

        @if($events->isEmpty())
        
            <h1>Não foi possivel encontrar nenhum evento com {{$search}}! <a href="/">Ver todos</a></h1>

        @else
            <h2>Proximos eventos</h2>
            <p class="subtitle">
                Veja os eventos dos próximos dias
            </p>
        @endif
    </div>
</div>
@endsection