@extends('layouts')

@section('content')
    <div class="content-wrapper">

        <h2 class="card-title">Liste des Incidents en attente</h2>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="home-tab">
            <div class="tab-content tab-content-basic">
                <div class="row grid grid-col-4 gap-2 md:grid-cols-3">
                    @foreach ($incidents as $request)
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body ">
                                    <h2 class="card-title text-center"> <strong class="text-info">Incident ID:{{ $request->id }} </strong></h2>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <img src="{{ asset('storage/' . $request->image) }}"
                                                style="height: 150px;width:90px" alt="{{ $request->employee->name }}">
                                        </div>
                                        <div class="col-md-7">
                                            <p><strong>Quicklock ID :</strong>{{ $request->employee->matricule }}</p>
                                            <p><strong>Nom :</strong> {{ $request->employee->name }}</p>
                                            <p><strong>Statut :</strong>{{ $request->status }}</p>
                                            <p><strong>Création:</strong>{{ $request->created_at }}</p>


                                        </div>
                                        @if (!$request->accepter)
                                            <div class="d-flex mt-2">
                                                <form method="post" action="{{ route('incidents.accept', $request->id) }}">
                                                    @csrf
                                                    <button type="submit" class="text-white btn btn-success">Accepter
                                                    </button>
                                                </form>
                                                <form method="post" action="{{ route('incidents.reject', $request->id) }}">
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

                                </div>


                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
