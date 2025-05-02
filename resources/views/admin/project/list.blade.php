@extends('admin.layout.master')

@section('title')
    Project List
@endsection

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.project.list' : (auth()->user()->hasRole('superadmin') ? 'superadmin.project.list' : 'client.project.list')) }}">Project</a>
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
                        <!-- Add New Project Button -->
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
                        <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.project.add-new' : 'superadmin.project.add-new') }}" class="btn btn-sm btn-primary page-header-button">
                            <div class="button-icon-wrapper">
                                <i data-feather="plus" class="icon-sm"></i>
                            </div>
                            Add New Project
                        </a>
                        @endif
                        <!-- Search Form -->
                        <div class="ms-auto">
                            <form class="d-flex" id="search-form" method="GET" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.project.list' : (auth()->user()->hasRole('superadmin') ? 'superadmin.project.list' : 'client.project.list')) }}">
                                <input type="text" name="name" id="search-project" class="form-control form-control-sm" placeholder="Search projects..." value="{{ request('name') }}" aria-label="Search"/>
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                            </form>
                            <small id="search-message" class="text-danger mt-1" style="display: none;">Please enter a porject name to search.</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-custom table-sm text-center">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 10%;">#</th>
                                    <th scope="col" style="width: 10%;">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => $sortBy === 'name' && $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                            Name
                                            <span class="sort-icon" style="flex-direction: column; align-items: center;">
                                                @if(request('sort_by') === 'name')
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
                                    <th scope="col" style="width: 10%;">Description</th>
                                    <th scope="col" style="width: 10%;">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => $sortBy === 'status' && $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                            Status
                                            <span class="sort-icon">
                                                @if(request('sort_by') === 'status')
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
                                    <th scope="col" style="width: 10%;">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => $sortBy === 'created_at' && $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                            Created Date
                                            <span class="sort-icon">
                                                @if(request('sort_by') === 'created_at')
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
                                    <th scope="col" style="width: 10%;">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'updated_at', 'sort_order' => $sortBy === 'updated_at' && $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                            Updated Date
                                            <span class="sort-icon">
                                                @if(request('sort_by') === 'updated_at')
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
                                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('superadmin'))
                                    <th scope="col" style="width: 10%;">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="project-list">
                                @include('admin.project.partials.table_rows', ['projects' => $projects])
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2" id="pagination">
                        {{ $projects->links() }}
                    </div>
                    <div id="no-results-message" style="display: none;" class="alert alert-warning">
                        No projects found matching your search criteria.
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
        const searchInput = document.getElementById("search-project");
        const form = searchInput.closest("form");
        const errorMessage = document.getElementById("search-message");

    // Listen for the search button click
        form.addEventListener("submit", function (event) {
            // If the input is empty, prevent form submission and show the error message
            if (searchInput.value.trim() === "") {
                event.preventDefault(); // Prevent form submission
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