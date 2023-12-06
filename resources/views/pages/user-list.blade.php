@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">

                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row flex-grow">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded">
                                        <div class="card-body">
                                            <div class="d-sm-flex justify-content-between align-items-start">
                                                <div>
                                                    <h4 class="card-title card-title-dash">Administrateurs du Tableau de
                                                        bord
                                                    </h4>
                                                    <p class="card-subtitle card-subtitle-dash">Nous
                                                        avons {{ $userCount }} utilisateurs</p>
                                                </div>

                                            </div>
                                            <div class="table-responsive  mt-1">
                                                <table class="table select-table table-hover">
                                                    <thead class="orange">
                                                        <tr>
                                                            <th class="text-white pl-2">Nom et Email</th>
                                                            <th class="text-white">Role</th>
                                                            <th class="text-white">Status</th>
                                                            <th class="text-white">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($users as $user)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex ">
                                                                        @if ($user->employee && $user->employee->image_path)
                                                                            <img src="{{ asset('storage/' . $user->employee->image_path) }}"
                                                                                alt="{{ $user->name }}">
                                                                        @else
                                                                            <img src="{{ asset('images/default.png') }}"
                                                                                alt="{{ $user->name }}">
                                                                        @endif
                                                                        <div>
                                                                            <h6>{{ $user->name }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <p>{{ $user->role->name }}</p>
                                                                </td>
                                                                <td>
                                                                    <h6>{{ $user->activated ? 'Actif' : 'Inactif' }}</h6>
                                                                </td>
                                                                <td>
                                                                    @if ($user->activated)
                                                                        <form
                                                                            action="{{ route('deactivate.user', $user->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button class=" text-white btn btn-danger"
                                                                                type="submit">Désactiver</button>
                                                                        </form>
                                                                    @else
                                                                        <form
                                                                            action="{{ route('activate.user', $user->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button
                                                                                class=" text-white btn btn-success"type="submit">Activer</button>
                                                                        </form>
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
    </div>
@endsection
