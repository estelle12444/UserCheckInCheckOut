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
                                            <p class="statistics-title">Nombre de sites</p>
                                            <h3 class="rate-percentage">32.53%</h3>
                                        </div>
                                        <div>
                                            <p class="statistics-title">Nombre d'entrées</p>
                                            <h3 class="rate-percentage">7,682</h3>

                                        </div>
                                        <div>
                                            <p class="statistics-title">Heure moyen des employée</p>
                                            <h3 class="rate-percentage">68.8</h3>

                                        </div>
                                        <div class="d-none d-md-block">
                                            <p class="statistics-title">Total</p>
                                            <h3 class="rate-percentage">2m:35s</h3>
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
                                                                            Nombre d'employé par site
                                                                        </h4>


                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th colspan="2">Jour</th>
                                                                                    <th>Site</th>
                                                                                    <th>Nombre d'employée</th>

                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($nombres as $siteId => $jours)
                                                                                        @foreach ( $jours as $jour => $nombres )
                                                                                        <tr>
                                                                                            <td colspan="2">{{ $jour }}</td>
                                                                                            <td>{{  config('localisation')[$siteId - 1]["name"]}}</td>
                                                                                            <td>{{ $nombres}}</td>
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

{{-- @push('scripts')
    <script>
        let data = [0,0,0,0,0,0,0];
        let weekdays = @json(array_keys($result));
        let hours = @json(array_values($result));
        let i = 0;
        for (const day of weekdays) {
            data[day-1] = hours[i];
            i++;
        }
        var graphGradient = document.getElementById("performaneLinee").getContext('2d');
        var saleGradientBg = graphGradient.createLinearGradient(5, 0, 5, 100);
        saleGradientBg.addColorStop(0, 'rgba(26, 115, 232, 0.18)');
        saleGradientBg.addColorStop(1, 'rgba(26, 115, 232, 0.02)');

        var salesTopData = {
            labels: [ "LUN", "MAR", "MER", "JEU", "VEN", "SAM", "DIM"],
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
                pointRadius: [4, 4, 4, 4, 4,4, 4, 4, 4, 4,4, 4, 4],
                pointHoverRadius: [2, 2, 2, 2, 2,2, 2, 2, 2, 2,2, 2, 2],
                pointBackgroundColor: ['#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3','#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3','#1F3BB3)', '#1F3BB3', '#1F3BB3', '#1F3BB3','#1F3BB3)'],
                pointBorderColor: ['#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff','#fff',],
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
                        color:"#F0F0F0",
                        zeroLineColor: '#F0F0F0',
                    },
                    ticks: {
                        beginAtZero: false,
                        autoSkip: true,
                        maxTicksLimit: 7,
                        fontSize: 12,
                        color:"#6B778C"
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
                    color:"#6B778C"
                    }
                }],
            },
            legend:false,
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
@endpush --}}
