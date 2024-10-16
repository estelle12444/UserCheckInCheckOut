@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="modal fade" id="mapPopup" tabindex="-1" role="dialog" aria-labelledby="mapPopupLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="mapPopupLabel">Carte</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="popup" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                        <div class="btn-wrapper">
                            <a href="#" id="exportButtonDetailEmployee" class="btn btn-primary text-white me-0">
                                <i class="icon-download"></i>Exporter en pdf
                            </a>
                            <a href="#" id="exportButtonExcell" class="btn btn-success text-white me-0">
                                <i class="icon-download"></i>Exporter en excel
                            </a>
                        </div>
                    </div>
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row flex-grow">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded">
                                        <div class="card-body" id="DetailEmployeeTable">
                                            @if ($employee)
                                            <div class="card-header orange text-white">Fiche de l'employé:{{ $employee->name }}</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            @if ($employee->image_path)
                                                                <img class="img-fluid rounded" style="border: 2px solid #EF8032;"  src="{{ asset('storage/' . $employee->image_path) }}" alt="{{ $employee->name }}">
                                                            @else
                                                                <img class="img-fluid rounded" style="border: 2px solid #EF8032;"  src="{{ asset('images/default.png') }}" alt="{{ $employee->name }}">
                                                            @endif
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <h6 class="font-weight-bold">Nom et Prénom(s):</h6>
                                                                    <p>{{ $employee->name }}</p>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <h6>Total d'heure de travail</h6>
                                                                    <h5 class="text-info">{{ App\Helper::getTimeDifferenceTotal($employee->id) }}</h5>
                                                                </div>
                                                            </div>                                                            <h6 class="font-weight-bold">Poste:</h6>
                                                            <p>{{ $employee->designation }}</p>
                                                            <hr class="my-2">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <h6 class="font-weight-bold">Département :</h6>
                                                                    <p>{{ App\Helper::searchByNameAndId('department', $employee->department_id)->name ?? '' }}</p>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <h6>Total Panier de flexibilité </h6>
                                                                    <h5 class="text-info">{{ App\Helper::totalHeureFlex($employee->id) }}</h5>
                                                                </div>
                                                            </div>
                                                            <h6 class="font-weight-bold">Quicklock ID:</h6>
                                                            <p>{{ $employee->matricule }}</p>
                                                        </div>
                                                        <div class="">
                                                            <h3 class="mt-4 text-info">Historiques des Entrées et de sorties</h3>
                                                            <h5 class=" mt-4 " style="color:rgb(249, 139, 99)">Du {{ $dateRange['start']->format('Y-m-d') }} au {{ $dateRange['end']->format('Y-m-d') }}</h5>
                                                        </div>
                                                        <table aria-describedby="mydesc" id="detailEmployeeTable" class="table table-striped dataTable">
                                                            <thead class="bg-info">
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Entrée</th>
                                                                    <th>Sorties</th>
                                                                    <th>Site</th>
                                                                    <th>Durée de travail</th>
                                                                    <th>Panier de flexibilité</th>
                                                                    <th>Position</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($groupedHistoryEntries->groupby('day_at_in') as $entry)
                                                                    @php
                                                                        $difference = 0;
                                                                        $overtime = 0;
                                                                        foreach ($entry as $key => $value) {
                                                                            $result = App\Helper::calculateTimeDifferenceAndOvertime($value->time_at_in, $value->time_at_out);
                                                                            $difference += $result['difference'] ?? 0;
                                                                            $overtime += $result['overtime'] ?? 0;
                                                                        }
                                                                    @endphp
                                                                    @foreach ($entry as $value)
                                                                    <tr>
                                                                        <td>{{ $value->day_at_in }}</td>
                                                                        <td>{{ $value->time_at_in }}</td>
                                                                        <td>{{ $value->time_at_out }}</td>
                                                                        <td>{{ App\Helper::searchByNameAndId('localisation', $value->localisation_id)->name ?? '' }}</td>
                                                                        @if ($loop->index == 0)
                                                                            <td class="text-center" rowspan="{{ $entry->count() }}">
                                                                                <h6 class="cbleu">{{ $difference }} h </h6>
                                                                            </td>
                                                                            <td class="text-center" rowspan="{{ $entry->count() }}">
                                                                                <h6 class="cRouge ">{{ $overtime }} h
                                                                                    @if ($result['overtime'] > 0)
                                                                                        <i class="mdi mdi-arrow-up-drop-circle text-success"></i>
                                                                                    @elseif($result['overtime'] < 0)
                                                                                        <i class="mdi mdi-arrow-down-drop-circle text-danger"></i>
                                                                                    @else
                                                                                        <i></i>
                                                                                    @endif
                                                                                </h6>
                                                                            </td>
                                                                        @endif
                                                                            <td>
                                                                             @if ($value->lat != 0 && $value->lon != 0)
                                                                                <button  class="displayModal1 btn btn-success btn-rounded btn-icon text-white" data-toggle="modal" data-target="#mapPopup" data-latlon="{{$value->lat}}, {{$value->lon}}">
                                                                                    <i class="ti-location-pin"></i>
                                                                            </button>
                                                                                @else
                                                                                    <p class="text-danger">pas de coordonnées</p>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        <div class="justify-content">{{ $groupedHistoryEntries->links() }}</div>
                                                        <table class="mx-2 table ">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Total des heures travaillées:
                                                                        <strong class="Cbleue">
                                                                            {{ App\Helper::getTimeDifferenceParPeriode($dateRange['start'], $dateRange['end'], $employee->id) }}
                                                                        </strong>
                                                                    </td>
                                                                    <td>Total du panier :
                                                                        <strong class="CRouge">
                                                                            {{ App\Helper::getTimeFlexParPeriode($dateRange['start'], $dateRange['end'], $employee->id) }}
                                                                        </strong>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
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
                                                <h4 class="card-title ">
                                                    Statistique des heures cumulées d'un employée </h4>
                                                <h6 class="card-subtitle card-subtitle-dash"
                                                    style="color:rgb(249, 139, 99);font-weight:700">Du
                                                    {{ $dateRange['start']->format('Y-m-d') }} au
                                                    {{ $dateRange['end']->format('Y-m-d') }}
                                                </h6>
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
                                                        <h4 class="card-title "> Heures Cumulées </h4>
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h6 class="card-subtitle card-subtitle-dash" style="color:rgb(249, 139, 99);font-weight:700">
                                                                Du {{ $dateRange['start']->format('Y-m-d') }} au {{ $dateRange['end']->format('Y-m-d') }}
                                                            </h6>
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
                                                                @foreach ($statData as $key => $hour)
                                                                    <tr>
                                                                        <td>{{ $key }}</td>
                                                                        <td></td>
                                                                        <td>{{ $hour }} </td>
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
@endsection
@push('scripts')

    <script>
        var map = L.map('popup').setView([5.328056, -4.001333], 10);
        var marker;
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        document.querySelectorAll('.displayModal1').forEach(element => {
            element.addEventListener('click', function(){
                var data = this.dataset.latlon;
                var coords = data.split(',');
                setTimeout(function() {
                map.invalidateSize();

                if (marker) {
                        marker.removeFrom(map);
                    }
                marker= L.marker([coords[0], coords[1]]).addTo(map)
                .bindPopup('Latitude: ' + coords[0] + '<br>Longitude: ' + coords[1])
                .openPopup();

                map.setView([coords[0], coords[1]], 15);
            }, 400);
            })
        });
    </script>
    <script>
        document.getElementById('exportButtonExcell').addEventListener('click', function() {
            var table = document.getElementById('detailEmployeeTable');
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


        document.getElementById('exportButtonDetailEmployee').addEventListener('click', function() {
            var employeeName = @json($employee->name);
            var employeeMartricule = @json($employee->matricule);

            var fileName = employeeName + '_' + employeeMartricule + '.pdf';
            try {
                var element = document.getElementById('exportButtonDetailEmployee');
                var DetailEmployeeTable = document.getElementById('DetailEmployeeTable');
                var opt = {
                    margin: 1,
                    filename: fileName,
                    image: {
                        type: 'jpeg',quality: 0.98
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

                html2pdf().set(opt).from(DetailEmployeeTable).save();
                html2pdf(DetailEmployeeTable, opt);

            } catch (error) {
                console.error('An error occurred:', error);
            }
        });
    </script>
    <script>
        function getPeriodFilter() {
            let [start, end] = [null, null];
            let params = new URL(document.location).searchParams.get('selectedDates');

            if (params == null) return [start, end];

            params = params.split(/to| /).filter((el) => el.length > 0);

            if (params.length == 1) {
                start = params[0];
            } else if (params.length == 2) {
                let dates = params.map(el => new Date(el));
                [start, end] = dates[0].getTime() < dates[1].getTime() ? params : params.reverse();
            }
            return [start, end];
        }

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

        const [start, end] = getPeriodFilter();
        let labels, data;
        let [startDate, endDate] = [moment(start), moment(end)];
        let diff = endDate.diff(startDate, 'days') + 1;
        let days = @json(array_keys($statData));
        let statData = @json(array_values($statData));

        if (start == null || end == null || diff <= 7) {
            labels = ["LUN", "MAR", "MER", "JEU", "VEN", "SAM", "DIM"];
            data = Array(7).fill([0]).flat();
            for (let index = 0; index < days.length; index++) {
                const day = days[index];
                if (statData[index] != 0) {
                    data[day - 1] = statData[index];
                }
            }
        } else {
            labels = getRangeDate(startDate, endDate);
            data = Array(diff + 1).fill([0]).flat();
            for (let index = 0; index < days.length; index++) {
                let day = days[index].replace(/(\d{4})\-(\d{2})\-(\d{2})/, "$3/$2/$1");
                let goodIndex = labels.indexOf(day);
                data[goodIndex] = parseInt(statData[index]);
            }
        }

        var graphGradient = document.getElementById("performaneLine").getContext('2d');
        var saleGradientBg = graphGradient.createLinearGradient(5, 0, 5, 100);
        saleGradientBg.addColorStop(0, 'rgba(26, 115, 232, 0.18)');
        saleGradientBg.addColorStop(1, 'rgba(26, 115, 232, 0.02)');

        var salesTopData = {
            labels: labels,
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
