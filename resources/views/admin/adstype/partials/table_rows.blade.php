@forelse($adstypes as $adstype)
<tr>
    <th scope="row">{{ ($adstypes->currentPage() - 1) * $adstypes->perPage() + $loop->iteration }}</th>
    <td>{{ $adstype->name}}</td>
    <td>{{ empty($adstype->description) ? "-" : $adstype->description }}</td>
    <td class="text-capitalize"><span class="status-circle {{ $adstype->status == 1 ? 'status-active' : 'status-inactive' }}"></span>{{ $adstype->status == 1 ? 'Active' : 'Inactive' }}</td>
    <td>{{ $adstype->created_at->format('m-d-Y') }}</td>
    <td>{{ $adstype->updated_at->format('m-d-Y') }}</td>
</tr>

@empty
<tr>
    <td colspan="6" class="text-center">No adstype found.</td>
</tr>
@endforelse
<!-- bootstrap js -->
<script src="{{ mix('js/dashboard.js') }}"></script>
