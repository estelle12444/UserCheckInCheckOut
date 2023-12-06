@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <h2 class="card-title">Liste des Incidents Résolus</h2>
                    <div class="tab-content tab-content-basic">

                        @foreach ($listAccepts  as $request)
                            <div class="col-md-4 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body ">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <h2 class="card-title"> Incident ID:{{$request->id}}</h2>
                                                <p>Quicklock ID : {{ $request->employee->matricule }}</p>
                                                <p>Nom : {{ $request->employee->name }}</p>

                                                <p>Statut : <strong class="text-danger">{{ $request->status }} </strong></p>

                                                @if (!$request->accepted)
                                                    <div class="d-flex">
                                                        {{-- <form method="post"
                                                            action="{{ route('incidents.accept', $request->id) }}">
                                                            @csrf
                                                            <button type="submit"
                                                                class="text-white btn btn-success mr-2">Accepter
                                                                </button>
                                                        </form> --}}
                                                        <form method="post"
                                                            action="{{ route('incidents.reject', $request->id) }}">
                                                            @csrf
                                                            <button type="submit" class="text-white btn btn-danger">Refuser
                                                                </button>
                                                        </form>
                                                        {{-- <button href="{{ route('incidents.index') }}"
                                                            class=" btn btn-light"> Retour</button> --}}
                                                    </div>
                                                @else
                                                    <p>Incident déjà accepté</p>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <img src="{{ asset('storage/' . $request->image) }}"
                                                    alt="{{ $request->employee->name }}">
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
