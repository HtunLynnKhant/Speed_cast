@extends ('admin.layout.master')

@section('title')
Admin List
@endsection

@section('content')

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.user.list' : 'superadmin.user.list') }}">Admin List</a></li>
        <li class="breadcrumb-item active" aria-current="page"></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <!-- Right side: Filter Button -->
                        
                        <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.user.add-new' : 'superadmin.user.add-new') }}" type="button" class="btn btn-sm btn-primary page-header-button">
                            <div class="button-icon-wrapper">
                                <i data-feather="plus" class="icon-sm"></i>
                            </div>
                            Add New Admin-User
                        </a>
                        <!-- Left side: Icon and Header -->
                        <div class="d-flex flex-column align-items-start">
                            <form class="d-flex" id="search-form" method="GET" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.user.list' : 'superadmin.user.list') }}">
                                <input type="text" name="name" id="search-admin" class="form-control form-control-sm" value="{{ request('name') }}" placeholder="Search Admin..." aria-label="Search" />
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                            </form>
                            <small id="search-message" class="text-danger mt-1" style="display: none;">Please enter a Admin name to search.</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-custom table-sm text-center">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 10%;">Name</th>
                                    <th scope="col" style="width: 10%;">Mail</th>
                                    <th scope="col" style="width: 10%;">Role</th>
                                    <th scope="col" style="width: 10%;">Status</th>
                                    <th scope="col" style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="admin-list">
                                @include('admin.user.partials.table_rows', ['users' => $users])
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2" id="pagination">
                        {{ $users->links() }}
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
        const searchInput = document.getElementById("search-admin");
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