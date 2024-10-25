@extends('admin.index')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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

        .alert {
            margin-top: 20px;
        }

        .preview-image {
            max-width: 100px;
            margin-top: 10px;
        }
    </style>

    <div class="container-fluid">
        <h3 class="mb-4 text-center">Search Results for "{{ $search }}"</h3>
        @if ($products->isEmpty() && $categories->isEmpty())
            <div class="alert alert-warning text-center" role="alert">
                No products found for "{{ $search }}".
            </div>
        @else
            <table class="table_deg table-bordered table-striped">
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
                        <tr id="product_{{ $product->id }}">
                            <td class="product-title">{{ $product->title }}</td>
                            <td class="product-description">{{ $product->description }}</td>
                            <td class="product-image">
                                @if ($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->title }}"
                                        width="50" id="product_image_{{ $product->id }}">
                                @endif
                            </td>
                            <td class="product-price">{{ $product->price }}</td>
                            <td class="product-category" data-category-id="{{ $product->category }}">
                                {{ $categories->find($product->category)->category_name ?? 'N/A' }}
                            </td>
                            <td class="product-quantity">{{ $product->quantity }}</td>
                            <td class="action-buttons text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-warning btn-action btn-edit"
                                        data-id="{{ $product->id }}">Edit</button>
                                    <button type="button" class="btn btn-danger btn-action btn-delete"
                                        data-id="{{ $product->id }}">Delete</button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Edit Form (hidden by default) -->
                        <tr id="edit_form_{{ $product->id }}" style="display: none;">
                            <td><input type="text" class="form-control" name="title" value="{{ $product->title }}">
                            </td>
                            <td>
                                <textarea class="form-control" name="description">{{ $product->description }}</textarea>
                            </td>
                            <td>
                                @if ($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->title }}"
                                        width="50" id="edit_product_image_{{ $product->id }}"><br>
                                @endif
                                <input type="file" class="form-control-file" name="image" id="edit_image_input_{{ $product->id }}">
                                <!-- Hidden field to store original image path -->
                                <input type="hidden" name="current_image" value="{{ $product->image }}">
                                <div class="preview-image" id="edit_image_preview_{{ $product->id }}"></div>
                            </td>
                            <td><input type="number" class="form-control" name="price" value="{{ $product->price }}">
                            </td>
                            <td>
                                <select class="form-control" name="category">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" class="form-control" name="quantity"
                                    value="{{ $product->quantity }}"></td>
                            <td>
                                <button type="button" class="btn btn-success btn-save">Save</button>
                                <button type="button" class="btn btn-secondary btn-cancel">Cancel</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $products->links() }} <!-- Pagination links -->
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {

            // Event delegation for edit button
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                console.log('Edit button clicked for product ID:', id);
                $("#product_" + id).hide();
                $("#edit_form_" + id).show();
            });

            // Event delegation for cancel button
            $(document).on('click', '.btn-cancel', function() {
                const id = $(this).closest('tr').attr('id').replace('edit_form_', '');
                console.log('Cancel edit for product ID:', id);
                $("#edit_form_" + id).hide();
                $("#product_" + id).show();
            });

            // Event delegation for file input change
            $(document).on('change', 'input[type=file]', function() {
                const id = $(this).attr('id').replace('edit_image_input_', '');
                const preview = $("#edit_image_preview_" + id)[0];
                const file = this.files[0];
                
                // Check if file is an image
                if (file && file.type.startsWith('image')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview Image" width="100">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = '';
                }
            });

            // Event delegation for save button
            $(document).on('click', '.btn-save', function() {
                const id = $(this).closest('tr').attr('id').replace('edit_form_', '');
                console.log('Save button clicked for product ID:', id);

                const row = $("#edit_form_" + id);

                // Prepare FormData object
                const formData = new FormData();
                formData.append('title', row.find("input[name=title]").val());
                formData.append('description', row.find("textarea[name=description]").val());
                formData.append('price', row.find("input[name=price]").val());
                formData.append('category', row.find("select[name=category]").val());
                formData.append('quantity', row.find("input[name=quantity]").val());
                formData.append('_token', "{{ csrf_token() }}");

                // Handle image update
                const imageInput = row.find("input[name=image]")[0];
                if (imageInput.files.length > 0) {
                    formData.append('image', imageInput.files[0]);
                } else {
                    // If no new image selected, send the current image path
                    formData.append('current_image', row.find("input[name=current_image]").val());
                }

                console.log('FormData for product ID:', id, formData);

                // AJAX request to update product
                $.ajax({
                    url: "{{ url('admin/products') }}/" + id,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log('Product updated successfully:', response);

                        // Update displayed product details
                        const productRow = $("#product_" + id);
                        productRow.find(".product-title").text(formData.get('title'));
                        productRow.find(".product-description").text(formData.get('description'));
                        productRow.find(".product-price").text(formData.get('price'));
                        productRow.find(".product-category").text(row.find("select[name=category] option:selected").text());
                        productRow.find(".product-quantity").text(formData.get('quantity'));

                        // Update image if changed
                        if (formData.has('image')) {
                            const imageURL = URL.createObjectURL(imageInput.files[0]);
                            $("#product_image_" + id).attr('src', imageURL);
                            $("#edit_product_image_" + id).attr('src', imageURL);
                        }

                        // Hide edit form and show product row
                        $("#edit_form_" + id).hide();
                        productRow.show();

                        showAlert('Product updated successfully!', 'success');
                    },
                    error: function(xhr) {
                        console.error('Failed to update product:', xhr);
                        showAlert('Failed to update product.', 'danger');
                    }
                });
            });

            // Function to delete a product
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
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
                        $.ajax({
                            url: "{{ url('admin/products') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                console.log('Product deleted successfully:', response);
                                $("#product_" + id).remove();
                                showAlert('Product deleted successfully!', 'success');
                            },
                            error: function(xhr) {
                                console.error('Failed to delete product:', xhr);
                                showAlert('Failed to delete product.', 'danger');
                            }
                        });
                    }
                });
            });

            // Function to show alert messages
            function showAlert(message, type) {
                const alertDiv = `<div class="alert alert-${type}" role="alert">${message}</div>`;
                $('.alert').html(alertDiv).show();
            }
        });
    </script>

@endsection
