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
                                                    <h4 class="card-title card-title-dash">Listes des utilisateurs du
                                                        dashboard
                                                    </h4>
                                                    <p class="card-subtitle card-subtitle-dash">Nous
                                                        avons {{ $userCount }} utilisateurs</p>
                                                </div>

                                            </div>
                                            <div class="table-responsive  mt-1">
                                                <table class="table select-table">
                                                    <thead>
                                                        <tr>
                                                            {{-- <th>
                                                                <div class="form-check form-check-flat mt-0">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input"
                                                                            aria-checked="false"><i
                                                                            class="input-helper"></i></label>
                                                                </div>
                                                            </th> --}}
                                                            <th>Nom et EMail</th>
                                                            <th>Role</th>

                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($users as $user)
                                                            <tr>

                                                                <td>

                                                                    <div class="d-flex ">
                                                                        @if ($user->employee && $user->employee->image_path)
                                                                        <img src="{{ asset($user->employee->image_path) }}" alt="{{ $user->name }}">
                                                                    @else
                                                                        Aucune image
                                                                    @endif
                                                                        <div>
                                                                            <h6>{{ $user->name  }}</h6>

                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <p>{{ $user->role->name }}</p>

                                                                </td>
                                                                <td><h6>{{ $user->activated ? 'Activé' : 'Inactif' }}</h6></td>
                                                                <td>
                                                                    @if ($user->activated)
                                                                        <form
                                                                            action="{{ route('deactivate.user', $user->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button class=" text-white btn btn-danger" type="submit">Désactiver</button>
                                                                        </form>
                                                                    @else
                                                                        <form
                                                                            action="{{ route('activate.user', $user->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button class=" text-white btn btn-success" type="submit">Activer</button>
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
