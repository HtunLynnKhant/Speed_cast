@extends ('admin.layout.master')

@section('title', 'Drivers List')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.drivers.list' : 'superadmin.drivers.list') }}">Drivers</a>
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
                        <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.drivers.add-new' : 'superadmin.drivers.add-new') }}" 
                            type="button" class="btn btn-sm btn-primary">
                            <i data-feather="plus" class="icon-sm"></i> Add New Driver
                        </a>

                        <div class="d-flex flex-column align-items-start">
                            <form class="d-flex" id="search-form" method="GET" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.drivers.list' : 'superadmin.drivers.list') }}">
                                <input type="text" name="driver" id="search-drivers" class="form-control form-control-sm" value="{{ request('driver') }}" placeholder="Search Drivers..." aria-label="Search" />
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                            </form>
                            <small id="search-message" class="text-danger mt-1" style="display: none;">Please enter a driver name to search.</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th scope="col" style="width: 10%;">Driver Name</th>
                                    <th scope="col" style="width: 10%;">Mail</th>
                                    <th scope="col" style="width: 10%;">Driver IC Number</th>
                                    <th scope="col" style="width: 10%;">Car Plate</th>
                                    <th scope="col" style="width: 10%;">Driver's License</th>
                                    <th scope="col" style="width: 10%;">Car's License</th>
                                    <th scope="col" style="width: 10%;">Status</th>
                                    <th scope="col" style="width: 10%;">Created Date</th>
                                    <th scope="col" style="width: 10%;">Updated Date</th>
                                    <th scope="col" style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="drivers-list">
                                @include('admin.driver.partials.table_rows', ['drivers' => $drivers])
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2" id="pagination">
                        {{ $drivers->links() }}
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
        const searchInput = document.getElementById("search-drivers");
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
            if (searchInput.value.trim() === "") {
                form.submit();
            }
            errorMessage.style.display = "none";
        });
    });
</script>
@endsection