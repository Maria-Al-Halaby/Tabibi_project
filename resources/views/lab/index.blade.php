<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Dashboard</title>
</head>
<body>

    <h1>Lab Dashboard</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patient</th>
                <th>Center</th>
                <th>Tests</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->id }}</td>
                    <td>{{ $appointment->patient?->user?->name }} {{ $appointment->patient?->user?->last_name }}</td>
                    <td>{{ $appointment->clinic_center?->name }}</td>
                    <td>
                        @forelse($appointment->labTests as $test)
                            <div>{{ $test->name }}</div>
                        @empty
                            ---
                        @endforelse
                    </td>
                    <td>{{ $appointment->start_at?->format('Y-m-d') }}</td>
                    <td>{{ $appointment->start_at?->format('H:i') }}</td>
                    <td>{{ $appointment->status }}</td>
                    <td>
                        <a href="{{ route('lab.appointments.complete.form', $appointment->id) }}">
                            Complete Appointment
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No lab appointments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>