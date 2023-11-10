@extends('layouts')

@section('content')

        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <div class="home-tab">
                        <div class="d-sm-flex align-items-center justify-content-between border-bottom">

                            <div class="btn-wrapper">
                                <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i>
                                    Share</a>
                                <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                                <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i>
                                    Exporter</a>
                            </div>

                        </div>

                        <div class="tab-content tab-content-basic">
                            <div class="tab-pane fade show active" id="overview" role="tabpanel"
                                aria-labelledby="overview">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Listes des employés du site : {{$site->name ?? " "}}
                                                        </h4>
                                                        <p class="card-subtitle card-subtitle-dash">Nous
                                                            avons 50+ employées</p>
                                                    </div>

                                                </div>
                                                <div class="table-responsive  mt-1">
                                                    <table class="table select-table">
                                                        <thead>
                                                            <tr>

                                                                <th>Employée</th>
                                                                <th>Département</th>
                                                                <th>Nombre Heures</th>
                                                                <th>Heures Sups</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                @foreach ($history_entries as $history_entry)

                                                                <td>
                                                                    <div class="d-flex ">
                                                                        <img src="images/faces/face1.jpg" alt="">
                                                                        <div>
                                                                            <h6>{{ $history_entry->employee->name }}</h6>
                                                                            <p>{{ $history_entry->employee->designation }}</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <h6>Company name 1</h6>
                                                                    <p>{{ App\Helper::searchByNameAndId('department', $history_entry->employee->department_id)->name ?? ""  }}

                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center mb-1 max-width-progress-wrap">

                                                                            <p >{{ $history_entry->in_out }}</p>
                                                                        </div>

                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p>4 H</p>
                                                                </td>
                                                                @endforeach
                                                            </tr>

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
