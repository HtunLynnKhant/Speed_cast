@forelse($adss as $ads)
<tr>
    <th scope="row">{{ ($adss->currentPage() - 1) * $adss->perPage() + $loop->iteration }}</th>
    <td>{{ $ads->title }}</td>
    <td>{{ $ads->project->name }}</td>
    <td>{{ $ads->adsType->name }}</td>
    <td>
        <a href="#" class="btn btn-sm btn-primary simple-modal" data-bs-toggle="modal" data-bs-target="#{{ $ads->id }}df"> <i data-feather="eye" class="icon-sm me-2"></i>View Media </a>

        <div class="modal fade simple-modal" role="dialog" tabindex="-1" id="{{ $ads->id }}df">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button class="btn-close btn-close-primary" data-bs-dismiss="modal"></button>
                    <div class="modal-body">
                        @if($ads->isImage())
                        <img class="img-fluid" src="{{ config('services.s3host') . '/' . $ads->content_path }}" alt="Image" />
                        @elseif($ads->isVideo())
                        <video class="img-fluid" controls>
                            <source src="{{ config('services.s3host') . '/' . $ads->content_path }}" type="video/mp4" />
                            Your browser does not support the video tag.
                        </video>
                        @else
                        <p>Unsupported media type</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </td>
    <td>
        {{ $ads->page_id}}
    </td>
    <td>
        {{ $ads->category ? $ads->category->name : 'No Category' }}
    </td>
    <td>{{ $ads->total_price}}</td>
    <td>{{ \Carbon\Carbon::parse($ads->sub->active_from)->format('m-d-Y') }}</td>
    <td>{{ \Carbon\Carbon::parse($ads->sub->end_on)->format('m-d-Y') }}</td>
    <td class="text-capitalize"><span class="status-circle {{ $ads->status == 1 ? 'status-active' : 'status-inactive' }}"></span>{{ $ads->status == 1 ? 'Active' : 'Inactive' }}</td>
    <td class="text-capitalize">
        @if(Auth::user()->role == 'admin')
            <form action="{{ route('admin.ads.updateapprove', $ads->id) }}" method="POST" style="display: inline-block !important; width: 120px !important;">
                @csrf
                @method('PUT')
                <div class="approval-dropdown">
                    <select name="is_approve" class="form-select is-approve-select" onchange="this.form.submit()">
                        <option value="pending" {{ $ads->Is_Approve == 'pending' ? 'selected' : '' }}>
                            🔶 Pending
                        </option>
                        <option value="approved" {{ $ads->Is_Approve == 'approved' ? 'selected' : '' }}>
                            ✅ Approved
                        </option>
                        <option value="rejected" {{ $ads->Is_Approve == 'rejected' ? 'selected' : '' }}>
                            ❌ Rejected
                        </option>
                    </select>
                </div>
            </form>
        @elseif(in_array(Auth::user()->role, ['superadmin', 'driver', 'client']))
            
                {{ $ads->Is_Approve }}
            
        @endif
    </td>
    <td>
        <div class="btn-group">
            <!-- Edit Button with Feather Icon -->
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
            <a class="btn-icon-edit" href="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.edit' : 'superadmin.ads.edit', ['id' => $ads->id]) }}" title="Edit">
                <i data-feather="edit"></i>
            </a>
            @endif

            <a class="btn-icon-eye" href="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.detail' : (auth()->user()->hasRole('client') ? 'client.ads.detail' : 'superadmin.ads.detail'), ['id' => $ads->id]) }}" title="Detail">
                <i data-feather="eye"></i>
            </a>
        </div>
    </td>
    
</tr>
@empty
<tr>
    <td colspan="8" class="text-center">No ads found.</td>
</tr>
@endforelse


<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>
