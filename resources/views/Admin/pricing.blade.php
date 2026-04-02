<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pricing Management</title>
</head>
<body>

    <h1>Pricing Management</h1>

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

    <!-- 🟢 Lab Tests -->
    <h2>Lab Tests Pricing</h2>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Test Name</th>
                <th>Current Price</th>
                <th>New Price</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($labTests as $test)
                <tr>
                    <td>{{ $test['name'] }}</td>

                    <td>
                        {{ $test['price'] ?? 'Not Set' }}
                    </td>

                    <td>
                        <form action="{{ route('Admin.Pricing.lab') }}" method="POST">
                            @csrf

                            <input type="hidden" name="lab_test_id" value="{{ $test['id'] }}">

                            <input type="number" name="price" step="0.01" placeholder="Enter price" required>
                    </td>

                    <td>
                            <button type="submit">Save</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <!-- 🟣 Radiology -->
    <h2>Radiology Pricing</h2>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Image Type</th>
                <th>Current Price</th>
                <th>New Price</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($images as $img)
                <tr>
                    <td>{{ $img['name'] }}</td>

                    <td>
                        {{ $img['price'] ?? 'Not Set' }}
                    </td>

                    <td>
                        <form action="{{ route('Admin.Pricing.radiology') }}" method="POST">
                            @csrf

                            <input type="hidden" name="type_of_medical_image_id" value="{{ $img['id'] }}">

                            <input type="number" name="price" step="0.01" placeholder="Enter price" required>
                    </td>

                    <td>
                            <button type="submit">Save</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>