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
                                                    <h4 class="card-title card-title-dash">Listes des employées du
                                                        dashboard
                                                    </h4>
                                                    <p class="card-subtitle card-subtitle-dash">Nous
                                                        avons {{ $employeeCount }} employées</p>
                                                </div>

                                            </div>
                                            <div class="table-responsive  mt-1">
                                                <table class="table select-table table-hover ">
                                                    <thead class="orange ">
                                                        <tr>
                                                            <th class="text-white pl-2">Nom</th>
                                                            <th class="text-white">Designation</th>
                                                            <th class="text-white">Matricule</th>
                                                            <th class="text-white">Administration</th>
                                                            <th class="text-white"> Detail</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($employees as $employee)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex ">
                                                                        @if ($employee->image_path)
                                                                            <img src="{{ asset($employee->image_path) }}"
                                                                                alt="{{ $employee->name }}">
                                                                        @else
                                                                            Aucune image
                                                                        @endif
                                                                        <div>
                                                                            <h6>{{ $employee->name }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $employee->designation }}</td>
                                                                <td>{{ $employee->matricule }}</td>
                                                                <td>
                                                                    @if ($employee->activated)
                                                                        <form
                                                                            action="{{ route('deactivate.employee', $employee->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class=" text-white btn btn-success"
                                                                                type="submit">Accès</button>
                                                                        </form>
                                                                    @else
                                                                        <form
                                                                            action="{{ route('activate.employee', $employee->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <button class=" text-white btn btn-danger"
                                                                                type="submit">Pas Accès</button>
                                                                        </form>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a class=" text-white btn btn-info"
                                                                        href="{{ route('employeeDetail', ['id' => $employee->id]) }}">
                                                                        Voir Plus </a>
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
