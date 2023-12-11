@extends('layouts')

@section('content')
    <div class="content-wrapper">

        {{-- <div class="home-tab">
            <h2 class="card-title">Liste des Incidents A supprimer </h2>
            <div class="bg-info">
                <p class="text-white "> Pour les différentes images figurantes dans cette partie nous n'avons pas pu
                    identifier de visage à l'intérieur des photos fournis</p>
            </div>
            <div class="tab-content tab-content-basic">
                <div class="row">
                    @foreach ($listDelete as $request)
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
                                                <form action="{{ route('incidents.destroy', $request) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger text-white"
                                                        onclick="return confirm('Are you sure you want to delete this incident?')">Delete</button>
                                                </form>
                                                @if (session('success'))
                                                    <div class="alert alert-success">
                                                        {{ session('success') }}
                                                    </div>
                                                @endif

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
        </div> --}}
        <div class="home-tab">
            <h2 class="card-title">Liste des incidents à supprimer</h2>
            <h6 class=" my-4 card-subtitle card-subtitle-dash text-info ">Pour les différentes images figurantes dans cette partie nous n'avons pas pu
                identifier de visage à l'intérieur des photos fournis</h6>

            <div class="card card-rounded">
                <div class="row">

                    <div class="col-12 grid-margin stretch-card">
                    <div class="table-responsive  mt-1">
                        @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                        <table id="RejectTable" class="table select-table  table-hover">
                            <thead class="orange">
                                <tr>
                                    <th class="text-white pl-2">Incident ID </th>
                                    <th class="text-white">Image</th>
                                    <th class="text-white">Quiclock ID</th>
                                    <th class="text-white">Statut</th>
                                    <th class="text-white">Création</th>
                                    <th class="text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($listDelete)
                                    @foreach ($listDelete as $request)
                                        <tr>
                                            <td class="pl-2 text-center">{{ $request->id }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $request->image) }}"style="height: 60px;width:70px"
                                                alt="{{ $request->employee->name }}">
                                            </td>
                                            <td >
                                                 <h6>{{ $request->employee->matricule }}</h6>
                                                <h6>{{ $request->employee->name }}</h6>
                                                <p>{{ $request->employee->designation }}</p>
                                            </td>
                                            <td><h6 class="text-danger">{{ $request->status }}</h6></td>
                                            <td><h6>{{ $request->created_at }}</h6></td>
                                            <td>
                                                <div class="d-flex mt-2">
                                                    <form action="{{ route('incidents.destroy', $request) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger text-white"
                                                            onclick="return confirm('Are you sure you want to delete this incident?')">supprimer</button>
                                                    </form>
                                                    @if (session('success'))
                                                        <div class="alert alert-success">
                                                            {{ session('success') }}
                                                        </div>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <p>Aucune donnée disponible </p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
