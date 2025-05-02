@extends ('admin.layout.master')

@section('title', 'ADS List')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : (auth()->user()->hasRole('superadmin') ? 'superadmin.ads.list' : 'client.ads.list')) }}">ADS</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                   
                    <a href="{{ route(
                        auth()->user()->hasRole('admin') 
                            ? 'admin.ads.add-new' 
                            : (auth()->user()->hasRole('client') 
                                ? 'client.ads.add-new' 
                                : 'superadmin.ads.add-new')
                    ) }}" 
                    type="button" class="btn btn-sm btn-primary">
                        <i data-feather="plus" class="icon-sm"></i> Add New ADS
                    </a>
                    
                        <div class="ms-auto">
                            <form class="d-flex" id="search-form" method="GET" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : 'superadmin.ads.list') }}">
                                <input type="text" name="title" id="search-ads" class="form-control form-control-sm" value="{{ request('title') }}" placeholder="Search ADS..." aria-label="Search" />
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                            </form>
                            <small id="search-message" class="text-danger mt-1" style="display: none;">Please enter a ads name to search.</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 10%;">Title</th>
                                    <th scope="col" style="width: 10%;">Project</th>
                                    <th scope="col" style="width: 10%;">Ads Type</th>
                                    <th scope="col" style="width: 10%;">Media</th>
                                    <th scope="col" style="width: 10%;">Page No</th>
                                    <th scope="col" style="width: 10%;">Category</th>
                                    <th scope="col" style="width: 10%;">Total Price</th>
                                    <th scope="col" style="width: 10%;">Action From</th>
                                    <th scope="col" style="width: 10%;">End ON</th>
                                    <th scope="col" style="width: 10%;">Status</th>
                                    <th scope="col" style="width: 20%;">Is Approved</th>
                                    <th scope="col" style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="ads-list">
                                @include('admin.Ads.partials.table_rows', ['adss' => $adss])
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2" id="pagination">
                        {{ $adss->links() }}
                    </div>
                    <div id="no-results-message" style="display: none;">No results found.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search-ads");
        const form = searchInput.closest("form");
        const errorMessage = document.getElementById("search-message");

        // Listen for the search button click
        form.addEventListener("submit", function (event) {
            if (searchInput.value.trim() === "") {
                event.preventDefault();
                errorMessage.style.display = "block";
            } else {
                errorMessage.style.display = "none";
            }
        });

        // Listen for input changes in the search field
        searchInput.addEventListener("input", function () {
            if (searchInput.value.trim() === "") {
                form.submit();
            }
            errorMessage.style.display = "none";
        });
    });
</script>
@endsection