@forelse($tablets as $tablet)
<tr>
    <th scope="row">{{ ($tablets->currentPage() - 1) * $tablets->perPage() + $loop->iteration }}</th>
    <td>{{ $tablet->name }}</td>
    <td>{{ $tablet->description ?? "-" }}</td>
    <td class="text-capitalize"><span class="status-circle {{ $tablet->status == 1 ? 'status-active' : 'status-inactive' }}"></span>{{ $tablet->status == 1 ? 'Active' : 'Inactive' }}</td>
    <td>
        {{ $tablet->created_at->format('m-d-Y') }}
    </td>
    <td>
        {{ $tablet->updated_at->format('m-d-Y') }}
    </td>
    <td>
        <div class="btn-group">
            <!-- Edit Button with Feather Icon -->
            <a class="btn-icon-edit" href="{{ route(auth()->user()->hasRole('admin') ? 'admin.tablets.edit' : 'superadmin.tablets.edit', ['id' => $tablet->id]) }}" title="Edit">
                <i data-feather="edit"></i>
            </a>

            <!-- Delete Button with Feather Icon -->
            <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.tablets.delete' : 'superadmin.tablets.delete', $tablet->id) }}" class="d-inline" method="POST" id="delete-form-{{ $tablet->id }}">
                @csrf @method('DELETE')
                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $tablet->id }}" class="btn-icon-delete">
                    <i data-feather="trash"></i>
                </button>
            </form>

            <!-- Delete Modal for each category -->
            <div class="modal fade" id="deleteModal-{{ $tablet->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $tablet->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel-{{ $tablet->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $tablet->id }}').submit();" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center">No Tablets found.</td>
</tr>
@endforelse

<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>
