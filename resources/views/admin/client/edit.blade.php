@extends('admin.layout.master')
@section('title', 'Edit Client')

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.clients.list' : 'superadmin.clients.list') }}">Clients</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Client</li>
    </ol>
</nav>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.clients.update' : 'superadmin.clients.update', $clients->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <!-- Client's Name, Email -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Client Name</label>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" value="{{ old('name', $clients->user->name) }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Client Email</label>
                                <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" value="{{ old('email', $clients->user->email) }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Company Name and Registration Number -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control form-control-lg @error('company_name') is-invalid @enderror" id="company_name" value="{{ old('company_name', $clients->company_name) }}">
                                @error('company_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="registration_number" class="form-label">Registration Number</label>
                                <input type="text" name="registration_number" class="form-control form-control-lg @error('registration_number') is-invalid @enderror" id="registration_number" value="{{ old('registration_number', $clients->registration_number) }}">
                                @error('registration_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <small style="color:red">* (leave blank to keep current password)</small>
                                <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <small style="color:red">*</small>
                                <!-- Active Status -->
                                <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="is_active" id="active_status" value="1" {{ old('is_active', $clients->user->is_active) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active_status">Active</label>
                                </div>

                                <!-- Inactive Status -->
                                <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="is_active" id="inactive_status" value="0" {{ old('is_active', $clients->user->is_active) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inactive_status">Inactive</label>
                                </div>

                                @error('is_active')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ads Settings -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="allow_type_of_ads_set" class="form-label">Allow Type Of Ads Set</label>
                                <small style="color:red">*</small>
                                <!-- Main Banner Submission -->
                                <div class="form-check">
                                    <input type="checkbox" name="allow_type_of_ads_set[]" id="main_banner_submission" 
                                        value="main_banner_submission" class="form-check-input" 
                                        {{ in_array('main_banner_submission', $allowedAds) ? 'checked' : '' }}>
                                    <label for="main_banner_submission" class="form-check-label">Main Banner Submission</label>
                                </div>

                                <!-- Sub Banner Submission -->
                                <div class="form-check">
                                    <input type="checkbox" name="allow_type_of_ads_set[]" id="sub_banner_submission" 
                                        value="sub_banner_submission" class="form-check-input" 
                                        {{ in_array('sub_banner_submission', $allowedAds) ? 'checked' : '' }}>
                                    <label for="sub_banner_submission" class="form-check-label">Sub Banner Submission</label>
                                </div>

                                <!-- Ads Pop Up -->
                                <div class="form-check">
                                    <input type="checkbox" name="allow_type_of_ads_set[]" id="ads_popup" 
                                        value="ads_popup" class="form-check-input" 
                                        {{ in_array('ads_popup', $allowedAds) ? 'checked' : '' }}>
                                    <label for="ads_popup" class="form-check-label">Ads Pop Up</label>
                                </div>

                                <!-- Lead Generation -->
                                <div class="form-check">
                                    <input type="checkbox" name="allow_type_of_ads_set[]" id="lead_generation" 
                                        value="lead_generation" class="form-check-input" 
                                        {{ in_array('lead_generation', $allowedAds) ? 'checked' : '' }}>
                                    <label for="lead_generation" class="form-check-label">Lead Generation</label>
                                </div>

                                @error('allow_type_of_ads_set')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Update Client</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection