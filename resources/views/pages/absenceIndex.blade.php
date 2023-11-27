@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">

                        <div class="btn-wrapper">

                            <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                            <a href="#" id="exportButton" class="btn btn-primary text-white me-0"><i
                                    class="icon-download"></i>
                                Exporter en pdf</a>
                            <a href="#" id="AbsenceButtonExcell" class="btn btn-success text-white me-0"><i
                                    class="icon-download"></i>
                                Exporter en excel</a>
                        </div>

                    </div>
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row flex-grow">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded">
                                        <div class="card-body" id="historyTable">

                                            @if (!$absence->isEmpty())
                                                <h3>Employés Absents pour la journée du {{ $date }}</h3>
                                                <p class="card-subtitle card-subtitle-dash">Nous
                                                    avons {{ $nbre }} employés absents </p>
                                                <div class="table-responsive  mt-1">
                                                    <table id="AbsentEmployeeTable" class="table select-table table-hover">
                                                        <thead class="orange">
                                                            <tr>
                                                                <th class="text-white  pl-2">Quicklock ID</th>
                                                                <th class="text-white pl-2">Nom et Poste</th>
                                                                <th class="text-white">Département</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($absence as $employee)
                                                                <tr>
                                                                    <td class="pl-2">{{$employee->matricule}}</td>
                                                                    <td >
                                                                        <div class="d-flex ">
                                                                            <a
                                                                                href="{{ route('employeeDetail', ['id' => $employee->id]) }}">
                                                                                <img src="{{ asset($employee->image_path) }}"
                                                                                    alt="{{ $employee->name }}">
                                                                            </a>
                                                                            <div>
                                                                                <a
                                                                                    href="{{ route('employeeDetail', ['id' => $employee->id]) }}">
                                                                                    <h6>{{ $employee->name }}
                                                                                    </h6>
                                                                                </a>
                                                                                <p>{{ $employee->designation }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <h6>{{ App\Helper::searchByNameAndId('department', $employee->department_id)->name ?? '' }}
                                                                        </h6>
                                                                        </p>
                                                                    </td>

                                                                </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p>Aucun employé absents</p>
                                            @endif
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
        document.getElementById('AbsenceButtonExcell').addEventListener('click', function() {
            var table = document.getElementById('AbsentEmployeeTable');
            console.log(" table selectionné OK");

            var ws = XLSX.utils.table_to_sheet(table);
            console.log(" objet ws OK", ws);

            XLSX.utils.sheet_add_aoa(ws, [], {
                origin: 'A1'
            });
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Feuille1');


            var fileName = 'Absences' + '_' + 'employees' + '.xlsx';
            console.log("Nom du fichier", fileName);

            XLSX.writeFile(wb, fileName);
            console.log("ficher telechargé créé avec succès");
        });
    </script>
@endpush
