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
                            <a href="#" id="exportButtonExcell" class="btn btn-success text-white me-0"><i
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
                                            @if ($employee)
                                                <div class="card-header orange text-white">Fiche de l'employé:
                                                    {{ $employee->name }}</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            @if ($employee->image_path)
                                                                <img class="img-fluid rounded"
                                                                    style="border: 2px solid #EF8032;"
                                                                    src="{{ asset($employee->image_path) }}"
                                                                    alt="{{ $employee->name }}">
                                                                {{-- <img src="{{ asset('storage/' . $employee->image_path) }}"
                                                                                alt="{{ $employee->name }}"> --}}
                                                            @else
                                                                <p class="text-muted">Aucune image</p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <h6 class="font-weight-bold">Nom et Prénom(s):</h6>
                                                                    <p>{{ $employee->name }}</p>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <h6 class="font-weight-bold">Panier de flexibilité Total
                                                                        : </h6>
                                                                    <p>{{ App\Helper::totalHeureFlex($employee->id) }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <h6 class="font-weight-bold">Poste:</h6>
                                                            <p>{{ $employee->designation }}</p>
                                                            <hr class="my-2">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <h6 class="font-weight-bold">Département :</h6>
                                                                    <p>{{ App\Helper::searchByNameAndId('department', $employee->department_id)->name ?? '' }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <h6 class="font-weight-bold"> Total d'heure: </h6>
                                                                    <p> H
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <h6 class="font-weight-bold">Quicklock ID:</h6>
                                                            <p>{{ $employee->matricule }}</p>
                                                        </div>
                                                        <h3 class="my-4 text-info">Historiques des Entrées et de sorties
                                                        </h3>
                                                        <table id="DetailEmployeeTable" class="table table-striped dataTable">
                                                            <thead class="bg-info">
                                                                <tr>
                                                                    <th>Site </th>
                                                                    <th>Date</th>
                                                                    <th>Entrée</th>
                                                                    <th>Sortie</th>
                                                                    <th>Heure travaillée</th>
                                                                    <th>Panier de flexibilité</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    @php
                                                                        $resultats = [];
                                                                        $totalDifference = 0;
                                                                        $totalOvertime = 0;
                                                                    @endphp

                                                                    @foreach ($groupedHistoryEntries as $entry)
                                                                        <td>{{ App\Helper::searchByNameAndId('localisation', $entry->localisation_id)->name ?? '' }}
                                                                        </td>
                                                                        <td>{{ $entry->day_at_in }}</td>
                                                                        <td>{{ $entry->time_at_in }}</td>
                                                                        <td>{{ $entry->time_at_out }}</td>
                                                                        @php
                                                                            $result = App\Helper::calculateTimeDifferenceAndOvertime($entry->time_at_in, $entry->time_at_out);
                                                                            $resultats[] = $result;
                                                                            $totalDifference += $result['difference'] ?? 0;
                                                                            $totalOvertime += $result['overtime'] ?? 0;
                                                                        @endphp
                                                                        <td> {{ $result['difference'] }} h</td>
                                                                        <td> {{ $result['overtime'] }} h
                                                                            @if ($result['overtime'] > 0)
                                                                                <i
                                                                                    class="mdi mdi-arrow-up-drop-circle text-success"></i>
                                                                            @else
                                                                                <i
                                                                                    class="mdi mdi-arrow-down-drop-circle text-danger"></i>
                                                                            @endif
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <p>Employee not found.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7 d-flex flex-column">
                        <div class="row flex-grow">
                            <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                                <div class="card card-rounded">
                                    <div class="card-body">
                                        <div class="d-sm-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h4 class="card-title card-title-dash">
                                                    Statistique des heures cumulées d'un employée par jour</h4>
                                            </div>
                                            <div id="performance-line-legend"></div>
                                        </div>
                                        <div class="chartjs-wrapper mt-5">
                                            <canvas id="performaneLine"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 d-flex flex-column">
                        <div class="row flex-grow">
                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h4 class="card-title card-title-dash"> Heures Cumulées pour la
                                                                semaine</h4>
                                                        </div>
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">Jour</th>
                                                                    <th>Total d'heures</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $i = 0;
                                                                @endphp
                                                                {{-- @foreach ($result as $key => $hour)
                                                                    <tr>
                                                                        <td>N°{{ $key }}</td>
                                                                        <td>{{ $weekdays[$i] }}</td>
                                                                        <td>{{ $hour }} </td>
                                                                    </tr>
                                                                    @php
                                                                        $i++;
                                                                    @endphp
                                                                @endforeach --}}

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
        </div>
    </div>

    </div>


@endsection
@push('scripts')
    <script>
        new DataTable('#DetailEmployeeTable', {
            paging: true,
            pageLength: 5,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            }
        });
        document.getElementById('exportButtonExcell').addEventListener('click', function() {
            var table = document.getElementById('DetailEmployeeTable');
            console.log(" table selectionné OK");

            var ws = XLSX.utils.table_to_sheet(table);
            console.log(" objet ws OK", ws);

            XLSX.utils.sheet_add_aoa(ws, [], {
                origin: 'A1'
            });
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Feuille1');

            var employeeName = @json($employee->name);
            var employeeMartricule = @json($employee->matricule);

            var fileName = employeeName + '_' + employeeMartricule + '.xlsx';
            console.log("Nom du fichier", fileName);

            XLSX.writeFile(wb, fileName);
            console.log("ficher telechargé créé avec succès");
        });



        let data = [0, 0, 0, 0, 0, 0, 0];
        let weekdays = @json(array_keys($result));
        let hours = @json(array_values($result));
        let i = 0;
        for (const day of weekdays) {
            data[day - 1] = hours[i];
            i++;
        }
        var graphGradient = document.getElementById("performaneLine").getContext('2d');
        var saleGradientBg = graphGradient.createLinearGradient(5, 0, 5, 100);
        saleGradientBg.addColorStop(0, 'rgba(26, 115, 232, 0.18)');
        saleGradientBg.addColorStop(1, 'rgba(26, 115, 232, 0.02)');

        var salesTopData = {
            labels: ["LUN", "MAR", "MER", "JEU", "VEN", "SAM", "DIM"],
            datasets: [{
                label: '',
                data: data,
                backgroundColor: saleGradientBg,
                borderColor: [
                    '#1F3BB3',
                ],
                borderWidth: 1.5,
                fill: true, // 3: no fill
                pointBorderWidth: 1,
                pointRadius: [4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                pointHoverRadius: [2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2],
                pointBackgroundColor: ['#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3)', '#1F3BB3',
                    '#1F3BB3', '#1F3BB3', '#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3)'
                ],
                pointBorderColor: ['#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff',
                    '#fff', '#fff', '#fff', '#fff',
                ],
            }]
        };

        var salesTopOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    gridLines: {
                        display: true,
                        drawBorder: false,
                        color: "#F0F0F0",
                        zeroLineColor: '#F0F0F0',
                    },
                    ticks: {
                        beginAtZero: false,
                        autoSkip: true,
                        maxTicksLimit: 7,
                        fontSize: 12,
                        color: "#6B778C"
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        beginAtZero: false,
                        autoSkip: true,
                        maxTicksLimit: 7,
                        fontSize: 10,
                        color: "#6B778C"
                    }
                }],
            },
            legend: false,
            elements: {
                line: {
                    tension: 0.4,
                }
            },
            tooltips: {
                backgroundColor: 'rgba(31, 59, 179, 1)',
            }
        }

        var salesTop = new Chart(graphGradient, {
            type: 'line',
            data: salesTopData,
            options: salesTopOptions
        });
        document.getElementById('performance-line-legend').innerHTML = salesTop.generateLegend();
    </script>
@endpush
