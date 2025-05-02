@forelse($projects as $project)
<tr>
    <th scope="row">{{ ($projects->currentPage() - 1) * $projects->perPage() + $loop->iteration }}</th>
    <td>{{ $project->name }}</td>
    <td>{{ $project->description ?? "-" }}</td>
    <td class="text-capitalize"><span class="status-circle {{ $project->status == 1 ? 'status-active' : 'status-inactive' }}"></span>{{ $project->status == 1 ? 'Active' : 'Inactive' }}</td>
    <td>
        {{ $project->created_at->format('m-d-Y') }}
    </td>
    <td>
        {{ $project->updated_at->format('m-d-Y') }}
    </td>
    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
    <td>
        <div class="btn-group">
            <!-- Edit Button with Feather Icon -->
            <a class="btn-icon-edit" href="{{ route(auth()->user()->hasRole('admin') ? 'admin.project.edit' : 'superadmin.project.edit', ['id' => $project->id]) }}" title="Edit">
                <i data-feather="edit"></i>
            </a>

            <!-- Delete Button with Feather Icon -->
            <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.project.delete' : 'superadmin.project.delete', $project->id) }}" class="d-inline" method="POST" id="delete-form-{{ $project->id }}">
                @csrf @method('DELETE')
                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $project->id }}" class="btn-icon-delete">
                    <i data-feather="trash"></i>
                </button>
            </form>

            <!-- Delete Modal for each category -->
            <div class="modal fade" id="deleteModal-{{ $project->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $project->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel-{{ $project->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $project->id }}').submit();" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
    @endif
</tr>
@empty
<tr>
    <td colspan="6" class="text-center">No Project found.</td>
</tr>
@endforelse

<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>

