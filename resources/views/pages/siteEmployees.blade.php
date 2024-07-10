@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="modal fade" id="mapPopup" tabindex="-1" role="dialog" aria-labelledby="mapPopupLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="mapPopupLabel">Carte</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="map-popup" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                        <div class="btn-wrapper">
                            <a href="#" id="exportButtonSite" class="btn btn-primary text-white me-0">
                                <i class="icon-download"></i>Exporter en pdf</a>
                            {{-- <a href="#" id="ButtonExcel" class="btn btn-success text-white me-0">
                                <i class="icon-download"></i>Exporter en excel</a> --}}

                            <a href="{{ route('export.site', ['id' => $id]) }}" class="btn btn-success text-white me-0">
                                <i class="icon-download"></i>Exporter en excel</a>
                        </div>
                    </div>
                    <div class="tab-content tab-content-basic">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                            <div class="row flex-grow">
                                <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded">
                                        <div class="card-body" id="siteEmployeeTable">
                                            <div
                                                class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                                                <h3 class="card-title card-title-dash mb-2 mb-sm-0">
                                                    Liste des employés du site : <strong
                                                        class="orange">{{ $site->name ?? ' ' }}</strong>
                                                </h3>
                                                <h3 class="ml-4 card-title card-title-dash mb-2 mb-sm-0"
                                                    style="color:rgb(249, 139, 99);font-weight:700">
                                                    Du {{ $dateRange['start']->format('Y-m-d') }}
                                                    au {{ $dateRange['end']->format('Y-m-d') }}
                                                </h3>
                                            </div>
                                            <p class="card-subtitle card-subtitle-dash">Nous avons
                                                {{ $filtreEmployees }}employées</p>
                                            <div class="table-responsive  mt-1" >
                                                <table id="employeeTable" class="table select-table table-hover" style="overflow-x:hidden;">
                                                    <thead class="orange">
                                                        <tr>
                                                            <th class="text-white pl-2">Employée</th>
                                                            <th class="text-white">Département</th>
                                                            <th class="text-white">Date </th>
                                                            <th class="text-white"> Entrée</th>
                                                            <th class="text-white"> Sortie</th>
                                                            <th class="text-white">Durée de travail</th>
                                                            <th class="text-white">Flexibilité </th>
                                                            <th class="text-white">Position</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!$history_entries->isEmpty())
                                                            @foreach ($history_entries as $history_entry)
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex ">
                                                                            <a
                                                                                href="{{ route('employeeDetail', ['id' => $history_entry->employee->id]) }}">
                                                                                @if ($history_entry->employee->image_path)
                                                                                    <img style="height: 50px !important; width:auto"  src="{{ asset('storage/' . $history_entry->employee->image_path) }}"alt="{{ $history_entry->employee->name }}">
                                                                                @else
                                                                                    <img style="height: 50px !important; width:auto"  src="{{ asset('/images/default.png') }}" alt="{{ $employee->name }}">
                                                                                @endif
                                                                            </a>
                                                                            <div>
                                                                                <a
                                                                                    href="{{ route('employeeDetail', ['id' => $history_entry->employee->id]) }}">
                                                                                    <h6>{{ $history_entry->employee->name }}
                                                                                    </h6>
                                                                                </a>
                                                                                <p>{{ <p>
                                                                                    {{ substr($history_entry->employee->designation, 0, 10) .
                                                                                       (strlen($history_entry->employee->designation) > 10 ? '...' : '') }}
                                                                                 }}</p>
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
                                                                    <td>
                                                                        <h6 class="marron">{{ $history_entry->time_at_in }}
                                                                        </h6>
                                                                    </td>
                                                                    <td>
                                                                        @if ($history_entry->day_at_out && $history_entry->time_at_out)
                                                                            <h6 class="marron">
                                                                                {{ $history_entry->time_at_out }}</h6>
                                                                        @else
                                                                            Pas encore sorti
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <h6 class="cbleu">
                                                                            {{ App\Helper::getHeuresEmployesParJour($history_entry->employee->id, $history_entry->day_at_in) }}
                                                                        </h6>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <h6 class="cRouge ">
                                                                            {{ $flexibilite = App\Helper::getTimeFlexParJour($history_entry->employee->id, $history_entry->day_at_in) }}
                                                                            @if ($flexibilite > 0)
                                                                                <i
                                                                                    class="mdi mdi-arrow-up-drop-circle text-success"></i>
                                                                            @elseif($flexibilite < 0)
                                                                                <i
                                                                                    class="mdi mdi-arrow-down-drop-circle text-danger"></i>
                                                                            @else
                                                                                <i></i>
                                                                            @endif
                                                                        </h6>
                                                                    </td>
                                                                    <td>
                                                                        @if ($history_entry->lat != 0 && $history_entry->lon != 0)
                                                                            <button
                                                                                class="displayModal btn btn-success btn-rounded btn-icon text-white"
                                                                                data-toggle="modal" data-target="#mapPopup"
                                                                                data-latlon="{{ $history_entry->lat }}, {{ $history_entry->lon }}">
                                                                                <i class="ti-location-pin"></i>
                                                                            </button>
                                                                        @else
                                                                            <p class="text-danger">pas de coordonnées</p>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <p>Aucune donnée disponible pendant la période spécifiée.</p>
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
        var map = L.map('map-popup').setView([5.328056, -4.001333], 10);
        // var marker = new L.Marker([3, 5]);
        var marker;
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        document.querySelectorAll('.displayModal').forEach(element => {
            element.addEventListener('click', function() {
                var data = this.dataset.latlon;
                var coords = data.split(',');

                setTimeout(function() {
                    map.invalidateSize();

                    if (marker) {
                        marker.removeFrom(map);
                    }

                    marker = L.marker([coords[0], coords[1]]).addTo(map)
                        .bindPopup('Latitude: ' + coords[0] + '<br>Longitude: ' + coords[1])
                        .openPopup();

                    map.setView([coords[0], coords[1]], 15);
                }, 400);
            })
        });

        document.getElementById('exportButtonSite').addEventListener('click', function() {
            let start = @json($dateRange['start']->format('Y-m-d'));
            let end = @json($dateRange['end']->format('Y-m-d'));
            let site = @json($site->name);
            let fileName = 'listes_employees_site_' + site + '_du_' + start + '_au_' + end + '.pdf';
            try {
                var element = document.getElementById('exportButtonSite');
                var SiteEmployeeTable = document.getElementById('siteEmployeeTable');
                var opt = {
                    margin: 1,
                    filename: fileName,
                    image: {
                        type: 'jpeg',
                        quality: 0.98
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

                html2pdf().set(opt).from(SiteEmployeeTable).save();
                html2pdf(SiteEmployeeTable, opt);

            } catch (error) {
                console.error('An error occurred:', error);
            }
        });

        new DataTable('#employeeTable', {
            paging: true,
            pageLength: 15,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            }
        });
    </script>
@endpush
