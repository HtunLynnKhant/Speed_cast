@forelse($clients as $client)
<tr>
    <th scope="row">{{ ($clients->currentPage() - 1) * $clients->perPage() + $loop->iteration }}</th>
    <td>{{ $client->user_name }}</td>
    <td>{{ $client->email}}</td>
    <td>{{ $client->company_name }}</td>
    <td>{{ $client->registration_number }}</td>
    <td>
        @php
            $adsSettings = is_string($client->allow_type_of_ads_set) ? json_decode($client->allow_type_of_ads_set) : $client->allow_type_of_ads_set;
        @endphp

        @if (is_array($adsSettings) && count($adsSettings) > 0)
            <div class="d-flex flex-wrap gap-1">
                @foreach ($adsSettings as $adSetting)
                    @if ($adSetting == 'lead_generation')
                        <span class="badge bg-primary">Lead Generation</span>
                    @elseif ($adSetting == 'main_banner_submission')
                        <span class="badge bg-primary">Main Banner</span>
                    @elseif ($adSetting == 'sub_banner_submission')
                        <span class="badge bg-primary">Sub Banner</span>
                    @elseif ($adSetting == 'ads_popup')
                        <span class="badge bg-primary">Ads Pop Up</span>
                    @else
                        <span class="badge bg-danger">Not Allow</span>
                    @endif
                @endforeach
            </div>
        @else
            <span class="badge bg-secondary">No Set</span>
        @endif
    </td>
    <td class="text-capitalize">
        <span class="status-circle {{ $client->is_active == 1 ? 'status-active' : 'status-inactive' }}"></span>
        {{ $client->is_active == 1 ? 'Active' : 'Inactive' }}
    </td>
    <td>
        <div class="btn-group">
            <!-- Edit Button -->
            <a class="btn-icon-edit" href="{{ route(auth()->user()->hasRole('admin') ? 'admin.clients.edit' : 'superadmin.clients.edit', ['id' => $client->id]) }}" title="Edit">
                <i data-feather="edit"></i>
            </a>

            <!-- Delete Button -->
            <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.clients.delete' : 'superadmin.clients.delete', $client->id) }}" class="d-inline" method="POST" id="delete-form-{{ $client->id }}">
                @csrf
                @method('DELETE')
                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $client->id }}" class="btn-icon-delete">
                    <i data-feather="trash"></i>
                </button>
            </form>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal-{{ $client->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $client->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel-{{ $client->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this client?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $client->id }}').submit();" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="tedt-center">No clients found.</td>
</tr>
@endforelse

<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>