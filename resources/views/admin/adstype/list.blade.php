@extends ('admin.layout.master')

@section('title')
AdsType List
@endsection

@section('content')

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="">Ads Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">AdsType</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <!-- Left side: Icon and Header -->
                        <div class="d-flex align-items-end"></div>

                        <!-- Right side: Search form -->
                        <div class="ms-auto">
                            <form class="d-flex" method="GET" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.type.list' : (auth()->user()->hasRole('client') ? 'client.type.list' : 'superadmin.type.list')) }}">
                                <input type="text" name="name" id="search-adstype" class="form-control form-control-sm" placeholder="Search AdsType..." aria-label="Search" value="{{ request('name') }}" />
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                            </form>
                            <small id="search-message" class="text-danger mt-1" style="display: none;">Please enter a adstype name to search.</small>
                        </div>
                    </div>
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-custom table-sm text-center">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 10%;">Name</th>
                                    <th scope="col" style="width: 10%;">Description</th>
                                    <th scope="col" style="width: 10%;">Status</th>
                                    <th scope="col" style="width: 10%;">Created Date</th>
                                    <th scope="col" style="width: 10%;">Updated Date</th>
                                </tr>
                            </thead>
                            <tbody id="adstype-list">
                            @include('admin.adstype.partials.table_rows', ['adstypes' => $adstypes])
                                <div id="no-results-message" style="display: none;" class="alert alert-warning">
                                    No categories found matching your search criteria.
                                </div>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        {{ $adstypes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search-adstype");
        const form = searchInput.closest("form");
        const errorMessage = document.getElementById("search-message");

        // Listen for the search button click
        form.addEventListener("submit", function (event) {
            // If the input is empty, prevent form submission and show the error message
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