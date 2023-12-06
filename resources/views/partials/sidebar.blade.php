{{-- <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="mdi mdi-grid-large menu-icon"></i>
          <span class="menu-title">Tableau de bord</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" >
          <i class="mdi mdi-floor-plan menu-icon"></i>
          <span class="menu-title">Localisation</span>
        </a>
        <ul class="nav flex-column sub-menu">
          @foreach (config('localisation') as $localisation)
            <li class="nav-item {{ request()->is("site/{$localisation['id']}/employees") ? 'active' : '' }}">
              <a class="nav-link" href="/site/{{ $localisation['id'] }}/employees">
                {{ $localisation['name'] }}
              </a>
            </li>
          @endforeach
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" >
          <i class="mdi mdi-floor-plan menu-icon"></i>
          <span class="menu-title">Tableaux</span>
        </a>
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('flexibilityIndex') }}">Flexibilité des employés</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('absenceIndex') }}">Absences</a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="mdi mdi-account-circle-outline menu-icon"></i>
          <span class="menu-title">Employés</span>
        </a>
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('employees.index') }}">Liste</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('employeeRegisterForm') }}">Enregistrer un nouvel</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('employeeRegister') }}">Importation Excel</a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}">
          <i class="mdi mdi-power menu-icon"></i>
          <span class="menu-title">Déconnexion</span>
        </a>
      </li>
    </ul>
</nav> --}}

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Tableau de bord</span>
            </a>
        </li>
        {{-- <li class="nav-item nav-category">Localisation</li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          <i class="menu-icon mdi mdi-floor-plan"></i>
          <span class="menu-title">Sites</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            @foreach (config('localisation') as $localisation)
            <li class="nav-item {{ request()->is("site/{$localisation['id']}/employees") ? 'active' : '' }}">
              <a class="nav-link" href="/site/{{ $localisation['id'] }}/employees">
                {{ $localisation['name'] }}
              </a>
            </li>
          @endforeach
        </ul>
        </div>
      </li> --}}
      <li class="nav-item nav-category">Localisations</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#sites" aria-expanded="false" aria-controls="sites">
                <i class="menu-icon mdi mdi-layers-outline"></i>
                <span class="menu-title">sites</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sites">

                <ul class="nav flex-column sub-menu">
                    @foreach (config('localisation') as $localisation)
                        <li
                            class="nav-item {{ request()->is("site/{$localisation['id']}/employees") ? 'active' : '' }}">
                            <a class="nav-link" href="/site/{{ $localisation['id'] }}/employees">
                                {{ $localisation['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>
        <li class="nav-item nav-category">Gestion des Employées</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
                <i class="menu-icon mdi mdi-layers-outline"></i>
                <span class="menu-title">Employés</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('employees.index') }}">Liste</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('employeeRegisterForm') }}">Enregistrer
                            nouvel employé</a></li>

                    <li class="nav-item"><a class="nav-link" href="{{ route('flexibilityIndex') }}">Flexibilités
                            des
                            employées</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('absenceIndex') }}">Absences</a>
                    </li>

                    <li class="nav-item"> <a class="nav-link" href="{{ route('employeeRegister') }}">Importation
                            Excel</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item nav-category">Gestions des incidents</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#incidents" aria-expanded="false" aria-controls="incidents">
                <i class="menu-icon mdi mdi-layers-outline"></i>
                <span class="menu-title">Incidents</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="incidents">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('incidents.index') }}">En attente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('incidents.listAccept') }}">Accepter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('incidents.listReject') }}">Refuser</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('incidents.to_delete') }}">A Supprimer</a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="nav-item nav-category"> Administrations</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#admin" aria-expanded="false" aria-controls="admin">
              <i class="menu-icon mdi mdi-layers-outline"></i>
              <span class="menu-title">Administrations</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="admin">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{route('user-list')}}">Administrateurs</a></li>
              </ul>
            </div>
          </li>
{{--
        <li class="nav-item nav-category"> Administrations</li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('user-list')}}">
            <i class="menu-icon mdi mdi-file-document"></i>
            <span class="menu-title">Administrateurs </span>
        </a>
    </li> --}}


        <li class="nav-item nav-category"> Deconnexion</li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}">
            <i class="menu-icon mdi mdi-file-document"></i>
            <span class="menu-title">Deconnexion</span>
        </a>
    </li>
    </ul>
</nav>
