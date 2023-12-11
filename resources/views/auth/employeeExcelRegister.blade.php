@extends('layouts')

@section('content')
    {{-- <div class="container-fluid">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Enregistrement de l\'employé') }}</div>

                    <div class="card-body">
                        <p class="mb-4">
                            Bienvenue sur la page d'enregistrement des employés. Vous pouvez utiliser un fichier Excel pour
                            importer plusieurs employés en une seule fois.
                        </p>

                        <div class="alert alert-info">
                            <strong>Instructions :</strong>
                            <ul>
                                <li>Assurez-vous que le fichier Excel suit le format requis.</li>
                                <li>Le fichier doit avoir une ligne d'en-tête avec les colonnes suivantes : <code>matricule,
                                        name, designation, department_id</code>.</li>
                                <li>Le fichier peut contenir plusieurs lignes, une pour chaque employé.</li>
                            </ul>
                        </div>

                        <form action="{{ route('importEmployee') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label">Sélectionnez le fichier Excel</label>
                                <input type="file" class="form-control" name="file" id="file"
                                    accept=".xls, .xlsx" required>
                            </div>

                            <div class="row mb-0 justify-content-center">
                                <div class="col-md-6 text-center">
                                    <button class="btn btn-success" type="submit">
                                        <i class="ti-file btn-icon-prepend"></i> Importer
                                    </button>
                                </div>
                                <div class="col-md-6 text-center">
                                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                        Retour
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Enregistrement de l\'employé') }}</div>

                    <div class="card-body">
                        <p class="mb-4">
                            Vous pouvez utiliser un fichier Excel pour importer plusieurs employés en une seule fois.
                        </p>

                        <div class="alert alert-info">
                            <strong>Instructions :</strong>
                            <ul>
                                <li>Assurez-vous que le fichier Excel suit le format requis.</li>
                                <li>Le fichier doit avoir une ligne d'en-tête avec les colonnes suivantes : <code>matricule,
                                        name, designation, department_id</code>.</li>
                                <li>Le fichier peut contenir plusieurs lignes, une pour chaque employé.</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Sélectionnez le fichier Excel</label>
                            <input type="file" class="form-control" name="file" id="file" accept=".xls, .xlsx" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-success" id="importButton">
                                    <i class="ti-file btn-icon-prepend"></i> Importer
                                </button>
                                <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                    Retour
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="card-title"> Bienvenue sur la page d'enregistrement des employés.</h2>
                                    <p>Vous pouvez utiliser un fichier Excel pour
                                        importer plusieurs employés en une seule fois.</p>
                                    </p>

                                    <div class="alert alert-info">
                                        <strong>Instructions :</strong>
                                        <ul>
                                            <li>Assurez-vous que le fichier Excel suit le format requis.</li>
                                            <li>Le fichier doit avoir une ligne d'en-tête avec les colonnes suivantes :
                                                <code>matricule,
                                                    name, designation, department_id</code>.
                                            </li>
                                            <li>Le fichier peut contenir plusieurs lignes, une pour chaque employé.</li>
                                        </ul>
                                    </div>


                                    <div class="card-body d-flex justify-content-between">

                                        <button class="btn btn-primary text-white" id="showSwalButton">
                                            Afficher l'example
                                        </button>
                                        <button type="button" class="btn btn-primary text-white" data-toggle="modal"
                                            data-target="#exampleModal">
                                            Afficher Listes des départements
                                        </button>
                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Listes des
                                                            départements</h5>
                                                        <button type="button" class="btn btn-danger text-white"
                                                            class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="d-flex justify-content-center">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Nom du département</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach (config('department') as $jobTitle)
                                                                    <tr>
                                                                        <td>{{ $jobTitle['id'] }}</td>
                                                                        <td>{{ $jobTitle['name'] }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary text-white"
                                                            data-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-success text-white" id="showFormButton">
                                            Importation
                                        </button>
                                    </div>
                                    <form action="{{ route('importEmployee') }}" method="POST"
                                        enctype="multipart/form-data" id="importForm" style="display:none;">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="file " class="form-label ">Sélectionnez le fichier
                                                    Excel</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="file" class="form-control col-md-8" name="file"
                                                    id="file" accept=".xls, .xlsx" required>
                                            </div>
                                        </div>

                                        <div class="row mb-0 justify-content-center">
                                            <div class="col-md-6 text-center">
                                                <button class="btn btn-success text-white" type="submit">
                                                    <i class="ti-file btn-icon-prepend"></i> Importer
                                                </button>
                                            </div>

                                        </div>
                                    </form>

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
        var showSwalButton = document.getElementById('showSwalButton');
        var showFormButton = document.getElementById('showFormButton');
        var importForm = document.getElementById('importForm');


        showFormButton.addEventListener('click', function() {
            showFormButton.style.display = 'none';
            showSwalButton.style.display = 'block';
            importForm.style.display = 'block';
        });
        document.getElementById('showSwalButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Aperçu du format du fichier Excel',
                html: '<table class="table">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>matricule</th>' +
                    '<th>nom</th>' +
                    '<th>designation</th>' +
                    '<th>department_id</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>' +
                    '<tr>' +
                    '<td>123</td>' +
                    '<td>John Doe</td>' +
                    '<td>Manager</td>' +
                    '<td>1</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>456</td>' +
                    '<td>Jane Smith</td>' +
                    '<td>Developer</td>' +
                    '<td>2</td>' +
                    '</tr>' +
                    '</tbody>' +
                    '</table>',
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: 'Fermer',
                confirmButtonColor: '#007BFF'
            });
        });
    </script>
@endpush
