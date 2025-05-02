@extends ('admin.layout.master')
@section('title')
New Ads
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route(auth()->user()->hasRole('admin') ? 'admin.ads.list' : 'superadmin.ads.list')}}">ADS</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add ADS</li>
        </ol>
</nav>

<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
        <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.ads.store' : (auth()->user()->hasRole('client') ? 'client.ads.store' 
            : 'superadmin.ads.store')) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="row">
                            <!-- title -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="totalprice" class="form-label">Title</label>
                                    <small style="color:red">*</small>
                                    <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter ads title" value="{{ old('title') }}">
                                    
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
                                        <option value="" disabled {{ old('project_id') ? '' : 'selected' }}>Select a project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                            <!-- content_type -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contentType" class="form-label">Content Type</label>
                                    <small style="color:red">*</small>
                                    <select name="content_type_id" id="contentType" class="form-control @error('content_type_id') is-invalid @enderror">
                                        <option value="" disabled {{ old('content_type_id') ? '' : 'selected' }}>Select a Content Type</option>
                                        <option value="{{ App\Enums\PaymentTypes::FULL->value }}" {{ old('content_type_id') == App\Enums\ContentTypes::IMAGE->value ? 'selected' : '' }}>Image</option>
                                        <option value="{{ App\Enums\PaymentTypes::PARTIAL->value }}" {{ old('content_type_id') == App\Enums\ContentTypes::VIDEO->value ? 'selected' : '' }}>Video</option>
                                    </select>

                                    @error('content_type_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- ads_type -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="adsType" class="form-label">Ads Type</label>
                                    <small style="color:red">*</small>
                                    <select name="ads_type_id" id="adsType" class="form-control @error('ads_type_id') is-invalid @enderror">
                                        <option value="" disabled {{ old('ads_type_id') ? '' : 'selected' }}>Select an Ads Type</option>
                                        @foreach($adsTypes as $adsType)
                                            <option value="{{ $adsType->id }}" {{ old('ads_type_id') == $adsType->id ? 'selected' : '' }}>
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
                            <!-- contentpath -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formFile">Image or Video</label>
                                    <small style="color:red">*</small>
                                    <div class="input_file d-flex align-items-center">
                                        <label for="formFile" class="custom-file-label">Browse file</label>
                                        <input class="custom-file-input" name="content_path" type="file" id="formFile" onchange="updateFileName()">
                                        <span id="file-chosen" class="file-chosen">No file chosen</span>
                                    </div>
                                    <div class="mt-3">
                                        <img id="image-preview" style="margin-top: 10px; width: 200px; height: 100px; display: none;" alt="Image preview">
                                    </div>
                                    <div id="file-error-message" class="invalid-feedback" style="display: none;"></div>
                                </div>
                            </div>
                            <!-- totlal price -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="totalprice" class="form-label">Total Price</label>
                                    <small style="color:red">*</small>
                                    <input type="text" class="form-control form-control-lg @error('totalprice') is-invalid @enderror" id="totalprice" name="totalprice" placeholder="Enter Total Price" value="{{ old('totalprice') }}">
                                    
                                    @error('totalprice')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- discount -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount" class="form-label">Discount Price</label>
                                    <small style="color:red">*</small>
                                    <input 
                                        type="text" 
                                        class="form-control form-control-lg @error('discount') is-invalid @enderror" 
                                        id="discount" 
                                        name="discount" 
                                        placeholder="Enter Discount Price" 
                                        value="{{ old('discount') }}"
                                    >

                                    <!-- Error message handling -->
                                    @error('discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- date form -->
                            <div class="col-md-4">
                                <div class="mb-3 row">
                                    <!-- Active From -->
                                    <div class="col-6 mb-3">
                                        <label for="activeFrom" class="form-label">Active From</label>
                                        <small style="color:red">*</small>
                                        <input type="date" name="active_from" class="form-control @error('active_from') is-invalid @enderror" id="activeFrom" value="{{ old('active_from') }}">
                                        <!-- Inline error message -->
                                        @error('active_from')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- End On -->
                                    <div class="col-6 mb-3">
                                        <label for="endOn" class="form-label">End On</label>
                                        <small style="color:red">*</small>
                                        <input type="date" name="end_on" class="form-control @error('end_on') is-invalid @enderror" id="endOn" value="{{ old('end_on') }}">
                                        <!-- Inline error message -->
                                        @error('end_on')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Category Dropdown -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Select Category</label>
                                    <small style="color:red">*</small>
                                    <select name="category_id" id="category" class="form-control @error('category_id') is-invalid @enderror"">
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Page Dropdown (populated dynamically) -->
                                <div class="mb-3">
                                    <label for="page" class="form-label">Select Page</label>
                                    <small style="color:red">*</small>
                                    <select name="page_id" id="page" class="form-control @error('page_id') is-invalid @enderror">
                                        <option value="">Select a page</option>
                                    </select>
                                    @error('page_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Add ADS</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
@section('js')
<!-- Include jQuery for AJAX functionality -->
<script>

    function updateFileName() {
        const fileInput = document.getElementById('formFile');
        const fileChosen = document.getElementById('file-chosen');
        fileChosen.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'No file chosen';
    }
    document.getElementById('contentType').addEventListener('change', function() {
        // Clear file input and error message when content type changes
        document.getElementById('formFile').value = ''; // Clear the input
        document.getElementById('image-preview').style.display = 'none'; // Hide the image preview
        document.getElementById('file-chosen').textContent = 'No file chosen'; // Reset file chosen text
        document.getElementById('file-error-message').textContent = ''; // Clear previous error message
        document.getElementById('file-error-message').style.display = 'none'; // Hide error message
    });

    document.getElementById('formFile').addEventListener('change', function() {
        var fileInput = this;
        var file = fileInput.files[0];
        var contentType = document.getElementById('contentType').value;
        var validFile = false;

        // Clear previous error message
        document.getElementById('file-error-message').textContent = ''; 
        document.getElementById('file-error-message').style.display = 'none'; // Hide error message

        if (file) {
            // Get file extension
            var fileExtension = file.name.split('.').pop().toLowerCase();

            if (contentType == '1') { // Assuming 1 = Image
                validFile = ['jpeg', 'jpg', 'png'].includes(fileExtension);
                if (!validFile) {
                    document.getElementById('file-error-message').textContent = 'Please upload an image file (JPEG, PNG).';
                    document.getElementById('file-error-message').style.display = 'block'; // Show error message
                    fileInput.value = ''; // Clear invalid file
                    document.getElementById('image-preview').style.display = 'none'; // Hide the image preview
                    document.getElementById('file-chosen').textContent = 'No file chosen'; // Reset file chosen text
                } else {
                    // Preview the image
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('image-preview').src = e.target.result;
                        document.getElementById('image-preview').style.display = 'block'; // Show the preview
                    };
                    reader.readAsDataURL(file);
                    document.getElementById('file-chosen').textContent = file.name; // Update chosen file text
                }
            } else if (contentType == '2') { // Assuming 2 = Video
                validFile = ['mp4', 'avi'].includes(fileExtension);
                if (!validFile) {
                    document.getElementById('file-error-message').textContent = 'Please upload a video file (MP4, AVI).';
                    document.getElementById('file-error-message').style.display = 'block'; // Show error message
                    fileInput.value = ''; // Clear invalid file
                    document.getElementById('image-preview').style.display = 'none'; // Hide the image preview
                    document.getElementById('file-chosen').textContent = 'No file chosen'; // Reset file chosen text
                } else {
                    // Video preview can be handled if needed
                    document.getElementById('file-chosen').textContent = file.name; // Update chosen file text
                    // You may want to implement video preview here if required
                }
            }
        }
    });
    $(document).ready(function() {
        // Dynamically load pages based on selected category
        $('#category').on('change', function() {
            var categoryId = $(this).val();
            const baseUrl = "{{ auth()->user()->hasRole('admin') ? '/admin/get-pages/' : (auth()->user()->hasRole('client') ? '/client/get-pages/' : '/superadmin/get-pages/') }}";
            if (categoryId) {
                $.ajax({
                    url: baseUrl + categoryId,  // Correct endpoint for loading pages
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#page').empty();
                        $('#page').append('<option value="">Select a page</option>');
                        if (response.original && response.original.length > 0) {
                            $.each(response.original, function(index, value) {
                                $('#page').append('<option value="' + value.page_no + '">' + value.page_no + '</option>');
                            });
                        } else {
                            $('#page').append('<option value="">No pages available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#page').empty();
                        $('#page').append('<option value="">Error loading pages</option>');
                    }
                });
            } else {
                $('#page').empty();
                $('#page').append('<option value="">Select a page</option>');
            }
        });
    });
</script>
@endsection
