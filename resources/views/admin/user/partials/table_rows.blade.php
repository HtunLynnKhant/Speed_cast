@forelse($users as $user)
<tr>
    <th scope="row">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</th>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->role}}</td>
    <!-- Check if the user is logged in -->
    <td class="text-capitalize">
        <span class="status-circle {{ Auth::check() ? 'status-active' : 'status-inactive' }}"></span>
        {{ Auth::check() ? 'Active' : 'Inactive' }}
    </td>
    <td>
        <div class="btn-group">
            <!-- Edit Button with Feather Icon -->
            <a class="btn-icon-edit" href="{{ route(auth()->user()->hasRole('admin') ? 'admin.user.edit' : 'superadmin.user.edit', ['user' => $user->id]) }}" title="Edit">
                <i data-feather="edit"></i>
            </a>

            @if(auth()->user()->hasRole('superadmin'))
            <!-- Delete Button with Feather Icon -->
            <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.user.delete' : 'superadmin.user.delete', ['user' => $user->id]) }}" class="d-inline" method="POST" id="delete-form-{{ $user->id }}">
                @csrf @method('DELETE')
                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $user->id }}" class="btn-icon-delete">
                    <i data-feather="trash"></i>
                </button>
            </form>

            <!-- Delete Modal for each category -->
            <div class="modal fade" id="deleteModal-{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel-{{ $user->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $user->id }}').submit();" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">No Admin found.</td>
</tr>
@endforelse

<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>
