<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ route('home') }}">
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
            @if (request()->routeIs('flexibilityIndex') ||
                    request()->is('employees/*/detail') ||
                    request()->routeIs('absenceIndex') ||
                    request()->is('home'))
                <li class="nav-item d-none d-lg-block">
                    <form action="" id="date-filter">
                        {{-- @dd( request()->get('selectedDates')) --}}
                        <div class="input-group date datepicker navbar-date-picker">
                            <span class="input-group-addon input-group-prepend border-right">
                                <input type="text" id="datePicker" name="selectedDates" multiple>
                                <span class="icon-calendar input-group-text calendar-icon"></span>
                            </span>

                        </div>
                    </form>

                </li>
            @elseif (request()->is('site/*/employees'))
                <li class="nav-item dropdown d-none d-lg-block">
                    @php
                        $currentSite = request()->segment(2);
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
                                <input type="text" id="datePicker" name="selectedDates" multiple>
                                <span class="icon-calendar input-group-text calendar-icon"></span>
                            </span>

                        </div>
                    </form>

                </li>
            {{-- @elseif (request()->routeIs('incidents.index')||request()->routeIs('incidents.listAccept') ||request()->routeIs('incidents.listReject') )
                <li class="nav-item dropdown">
                    <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="icon-bell"></i>
                        <span class="count"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                        aria-labelledby="countDropdown">
                        <a class="dropdown-item py-3">
                            <p class="mb-0 font-weight-medium float-left">Vous avez {{ app('App\Http\Controllers\IncidentController')->countPendingIncidents() }}  demandes en attente </p>
                            <span class="badge badge-pill badge-primary float-right" href="{{route('incidents.index')}}">Voir Plus</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @php
                             $latestPending= app('App\Http\Controllers\IncidentController')->latestPendingIncidents();

                        @endphp
                       @foreach ($latestPending as $incident)
                       <a class="dropdown-item preview-item" href="{{route('incidents.index')}}">
                           <div class="preview-thumbnail">

                               <img src="{{ asset('storage/' .$incident->image) }}"   alt="{{ $incident->employee->name }}" class="img-sm profile-pic">

                           </div>

                           <div class="preview-item-content flex-grow py-2">
                               <p class="preview-subject ellipsis font-weight-medium text-dark"> {{ $incident->employee->name }}</p>
                               {{ $incident->employee->designation}}
                           </div>

                       </a>
                   @endforeach
                    </div>
                </li> --}}
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link {{ app('App\Http\Controllers\IncidentController')->countPendingIncidents() != 0 ? 'count-indicator' : '' }}" id="countDropdown" href="#" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="icon-bell"></i>
                    <span class="count"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                    aria-labelledby="countDropdown">
                    <a class="dropdown-item py-3" href="{{route('incidents.index')}}">
                        <p class="mb-0 font-weight-medium float-left">Vous avez <strong class="px-2 text-danger ">
                            {{ app('App\Http\Controllers\IncidentController')->countPendingIncidents() }} </strong>  demandes en attente </p>
                        <span class="badge badge-pill badge-primary float-right" >Voir Plus</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    @php
                         $latestPending= app('App\Http\Controllers\IncidentController')->latestPendingIncidents();

                    @endphp
                   @foreach ($latestPending as $incident)
                   <a class="dropdown-item preview-item" href="{{route('incidents.index')}}">
                       <div class="preview-thumbnail">

                           <img src="{{ asset('storage/' .$incident->image) }}"   alt="{{ $incident->employee->name }}" class="img-sm profile-pic">
                       </div>

                       <div class="preview-item-content flex-grow py-2">
                           <p class="preview-subject ellipsis font-weight-medium text-dark"> {{ $incident->employee->name }}</p>
                           {{ $incident->employee->designation}}
                       </div>

                   </a>
               @endforeach
                </div>
            </li>
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
                    <a class="dropdown-item" href="{{ route('profile.show', ['id' => Auth::user()->id]) }}"><i
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
