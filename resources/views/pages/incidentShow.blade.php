@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">

                        <div class="col-md-9 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body ">
                                    <div class="row">
                                        <div class="col-md-8">
                                        <h2 class="card-title">Détails de l'incident</h2>
                                        <p>ID de l'incident : {{ $incident->id }}</p>
                                        <p>Nom de l'employée: {{ $incident->employee->name }}</p>
                                        <p>Description : {{ $incident->description }}</p>
                                        <p>Statut : {{ $incident->status }}</p>

                                        @if (!$incident->accepted)
                                        <div class="d-flex">
                                            <form method="post" action="{{ route('incidents.accept', $incident->id) }}">
                                                @csrf
                                                <button type="submit" class="text-white btn btn-success mr-2">Accepter l'incident</button>
                                            </form>
                                            <form method="post" action="{{ route('incidents.reject', $incident->id) }}">
                                                @csrf
                                                <button type="submit" class="text-white btn btn-danger">Refuser l'incident</button>
                                            </form>
                                            <button href="{{route('incidents.index')}}" class=" btn btn-light"> Retour</button>
                                        </div>
                                        @else
                                            <p>Incident déjà accepté</p>
                                        @endif
                                        </div>
                                        <div class="col-md-4">
                                            <img src="{{ asset('storage/' . $incident->image) }}" alt="{{ $incident->employee->name }}" >
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
