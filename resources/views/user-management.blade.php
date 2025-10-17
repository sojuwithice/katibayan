<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SK & KK Accounts</title>
    <link rel="stylesheet" href="{{ asset('css/user-management.css') }}">
</head>
<body>
    <div class="container">
        <h1 class="page-title">Admin Dashboard - Accounts</h1>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Uploaded File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($skUsers as $user)
                    <tr>
                        <td>{{ $user->given_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact_no }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ strtoupper($user->role ?? 'N/A') }}</td>
                        <td>
                            @if($user->account_status === 'pending')
                                <span class="status pending">Pending</span>
                            @elseif($user->account_status === 'approved')
                                <span class="status approved">Approved</span>
                            @elseif($user->account_status === 'rejected')
                                <span class="status rejected">Rejected</span>
                            @else
                                <span class="status unknown">Unknown</span>
                            @endif
                        </td>
                        <td>
                            @if($user->role === 'sk' && optional($user->skOfficial)->oath_certificate_path)
                                <a href="{{ asset('storage/' . $user->skOfficial->oath_certificate_path) }}" target="_blank">View Oath Certificate</a>
                            @elseif($user->role === 'kk' && optional($user->kkMember)->barangay_indigency_path)
                                <a href="{{ asset('storage/' . $user->kkMember->barangay_indigency_path) }}" target="_blank">View Barangay Indigency</a>
                            @else
                                <span class="no-file">No file uploaded</span>
                            @endif
                        </td>
                        <td class="action-buttons">
                            @if($user->account_status === 'pending')
                                {{-- Approve Button --}}
                                <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn accept">Accept</button>
                                </form>

                                {{-- Reject Button --}}
                                <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn reject">Reject</button>
                                </form>
                            @else
                                <span class="no-action">No actions available</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="no-data">No accounts to review</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
