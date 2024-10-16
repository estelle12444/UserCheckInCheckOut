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
                                                        Analytics
                                                    </h4>
                                                    <p class="card-subtitle card-subtitle-dash">Nous
                                                        avons {{ $employeeCount }} employées</p>
                                                </div>

                                            </div>
                                            <div class="table-responsive  mt-1">
                                                <table id="flexibitlite" class="table select-table table-hover ">
                                                    <thead class="orange ">
                                                        <tr>
                                                            <th class="text-white  pl-2">Quicklock ID</th>
                                                            <th class="text-white">Employée</th>
                                                            <th class="text-white">Total des Heures effectuées</th>
                                                            <th class="text-white">Total flexibilité </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($employees as $employee)
                                                            <tr>

                                                                <td class=" pl-2">{{ $employee->matricule }}</td>
                                                                <td>
                                                                    <div class="d-flex ">
                                                                        @if ($employee->image_path)
                                                                            <img style="height: 50px !important; width:auto" src="{{ asset('storage/' . $employee->image_path) }}"
                                                                                alt="{{ $employee->name }}">
                                                                        @else
                                                                            <img src="{{ asset('images/default.png') }}"
                                                                                alt="{{ $employee->name }}">
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
                                                                <td class="text-center">
                                                                    <h6 class="cbleu">
                                                                        {{ App\Helper::getTimeDifferenceParPeriode($dateRange['start'], $dateRange['end'], $employee->id) }}
                                                                    </h6>
                                                                </td>
                                                                <td class="text-center">
                                                                    <h6 class="cRouge ">
                                                                        {{ App\Helper::getTimeFlexParPeriode($dateRange['start'], $dateRange['end'], $employee->id) }}
                                                                    </h6>
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
@endsection

@push('scripts')
    <script>
        new DataTable('#flexibitlite', {
            paging: true,
            pageLength: 10,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            }
        });
    </script>
@endpush
