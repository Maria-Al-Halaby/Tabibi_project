<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radiology Dashboard</title>
</head>
<body>

    <h1>Radiology Dashboard</h1>

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
                <th>Image Type</th>
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
                    <td>{{ $appointment->radiologyAppointment?->type?->name ?? '---' }}</td>
                    <td>{{ $appointment->start_at?->format('Y-m-d') }}</td>
                    <td>{{ $appointment->start_at?->format('H:i') }}</td>
                    <td>{{ $appointment->status }}</td>
                    <td>
                        <a href="{{ route('radiology.appointments.complete.form', $appointment->id) }}">
                            Complete Appointment
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No radiology appointments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>