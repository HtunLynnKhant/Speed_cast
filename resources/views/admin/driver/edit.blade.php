@extends ('admin.layout.master')
@section('title')
New Banner
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.drivers.list' : 'superadmin.drivers.list') }}">Drivers</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add-Drivers</li>
        </ol>
</nav>
<div class="row">
    <h3 class="page-header-title mb-4 px-4">
        <div class="icon-wrapper">
            <i data-feather="plus"></i>
        </div>
        Edit Drivers
    </h3>
</div>
<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.drivers.update' : 'superadmin.drivers.update', $drivers->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    
                    <div class="row mb-3">
                        <!-- First Column with 2 Rows -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Driver's Name</label>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" value="{{ old('name', $drivers->user->name) }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Driver's Email</label>
                                <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" value="{{ old('email', $drivers->user->email) }}">
                                @error('email')
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
                                <input class="form-check-input" type="radio" name="is_active" id="active_status" value="1" {{ old('is_active', $drivers->user->is_active) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active_status">Active</label>
                                </div>

                                <!-- Inactive Status -->
                                <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="is_active" id="inactive_status" value="0" {{ old('is_active', $drivers->user->is_active) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inactive_status">Inactive</label>
                                </div>

                                @error('is_active')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="driver_ic_number" class="form-label">Driver IC Number</label>
                                <small style="color:red">*</small>
                                <input type="text" name="driver_ic_number" class="form-control form-control-lg @error('driver_ic_number') is-invalid @enderror" id="driver_ic_number" placeholder="Enter IC Number" value="{{ old('driver_ic_number',$drivers->driver_ic_number) }}">
                                @error('driver_ic_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="driver_car_plate" class="form-label">Driver Car Plate</label>
                                <small style="color:red">*</small>
                                <input type="text" name="driver_car_plate" class="form-control form-control-lg @error('driver_car_plate') is-invalid @enderror" id="driver_car_plate" placeholder="Enter Car Plate" value="{{ old('driver_car_plate',$drivers->driver_car_plate) }}">
                                @error('driver_car_plate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Second Column with 1 Row containing 2 Columns -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="formFile1">Drivers License</label>
                                    <small style="color:red">* (Ratio 3:1)</small>
                                    <div class="input_file d-flex align-items-center">
                                        <label for="formFile1" class="custom-file-label-t2">Browse File</label>
                                        <input class="custom-file-input @error('drivers_license') is-invalid @enderror" name="drivers_license" type="file" id="formFile1" onchange="updateFileName('formFile1', 'file-chosen1', 'image-preview1')">
                                        <span id="file-chosen1" class="file-chosen">No file chosen</span>
                                    </div>
                                    @error('drivers_license')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-3">
                                        <img id="image-preview1" style="margin-top: 10px; width: 200px; height: 100px;" alt="Active Icon preview" src="{{ $drivers->drivers_license ? config('services.s3host') . '/' . $drivers->drivers_license : 'https://placehold.jp/400x200.png' }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="formFile2">Drivers Car License</label>
                                    <small style="color:red">* (Ratio 3:1)</small>
                                    <div class="input_file d-flex align-items-center">
                                        <label for="formFile2" class="custom-file-label-t2">Browse File</label>
                                        <input class="custom-file-input @error('drivers_car_license') is-invalid @enderror" name="drivers_car_license" type="file" id="formFile2" onchange="updateFileName('formFile2', 'file-chosen2', 'image-preview2')">
                                        <span id="file-chosen2" class="file-chosen">No file chosen</span>
                                    </div>
                                    @error('drivers_car_license')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-3">
                                        <img id="image-preview2" style="margin-top: 10px; width: 200px; height: 100px;" alt="Default Icon preview" src="{{ $drivers->drivers_car_license ? config('services.s3host') . '/' . $drivers->drivers_car_license : 'https://placehold.jp/400x200.png' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Radio Buttons -->
                        <div class="col-md-4">
                        <label class="form-label" for="formFile1">Status</label>
                        <small style="color:red">*</small>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input @error('status') is-invalid @enderror" 
                                    type="radio" 
                                    name="status" 
                                    value="{{ \App\Enums\RecordStatus::ACTIVE->value }}" 
                                    {{ old('status', $drivers->status) == \App\Enums\RecordStatus::ACTIVE->value ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input @error('status') is-invalid @enderror" 
                                    type="radio" 
                                    name="status" 
                                    value="{{ \App\Enums\RecordStatus::INACTIVE->value }}" 
                                    {{ old('status', $drivers->status) == \App\Enums\RecordStatus::INACTIVE->value ? 'checked' : '' }}>
                                <label class="form-check-label">Inactive</label>
                            </div>        
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Update Driver</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function updateFileName(fileInputId, fileChosenId, imagePreviewId) {
    const fileInput = document.getElementById(fileInputId);
    const fileChosen = document.getElementById(fileChosenId);
    const imagePreview = document.getElementById(imagePreviewId);

    // Update the file name display
    fileChosen.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'No file chosen';

    // Display the image preview
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };

        reader.readAsDataURL(file);
    } else {
        imagePreview.style.display = 'none';
        imagePreview.src = '';
    }
}
</script>
@endsection