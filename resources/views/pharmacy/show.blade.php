<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Details</title>
</head>
<body>

    <h1>Prescription Details</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p><strong>Prescription ID:</strong> {{ $prescription->id }}</p>
    <p>
        <strong>Patient:</strong>
        {{ $prescription->appointment?->patient?->user?->name }}
        {{ $prescription->appointment?->patient?->user?->last_name }}
    </p>
    <p>
        <strong>Doctor:</strong>
        {{ $prescription->appointment?->doctor?->user?->name }}
        {{ $prescription->appointment?->doctor?->user?->last_name }}
    </p>
    <p><strong>Date:</strong> {{ $prescription->appointment?->start_at?->format('Y-m-d') }}</p>
    <p><strong>Status:</strong> {{ $prescription->status }}</p>
    <p><strong>General Note:</strong> {{ $prescription->general_note ?? '---' }}</p>

    <hr>

    <h3>Medicines</h3>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Medicine Name</th>
                <th>Dose</th>
                <th>Frequency</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Instructions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prescription->items as $item)
                <tr>
                    <td>{{ $item->medicine_name }}</td>
                    <td>{{ $item->dose }}</td>
                    <td>{{ $item->frequency }}</td>
                    <td>{{ $item->start_date }}</td>
                    <td>{{ $item->end_date }}</td>
                    <td>{{ $item->instructions ?? '---' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No medicines found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <hr>

    <h3>Update Status</h3>

    <form action="{{ route('pharmacy.prescriptions.updateStatus', $prescription->id) }}" method="POST">
        @csrf

        <select name="status" required>
            <option value="pending" {{ $prescription->status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="ready" {{ $prescription->status === 'ready' ? 'selected' : '' }}>Ready</option>
            <option value="dispensed" {{ $prescription->status === 'dispensed' ? 'selected' : '' }}>Dispensed</option>
        </select>

        <button type="submit">Update Status</button>
    </form>

    <br>
    <a href="{{ route('pharmacy.dashboard') }}">Back to Dashboard</a>

</body>
</html>