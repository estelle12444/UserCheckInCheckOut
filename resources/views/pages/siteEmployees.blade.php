@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">

                        <div class="btn-wrapper">

                            <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                            <a href="#" id="exportButtonSite" class="btn btn-primary text-white me-0"><i
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
                                        <div class="card-body" id="siteEmployeeTable">
                                            <div class="d-sm-flex justify-content-between align-items-start">
                                                <div>
                                                    <div
                                                        class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                                                        <h3 class="card-title card-title-dash mb-2 mb-sm-0">
                                                            Liste des employés du site : <strong
                                                                class="orange">{{ $site->name ?? ' ' }}</strong>
                                                        </h3>
                                                        <h3 class="ml-4 card-title card-title-dash mb-2 mb-sm-0"
                                                            style="color:rgb(249, 139, 99);font-weight:700">
                                                            Du {{ $dateRange['start']->format('Y-m-d') }} au
                                                            {{ $dateRange['end']->format('Y-m-d') }}
                                                        </h3>
                                                    </div>
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
                                                            <th class="text-white">Durée de travail</th>
                                                            <th class="text-white">Flexibilité </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!$history_entries->isEmpty())
                                                            @foreach ($history_entries as $history_entry)
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex ">
                                                                            <a
                                                                                href="{{ route('employeeDetail', ['id' => $history_entry->employee->id]) }}">

                                                                                @if ($history_entry->employee->image_path)
                                                                                    <img src="{{ asset('storage/' . $history_entry->employee->image_path) }}"
                                                                                        alt="{{ $history_entry->employee->name }}">
                                                                                @else
                                                                                    <img src="{{ asset('/public/images/default.png') }}"
                                                                                        alt="{{ $employee->name }}">
                                                                                @endif
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
                                                                    <td class="text-center">
                                                                        <h6 class="cbleu">
                                                                            {{ App\Helper::getHeuresEmployesParJour($history_entry->employee->id, $history_entry->day_at_in) }}
                                                                        </h6>

                                                                    </td>
                                                                    <td class="text-center">
                                                                        <h6 class="cRouge ">
                                                                            {{ App\Helper::getTimeFlexParJour($history_entry->employee->id, $history_entry->day_at_in) }}
                                                                        </h6>

                                                                    </td>

                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <p>Aucune donnés disponible pendant la période
                                                                spécifiée.
                                                            </p>
                                                        @endif

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

        document.getElementById('exportButtonSite').addEventListener('click', function() {
            let start = @json($dateRange['start']->format('Y-m-d'));
            let end = @json($dateRange['end']->format('Y-m-d'));
            let site = @json($site->name);
            let fileName = 'listes_employees_site_' + site + '_du_' + start + '_au_' + end + '.pdf';
            try {
                var element = document.getElementById('exportButtonSite');
                var SiteEmployeeTable = document.getElementById('siteEmployeeTable');
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

                html2pdf().set(opt).from(SiteEmployeeTable).save();
                html2pdf(SiteEmployeeTable, opt);

            } catch (error) {
                console.error('An error occurred:', error);
            }
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
