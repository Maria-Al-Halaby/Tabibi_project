<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Prescription Details') }}</title>
</head>
<body>

<h1>{{ __('Prescription Details') }}</h1>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if($errors->any())<div style="color: red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<p><strong>{{ __('ID:') }}</strong> {{ $prescription->id }}</p>

<p><strong>{{ __('Patient:') }}</strong>
    {{ $prescription->appointment?->patient?->user?->name }}
    {{ $prescription->appointment?->patient?->user?->last_name }}
</p>

<p><strong>{{ __('Doctor:') }}</strong>
    {{ $prescription->appointment?->doctor?->user?->name }}
    {{ $prescription->appointment?->doctor?->user?->last_name }}
</p>

<p><strong>{{ __('Date:') }}</strong>
    {{ optional($prescription->appointment?->start_at)->format('Y-m-d') }}
</p>

<p>
    <strong>{{ __('Status:') }}</strong>
    <span style="
        color:
        @if($prescription->pharmacy_status == 'pending') orange
        @elseif($prescription->pharmacy_status == 'ready') blue
        @else green
        @endif
    ">
        {{ match ($prescription->pharmacy_status) {
            'ready' => __('Ready'),
            'dispensed' => __('Dispensed'),
            default => __('Pending'),
        } }}
    </span>
</p>

<p><strong>{{ __('Note:') }}</strong> {{ $prescription->general_note ?? '---' }}</p>

<hr>

<h3>{{ __('Medicines') }}</h3>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Dose') }}</th>
            <th>{{ __('Frequency') }}</th>
            <th>{{ __('Start') }}</th>
            <th>{{ __('End') }}</th>
            <th>{{ __('Instructions') }}</th>
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
                <td colspan="6">{{ __('No medicines') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>

<hr>

<h3>{{ __('Update Status') }}</h3>

<form action="{{ route('pharmacy.prescriptions.updateStatus', $prescription->id) }}" method="POST">
    @csrf

    <select name="pharmacy_status" required>
        <option value="pending" {{ $prescription->pharmacy_status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
        <option value="ready" {{ $prescription->pharmacy_status == 'ready' ? 'selected' : '' }}>{{ __('Ready') }}</option>
        <option value="dispensed" {{ $prescription->pharmacy_status == 'dispensed' ? 'selected' : '' }}>{{ __('Dispensed') }}</option>
    </select>

    <button type="submit">{{ __('Update') }}</button>
</form>

<br>

<a href="{{ route('pharmacy.dashboard') }}">{{ __('Back') }}</a>

</body>
</html>
