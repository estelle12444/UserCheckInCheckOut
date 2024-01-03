@extends('layouts')

@section('content')
    <div class="content-wrapper">

        <div class="home-tab">
            <h2 class="card-title">Liste des incidents Acceptés</h2>
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
                                        {{-- <th class="text-white">Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($listAccept)
                                        @foreach ($listAccept as $request)
                                            <tr>
                                                <td class="pl-2 text-center">{{ $request->id }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $request->image) }}"style="height: 100px;width:auto"
                                                        alt="{{ $request->employee->name }}">
                                                </td>
                                                <td>
                                                    <h6>{{ $request->employee->matricule }}</h6>
                                                    <h6>{{ $request->employee->name }}</h6>
                                                    <p>{{ $request->employee->designation }}</p>
                                                </td>
                                                <td>
                                                    <h6 class="text-success">{{ $request->status }}</h6>
                                                </td>
                                                <td>
                                                    <h6>{{ $request->created_at }}</h6>
                                                </td>
                                                {{-- <td>
                                                    @if (!$request->accepter)
                                                        <div class="d-flex mt-2">

                                                            <form action="{{ route('incidents.destroy', $request) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger text-white"
                                                                    onclick="return confirm('Are you sure you want to delete this incident?')">Delete</button>
                                                            </form>

                                                        </div>
                                                    @else
                                                        <p>Incident déjà accepté</p>
                                                    @endif

                                                </td> --}}
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
