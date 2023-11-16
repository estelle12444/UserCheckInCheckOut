@extends('layouts')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="home-tab">

                    <div class="card-header">Employee Detail</div>

                    <div class="card-body">
                        @if ($employee)
                            <p>Name: {{ $employee->name }}</p>
                            <p>Email: {{ $employee->email }}</p>
                            <p>Designation: {{ $employee->designation }}</p>

                            <h3>Entry and Exit Times</h3>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Entry Time</th>
                                        <th>Exit Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee->historyEntries->employee_id as $entry)
                                        <tr>

                                            <td>{{ $entry->time_at_in->format('H:i:s') }}</td>
                                            <td>
                                                @if ($entry->time_at_out)
                                                    {{ $entry->time_at_out->format('H:i:s') }}
                                                @else
                                                    Not yet exited
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Employee not found.</p>
                        @endif
                    </div>

            </div>
        </div>

    </div>


@endsection
