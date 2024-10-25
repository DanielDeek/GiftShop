<tr id="category_{{ $c->id }}">
    <td>
        <input type="text" class="form-control" id="txtName_{{ $c->id }}" name="txtName_{{ $c->id }}" value="{{ $c->category_name }}">
    </td>
    <td>
        <button type="button" class="btn btn-primary btn-action" onclick="updateCategory({{ $c->id }})">Update</button>
        <button type="button" class="btn btn-danger btn-action" onclick="deleteCategory({{ $c->id }})">Delete</button>
    </td>
</tr>