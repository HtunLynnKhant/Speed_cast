@forelse($categories as $category)
<tr>
    <th scope="row">{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</th>
    <td>{{ $category->name }}</td>
    <td>
        <a href="" class="btn btn-sm btn-primary simple-modal" data-bs-toggle="modal" data-bs-target="#{{ $category->id }}"> <i data-feather="eye" class="icon-sm me-2"></i>view Image </a>
        <div class="modal fade simple-modal" role="dialog" tabindex="-1" id="{{ $category->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button class="btn-close btn-close-primary" data-bs-dismiss="modal"></button>
                    <div class="modal-body">
                        <img class="img-fluid" id="image" src="{{ config('services.s3host') . '/' . $category->active_icon_path }}" />
                    </div>
                </div>
            </div>
        </div>
    </td>
    <td>
        <a href="" class="btn btn-sm btn-primary simple-modal" data-bs-toggle="modal" data-bs-target="#{{ $category->id }}df"> <i data-feather="eye" class="icon-sm me-2"></i>view Image </a>
        <div class="modal fade simple-modal" role="dialog" tabindex="-1" id="{{ $category->id }}df">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button class="btn-close btn-close-primary" data-bs-dismiss="modal"></button>
                    <div class="modal-body">
                        <img class="img-fluid" id="image" src="{{ config('services.s3host') . '/' . $category->default_icon_path }}" />
                    </div>
                </div>
            </div>
        </div>
    </td>
    <td class="text-capitalize"><span class="status-circle {{ $category->status === 'active' ? 'status-active' : 'status-inactive' }}"></span>{{$category->status}}</td>
    <td>
        <div class="btn-group">
            <a class="btn-icon-edit" href="{{ route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.category.edit' : 
                (auth()->user()->hasRole('admin') ? 'admin.category.edit' : 'client.category.edit'), 
                ['id' => $category->id]
            ) }}" title="Edit">
                <i data-feather="edit"></i>
            </a>

            <!-- Delete Button with Feather Icon -->
            <form action="{{ route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.category.delete' : 
                (auth()->user()->hasRole('admin') ? 'admin.category.delete' : 'client.category.delete'), 
                $category->id
            ) }}" class="d-inline" method="POST" id="delete-form-{{ $category->id }}">
                @csrf 
                @method('DELETE')
                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $category->id }}" class="btn-icon-delete">
                    <i data-feather="trash"></i>
                </button>
            </form>

            <!-- Modal for each category -->
            <div class="modal fade" id="deleteModal-{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $category->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel-{{ $category->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $category->id }}').submit();" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center">No Category found.</td>
</tr>
@endforelse

<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>
