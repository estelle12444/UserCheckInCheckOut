@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                        <div class="btn-wrapper">
                            <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                            <a href="#" id="exportButtonAbsent" class="btn btn-primary text-white me-0"><i
                                    class="icon-download"></i>Exporter en pdf</a>
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
                                        <div class="card-body" id="historyAbsentEmployeeTable">
                                            @if (!$employees->isEmpty())
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h3 class="mb-0">Liste des Employés Absents</h3>
                                                <h4 class="card-subtitle card-subtitle-dash" style="color:rgb(249, 139, 99);font-weight:600">
                                                    Du {{ $fromDate->format('Y-m-d') }} au {{ $toDate->format('Y-m-d') }}
                                                </h4>
                                            </div>
                                                <p class="card-subtitle card-subtitle-dash">Nous
                                                    avons {{ $nbre }} employés absents </p>
                                                <div class="table-responsive  mt-1">
                                                    <table id="AbsentEmployeeTable" class="table select-table table-hover">
                                                        <thead class="orange">
                                                            <tr>
                                                                <th class="text-white  pl-2">Quicklock ID</th>
                                                                <th class="text-white pl-2">Nom et Poste</th>
                                                                <th class="text-white">Département</th>
                                                                <th class="text-white">Jour d'absence </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($employees as $employee)
                                                                <tr>
                                                                    <td class="pl-2">{{ $employee->matricule }}</td>
                                                                    <td>
                                                                        <div class="d-flex ">
                                                                            <a
                                                                                href="{{ route('employeeDetail', ['id' => $employee->id]) }}">
                                                                                <img src="{{ asset('storage/' . $employee->image_path) }}"
                                                                                    alt="{{ $employee->name }}">
                                                                            </a>
                                                                            <div>
                                                                                <a
                                                                                    href="{{ route('employeeDetail', ['id' => $employee->id]) }}">
                                                                                    <h6>{{ $employee->name }}</h6>
                                                                                </a>
                                                                                <p>{{ $employee->designation }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <h6>{{ App\Helper::searchByNameAndId('department', $employee->department_id)->name ?? '' }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>
                                                                        @if (count($employee->absentDays) > 0)
                                                                            <ul class="list-ticked">
                                                                                @foreach ($employee->absentDays as $absentDay)
                                                                                    <li>{{ $absentDay }}</li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @else
                                                                            <p>Aucun employé absent pendant la période
                                                                                spécifiée.
                                                                            </p>
                                                                        @endif
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

            let start = @json($fromDate->format('Y-m-d'));
            let end = @json($toDate->format('Y-m-d'));
            var fileName = 'Absences_employees' + '_' + start + 'au ' + end + '.xlsx';
            console.log("Nom du fichier", fileName);

            XLSX.writeFile(wb, fileName);
            console.log("ficher telechargé créé avec succès");
        });

        document.getElementById('exportButtonAbsent').addEventListener('click', function() {
            let start = @json($fromDate->format('Y-m-d'));
            let end = @json($toDate->format('Y-m-d'));
            let fileName = 'Absences_employees_' + start + '_to_' + end + '.pdf';
            try {
                var element = document.getElementById('exportButtonAbsent');
                var AbsentEmployeeTable = document.getElementById('AbsentEmployeeTable');
                var opt = {
                    margin: 1,
                    filename: fileName,
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'letter',
                        orientation: 'portrait'
                    }
                };

                html2pdf().set(opt).from(AbsentEmployeeTable).save();
                html2pdf(AbsentEmployeeTable, opt);

            } catch (error) {
                console.error('An error occurred:', error);
            }
        });

        new DataTable('#AbsentEmployeeTable', {
            paging: true,
            pageLength: 15,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            }
        });
    </script>
@endpush
