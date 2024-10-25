@extends('admin.index')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .input-group {
            max-width: 600px;
            margin: 0 auto;
        }
        .table_deg {
            text-align: center;
            margin: auto;
            border: 2px solid white;
            margin-top: 50px;
            width: 600px;
        }
        th {
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
            color: skyblue;
        }
        td {
            color: skyblue;
            padding: 10px;
            border: 1px solid white;
        }
        .page-content {
            padding: 20px;
        }
        .btn-action {
            margin-right: 5px;
        }
        h3 {
            color: skyblue;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
            <h3 class="mb-4 text-center">Manage Categories</h3>
            <div class="alert alert-dismissible fade show" style="display:none;" role="alert">
                <div id="msg"></div>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="table_categories">
                    @foreach ($categories as $c)
                        @include('admin.category_row', ['c' => $c])
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center"><strong>New Category</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" class="form-control" id="txtName_0" name="txtName_0">
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-action" id="btnAdd">Add</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $(document).ready(function() {
            // Add Category
            $("#btnAdd").click(function() {
                let name = $("#txtName_0").val();
                if (name == "") {
                    showAlert('Please enter a category name', 'danger');
                    return;
                }
                $(this).attr('disabled', 'disabled');
                $.ajax({
                    url: "{{ route('admin.categories.store') }}",
                    type: 'POST',
                    data: {
                        category: name,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showAlert(response.msg, 'success');
                        $("#table_categories").append(response.row);
                        $("#txtName_0").val('');
                        $("#btnAdd").removeAttr('disabled');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422 && xhr.responseJSON.errors && xhr.responseJSON.errors.category) {
                            showAlert(xhr.responseJSON.errors.category[0], 'danger');
                        } else {
                            console.error(xhr.responseText);
                            showAlert('An error occurred while adding the category', 'danger');
                        }
                        $("#btnAdd").removeAttr('disabled');
                    }
                });
            });
            // Function to show alerts
            function showAlert(message, type) {
                const alertDiv = $(".alert");
                alertDiv.removeClass('alert-success alert-danger').addClass('alert-' + type);
                $("#msg").html(message);
                alertDiv.fadeIn();
                setTimeout(() => {
                    alertDiv.fadeOut();
                }, 3000);
            }
        });

        // Update Category
        function updateCategory(id) {
                let newName = $("#txtName_" + id).val().trim();
                let currentName = $("#category_name_" + id).text().trim();

                if (newName === "") {
                    showAlert('Please enter a category name', 'danger');
                    return;
                }

                if (newName === currentName) {
                    showAlert('No changes made', 'info');
                    return;
                }

                $.ajax({
                    url: "{{ url('/admin/categories/update') }}/" + id,
                    type: 'POST',
                    data: {
                        category: newName,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showAlert(response.msg, 'success');
                        $("#category_name_" + id).text(newName);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        showAlert('An error occurred while updating the category', 'danger');
                    }
                });
            }


        // Delete Category
        function deleteCategory(id) {
    // Show SweetAlert confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to recover this category!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed deletion, proceed with AJAX request
            $.ajax({
                url: "{{ url('/admin/categories/delete') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showAlert(response.msg, 'success');
                    $("#category_" + id).remove();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    showAlert('An error occurred while deleting the category', 'danger');
                }
            });
        }
    });
}

        // Function to show alerts (shared by all scripts)
        function showAlert(message, type) {
            const alertDiv = $(".alert");
            alertDiv.removeClass('alert-success alert-danger').addClass('alert-' + type);
            $("#msg").html(message);
            alertDiv.fadeIn();
            setTimeout(() => {
                alertDiv.fadeOut();
            }, 3000);
        }
    </script>
@endsection
