@extends('layouts')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                        <div class="row flex-grow">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card card-rounded">
                                    <div class="card-body">
                                        <div class="d-sm-flex justify-content-between align-items-start">
                                            <div>
                                                <h4 class="card-title card-title-dash">Listes des employées de Sah
                                                </h4>
                                                <p class="card-subtitle card-subtitle-dash">Nous
                                                    avons {{ $employeeCount }} employées</p>
                                            </div>

                                        </div>
                                        <div class="table-responsive  mt-1">
                                            <table class="table select-table table-hover ">
                                                <thead class="orange ">
                                                    <tr>
                                                        <th class="text-white  pl-2">Quicklock ID</th>
                                                        <th class="text-white">Nom et Poste</th>
                                                        <th class="text-white">Heures travaillées</th>
                                                        <th class="text-white"> heures Supp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($employees as $employee)
                                                        <tr>

                                                            <td class=" pl-2">{{ $employee->matricule }}</td>
                                                            <td>
                                                                <div class="d-flex ">
                                                                    @if ($employee->image_path)
                                                                        <img src="{{ asset($employee->image_path) }}"
                                                                            alt="{{ $employee->name }}">
                                                                    @else
                                                                        Aucune image
                                                                    @endif
                                                                    <div>
                                                                        <a class=""
                                                                            href="{{ route('employeeDetail', ['id' => $employee->id]) }}">
                                                                            <h6>{{ $employee->name }}</h6>
                                                                        </a>
                                                                        <p>{{ $employee->designation }}</p>
                                                                    </div>

                                                                </div>
                                                            </td>
                                                            <td> <h6  class="cbleu" ><em>{{ App\Helper::calculateTimeDifference($startOfWeek,$endOfWeek, $employee->id)}}</em></h6></td>
                                                            <td> <h6 class="cRouge" > <em> {{ App\Helper::calculateTimeSuppParPeriode($startOfWeek,$endOfWeek, $employee->id)}} </em></h6></td>
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

@endsection
