@extends ('admin.layout.master')
@section('title')
Edit Ads
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : (auth()->user()->hasRole('superadmin') ? 'superadmin.ads.list' : 'client.ads.list')) }}">ADS</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit ADS</li>
        </ol>
</nav>

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.view.payment' : 'superadmin.ads.view.payment', $ads->id) }}" type="button" class="btn btn-sm btn-primary">
                    View payment
                    </a>
                    <!-- Left side: Icon and Header -->
                    <div class="d-flex align-items-center">
                    </div>
                </div>
            <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.update' : 'superadmin.ads.update', $ads->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="row">
                            <!-- title -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="totalprice" class="form-label">Title</label>
                                    <small style="color:red">*</small>
                                    <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter ads title" value="{{ old('title',$ads->title) }}">
                                    
                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Project Dropdown -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="projectId" class="form-label">Project</label>
                                    <small style="color:red">*</small>
                                    <select name="project_id" id="projectId" class="form-control @error('project_id') is-invalid @enderror">
                                        <option value="" disabled {{ old('project_id', $ads->project_id) ? '' : 'selected' }}>Select a project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ $project->id == old('project_id', $ads->project_id) ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Content Type Dropdown -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contentType" class="form-label">Content Type</label>
                                    <small style="color:red">*</small>
                                    <select name="content_type_id" id="contentType" class="form-control @error('content_type_id') is-invalid @enderror">
                                        <option value="" disabled>Select a Content Type</option>
                                        <option value="{{ App\Enums\ContentTypes::IMAGE->value }}" {{ $ads->content_type_id == old('content_type_id', App\Enums\ContentTypes::IMAGE->value) ? 'selected' : '' }}>Image</option>
                                        <option value="{{ App\Enums\ContentTypes::VIDEO->value }}" {{ $ads->content_type_id == old('content_type_id', App\Enums\ContentTypes::VIDEO->value) ? 'selected' : '' }}>Video</option>
                                    </select>
                                    @error('content_type_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Content Path -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formFile1">Content Path</label>
                                    <small style="color:red">*</small>
                                    <div class="input_file d-flex align-items-center">
                                        <label for="formFile1" class="custom-file-label-t2">Browse File</label>
                                        <input class="custom-file-input @error('content_path') is-invalid @enderror" name="content_path" type="file" id="formFile1" 
                                            onchange="updateFileName()">
                                        <span id="file-chosen1" class="file-chosen">No file chosen</span>
                                    </div>
                                    <!-- Display error message for server-side validation -->
                                    @error('content_path')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <!-- Client-side error message display -->
                                    <div id="file-error-message" class="text-danger mt-1" style="display: none;"></div>
                                    <div class="mt-2">
                                        <img id="image-preview1" style="width: 200px; height: 100px;" 
                                            alt="Active Icon preview" 
                                            src="{{ $ads->content_path ? config('services.s3host') . '/' . $ads->content_path : 'https://placehold.jp/400x200.png' }}">
                                            <video id="video-preview1" style="width: 200px; height: 100px; display: none;" controls>
                                            <source src="{{ $ads->content_path ? config('services.s3host') . '/' . $ads->content_path : '' }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                            </div>
                            <!-- Ads Type Dropdown -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="adsType" class="form-label">Ads Type</label>
                                    <small style="color:red">*</small>
                                    <select name="ads_type_id" id="adsType" class="form-control @error('ads_type_id') is-invalid @enderror">
                                        <option value="" disabled>Select an Ads Type</option>
                                        @foreach($adsTypes as $adsType)
                                            <option value="{{ $adsType->id }}" {{ $adsType->id == old('ads_type_id', $ads->ads_type_id) ? 'selected' : '' }}>
                                                {{ $adsType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ads_type_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Total Price Input -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="totalprice" class="form-label">Total Price</label>
                                    <small style="color:red">*</small>
                                    <input type="text" class="form-control form-control-lg @error('totalprice') is-invalid @enderror" id="totalprice" name="totalprice" placeholder="Enter Total Price" value="{{ old('totalprice', $ads->total_price) }}">
                                    @error('totalprice')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Discount Price Input -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount" class="form-label">Discount Price</label>
                                    <small style="color:red">*</small>
                                    <input type="text" class="form-control form-control-lg @error('discount') is-invalid @enderror" id="discount" name="discount" placeholder="Enter Discount Price" value="{{ old('discount', $ads->discount) }}">
                                    @error('discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Active Dates Input -->
                            <div class="col-md-4">
                                <div class="mb-3 row">
                                    <div class="col-6">
                                        <label for="activeFrom" class="form-label">Active From</label>
                                        <small style="color:red">*</small>
                                        <input type="date" name="active_from" class="form-control @error('active_from') is-invalid @enderror" id="activeFrom" value="{{ old('active_from', optional($ads->subscription)->active_from ? $ads->subscription->active_from->format('Y-m-d') : '') }}"">
                                        @error('active_from')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="endOn" class="form-label">End On</label>
                                        <small style="color:red">*</small>
                                        <input type="date" name="end_on" class="form-control @error('end_on') is-invalid @enderror" id="endOn" value="{{ old('active_from', optional($ads->subscription)->active_from ? $ads->subscription->end_on->format('Y-m-d') : '') }}"">
                                        @error('end_on')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- payment_status -->
                             <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contentType" class="form-label">Payment Status</label>
                                    <small style="color:red">*</small>
                                    <select name="payment_status" id="payment_status" class="form-control @error('payment_status') is-invalid @enderror">
                                        <option value="" disabled>Select a Content Type</option>
                                        <option value="{{ App\Enums\PaymentStatus::PENDING->value }}" {{ $ads->payment_status == old('payment_status', App\Enums\PaymentStatus::PENDING->value) ? 'selected' : '' }}>PENDING</option>
                                        <option value="{{ App\Enums\PaymentStatus::IN_PROCESS->value }}" {{ $ads->payment_status == old('payment_status', App\Enums\PaymentStatus::IN_PROCESS->value) ? 'selected' : '' }}>IN_PROCESS</option>
                                        <option value="{{ App\Enums\PaymentStatus::COMPLETED->value }}" {{ $ads->payment_status == old('payment_status', App\Enums\PaymentStatus::COMPLETED->value) ? 'selected' : '' }}>COMPLETED</option>
                                    </select>
                                    @error('payment_status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                             </div>
                             <!-- Status Radio Buttons -->
                             <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <small style="color:red">*</small>
                                    <div class="form-check form-check-sm">
                                        <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status" value="{{ \App\Enums\RecordStatus::ACTIVE->value }}" {{ $ads->status === \App\Enums\RecordStatus::ACTIVE->value ? 'checked' : '' }}>
                                        <label class="form-check-label">Active</label>
                                    </div>
                                    <div class="form-check form-check-sm">
                                        <input class="form-check-input @error('status') is-invalid @enderror" type="radio" name="status" value="{{ \App\Enums\RecordStatus::INACTIVE->value }}" {{ $ads->status === \App\Enums\RecordStatus::INACTIVE->value ? 'checked' : '' }}>
                                        <label class="form-check-label">Inactive</label>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                             </div>

                             <!-- Category Dropdown -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Select Category</label>
                                    <small style="color:red">*</small>
                                    <select name="category_id" id="category" class="form-control">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $ads->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Page Dropdown (populated dynamically) -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="page" class="form-label">Select Page</label>
                                    <small style="color:red">*</small>
                                    <select name="page_id" id="page" class="form-control @error('page_id') is-invalid @enderror">
                                        <option value="">Select a page</option>
                                        @foreach($pages as $page)
                                            <option value="{{ $page->id }}" {{ $page->id == old('page_id', $ads->page_id) ? 'selected' : '' }}>
                                                {{ $page->page_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('page_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Update ADS</button>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function updateFileName() {
    const fileInput = document.getElementById('formFile1');
    const fileChosen = document.getElementById('file-chosen1');
    fileChosen.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'No file chosen';
}

// Listen for content type selection changes
document.getElementById('contentType').addEventListener('change', function() {
    document.getElementById('formFile1').value = ''; // Clear the input
    document.getElementById('image-preview1').style.display = 'none'; // Hide image preview
    document.getElementById('video-preview1').style.display = 'none'; // Hide video preview
    document.getElementById('file-chosen1').textContent = 'No file chosen'; // Reset file chosen text
    document.getElementById('file-error-message').textContent = ''; // Clear previous error message
    document.getElementById('file-error-message').style.display = 'none'; // Hide error message
});

// Listen for file selection changes
document.getElementById('formFile1').addEventListener('change', function() {
    const fileInput = this;
    const file = fileInput.files[0];
    const contentType = document.getElementById('contentType').value;
    let validFile = false;

    // Clear previous error message
    document.getElementById('file-error-message').textContent = ''; 
    document.getElementById('file-error-message').style.display = 'none';

    if (file) {
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (contentType == '1') { // Image
            validFile = ['jpeg', 'jpg', 'png'].includes(fileExtension);
            if (validFile) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview1').src = e.target.result;
                    document.getElementById('image-preview1').style.display = 'block';
                    document.getElementById('video-preview1').style.display = 'none'; // Hide video preview
                };
                reader.readAsDataURL(file);
                document.getElementById('file-chosen1').textContent = file.name;
            } else {
                displayFileError('Please upload an image file (JPEG, PNG).');
            }
        } else if (contentType == '2') { // Video
            validFile = ['mp4', 'avi'].includes(fileExtension);
            if (validFile) {
                const videoPreview = document.getElementById('video-preview1');
                videoPreview.src = URL.createObjectURL(file);
                videoPreview.style.display = 'block';
                document.getElementById('image-preview1').style.display = 'none'; // Hide image preview
                document.getElementById('file-chosen1').textContent = file.name;
            } else {
                displayFileError('Please upload a video file (MP4, AVI).');
            }
        }
    }
});

function displayFileError(message) {
    const fileInput = document.getElementById('formFile1');
    document.getElementById('file-error-message').textContent = message;
    document.getElementById('file-error-message').style.display = 'block';
    fileInput.value = '';
    document.getElementById('file-chosen1').textContent = 'No file chosen';
    document.getElementById('image-preview1').style.display = 'none';
    document.getElementById('video-preview1').style.display = 'none';
}

$(document).ready(function () {
    // Handle category change
    $('#category').on('change', function () {
        const categoryId = $(this).val(); // Get selected category ID
        const baseUrl = "{{ auth()->user()->hasRole('admin') ? '/admin/get-pages/' : '/superadmin/get-pages/' }}";

        // Clear and reset the current page dropdown
        $('#page').empty().append('<option value="">Select a page</option>');

        if (categoryId) {
            // Make an AJAX request to fetch pages related to the selected category
            $.ajax({
                url: baseUrl + categoryId,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Check if the response contains valid pages
                    const pages = response.original || []; 
                    const oldPageId = "{{ old('page_id', $ads->page_id) }}";

                    if (pages.length > 0) {
                        // Populate the "Select Page" dropdown
                        pages.forEach(page => {
                            const isSelected = page.page_no == oldPageId ? 'selected' : '';
                            $('#page').append(`<option value="${page.page_no}" ${isSelected}>${page.page_no}</option>`);
                        });
                    } else {
                        // No pages available for the selected category
                        $('#page').append('<option value="">No pages available</option>');
                    }
                },
                error: function () {
                    // Handle error during the AJAX request
                    $('#page').empty().append('<option value="">Error loading pages</option>');
                }
            });
        }
    });

    // On page load, pre-select pages if editing an ad
    const selectedCategory = $('#category').val();
    if (selectedCategory) {
        $('#category').trigger('change'); // Trigger category change to load related pages
    }
});
</script>
@endsection