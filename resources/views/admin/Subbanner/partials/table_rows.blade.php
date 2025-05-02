@forelse($subbanners as $subbanner)
<tr>
    <th scope="row">{{ ($subbanners->currentPage() - 1) * $subbanners->perPage() + $loop->iteration }}</th>
    <td>{{ $subbanner->title }}</td>
    <td>
        @if ($subbanner->image_path)
            <a href="#" class="btn btn-sm btn-primary simple-modal" data-bs-toggle="modal" data-bs-target="#modal-{{ $subbanner->id }}">
                <i data-feather="eye" class="icon-sm me-2"></i>View Image
            </a>
            <div class="modal fade simple-modal" role="dialog" tabindex="-1" id="modal-{{ $subbanner->id }}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <button class="btn-close btn-close-primary" data-bs-dismiss="modal"></button>
                        <div class="modal-body">
                            <img class="img-fluid" id="image" src="{{ config('services.s3host') . '/' . $subbanner->image_path }}" alt="Subbanner Image" />
                        </div>
                    </div>
                </div>
            </div>
        @else
            -
        @endif
    </td>

    <td>{{ $subbanner->video_link ?? "-"}}</td>
    <td>{{ $subbanner->description ?? "-" }}</td>
    <td class="text-capitalize"><span class="status-circle {{ $subbanner->status == 1 ? 'status-active' : 'status-inactive' }}"></span>{{ $subbanner->status == 1 ? 'Active' : 'Inactive' }}</td>
    <td>
        <div class="btn-group">
            <a class="btn-icon-edit" href="{{ route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.subbanner.edit' : 
                (auth()->user()->hasRole('admin') ? 'admin.subbanner.edit' : 'client.subbanner.edit'), 
                ['id' => $subbanner->id]
            ) }}" title="Edit">
                <i data-feather="edit"></i>
            </a>
            <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $subbanner->id }}" class="btn-icon-delete">
                <i data-feather="trash"></i>
            </button>
            <div class="modal fade" id="deleteModal-{{ $subbanner->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $subbanner->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel-{{ $subbanner->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this banner?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $subbanner->id }}').submit();" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Form (hidden, triggered by modal) -->
            <form action="{{ route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.subbanner.delete' : 
                (auth()->user()->hasRole('admin') ? 'admin.subbanner.delete' : 'client.subbanner.delete'), 
                ['id' => $subbanner->id]
            ) }}" method="POST" id="delete-form-{{ $subbanner->id }}" class="d-none">
                @csrf 
                @method('DELETE')
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center">No subbanner found.</td>
</tr>
@endforelse

<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>
