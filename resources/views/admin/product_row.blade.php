<tr id="product_{{ $product->id }}">
    <td class="product-title">{{ $product->title }}</td>
    <td class="product-description">{{ $product->description }}</td>
    <td class="product-image">
        <img id="imagePreview_{{ $product->id }}" src="{{ asset($product->image) }}" alt="{{ $product->title }}" width="50">
        <input type="file" class="form-control file-input d-none" id="fileImage_{{ $product->id }}" name="image" accept="image/*">
    </td>
    <td class="product-price">{{ $product->price }}</td>
    <td class="product-category" data-category-id="{{ $product->category }}">
        {{ $categories->find($product->category)->category_name ?? 'N/A' }}
    </td>
    <td class="product-quantity">{{ $product->quantity }}</td>
    <td class="action-buttons text-center">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-warning btn-action" onclick="editProduct({{ $product->id }})">Edit</button>
            <button type="button" class="btn btn-danger btn-action" onclick="deleteProduct({{ $product->id }})">Delete</button>
        </div>
    </td>
</tr>
