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


                        </div>

                    </div>
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row flex-grow">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded">
                                        <div class="card-body" id="historyTable">

                                            @if ($employee)
                                                <div class="card-header">Fiche de l'employé: {{ $employee->name }}</div>

                                                <div class="card-body">
                                                    <div class="flex" style="display: flex; justify-content: center; align-items: center;">
                                                        @if ($employee->image_path)
                                                            <img class="" style="width: 200px; height: 200px;border: 2px solid #3498db; " src="{{ asset($employee->image_path) }}" alt="{{ $employee->name }}">
                                                        @else
                                                            Aucune image
                                                        @endif
                                                    </div>
                                                    <h6>Nom: {{ $employee->name }}</h6>
                                                    <br>
                                                    <h6>Designation: {{ $employee->designation }}</h6>
                                                    <br>


                                                    <h3>Heures d'Entrées et de sorties</h3>
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Site </th>
                                                                <th>Date d'Entrée</th>
                                                                <th>Heure d'entrée</th>
                                                                <th>Date de sortie</th>
                                                                <th>Heure de sortie</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($employee->historyEntries as $entry)
                                                                <tr>
                                                                    <td>{{ App\Helper::searchByNameAndId('localisation', $entry->localisation_id)->name ?? '' }}
                                                                    </td>
                                                                    <td>{{ $entry->day_at_in }}</td>
                                                                    <td>{{ $entry->time_at_in }}</td>
                                                                    <td>
                                                                        @if ($entry->day_at_out)
                                                                            {{ $entry->day_at_out }}
                                                                        @else
                                                                            Pas encore sorti
                                                                        @endif
                                                                    </td>

                                                                    <td>
                                                                        @if ($entry->time_at_out)
                                                                            {{ $entry->time_at_out }}
                                                                        @else
                                                                            Pas encore sorti
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
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
                                                            <h4 class="card-title card-title-dash"> Heures Cumulées</h4>
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
                                                                @foreach ($result as $key => $hour)
                                                                    <tr>
                                                                        <td>{{ $key }}</td>
                                                                        <td>{{ $weekdays[$i] }}</td>
                                                                        <td>{{ $hour }} H</td>
                                                                    </tr>
                                                                    @php
                                                                        $i++;
                                                                    @endphp
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
        </div>
    </div>

    </div>


@endsection
@push('scripts')
    <script>
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
