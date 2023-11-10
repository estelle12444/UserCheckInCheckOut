@extends('layouts')

@section('content')

        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <div class="home-tab">

                        <div class="tab-content tab-content-basic">
                            <div class="tab-pane fade show active" id="overview" role="tabpanel"
                                aria-labelledby="overview">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Listes des utilisateurs du dashboard
                                                        </h4>
                                                        <p class="card-subtitle card-subtitle-dash">Nous
                                                            avons 2 utilisateurs</p>
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
                                                                <th>Utilisateur</th>
                                                                <th>Role</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($users as $user)
                                                            <tr>

                                                                <td>
                                                                    <div class="d-flex ">
                                                                        <img src="images/faces/face1.jpg" alt="">
                                                                        <div>
                                                                            <h6>{{ $user->name }}</h6>
                                                                            <p>{{ $user->email }}</p>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                            <p>{{ $user->role->name }}</p>

                                                                </td>
                                                                <td>
                                                                    <a href="#" type="button" class="btn btn-success text-white"> Activer</a>

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
