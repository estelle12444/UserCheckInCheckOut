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
                                                <h3 class="rate-percentage">0 </h3>
                                            @endif


                                        </div>

                                        <div class="d-none d-md-block">
                                            <p class="statistics-title">Heure moyen d'entrée des employés</p>
                                            @if (isset($moyenneHeuresEntree))
                                                <h3 class="rate-percentage">{{$moyenneHeuresEntree }} </h3>
                                            @else
                                                <h3 class="rate-percentage">0 H</h3>
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
    const chartConfig = [
        {
            name: "Danga",
            gradientBackground: [46, 134, 193],
            gradientLine: [5, 0, 5, 100],
            borderColor: "#1F3BB3",
        },
        {
            name: "Laurier",
            gradientBackground: [130, 224, 170],
            gradientLine: [5, 0, 5, 100],
            borderColor: "#229954",
        },
        {
            name: "Campus",
            gradientBackground: [245, 176, 65],
            gradientLine: [5, 0, 5, 100],
            borderColor: "#E67E22",
        }
    ];

    function datasetOptionGenerator(opts, dataValue){
        const point = 7;
        const rgbColor = opts.gradientBackground.join(", ")
        const repeat = (value) => Array(point).fill([value]).flat();
        let graphGradient = document.getElementById("performaneLinee").getContext('2d');
        let saleGradientBg = graphGradient.createLinearGradient(...opts.gradientLine);

        saleGradientBg.addColorStop(0, `rgba(${rgbColor}, 0.8)`);
        saleGradientBg.addColorStop(1, `rgba(${rgbColor}, 0.02)`);
        return {
            label: opts.name,
            data: dataValue,
            backgroundColor: saleGradientBg,
            borderColor: [
                opts.borderColor,
            ],
            borderWidth: 1.5,
            fill: true, // 3: no fill
            pointBorderWidth: 1,
            pointRadius: repeat(4),
            pointHoverRadius: repeat(2),
            pointBackgroundColor: repeat(opts.borderColor),
            pointBorderColor: repeat('#fff'),
        }
    }

    let sites = @json(array_keys($nombres->toArray()));
    let datasetValues = [];
    let siteData = @json(array_values($nombres->toArray()));

    for (let index = 0; index < sites.length; index++) {
        let jours = Array(7).fill([0]).flat();
        for (const iterator in siteData[index]) {
            if(siteData[index][iterator] != 0){
                weekday = (new Date(iterator)).getDay();
                jours[weekday-1] = siteData[index][iterator];
            }
        }
        datasetValues.push(datasetOptionGenerator(chartConfig[sites[index]-1], jours));
    }

    var graphGradient = document.getElementById("performaneLinee").getContext('2d');

    var salesTopData = {
        labels: ["LUN", "MAR", "MER", "JEU", "VEN", "SAM", "DIM"],
        datasets: datasetValues
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
