@extends('home.index')

@section('content')

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-form {
            margin-bottom: 20px;
        }

        .profile-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .profile-form input[type="text"],
        .profile-form input[type="email"],
        .profile-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .profile-form button[type="submit"] {
            background-color: #4a90e2;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .profile-form button[type="submit"]:hover {
            background-color: #357bd8;
        }

        .delete-account {
            text-align: center;
        }

        .delete-account button[type="submit"] {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .delete-account button[type="submit"]:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<div class="container">
    <h2 id="userName">{{ $user->name }}'s Profile</h2>

    <form id="profileForm" class="profile-form" method="POST" action="{{ route('profile.update') }}">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ $user->name }}" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ $user->email }}" required>

        <button type="submit">Update Profile</button>
    </form>

    <hr>

    <div class="change-password">
        <h3>Change Password</h3>
        <form id="passwordForm" class="profile-form" method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="new_password_confirmation">Confirm New Password:</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>

            <button type="submit">Change Password</button>
        </form>
    </div>

    <hr>

    <div class="delete-account">
        <h3>Delete Account</h3>
        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
        <form id="deleteForm" method="POST" action="{{ route('profile.delete') }}">
            @csrf
            @method('DELETE')
            <button type="submit">Delete Account</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#profileForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.no_changes) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Changes',
                        text: response.message
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#userName').text(response.user.name);
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message
                });
            }
        });
    });


    
    $('#passwordForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#passwordForm')[0].reset();
            },
            error: function(xhr) {
                if (xhr.responseJSON.errors) {
                    if (xhr.responseJSON.errors.current_password) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.errors.current_password[0]
                        });
                    } else if (xhr.responseJSON.errors.new_password) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.errors.new_password[0]
                        });
                    } else if (xhr.responseJSON.errors.new_password_confirmation) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.errors.new_password_confirmation[0]
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message
                    });
                }
            }
        });
    });

    $('#deleteForm').submit(function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'DELETE',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.href = '/'; 
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message
                        });
                    }
                });
            }
        });
    });
</script>

@endsection
