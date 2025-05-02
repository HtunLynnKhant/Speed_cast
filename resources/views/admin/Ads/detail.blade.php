@extends ('admin.layout.master')

@section('title')
Ads Details
@endsection

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : (auth()->user()->hasRole('superadmin') ? 'superadmin.ads.list' : 'client.ads.list')) }}">ADS</a></li>
        <li class="breadcrumb-item active" aria-current="page">Details</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    

                    <div class="table-responsive">
                    <table class="table table-bordered table-custom table-sm text-center">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 3%;">#</th>
                                <th scope="col" style="width: 10%;">Project</th>
                                <th scope="col" style="width: 10%;">Total Price</th>
                                <th scope="col" style="width: 10%;">Discount Price</th>
                                <th scope="col" style="width: 10%;">Ads Type</th>
                                <th scope="col" style="width: 10%;">Active From</th>
                                <th scope="col" style="width: 10%;">End On</th>
                                <th scope="col" style="width: 10%;">Status</th>
                                <th scope="col" style="width: 10%;">Created Date</th>
                                <th scope="col" style="width: 10%;">Updated Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $ad->id }}</td>
                                <td>{{ $ad->project->name }}</td> <!-- Adjust according to your relationships -->
                                <td>{{ $ad->total_price }}</td>
                                <td>{{ $ad->discount }}</td>
                                <td>{{ $ad->adsType->name }}</td>
                                <td>{{ optional($ad->subscription)->active_from ? $ad->subscription->active_from->format('Y-m-d') : '' }}</td>
                                <td>{{ optional($ad->subscription)->end_on ? $ad->subscription->end_on->format('Y-m-d') : '' }}</td>
                                <td class="text-capitalize">
                                    <span class="status-circle {{ $ad->status == 1 ? 'status-active' : 'status-inactive' }}"></span>
                                    {{ $ad->status == 1 ? 'Active' : 'Inactive' }}
                                </td>
                                <td>{{ $ad->created_at->format('m-d-Y') }}</td>
                                <td>{{ $ad->updated_at->format('m-d-Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : (auth()->user()->hasRole('superadmin') ? 'superadmin.ads.list' : 'client.ads.list')) }}" class="btn btn-secondary">Back to Ads List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
