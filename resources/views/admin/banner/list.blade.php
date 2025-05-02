@extends ('admin.layout.master')

@section('title')
Banner List
@endsection

@section('content')

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.banner.list' : (auth()->user()->hasRole('admin') ? 'admin.banner.list' : 'client.banner.list')) }}">Main Banner</a></li>
        <li class="breadcrumb-item active" aria-current="page"></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.banner.add-new' : (auth()->user()->hasRole('admin') ? 'admin.banner.add-new' : 'client.banner.add-new')) }}" type="button" class="btn btn-sm btn-primary page-header-button">
                            <div class="button-icon-wrapper">
                                <i data-feather="plus" class="icon-sm"></i>
                            </div>
                            Add New Main Banner
                        </a>
                        <div class="d-flex flex-column align-items-start">
                            <form class="d-flex" id="search-form" method="GET" action="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.banner.list' : (auth()->user()->hasRole('admin') ? 'admin.banner.list' : 'client.banner.list')) }}" onsubmit="return validateForm()">
                                <input type="text" name="title" id="search-banner" class="form-control form-control-sm" value="{{ request('title') }}" placeholder="Search Main Banner..." aria-label="Search" />
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                            </form>
                            <small id="search-message" class="text-danger mt-1" style="display: none;">Please enter a banner name to search.</small>
                        </div>
                    </div>

                    
                    <div>
                        <!-- Banner list will be updated here with AJAX -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-custom table-sm text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 5%;">#</th>
                                        @php
                                            $sortBy = request('sort_by', 'id'); // Default to 'id'
                                            $sortOrder = request('sort_order', 'asc'); // Default to 'asc';
                                        @endphp

                                        <th scope="col" style="width: 10%;">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'title', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                Title
                                                <span class="sort-icon" style="flex-direction: column; align-items: center;">
                                                    @if($sortBy === 'title')
                                                        @if($sortOrder === 'asc')
                                                            <i data-feather="arrow-down" style="color: blue;width: 10px; height: 20px;"></i>
                                                        @else
                                                            <i data-feather="arrow-up" style="color: blue;width: 10px; height: 20px;"></i>
                                                        @endif
                                                    @else
                                                        <i data-feather="sliders" style="opacity: 0.3; width: 10px; height: 20px;"></i>
                                                    @endif
                                                </span>
                                            </a>
                                        </th>
                                        <th scope="col" style="width: 15%;">Image</th>
                                        <th scope="col" style="width: 15%;">Video Link</th>
                                        <th scope="col" style="width: 10%;">Status</th>
                                        <th scope="col" style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="banner-list">
                                @if ($banners->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">No banners found.</td>
                                    </tr>
                                @else
                                    @include('admin.banner.partials.table_rows', ['banners' => $banners])
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-2" id="pagination">
                        {{ $banners->links() }}
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
        const searchInput = document.getElementById("search-banner");
        const form = searchInput.closest("form");
        const errorMessage = document.getElementById("search-message");

        // Listen for the search button click
        form.addEventListener("submit", function (event) {
            // If the input is empty, prevent form submission and show the error message
            if (searchInput.value.trim() === "") {
                event.preventDefault(); // Prevent form submission
                errorMessage.style.display = "block"; // Show the error message
            } else {
                errorMessage.style.display = "none"; // Hide the error message if input is not empty
            }
        });

        // Listen for input changes in the search field
        searchInput.addEventListener("input", function () {
            // If the input is empty, refresh the form and hide the error message
            if (searchInput.value.trim() === "") {
                form.submit();
            }
            errorMessage.style.display = "none";
        });
    });
</script>

@endsection
