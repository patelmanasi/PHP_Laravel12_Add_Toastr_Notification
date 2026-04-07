<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Users Dashboard</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-delete {
            background: #ff4d4d;
        }

        .btn-toggle {
            background: #007bff;
        }

        .btn:hover {
            opacity: 0.85;
        }

        .search-form {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }

        .search-form input {
            flex: 1;
            padding: 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        #toastr {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 200px;
        }

        #toastr div {
            margin-bottom: 10px;
            padding: 10px;
            color: #fff;
            border-radius: 4px;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        .success {
            background: #28a745;
        }

        @keyframes fadein {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeout {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Users Dashboard</h2>

        {{-- Search + Export --}}
        <form method="GET" action="{{ route('users.index') }}" class="search-form">
            <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-success">Search</button>
            <a href="{{ route('users.export') }}" class="btn btn-success">Export CSV</a>
        </form>

        {{-- Users Table --}}
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        {{-- Status column (readonly) --}}
                        <td>
                            <span class="btn {{ $user->status ? 'btn-success' : 'btn-danger' }}">
                                {{ $user->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        {{-- Action column: Toggle + Delete --}}
                        <td>
                            <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-toggle">Toggle</button>
                            </form>
                            <form action="{{ route('users.delete', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>Total Users: {{ $users->total() }}</p>
        {{ $users->links() }}
    </div>

    {{-- Toastr --}}
    <div id="toastr">
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const t = document.querySelector('#toastr div');
            if (t) { setTimeout(() => t.style.display = 'none', 3000); }
        });
    </script>
</body>

</html>