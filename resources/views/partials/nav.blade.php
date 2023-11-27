<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="index.html">
                <img src="{{ asset('images/logo.png') }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="index.html">
                <img src="{{ asset('images/logo.png') }}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Bonjour, <span
                        class="text-black fw-bold">{{ app('App\Http\Controllers\HomeController')->geUsername() }}</span>
                </h1>
                <h3 class="welcome-sub-text">Performances des employés</h3>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            @if (request()->is('site/*/employees'))
                <li class="nav-item dropdown d-none d-lg-block">

                    @php
                        $currentSite = request()->segment(2); // Récupère la deuxième partie de l'URL (table-site/{site})
$localisations = config('localisation');
                    @endphp

                    <select id="siteSelect"
                        class="form-select nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split"
                        aria-label="Large select example">
                        <option selected>Selectionner le Site</option>
                        @foreach ($localisations as $localisation)
                            @php
                                $siteId = $localisation['id'];
                                $siteName = $localisation['name'];
                            @endphp
                            <option value="{{ $siteId }}" {{ $currentSite == $siteId ? 'selected' : '' }}>Site de
                                {{ $siteName }}</option>
                        @endforeach
                    </select>

                </li>
                <li class="nav-item d-none d-lg-block">
                    <form action="" id="date-filter">
                        {{-- @dd( request()->get('selectedDates')) --}}
                        <div class="input-group date datepicker navbar-date-picker">
                            <span class="input-group-addon input-group-prepend border-right">
                                <input type="text" id="datePicker" name="selectedDates"
                                    value="{{ now()->format('Y-m-d') }}" multiple>
                                <span class="icon-calendar input-group-text calendar-icon"></span>
                            </span>

                        </div>
                    </form>

                </li>
            @elseif (request()->is('home'))
                <li class="nav-item dropdown d-none d-lg-block">
                    @php
                        $currentSite = request()->segment(2); // Récupère la deuxième partie de l'URL (table-site/{site})
                    $localisations = config('localisation');
                    @endphp

                    <select id="siteSelect"
                        class="form-select nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split"
                        aria-label="Large select example">
                        <option selected>Selectionner le Site</option>
                        @foreach ($localisations as $localisation)
                            @php
                                $siteId = $localisation['id'];
                                $siteName = $localisation['name'];
                            @endphp
                            <option value="{{ $siteId }}" {{ $currentSite == $siteId ? 'selected' : '' }}>Site de
                                {{ $siteName }}</option>
                        @endforeach
                    </select>

                </li>

            @elseif (request()->is('employees/*/detail'))
                <li class="nav-item d-none d-lg-block">
                    <form action="" id="date-filter">
                        <div class="input-group date datepicker navbar-date-picker">
                            <span class="input-group-addon input-group-prepend border-right">
                                <input type="text" id="datePicker" name="selectedDates"
                                    value="{{ now()->format('Y-m-d') }}" multiple>
                                <span class="icon-calendar input-group-text calendar-icon"></span>
                            </span>

                        </div>
                    </form>

                </li>
            @endif

            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="img-xs rounded-circle"
                        src="{{ app('App\Http\Controllers\HomeController')->getPhoto() }}" alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-md rounded-circle" style="width: 50px; height:50px"
                            src="{{ app('App\Http\Controllers\HomeController')->getPhoto() }}" alt="Profile image">

                        <p class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            {{ __('Vous êtes connecté!') }}
                        </p>
                        <p class="mb-1 mt-3 font-weight-semibold">
                            {{ app('App\Http\Controllers\HomeController')->geUsername() }}</p>
                        <p class="fw-light text-muted mb-0">
                            {{ app('App\Http\Controllers\HomeController')->getEmail() }}
                        </p>
                    </div>
                    <a class="dropdown-item"><i
                            class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Mon Profile </a>

                    {{-- <a class="dropdown-item"><i
                            class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Mon
                        Activity</a> --}}

                    <a class="dropdown-item" href="{{ route('logout') }}"><i
                            class="dropdown-item-icon mdi mdi-power text-danger me-2"></i>Déconnexion</a>
                </div>
            </li>
        </ul>

    </div>
</nav>
