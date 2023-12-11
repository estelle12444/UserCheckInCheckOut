@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-header">{{ __('Enregistrement de l\'employée') }}</div>
                                <div class="card-body">
                                    <form action="{{ route('employees.store') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3">
                                            <label for="matricule"
                                                class="col-md-3 col-form-label text-md-end">{{ __('Quicklook') }}</label>

                                            <div class="col-md-8">
                                                <input id="matricule" type="text"
                                                    class="form-control @error('matricule') is-invalid @enderror"
                                                    name="matricule" value="{{ old('matricule') }}" required
                                                    autocomplete="matricule" autofocus>

                                                @error('matricule')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="name"
                                                class="col-md-3  text-md-end">{{ __('Nom et prénom') }}</label>

                                            <div class="col-md-8">
                                                <input id="name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ old('name') }}" required autocomplete="name" autofocus>

                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="designation"
                                                class="col-md-3 col-form-label text-md-end">{{ __('Designation') }}</label>

                                            <div class="col-md-8">
                                                <input id="designation" type="text"
                                                    class="form-control @error('designation') is-invalid @enderror"
                                                    name="designation" value="{{ old('designation') }}" required
                                                    autocomplete="designation" autofocus>

                                                @error('designation')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="department"
                                                class="col-md-3 col-form-label text-md-end">{{ __('Département') }}</label>
                                            <div class="col-md-8">
                                                <select name="department_id" style="color: #000000 !important"
                                                    class="form-select nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split"
                                                    aria-label="Large select example">
                                                    <option selected>Selectionner le département</option>
                                                    @foreach (config('department') as $department)
                                                        <option value="{{ $department['id'] }}">
                                                            {{ $department['name'] }}</option>
                                                    @endforeach
                                                </select>

                                                @error('department')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-3">

                                            <label for="image"
                                                class="col-md-3 col-form-label text-md-end">{{ __('Image') }}</label>
                                            <div class="col-md-8">
                                                <input id="image" type="file" name="image" class="form-control"
                                                    accept="image/*" onchange="previewImage(this);">
                                                <div id="imagePreview" class="mt-2"></div>
                                            </div>
                                        </div>

                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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
                                    <div class="row mb-0">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary text-white">
                                                <i class="ti-file btn-icon-prepend"></i> {{ __('Enregistrer') }}
                                            </button>
                                        </div>
                                </div>
                            </div>
                                </form>

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
        function previewImage(input) {
            var preview = document.getElementById('imagePreview');
            preview.innerHTML = ''; // Clear previous preview

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100%';
                    img.style.maxHeight = '150px'; // Set your preferred height

                    preview.appendChild(img);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
