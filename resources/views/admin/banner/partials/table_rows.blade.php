@forelse ($banners as $banner)
<tr>
    <th scope="row">{{ ($banners->currentPage() - 1) * $banners->perPage() + $loop->iteration }}</th>
    <td>{{ $banner->title }}</td>
    <td>
        @if($banner->contents->firstWhere('type', 1))
            <a href="#" class="btn btn-sm btn-primary simple-modal" data-bs-toggle="modal" data-bs-target="#{{ $banner->id }}">
                <i data-feather="eye" class="icon-sm me-2"></i>View Image
            </a>

            <!-- Modal -->
            <div class="modal fade simple-modal" role="dialog" tabindex="-1" id="{{ $banner->id }}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <button class="btn-close btn-close-primary" data-bs-dismiss="modal"></button>
                        <div class="modal-body">
                            <img class="img-fluid" id="image" src="{{ config('services.s3host') . '/' . $banner->contents->firstWhere('type', 1)->path }}" alt="Banner Image" />
                        </div>
                    </div>
                </div>
            </div>
        @else
            -
        @endif
    </td>
    <td>{{ $banner->video_link ?? "-"}}</td>
    <td class="text-capitalize"><span class="status-circle {{ $banner->status === 'active' ? 'status-active' : 'status-inactive' }}"></span>{{ $banner->status }}</td>
    <td>
        <div class="btn-group">
            <a class="btn-icon-edit" href="{{ route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.banner.edit' : 
                (auth()->user()->hasRole('admin') ? 'admin.banner.edit' : 'client.banner.edit'), 
                ['id' => $banner->id]
            ) }}" title="Edit">
                <i data-feather="edit"></i>
            </a>
            <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $banner->id }}" class="btn-icon-delete">
                <i data-feather="trash"></i>
            </button>
            <div class="modal fade" id="deleteModal-{{ $banner->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $banner->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel-{{ $banner->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this banner?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $banner->id }}').submit();" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Form (hidden, triggered by modal) -->
            <form action="{{ route(
                auth()->user()->hasRole('superadmin') ? 'superadmin.banner.delete' : 
                (auth()->user()->hasRole('admin') ? 'admin.banner.delete' : 'client.banner.delete'), 
                ['id' => $banner->id]
            ) }}" method="POST" id="delete-form-{{ $banner->id }}" class="d-none">
                @csrf 
                @method('DELETE')
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="4">No Category found.</td>
</tr>
@endforelse

<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>
