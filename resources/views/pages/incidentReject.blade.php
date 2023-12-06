@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <h2 class="card-title">Liste des Incidents Refusés</h2>
                    <div class="tab-content tab-content-basic">

                        @foreach ($listRejects as $request)
                            <div class="col-md-4 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body ">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <img src="{{ asset('storage/' . $request->image) }}"
                                                    alt="{{ $request->employee->name }}">
                                            </div>
                                            <div class="col-md-7">
                                                <h2 class="card-title"> Incident ID:{{ $request->id }}</h2>
                                                <p>Quicklock ID : {{ $request->employee->matricule }}</p>
                                                <p>Nom : {{ $request->employee->name }}</p>

                                                <p>Statut : <strong class="text-danger">{{ $request->status }} </strong></p>
                                            </div>
                                            @if (!$request->accepted)
                                                <div class="d-flex mt-2">
                                                    <form method="post"
                                                        action="{{ route('incidents.accept', $request->id) }}">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-white btn btn-success mr-2">Accepter
                                                        </button>
                                                    </form>

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
    </div>
@endsection
