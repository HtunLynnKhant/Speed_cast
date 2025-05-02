<table class="table table-bordered table-custom table-sm text-center">
    <thead class="table-light">
        <tr>
            <th scope="col" style="width: 5%;">#</th>
            <th scope="col" style="width: 10%;">Page number</th>
            <th scope="col" style="width: 10%;">Category Name</th>
            <th scope="col" style="width: 10%;">Description</th>
            <th scope="col" style="width: 10%;">Status</th>
            <th scope="col" style="width: 10%;">Created Date</th>
            <th scope="col" style="width: 10%;">Updated Date</th>
            <th scope="col" style="width: 10%;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pages as $page)
            <tr>
                <th scope="row">{{ ($pages->currentPage() - 1) * $pages->perPage() + $loop->iteration }}</th>
                <td>{{ $page->page_no }}</td>
                <td>{{ $page->category ? $page->category->name : 'No Category' }}</td>
                <td>{{ $page->description ? $page->description : '-' }}</td>
                <td class="text-capitalize">
                    <span class="status-circle {{ $page->status == 1 ? 'status-active' : 'status-inactive' }}"></span>{{ $page->status == 1 ? 'Active' : 'Inactive' }}
                </td>
                <td>
                    {{ $page->created_at->format('m-d-Y') }}
                </td>
                <td>
                    {{ $page->updated_at->format('m-d-Y') }}
                </td>
                <td>
                    <div class="btn-group">
                        <!-- Edit Button with Feather Icon -->
                        <a class="btn-icon-edit" href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.category.page.edit' : (auth()->user()->hasRole('admin') ? 'admin.category.page.edit' : 'client.category.page.edit'), ['id' => $page->id]) }}" title="Edit">
                            <i data-feather="edit"></i>
                        </a>

                         <!-- Delete Button with Feather Icon -->
                        <form action="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.category.page.delete' : (auth()->user()->hasRole('admin') ? 'admin.category.page.delete' : 'client.category.page.delete'), $page->id) }}" class="d-inline" method="POST" id="delete-form-{{ $page->id }}">
                            @csrf 
                            @method('DELETE')
                            <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $page->id }}" class="btn-icon-delete">
                                <i data-feather="trash"></i>
                            </button>
                        </form>

                        <!-- Modal for each category -->
                        <div class="modal fade" id="deleteModal-{{ $page->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $page->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $page->id }}">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this item?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" onclick="document.getElementById('delete-form-{{ $page->id }}').submit();" class="btn btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No pages found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="mt-2">
    {{ $pages->links() }}
</div>