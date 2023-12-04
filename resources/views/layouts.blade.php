<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>FaceRecognition</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">


    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}" />


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Add Daterangepicker CSS and JS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">


    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

</head>

<body>
    <div class="container-scroller">


        @include('partials.nav')

        <div class="container-fluid page-body-wrapper">

            <!-- partial:partials/_settings-panel.html -->
            {{-- @include('partials.settingsPanel') --}}
            <!-- partial -->

            <!-- partial:partials/_sidebar.html -->
            @include('partials.sidebar')
            <!-- partial -->
            <div class="main-panel">
                @yield('content')

                @include('partials.footer')
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendors/progressbar.js/progressbar.min.js') }}"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('js/off-canvas.js') }}"></script>
    <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/settings.js') }}"></script>
    <script src="{{ asset('js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    {{-- <script src="{{ asset('js/dashboard.js') }}"></script> --}}
    <script src="{{ asset('js/Chart.roundedBarCharts.js') }}"></script>
    <!-- End custom js for this page-->

    <!-- Add Bootstrap JS (if not already included) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.0.1/mammoth.browser.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script src="  https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="    https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script>
        // Utilisation de jQuery pour détecter le changement de  sélection
        $(document).ready(function() {
            $('#siteSelect').on('change', function() {
                var selectedSite = $(this).val();
                // Mettre à jour l'URL avec le nouvel identifiant de site
                window.location.href = '/site/' + selectedSite + '/employees';
            });


            var paramUrl = getQueryVariable('selectedDates');

            var firstday = @json(Carbon\Carbon::now()->startOfDay());
            var lastday = @json(Carbon\Carbon::now()->endOfWeek());

            flatpickr("#datePicker", {
                mode: "range",
                maxDate: new Date(),
                defaultDate: paramUrl !== null && paramUrl !== undefined ? paramUrl.split('+to+') : [
                    firstday, lastday
                ],
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length == 2) {
                        $('form#date-filter').submit();
                    }

                }
            });
            //console.log(getQueryVariable('selectedDates').split('+to+'));

            function getQueryVariable(variable) {
                var query = window.location.search.substring(1);
                var vars = query.split("&");
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split("=");
                    if (pair[0] == variable) {
                        return pair[1];
                    }
                }
            }
        });
        document.getElementById('exportButton').addEventListener('click', function() {
            try {
                var element = document.getElementById('exportButton');
                var historyTable = document.getElementById('historyTable');
                var opt = {
                    margin: 1,
                    filename: 'myfile.pdf',
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
                // New Promise-based usage:
                html2pdf().set(opt).from(historyTable).save();

                // Old monolithic-style usage:
                html2pdf(historyTable, opt);

            } catch (error) {
                console.error('An error occurred:', error);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
    <!-- Initialize the date range picker -->

</body>

</html>
