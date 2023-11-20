@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="statistics-details d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="statistics-title">Nombre de site</p>
                                            <h3 class="rate-percentage">{{ $nombreSites }}</h3>
                                        </div>
                                        <div>
                                            <p class="statistics-title">Nombre d'entrée</p>
                                            @if (isset($countEntries))
                                                <h3 class="rate-percentage">{{ $countEntries }}</h3>
                                            @else
                                                <h3 class="statistics-title">0 </h3>
                                            @endif


                                        </div>
                                        <div>
                                            <p class="statistics-title">Heure moyen d'entrée des employés</p>
                                            @if (isset($moyenneHeuresEntree))
                                                <h3 class="rate-percentage">{{ $moyenneHeuresEntree }}</h3>
                                            @else
                                                <h3 class="statistics-title">0 H</h3>
                                            @endif

                                        </div>
                                        <div class="d-none d-md-block">
                                            <p class="statistics-title">Total d'heure moyen des employés</p>
                                            @if (isset($totalHeures))
                                                <h3 class="rate-percentage">{{ $totalHeures }} </h3>
                                            @else
                                                <h3 class="statistics-title">0 H</h3>
                                            @endif

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

                                                        <h4 class="card-title card-title-dash">
                                                            Statistique des employées par site</h4>

                                                        <div id="performance-line-legend"></div>
                                                    </div>
                                                    <div class="chartjs-wrapper mt-5">
                                                        <canvas id="performaneLinee"></canvas>
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
                                                                    <h4 class="card-title card-title-dash">

                                                                        <td>Nombre d'employés entrants par site </td>

                                                                    </h4>

                                                                    <h5 class="card-subtitle card-subtitle-dash">
                                                                        @foreach ($weeklyEntries as $weekNumber => $entries)
                                                                            Semaine {{ $weekNumber }}
                                                                        @endforeach

                                                                    </h5>


                                                                    <table class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Site</th>
                                                                                <th>Jour</th>
                                                                                <th>Nombre d'employés</th>

                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($nombres as $localisationId => $localisationData)
                                                                                @foreach ($localisationData as $day => $employeeCount)
                                                                                    <tr>

                                                                                        <td>{{ config('localisation')[$localisationId - 1]['name'] }}
                                                                                        </td>
                                                                                        <td>{{ $day }}</td>
                                                                                        <td>{{ $employeeCount }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            @endforeach

                                                                        </tbody>
                                                                    </table>


                                                                    {{-- <canvas class="my-auto" id="doughnutChart"
                                                                        height="200"></canvas>
                                                                    <div id="doughnut-chart-legend"
                                                                        class="mt-5 text-center"></div> --}}
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
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let data = [0, 0, 0, 0, 0, 0, 0];
    let weekdays = @json(array_keys($nombres->toArray())); // Supposant que tous les jours aient le même ensemble de clés
    let sitesData = @json($nombres->toArray());

    // Récupérer le nombre d'employés entrants par site pour chaque jour
    for (let i = 0; i < weekdays.length; i++) {
        let totalEmployees = 0;

        // Ajouter le nombre d'employés entrants pour chaque site
        for (const site in sitesData[weekdays[i]]) {
            totalEmployees += sitesData[weekdays[i]][site];
        }

        data[i] = totalEmployees;
    }

    var graphGradient = document.getElementById("performaneLinee").getContext('2d');
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
