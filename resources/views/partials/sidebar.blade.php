<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">Localisation</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Sites</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    @foreach (config('localisation') as $localisation)
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('table')}}">{{$localisation['name']}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>

        <li class="nav-item nav-category">Administration</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-account-circle-outline"></i>
                <span class="menu-title">Utilisateurs</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="#"> Liste des utilisateurs </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"> Listes des  employés</a>
                    </li>
                </ul>
            </div>
        </li>
        <a href="{{route('logout')}}" class="btn btn-danger mt-2 " type="button" >
            Deconnexion
        </a>

    </ul>
</nav>
