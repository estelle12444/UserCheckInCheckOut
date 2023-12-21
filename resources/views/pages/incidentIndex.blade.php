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
                                <div class="card-body">
                                    <h2 class="card-title text-center"><strong class="text-info">Incident
                                            ID:{{ $request->id }}</strong></h2>

                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <img src="{{ asset('storage/' . $request->image) }}"
                                                style="height: auto; width: 160px;" alt="{{ $request->employee->name }}">
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <p><strong>Quicklock ID:</strong> {{ $request->employee->matricule }}</p>
                                            <p><strong>Nom:</strong> {{ $request->employee->name }}</p>
                                            <p><strong>Statut:</strong> {{ $request->status }}</p>
                                            <p><strong>Création:</strong> {{ $request->created_at }}</p>
                                        </div>
                                    </div>

                                    @if (!$request->accepter)
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <form method="post" action="{{ route('incidents.accept', $request->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-white btn btn-success">Accepter</button>
                                                </form>
                                            </div>
                                            <div class="col-md-6">
                                                <form method="post" action="{{ route('incidents.reject', $request->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-white btn btn-danger">Refuser</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <p class="mt-2">Incident déjà accepté</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
