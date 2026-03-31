<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management</title>
</head>
<body>

    <h1>Pharmacy Management</h1>

    <p><strong>Center:</strong> {{ $center->name }}</p>

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

    <hr>

    <h2>Add Pharmacist</h2>

    <form action="{{ route('Admin.Pharmacy.store') }}" method="POST">
        @csrf

        <input type="text" name="name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name">
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Add Pharmacist</button>
    </form>

    <hr>

    <h2>Pharmacists in This Center</h2>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pharmacists as $pharmacist)
                <tr>
                    <td>
                        {{ trim(($pharmacist->name ?? '') . ' ' . ($pharmacist->last_name ?? '')) }}
                    </td>
                    <td>{{ $pharmacist->email }}</td>
                    <td>{{ $pharmacist->phone }}</td>
                    <td>

                        <!-- Edit -->
                        <a href="{{ route('Admin.Pharmacy.edit', $pharmacist->id) }}">
                            Edit
                        </a>

                        <!-- Delete -->
                        <form action="{{ route('Admin.Pharmacy.destroy', $pharmacist->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Delete this pharmacist?')">
                                Delete
                            </button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No pharmacists added yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>