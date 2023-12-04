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
                            <a href="#" id="ButtonExcel" class="btn btn-success text-white me-0"><i
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
                                            <div class="d-sm-flex justify-content-between align-items-start">
                                                <div>
                                                    <h3 class="card-title card-title-dash">Listes des employés du site :
                                                        <strong class="orange">{{ $site->name ?? ' ' }}</strong>
                                                    </h3>
                                                    <p class="card-subtitle card-subtitle-dash">Nous
                                                        avons {{ $filtreEmployees }} employées</p>
                                                </div>

                                            </div>
                                            <div class="table-responsive  mt-1">
                                                <table id="employeeTable" class="table select-table table-hover">
                                                    <thead class="orange">
                                                        <tr>

                                                            <th class="text-white pl-2">Employée</th>
                                                            <th class="text-white">Département</th>
                                                            <th class="text-white">Date </th>
                                                            <th class="text-white"> Entrée</th>
                                                            <th class="text-white"> Sortie</th>
                                                            <th class="text-white"> Heure travaillée</th>
                                                            <th class="text-white"> Panier de flexibilité</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($history_entries as $history_entry)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex ">
                                                                        <a
                                                                            href="{{ route('employeeDetail', ['id' => $history_entry->employee->id]) }}">
                                                                            <img src="{{ asset($history_entry->employee->image_path) }}"
                                                                                alt="{{ $history_entry->employee->name }}">
                                                                        </a>
                                                                        <div>
                                                                            <a
                                                                                href="{{ route('employeeDetail', ['id' => $history_entry->employee->id]) }}">
                                                                                <h6>{{ $history_entry->employee->name }}
                                                                                </h6>
                                                                            </a>
                                                                            <p>{{ $history_entry->employee->designation }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <h6>{{ App\Helper::searchByNameAndId('department', $history_entry->employee->department_id)->name ?? '' }}
                                                                    </h6>
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <h6>{{ $history_entry->day_at_in }}</h6>
                                                                </td>
                                                                <td>
                                                                    <h6 class="marron">{{ $history_entry->time_at_in }}
                                                                    </h6>
                                                                </td>

                                                                <td>
                                                                    @if ($history_entry->day_at_out && $history_entry->time_at_out)
                                                                        <h6 class="marron">
                                                                            {{ $history_entry->time_at_out }}</h6>
                                                                    @else
                                                                        Pas encore sorti
                                                                    @endif

                                                                </td>
                                                                <td>
                                                                    {{ App\Helper::getHeuresEmployesParJour($history_entry->employee->id, $history_entry->day_at_in) }}

                                                                </td>
                                                                <td>
                                                                    {{ App\Helper::getTimeFlexParJour($history_entry->employee->id, $history_entry->day_at_in) }}

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
        document.getElementById('ButtonExcel').addEventListener('click', function() {
            var table = document.getElementById('employeeTable');
            console.log(" table selectionné OK");

            var ws = XLSX.utils.table_to_sheet(table);
            console.log(" objet ws OK", ws);

            XLSX.utils.sheet_add_aoa(ws, [], {
                origin: 'A1'
            });
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Feuille1');

            var siteName = @json($site->name);

            var fileName = siteName + '_' + 'employees' + '.xlsx';
            console.log("Nom du fichier", fileName);

            XLSX.writeFile(wb, fileName);
            console.log("ficher telechargé créé avec succès");
        });
        new DataTable('#employeeTable', {
            paging: true,
            pageLength: 5,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            }
        });
    </script>
@endpush
