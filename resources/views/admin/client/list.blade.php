@extends ('admin.layout.master')

@section('title', 'Clients List')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.clients.list' : 'superadmin.clients.list') }}">Clients</a>
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
                        <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.clients.add-new' : 'superadmin.clients.add-new') }}" 
                            type="button" class="btn btn-sm btn-primary">
                            <i data-feather="plus" class="icon-sm"></i> Add New Clients
                        </a>

                        <div class="d-flex flex-column align-items-start">
                            <form class="d-flex" method="GET" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.clients.list' : 'superadmin.clients.list') }}">
                                <input 
                                    type="text" 
                                    name="client" 
                                    class="form-control form-control-sm" 
                                    value="{{ request('client') }}" 
                                    placeholder="Search client..." 
                                    aria-label="Search" 
                                    id="search-clients" 
                                />
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
                            </form>
                            <small id="search-message" class="text-danger mt-1" style="display: none;">Please enter a client name to search.</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th scope="col" style="width: 10%;">Client Name</th>
                                    <th scope="col" style="width: 10%;">Mail</th>
                                    <th scope="col" style="width: 10%;">Company Name</th>
                                    <th scope="col" style="width: 10%;">Registration number</th>
                                    <th scope="col" style="width: 10%;">Set Permission</th>
                                    <th scope="col" style="width: 10%;">Status</th>
                                    <th scope="col" style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="clients-list">
                                @include('admin.client.partials.table_rows', ['clients' => $clients])
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2" id="pagination">
                        {{ $clients->links() }}
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
        const searchInput = document.getElementById("search-clients");
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
            }
            errorMessage.style.display = "none";
        });
    });
</script>
@endsection