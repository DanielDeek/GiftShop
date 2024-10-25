@extends('admin.index')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <style>
        .table_deg {
            text-align: center;
            margin: auto;
            border: 2px solid white;
            margin-top: 50px;
            width: 90%;
        }

        th,
        td {
            padding: 15px;
            font-size: 18px;
            color: skyblue;
            border: 1px solid white;
        }

        .btn-action {
            margin-right: 5px;
            display: inline-block;
        }

        h3 {
            color: skyblue;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
            <h3 class="mb-4 text-center">Manage Products</h3>
            <div class="alert alert-dismissible fade show" style="display:none;" role="alert">
                <div id="msg"></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <table class="table_deg table-bordered table-striped" style="width: 100%">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="table_products">
                    @foreach ($products as $product)
                        @include('admin.product_row', ['product' => $product, 'categories' => $categories])
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-center"><strong>Add New Product</strong></td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control" id="txtTitle" name="title" placeholder="Title"></td>
                        <td><input type="text" class="form-control" id="txtDescription" name="description" placeholder="Description"></td>
                        <td>
                            <input type="file" class="form-control" id="fileImage" name="image" accept="image/*" onchange="previewImage(event, 'new')">
                        </td>
                        <td><input type="number" class="form-control" id="txtPrice" name="price" placeholder="Price"></td>
                        <td>
                            <select class="form-control" id="selectCategory" name="category">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" class="form-control" id="txtQuantity" name="quantity" placeholder="Quantity"></td>
                        <td>
                            <button type="button" class="btn btn-success btn-action" id="btnAdd">Add</button>
                        </td>
                    </tr>
                </tfoot>
                
            </table>
        </div>
    </div>

    <script>
$(document).ready(function() {
    $('#btnAdd').click(function() {
        // Clear previous alert messages
        $('.alert').hide();

        // Prepare form data
        let formData = new FormData();
        formData.append('title', $('#txtTitle').val());
        formData.append('description', $('#txtDescription').val());
        formData.append('image', $('#fileImage')[0].files[0]); // Append the file
        formData.append('price', $('#txtPrice').val());
        formData.append('category', $('#selectCategory').val());
        formData.append('quantity', $('#txtQuantity').val());
        formData.append('_token', '{{ csrf_token() }}'); // Add CSRF token

        // Debugging: Check the form data
        console.log('Form Data:', formData);

        // Make the AJAX request
        $.ajax({
            url: "{{ route('admin.products.store') }}",
            type: 'POST',
            data: formData,
            contentType: false, // Important: this tells jQuery not to process the data
            processData: false, // Important: this tells jQuery not to convert the data to a string
            success: function(response) {
                console.log('AJAX Success Response:', response);

                if (response.row) {
                    $('#table_products').append(response.row); // Append new product row
                    showAlert('Product added successfully!', 'success');
                    clearForm();
                } else {
                    showAlert('Failed to add product. No row data returned.', 'danger');
                }
            },
            error: function(xhr) {
                console.log('AJAX Error Response:', xhr);
                let errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to add product.';
                showAlert(errorMessage, 'danger');
            }
        });
    });

    function clearForm() {
        $("#txtTitle").val('');
        $("#txtDescription").val('');
        $("#fileImage").val(''); // Clear the file input
        $("#txtPrice").val('');
        $("#selectCategory").val('');
        $("#txtQuantity").val('');
    }

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

        function editProduct(id) {
    const row = $("#product_" + id);
    const title = row.find(".product-title").text().trim();
    const description = row.find(".product-description").text().trim();
    const price = row.find(".product-price").text().trim();
    const category = row.find(".product-category").data("category-id");
    const quantity = row.find(".product-quantity").text().trim();
    const imagePath = row.find(".product-image img").attr('src'); // Get current image source

    // Fetch category options
    let categoryOptions = '';
    @foreach ($categories as $category)
        categoryOptions +=
            `<option value="{{ $category->id }}" ${category == {{ $category->id }} ? 'selected' : ''}>{{ $category->category_name }}</option>`;
    @endforeach

    // Prepare HTML for editing
    row.find(".product-title").html('<input type="text" class="form-control" value="' + title + '" />');
    row.find(".product-description").html('<input type="text" class="form-control" value="' + description + '" />');
    row.find(".product-price").html('<input type="number" class="form-control" value="' + price + '" />');
    row.find(".product-category").html(`
        <select class="form-control" id="selectCategory_${id}">${categoryOptions}</select>
    `);
    row.find(`#selectCategory_${id}`).val(category); // Set selected category
    row.find(".product-quantity").html('<input type="number" class="form-control" value="' + quantity + '" />');
    
    // Show file input for image editing
    row.find(".product-image").html(`
        <input type="file" class="form-control file-input" id="fileImage_${id}" name="image" accept="image/*">
        <img id="imagePreview_${id}" src="${imagePath}" alt="${title}" width="50" style="display: block;">
    `);

    // Add event listener to file input for image preview update
    row.find("#fileImage_" + id).on('change', function(event) {
        previewImage(event, id);
    });

    // Change action buttons to save/cancel
    row.find(".action-buttons").html(`
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary btn-action" onclick="updateProduct(${id})">Save</button>
            <button type="button" class="btn btn-secondary btn-action" onclick="cancelEdit(${id})">Cancel</button>
        </div>
    `);
}




function updateProduct(id) {
    const row = $("#product_" + id);
    const formData = new FormData();
    formData.append('title', row.find(".product-title input").val());
    formData.append('description', row.find(".product-description input").val());
    formData.append('price', row.find(".product-price input").val());
    formData.append('category', row.find(`#selectCategory_${id}`).val());
    formData.append('quantity', row.find(".product-quantity input").val());
    formData.append('_token', '{{ csrf_token() }}');

    // Handle image update
    const imageFile = row.find("#fileImage_" + id)[0].files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }

    $.ajax({
        url: "{{ route('admin.products.update', '') }}/" + id,
        type: 'POST', // Use POST method for file uploads
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            // Update displayed product details
            row.find(".product-title").text(formData.get('title'));
            row.find(".product-description").text(formData.get('description'));
            row.find(".product-price").text(formData.get('price'));
            row.find(".product-category").text(row.find(`#selectCategory_${id} option:selected`).text());
            row.find(".product-quantity").text(formData.get('quantity'));

            // Restore action buttons to edit/delete buttons
            row.find(".action-buttons").html(`
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-warning btn-action" onclick="editProduct(${id})">Edit</button>
                    <button type="button" class="btn btn-danger btn-action" onclick="deleteProduct(${id})">Delete</button>
                </div>
            `);

            showAlert('Product updated successfully!', 'success');
        },
        error: function(xhr) {
            showAlert('Failed to update product.', 'danger');
        }
    });
}
function previewImage(event, id) {
    const fileInput = event.target;
    const file = fileInput.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const imagePreview = $("#imagePreview_" + id);
        imagePreview.attr('src', e.target.result);
    }

    reader.readAsDataURL(file);
}


        function cancelEdit(id) {
            const row = $("#product_" + id);
            row.find(".product-title").html(row.find(".product-title input").val());
            row.find(".product-description").html(row.find(".product-description input").val());
            row.find(".product-price").html(row.find(".product-price input").val());
            row.find(".product-category").html(row.find(".product-category select option:selected").text());
            row.find(".product-quantity").html(row.find(".product-quantity input").val());
            row.find(".action-buttons").html(`
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-warning btn-action" onclick="editProduct(${id})">Edit</button>
                    <button type="button" class="btn btn-danger btn-action" onclick="deleteProduct(${id})">Delete</button>
                </div>
            `);
        }

        function deleteProduct(id) {
            // Use SweetAlert for confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this product!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed deletion, proceed with AJAX request
                    $.ajax({
                        url: "{{ route('admin.products.destroy', '') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $("#product_" + id).remove();
                            showAlert('Product deleted successfully!', 'success');
                        },
                        error: function(xhr) {
                            showAlert('Failed to delete product.', 'danger');
                        }
                    });
                }
            });
        }
    </script>
@endsection
