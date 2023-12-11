@extends('auth.layouts')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Connexion') }}</div>

                    <div class="card-body">

                        <h4>Bonjour ! Commençons</h4>

                        <form class="pt-3" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <input type="email"
                                    class="form-control form-control-lg form-control "
                                    id="exampleInputEmail1" placeholder="Email" name="email" value="{{ old('email') }}"
                                    required autocomplete="email" autofocus>

                            </div>
                            <div class="form-group">
                                <input type="password"
                                    class="form-control form-control-lg "
                                    name="password" required autocomplete="current-password" id="exampleInputPassword1"
                                    placeholder="Mot de Passe">
                               
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="mt-3">

                                <button type="submit"
                                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                    {{ __('Se connecter') }}
                                </button>
                            </div>
                            <div class="my-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <label class="form-check-label text-muted">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        {{ __('Se souvenir de moi') }}
                                    </label>
                                </div>
                                <a class="auth-link text-black"
                                    @if (Route::has('password.request')) href="{{ route('password.request') }}">
                          {{ __('Mot de passe oublié?') }}
                      </a> @endif
                                    </a>
                            </div>

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
