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
                                            <p class="statistics-title">Temps moyen passé au travail</p>
                                            @if (isset($averageHoursDuration))
                                                <h3 class="rate-percentage">{{ $averageHoursDuration }} </h3>
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
                                                    <div class="d-sm-flex justify-content-between align-items-start ">

                                                        <h6 class="card-title ">
                                                            Graphique du nombre d'employés entrants par site
                                                        </h6>
                                                        <div id="performance-line-legend"></div>
                                                    </div>

                                                    <h6 class="card-subtitle card-subtitle-dash"
                                                        style="color:rgb(249, 139, 99);font-weight:600">Du
                                                        {{ $dateRange['start']->format('Y-m-d') }} au
                                                        {{ $dateRange['end']->format('Y-m-d') }}
                                                    </h6>

                                                    <div class="chartjs-wrapper ">
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
                                                                    <h4 class="card-title ">
                                                                        <td>Nombre d'employés entrants par site </td>
                                                                    </h4>
                                                                    <h6 class="card-subtitle card-subtitle-dash"
                                                                        style="color:rgb(249, 139, 99)">Aujourd'hui
                                                                    </h6>

                                                                    <table class="table table-striped">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Site</th>
                                                                                <th>Jour</th>
                                                                                <th>Nombre d'employés</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($employeeCountBySite as $day)
                                                                                <tr>
                                                                                    <td>{{ config('localisation')[$day[0]->localisation_id - 1]['name'] }}
                                                                                    </td>
                                                                                    <td>{{ date('Y-m-d') }}</td>
                                                                                    <td>{{ count($day) }}</td>
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

                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row flex-grow">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded">
                                        <div class="card-body" id="historyTable">
                                            <div class="d-sm-flex justify-content-between align-items-start">

                                                <h4 class="card-title  mt-4 ">Historique des 10 dernières
                                                    entrées-sorties des employées
                                                </h4>
                                                <h6 class="card-subtitle card-subtitle-dash mt-4"
                                                    style="color:rgb(249, 139, 99);font-weight:600">Du
                                                    {{ $dateRange['start']->format('Y-m-d') }} au
                                                    {{ $dateRange['end']->format('Y-m-d') }}
                                                </h6>

                                            </div>
                                            <div class="table-responsive  mt-1">
                                                <table id="lastTable" class="table select-table  table-hover">
                                                    <thead class="orange">
                                                        <tr>
                                                            <th class="text-white pl-2">Site</th>
                                                            <th class="text-white">Employée</th>
                                                            <th class="text-white">Département</th>
                                                            <th class="text-white">Date</th>
                                                            <th class="text-white">Entrée</th>
                                                            <th class="text-white">Sortie</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($lastentriesAndExits)
                                                            @foreach ($lastentriesAndExits as $history_entry)
                                                                <tr>
                                                                    <td class="pl-2">
                                                                        <h6 style="color:#EF8032">
                                                                            {{ config('localisation')[$history_entry->localisation_id - 1]['name'] }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex ">
                                                                            <a
                                                                                href="{{ route('employeeDetail', ['id' => $history_entry->employee->id]) }}">
                                                                                <img src="{{ asset('storage/' . $employee->image_path) }}"
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
                                                                    </td>
                                                                    <td>
                                                                        <h6>{{ $history_entry->day_at_in }}</h6>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <h6 class="cbleu">
                                                                            {{ $history_entry->time_at_in }}

                                                                        </h6>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <h6 class="cRouge">
                                                                            {{ $history_entry->time_at_out }}
                                                                        </h6>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <p>Aucune donnée disponible pendant la période
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
        const chartConfig = [{
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

        const [start, end] = getPeriodFilter();
        getEntriesData(start, end).then(el => {
            moment.locale('fr');

            let labels = [];
            let datasetValues = [];
            let sites = Object.keys(el);
            let siteData = Object.values(el);
            let [startDate, endDate] = [moment(start), moment(end)];

            let diff = endDate.diff(startDate, 'days') + 1;
            console.log(diff, sites, siteData)

            if (start == null || end == null || diff <= 7) {
                labels = ["LUN", "MAR", "MER", "JEU", "VEN", "SAM", "DIM"];
                if (siteData.length == 0) {
                    let jours = Array(7).fill([0]).flat();

                    datasetValues.push(datasetOptionGenerator(chartConfig[0], jours));
                    datasetValues.push(datasetOptionGenerator(chartConfig[1], jours));
                    datasetValues.push(datasetOptionGenerator(chartConfig[2], jours));
                } else {
                    for (let index = 0; index < sites.length; index++) {
                        let jours = Array(7).fill([0]).flat();
                        for (const iterator in siteData[index]) {
                            if (siteData[index][iterator] != 0) {
                                const weekday = (new Date(iterator)).getDay();
                                jours[weekday - 1] = siteData[index][iterator];
                            }
                        }
                        datasetValues.push(datasetOptionGenerator(chartConfig[sites[index] - 1], jours));
                    }
                }

            } else {
                labels = getRangeDate(startDate, endDate);
                for (let index = 0; index < sites.length; index++) {
                    let jours = Array(diff).fill([0]).flat();
                    console.log(siteData[index])

                    for (const iterator in siteData[index]) {
                        console.log(siteData[index][iterator], iterator)

                        if (siteData[index][iterator] != 0) {
                            let goodDate = iterator.replace(/(\d{4})\-(\d{2})\-(\d{2})/, "$3/$2/$1");
                            let goodIndex = labels.indexOf(goodDate);
                            jours[goodIndex] = siteData[index][iterator];
                        }
                    }
                    datasetValues.push(datasetOptionGenerator(chartConfig[sites[index] - 1], jours));
                }
            }
            var graphGradient = document.getElementById("performaneLinee").getContext('2d');

            var salesTopData = {
                labels,
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
        });

        function getRangeDate(startDate, endDate) {
            const fixFormatting = (str) => str.replace(/^(\d{2})\/(\d{2})\/(\d{4})$/, "$2/$1/$3");

            let range = [];
            let fromDate = new Date(startDate.format('L'));
            let finishDate = new Date(endDate.format('L'));

            while (fromDate <= finishDate) {
                range.push(fixFormatting(moment(fromDate).format('L')));
                let newDate = fromDate.setDate(fromDate.getDate() + 1);
                fromDate = new Date(newDate);
            }

            return range;
        }

        function getPeriodFilter() {
            let [start, end] = [null, null];
            let params = new URL(document.location).searchParams.get('selectedDates');

            if (params == null) return [moment().weekday(0), moment().weekday(6)];

            params = params.split(/to| /).filter((el) => el.length > 0);

            if (params.length == 1) {
                start = params[0];
            } else if (params.length == 2) {
                let dates = params.map(el => new Date(el));
                [start, end] = dates[0].getTime() < dates[1].getTime() ? params : params.reverse();
            }
            return [start, end];
        }

        async function getEntriesData(start = null, end = null) {
            let response = await fetch(`${window.location.origin}/api/histories`, {
                method: "POST",
                mode: "cors",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    start,
                    end
                })
            });

            const resultat = await response.json();
            return resultat.data;
        }

        function datasetOptionGenerator(opts, dataValue) {
            const rgbColor = opts.gradientBackground.join(", ")
            const repeat = (value) => Array(dataValue.length).fill([value]).flat();
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
        new DataTable('#lastTable', {
            paging: true,
            pageLength: 15,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            }
        });
    </script>
@endpush
