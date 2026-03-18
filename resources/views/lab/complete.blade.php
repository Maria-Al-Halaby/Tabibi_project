<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Lab Appointment</title>
</head>
<body>

    <h1>Complete Lab Appointment</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p><strong>Appointment ID:</strong> {{ $appointment->id }}</p>
    <p><strong>Patient:</strong> {{ $appointment->patient?->user?->name }} {{ $appointment->patient?->user?->last_name }}</p>
    <p><strong>Center:</strong> {{ $appointment->clinic_center?->name }}</p>
    <p><strong>Date:</strong> {{ $appointment->start_at?->format('Y-m-d') }}</p>
    <p><strong>Time:</strong> {{ $appointment->start_at?->format('H:i') }}</p>

    <p><strong>Tests:</strong></p>
    <ul>
        @forelse($appointment->labTests as $test)
            <li>{{ $test->name }}</li>
        @empty
            <li>No tests selected</li>
        @endforelse
    </ul>

    <hr>

    <form action="{{ route('lab.appointments.complete') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

        <div>
            <label for="result_file">Upload Result File / PDF:</label><br>
            <input type="file" name="result_file" id="result_file" required>
        </div>

        <br>

        <div>
            <label for="notes">Notes (optional):</label><br>
            <textarea name="notes" id="notes" rows="5" cols="50"></textarea>
        </div>

        <br>

        <button type="submit">Complete Appointment</button>
    </form>

    <br>
    <a href="{{ route('lab.dashboard') }}">Back to Dashboard</a>

</body>
</html>