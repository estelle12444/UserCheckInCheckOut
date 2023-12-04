@extends('auth.layouts')

@section('content')
    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Enregistrement de l\'employée') }}</div>

                    <div class="card-body">
                        <form action="{{ route('importEmployee') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" />
                            <button class="btn btn-primary btn-icon-text" type="submit"><i class="ti-file btn-icon-prepend"></i>Importer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
