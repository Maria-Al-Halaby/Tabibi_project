<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Radiology Appointment</title>
</head>
<body>

    <h1>Complete Radiology Appointment</h1>

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
    <p><strong>Image Type:</strong> {{ $appointment->radiologyAppointment?->type?->name ?? '---' }}</p>
    <p><strong>Date:</strong> {{ $appointment->start_at?->format('Y-m-d') }}</p>
    <p><strong>Time:</strong> {{ $appointment->start_at?->format('H:i') }}</p>

    <hr>

    <form action="{{ route('radiology.appointments.complete') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

        <div>
            <label for="image_file">Upload Image / PDF:</label><br>
            <input type="file" name="image_file" id="image_file" required>
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
    <a href="{{ route('radiology.dashboard') }}">Back to Dashboard</a>

</body>
</html>