@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">
                    <div class="tab-content tab-content-basic">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">

                                <div class="card-header">{{ __('Mettre à jour les informations de l\'employée') }}</div>

                                <div class="card-body">
                                    <form action="{{ route('employees.update', $employee->id) }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="row mb-3">
                                            <label for="matricule"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Quicklook') }}</label>

                                            <div class="col-md-8">
                                                <input id="matricule" type="text" class="form-control" name="matricule"
                                                    value="{{ $employee->matricule }}" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="name"
                                                class="col-md-4  text-md-end">{{ __('Nom et prénom') }}</label>

                                            <div class="col-md-8">
                                                <input id="name" type="text" class="form-control" name="name"
                                                    value="{{ $employee->name }}" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="designation"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Designation') }}</label>

                                            <div class="col-md-8">
                                                <input id="designation" type="text" class="form-control"
                                                    name="designation" value="{{ $employee->designation }}" required>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="department"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Département') }}</label>
                                            <div class="col-md-8">
                                                <select name="department_id" id="siteSelect"
                                                    class="form-select nav-link dropdown-bordered"
                                                    aria-label="Sélectionnez le département"
                                                    style="color: #000000 !important">
                                                    <option selected disabled>Sélectionner le département</option>
                                                    @foreach (config('department') as $department)
                                                        <option value="{{ $department['id'] }}"
                                                            {{ old('department_id', $employee->department_id) == $department['id'] ? 'selected' : '' }}>
                                                            {{ $department['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="image"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Image') }}</label>

                                            <div class="col-md-8">
                                                <input id="image" type="file" name="image" class="form-control"
                                                    value="{{ asset('storage/' . $employee->image_path) }}" required>
                                            </div>
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
                                        <button class="btn btn-primary btn-icon-text text-white" type="submit"><i
                                                class="ti-file btn-icon-prepend"></i>Mettre à jour</button>
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
