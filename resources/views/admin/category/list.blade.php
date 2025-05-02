@extends('admin.layout.master')

@section('title')
Category List
@endsection

@section('content')

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.category.list' : (auth()->user()->hasRole('admin') ? 'admin.category.list' : 'client.category.list')) }}">Category</a></li>
        <li class="breadcrumb-item active" aria-current="page">Category List</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.category.add-new' : (auth()->user()->hasRole('admin') ? 'admin.category.add-new' : 'client.category.add-new')) }}" class="btn btn-sm btn-primary page-header-button">
                            <div class="button-icon-wrapper">
                                <i data-feather="plus" class="icon-sm"></i>
                            </div>
                            Add New Category
                        </a>

                        <div class="d-flex flex-column align-items-start">
                            <form class="d-flex" method="GET" action="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.category.list' : (auth()->user()->hasRole('admin') ? 'admin.category.list' : 'client.category.list')) }}">
                                <input type="text" name="name" id="search-category" class="form-control form-control-sm" placeholder="Search categories..." aria-label="Search" value="{{ request('name') }}"/>
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                            </form>
                            <small id="search-message" class="text-danger mt-1" style="display: none;">Please enter a category name to search.</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-custom table-sm text-center">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 10%;">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc']) }}" class="sort-link">
                                            Category Name
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
                                    <th scope="col" style="width: 15%;">Active Icon</th>
                                    <th scope="col" style="width: 15%;">Default Icon</th>
                                    <th scope="col" style="width: 10%;">Status</th>
                                    <th scope="col" style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="category-list">
                                @include('admin.category.partials.table_rows', ['categories' => $categories])
                                <div id="no-results-message" style="display: none;" class="alert alert-warning">
                                    No categories found matching your search criteria.
                                </div>
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination" class="mt-2">
                        {{ $categories->links() }}
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
        const searchInput = document.getElementById("search-category");
        const form = searchInput.closest("form");
        const errorMessage = document.getElementById("search-message");
        // Listen for the search button click
        form.addEventListener("submit", function (event) {
            // If the input is empty, you can prevent the form submission or handle it as needed
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