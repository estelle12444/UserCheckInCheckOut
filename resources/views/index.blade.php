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
                                            <p class="statistics-title">Entrées</p>
                                            <h3 class="rate-percentage">32.53%</h3>

                                        </div>
                                        <div>
                                            <p class="statistics-title">Sorties</p>
                                            <h3 class="rate-percentage">7,682</h3>

                                        </div>
                                        <div>
                                            <p class="statistics-title">Absents</p>
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
                                                    <div class="d-sm-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h4 class="card-title card-title-dash">
                                                                Performance Line Chart</h4>
                                                            <h5 class="card-subtitle card-subtitle-dash">
                                                                Lorem Ipsum is text of the
                                                                printing</h5>
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
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center mb-3">
                                                                        <h4 class="card-title card-title-dash">Type
                                                                            By Amount</h4>
                                                                    </div>
                                                                    <canvas class="my-auto" id="doughnutChart"
                                                                        height="200"></canvas>
                                                                    <div id="doughnut-chart-legend"
                                                                        class="mt-5 text-center"></div>
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
                            <div class="row">
                                <div class="col-lg-12 d-flex flex-column">


                                    <div class="row flex-grow">
                                        <div class="col-12 grid-margin stretch-card">
                                            <div class="card card-rounded">
                                                <div class="card-body">
                                                    <div class="d-sm-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h4 class="card-title card-title-dash">Entrées et Sorties des
                                                                employées
                                                            </h4>

                                                        </div>

                                                    </div>
                                                    <div class="table-responsive mt-1">
                                                        <table class="table select-table">
                                                            <thead>

                                                                <tr>
                                                                    <th>Employée</th>
                                                                    <th>Site</th>
                                                                    <th>Heures Entrées</th>
                                                                    <th>Heures de Sorties</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($history_entries as $history_entry)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <img src="{{ asset('images/faces/face1.jpg') }}"
                                                                                    alt="">
                                                                                <div>
                                                                                    <h6>{{ $history_entry->employee->name }}
                                                                                    </h6>
                                                                                    <p>{{ $history_entry->employee->designation }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <h6>{{ $history_entry->localisation_id }}</h6>
                                                                        </td>
                                                                        <td>
                                                                            <h5>{{ $history_entry->time_at_in }} </h5>
                                                                        </td>
                                                                        <td>
                                                                            <h5>{{ $history_entry->time_at_out }} </h5>
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
        </div>
    </div>
@endsection
