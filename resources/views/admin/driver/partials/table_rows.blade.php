@forelse($drivers as $driver)
<tr>
    <th scope="row">{{ ($drivers->currentPage() - 1) * $drivers->perPage() + $loop->iteration }}</th>
    <td>{{ $driver->user_name }}</td>
    <td>{{ $driver->email}}</td>
    <td>{{ $driver->driver_ic_number }}</td>
    <td>{{ $driver->driver_car_plate}}</td>
    <td>
        <a href="" class="btn btn-sm btn-primary simple-modal" data-bs-toggle="modal" data-bs-target="#{{ $driver->id }}"> <i data-feather="eye" class="icon-sm me-2"></i>view Image </a>
        <div class="modal fade simple-modal" id="{{ $driver->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog" role="document" style="max-width: 500px;">
                <div class="modal-content" style="width: 100%; height: 300px;">
                    <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body d-flex justify-content-center">
                        <img class="img-fluid" height="300" id="driver_image" src="{{ config('services.s3host') . '/' . $driver->drivers_license }}" />
                    </div>
                </div>
            </div>
        </div>
    </td>
    <td>
        <a href="" class="btn btn-sm btn-primary simple-modal" data-bs-toggle="modal" data-bs-target="#{{ $driver->id }}df"> <i data-feather="eye" class="icon-sm me-2"></i>view Image </a>
        <div class="modal fade simple-modal" role="dialog" tabindex="-1" id="{{ $driver->id }}df">
            <div class="modal-dialog modal-dialog" role="document" style="max-width: 500px;">
                <div class="modal-content" style="width: 100%; height: 300px;">
                    <button type="button" class="btn-close btn-close-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body d-flex justify-content-center">
                        <img class="img-fluid" height="300" id="driver_image" src="{{ config('services.s3host') . '/' . $driver->drivers_car_license }}" />
                    </div>
                </div>
            </div>
        </div>
    </td>
    <td class="text-capitalize">
        <span class="status-circle {{ $driver->is_active == 1 ? 'status-active' : 'status-inactive' }}"></span>
        {{ $driver->is_active == 1 ? 'Active' : 'Inactive' }}
    </td>
    <td>{{ \Carbon\Carbon::parse($driver->created_at)->format('m-d-Y') }}</td>
    <td>{{ \Carbon\Carbon::parse($driver->updated_at)->format('m-d-Y') }}</td>
    <td>
        <div class="btn-group">
            <!-- Edit Button with Feather Icon -->
            <a class="btn-icon-edit" href="{{ route(auth()->user()->hasRole('admin') ? 'admin.drivers.edit' : 'superadmin.drivers.edit', ['id' => $driver->id]) }}" title="Edit">
                <i data-feather="edit"></i>
            </a>

            <!-- Delete Button with Feather Icon -->
            <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.drivers.delete' : 'superadmin.drivers.delete', $driver->id) }}" class="d-inline" method="POST" id="delete-form-{{ $driver->id }}">
                @csrf @method('DELETE')
                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $driver->id }}" class="btn-icon-delete">
                    <i data-feather="trash"></i>
                </button>
            </form>

            <!-- Modal for each category -->
            <div class="modal fade" id="deleteModal-{{ $driver->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $driver->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel-{{ $driver->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $driver->id }}').submit();" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center">No driver found.</td>
</tr>
@endforelse


<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>
