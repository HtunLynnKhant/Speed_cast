@extends ('admin.layout.master')

@section('title')
SubBanner List
@endsection

@section('content')

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.subbanner.list' : (auth()->user()->hasRole('admin') ? 'admin.subbanner.list' : 'client.subbanner.list')) }}">Sub Banner</a></li>
        <li class="breadcrumb-item active" aria-current="page"></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.subbanner.add-new' : (auth()->user()->hasRole('admin') ? 'admin.subbanner.add-new' : 'client.subbanner.add-new')) }}" type="button" class="btn btn-sm btn-primary page-header-button">
                            <div class="button-icon-wrapper">
                                <i data-feather="plus" class="icon-sm"></i>
                            </div>
                            Add New Sub Banner
                        </a>
                        <div class="d-flex flex-column align-item-start">
                            <form class="d-flex" id="search-form" method="GET" action="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.subbanner.list' : (auth()->user()->hasRole('admin') ? 'admin.subbanner.list' : 'client.subbanner.list')) }}">
                                <input type="text" name="title" id="search-subbanner" class="form-control form-control-sm" value="{{ request('title') }}" placeholder="Search Sub Banner..." aria-label="Search" />
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                            </form>
                            <small id="search-message" class="text-danger mt-1" style="display: none;">Please enter a subbanner name to search.</small>
                        </div>
                    </div>

                    
                    <div>
                        <!-- Banner list will be updated here with AJAX -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-custom table-sm text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 5%;">#</th>
                                        <th scope="col" style="width: 10%;">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'title', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                                Title
                                                <span class="sort-icon" style="flex-direction: column; align-items: center;">
                                                    @if(request('sort_by') === 'title')
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
                                        <th scope="col" style="width: 15%;">Video link</th>
                                        <th scope="col" style="width: 15%;">description</th>
                                        <th scope="col" style="width: 10%;">Status</th>
                                        <th scope="col" style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="subbanner-list">
                                @if ($subbanners->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">No banners found.</td>
                                    </tr>
                                @else
                                    @include('admin.Subbanner.partials.table_rows', ['subbanners' => $subbanners])
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-2" id="pagination">
                        {{ $subbanners->links() }}
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
        const searchInput = document.getElementById("search-subbanner");
        const form = searchInput.closest("form");
        const errorMessage = document.getElementById("search-message")

        // Listen for the search button click
        form.addEventListener("submit", function (event) {
            // If the input is empty, you can prevent the form submission or handle it as needed
            if (searchInput.value.trim() === "") {
                // Optionally, you can show a message or handle the empty search case
                event.preventDefault();
                errorMessage.style.display = "block";
            }else{
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
