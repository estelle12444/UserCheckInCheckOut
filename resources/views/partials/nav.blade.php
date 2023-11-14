<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="/">
                <img src="{{asset('images/logo.png')}}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="/">
                <img src="{{asset('images/logo.png')}}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Bonjour, <span class="text-black fw-bold">{{ app('App\Http\Controllers\HomeController')->geUsername() }}</span></h1>
                <h3 class="welcome-sub-text">Performances des employés </h3>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            @if(request()->is('home') || request()->is('table-site/*'))
            <li class="nav-item dropdown d-none d-lg-block">
                {{-- <a class="nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split" id="messageDropdown"
                    href="#" data-bs-toggle="dropdown" aria-expanded="false"> Selectionner le Site </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                    aria-labelledby="messageDropdown">
                    <a class="dropdown-item py-3">
                        <p class="mb-0 font-weight-medium float-left">Selectionner le Site</p>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">Site de Danga
                            </p>
                           <p class="fw-light small-text mb-0">This is a Bundle featuring 16 unique dashboards
                            </p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">Site du Campus</p>

                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">Site de Laurier</p>

                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">Site de Duncan</p>

                        </div>
                    </a>
                </div> --}}
                @php
                $currentSite = request()->segment(2); // Récupère la deuxième partie de l'URL (table-site/{site})
                $localisations = config('localisation');
            @endphp

            <select id="siteSelect" class="form-select nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split" aria-label="Large select example">
                <option selected>Selectionner le Site</option>
                @foreach($localisations as $localisation)
                    @php
                        $siteId = $localisation['id'];
                        $siteName = $localisation['name'];
                    @endphp
                    <option value="{{ $siteId }}" {{ $currentSite == $siteId ? 'selected' : '' }}>Site de {{ $siteName }}</option>
                @endforeach
            </select>




            </li>
{{--
            <li class="nav-item d-none d-lg-block">
                <div id="datepicker-popup0" class="input-group date datepicker navbar-date-picker">
                    <span class="input-group-addon input-group-prepend border-right">
                        <span class="icon-calendar input-group-text calendar-icon"></span>
                    </span>
                    <input id="departure_date" type="date" class="form-control" name="departure_date" />
                </div>
            </li>
             <li class="nav-item d-none d-lg-block">
                <div id="datepicker-popup1" class="input-group date datepicker navbar-date-picker">
                    <span class="input-group-addon input-group-prepend border-right">
                        <span class="icon-calendar input-group-text calendar-icon"></span>
                    </span>
                    <input id="arrival_date" type="date" class="form-control" name="arrival_date" />
                </div>
            </li> --}}
            <li><div class="input-group input-daterange">
                <input type="text" class="form-control" value="2012-04-05">
                <div class="input-group-addon">to</div>
                <input type="text" class="form-control" value="2012-04-19">
            </div></li>
        @endif



            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img class="img-xs rounded-circle" src="{{asset('images/faces/face8.jpg')}}" alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-md rounded-circle" src="images/faces/face8.jpg" alt="Profile image">
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            {{ __('Vous êtes connecté!') }}
                        </div>
                        <p class="mb-1 mt-3 font-weight-semibold">{{ app('App\Http\Controllers\HomeController')->geUsername() }}</p>
                        <p class="fw-light text-muted mb-0"> {{ app('App\Http\Controllers\HomeController')->getEmail() }} </p>
                    </div>
                    <a class="dropdown-item"><i
                            class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> MON
                        Profile <span class="badge badge-pill badge-danger">1</span></a>
                    <a class="dropdown-item"><i
                            class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i>
                        Activity</a>

                    <a class="dropdown-item" href="{{route('logout')}}"><i
                            class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Déconnexion</a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>

