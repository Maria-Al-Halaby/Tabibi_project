<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Dashboard</title>
</head>
<body>

    <h1>Pharmacy Dashboard</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Prescription ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Status</th>
                <th>Medicines Count</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prescriptions as $prescription)
                <tr>
                    <td>{{ $prescription->id }}</td>
                    <td>
                        {{ $prescription->appointment?->patient?->user?->name }}
                        {{ $prescription->appointment?->patient?->user?->last_name }}
                    </td>
                    <td>
                        {{ $prescription->appointment?->doctor?->user?->name }}
                        {{ $prescription->appointment?->doctor?->user?->last_name }}
                    </td>
                    <td>{{ $prescription->appointment?->start_at?->format('Y-m-d') }}</td>
                    <td>{{ $prescription->status }}</td>
                    <td>{{ $prescription->items->count() }}</td>
                    <td>
                        <a href="{{ route('pharmacy.prescriptions.show', $prescription->id) }}">
                            View Details
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No prescriptions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>