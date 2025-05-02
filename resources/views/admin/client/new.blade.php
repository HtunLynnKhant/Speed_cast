@extends ('admin.layout.master')
@section('title')
New Client
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.clients.list' : 'superadmin.clients.list') }}">Clients</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add-Clients</li>
        </ol>
</nav>
<div class="row">
    <h3 class="page-header-title mb-4 px-4">
        <div class="icon-wrapper">
            <i data-feather="plus"></i>
        </div>
        Add New Clients
    </h3>
</div>
<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.clients.store' : 'superadmin.clients.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <!-- Client's Name, Email, and Password -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Client Name</label>
                                <small style="color:red">*</small>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" placeholder="Enter Name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Client Email</label>
                                <small style="color:red">*</small>
                                <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" placeholder="Enter Email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <small style="color:red">*</small>
                                <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" placeholder="Enter Password">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <small style="color:red">*</small>
                                <input type="password" name="password_confirmation" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Confirm Password">
                                @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <small style="color:red">*</small>
                                <input type="text" name="company_name" class="form-control form-control-lg @error('company_name') is-invalid @enderror" id="company_name" placeholder="Enter Company Name" value="{{ old('company_name') }}">
                                @error('company_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="registration_number" class="form-label">Registration Number</label>
                                <small style="color:red">*</small>
                                <input type="text" name="registration_number" class="form-control form-control-lg @error('registration_number') is-invalid @enderror" id="registration_number" placeholder="Enter Registration Number" value="{{ old('registration_number') }}">
                                @error('registration_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                            <label for="allow_type_of_ads_set" class="form-label">Allow Type Of Ads Set</label>
                            <small style="color:red">*</small>
                            <div class="form-check">
                                <input type="checkbox" name="allow_type_of_ads_set[]" id="main_banner_submission" value="1" class="form-check-input" {{ old('main_banner_submission') ? 'checked' : '' }}>
                                <label for="main_banner_submission" class="form-check-label">Main Banner Submission</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="allow_type_of_ads_set[]" id="sub_banner_submission" value="sub_banner_submission" class="form-check-input" {{ old('sub_banner_submission') ? 'checked' : '' }}>
                                <label for="sub_banner_submission" class="form-check-label">Sub Banner Submission</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="allow_type_of_ads_set[]" id="ads_popup" value="ads_popup" class="form-check-input" {{ old('ads_popup') ? 'checked' : '' }}>
                                <label for="ads_popup" class="form-check-label">Ads Pop Up</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="allow_type_of_ads_set[]" id="lead_generation" value="lead_generation" class="form-check-input" {{ old('lead_generation') ? 'checked' : '' }}>
                                <label for="lead_generation" class="form-check-label">Lead Generation</label>
                            </div>
                            </div>
                            @error('allow_type_of_ads_set')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Add Client</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection