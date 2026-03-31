<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Pharmacist</title>
</head>
<body>

    <h1>Edit Pharmacist</h1>

    <p><strong>Center:</strong> {{ $center->name }}</p>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('Admin.Pharmacy.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="First Name" required>
        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="Last Name">
        <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email" required>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Phone" required>
        <input type="password" name="password" placeholder="New Password (optional)">

        <button type="submit">Update Pharmacist</button>
    </form>

    <br>
    <a href="{{ route('Admin.Pharmacy.index') }}">Back</a>

</body>
</html>