@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                        <div class="btn-wrapper">


                            <a href="{{ route('employeeRegisterForm') }}"  class="btn btn-primary text-white me-0"><i class="mdi mdi-account-plus"></i>
                                Enregistrer un nouvel employé</a>
                            <a href="{{ route('employeeRegister') }}" class="btn btn-success text-white me-0">
                                <i class="mdi mdi-account-plus"></i> Importer un fichier excel </a>
                        </div>
                    </div>
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row flex-grow">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded">
                                        <div class="card-body">
                                            <div class="d-sm-flex justify-content-between align-items-start">
                                                <div>
                                                    <h4 class="card-title card-title-dash">Listes des employées de Sah
                                                        Analytics
                                                    </h4>
                                                    <p class="card-subtitle card-subtitle-dash">Nous
                                                        avons {{ $employeeCount }} employées</p>
                                                </div>


                                            </div>
                                            <div class="table-responsive  mt-1">
                                                <table id="employeeList" class="table select-table table-hover ">
                                                    <thead class="orange ">
                                                        <tr>
                                                            <th class="text-white pl-2">Employée</th>
                                                            <th class="text-white">Departement</th>
                                                            <th class="text-white">Matricule</th>
                                                            <th class="text-white">Administration</th>
                                                            <th class="text-white text-center"> Actions</th>
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
                                                                            {{-- <img src="{{ asset('storage/' . $employee->image_path) }}"
                                                                                alt="{{ $employee->name }}"> --}}
                                                                        @else
                                                                        <img src="{{ asset('images/default.png')}}" alt="{{ $employee->name }}">
                                                                        @endif
                                                                        <div>
                                                                            <h6>{{ $employee->name }}</h6>
                                                                            <p>{{ $employee->designation }}</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td >{{ App\Helper::searchByNameAndId('department', $employee->department_id)->name ?? '' }}</td>
                                                                <td >{{ $employee->matricule }}</td>
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
                                                                    <a class=" text-white btn btn-danger btn-icon-text"
                                                                        href="{{ route('employees.show', ['id' => $employee->id]) }}">
                                                                        <i class="mdi mdi-pencil"></i></a>
                                                                    <a class=" text-white btn btn-primary"
                                                                        href="{{ route('employeeDetail', ['id' => $employee->id]) }}">
                                                                        <i class="mdi mdi-account-details"></i> </a>
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
@push('scripts')
    <script>
        new DataTable('#employeeList', {
            paging: true,
            pageLength: 10,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            }
        });
    </script>
@endpush
