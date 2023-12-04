@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">

                        <div class="col-md-9 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="card-title">Votre Profil</h2>

                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if ($editing)
                                        <!-- Formulaire de modification -->
                                        <form class="forms-sample" action="{{ route('profile.update') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="name">Nom d'utilisateur</label>
                                                <input type="text" id="name" name="name"
                                                    value="{{ old('name', $user->name) }}" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" name="email"
                                                    value="{{ old('email', $user->email) }}" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="password">Mot de passe</label>
                                                <input type="password" id="password" name="password" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirmer le mot de passe</label>
                                                <input type="password" id="password_confirmation"
                                                    name="password_confirmation" class="form-control">
                                            </div>

                                            <button type="submit"
                                                class="btn btn-primary me-2 text-white">Sauvegarder</button>
                                        </form>
                                    @else
                                        <div class="card-body">
                                            <h5 class="pb-2"><strong>Nom d'utilisateur :</strong> {{ $user->name }}</h5>
                                            <h5 class="pb-4"><strong>Email :</strong> {{ $user->email }}</h5>

                                            <a href="{{ route('profile.show', ['edit' => true]) }}"
                                                class="btn btn-primary p-4 me-2 text-white hover:underline">Modifier le
                                                profil</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
