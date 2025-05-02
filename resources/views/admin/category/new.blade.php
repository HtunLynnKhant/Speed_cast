@extends ('admin.layout.master')
@section('title')
New Banner
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.category.list' : (auth()->user()->hasRole('admin') ? 'admin.category.list' : 'client.category.list')) }}">Category</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add-Category</li>
        </ol>
</nav>
<div class="row">
    <h3 class="page-header-title mb-4 px-4">
        <div class="icon-wrapper">
            <i data-feather="plus"></i>
        </div>
        Add New Categories
    </h3>
</div>
<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.category.store' : (auth()->user()->hasRole('admin') ? 'admin.category.store' : 'client.category.store')) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <!-- First Column with 2 Rows -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Category Name</label>
                                <small style="color:red">*</small>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" id="categoryName" placeholder="Enter Name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="categoryDescription" class="form-label">Description</label>
                                <textarea name="description" class="form-control form-control-lg @error('description') is-invalid @enderror" id="categoryDescription" placeholder="Enter Description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Second Column with 1 Row containing 2 Columns -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="formFile1">Active Icon</label>
                                    <small style="color:red">* (Ratio 3:1)</small>
                                    <div class="input_file d-flex align-items-center">
                                        <label for="formFile1" class="custom-file-label-t2">Browse File</label>
                                        <input class="custom-file-input @error('active_icon_path') is-invalid @enderror" name="active_icon_path" type="file" id="formFile1" onchange="updateFileName('formFile1', 'file-chosen1', 'image-preview1')">
                                        <span id="file-chosen1" class="file-chosen">No file chosen</span>
                                    </div>
                                    @error('active_icon_path')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-3">
                                        <img id="image-preview1" style="margin-top: 10px; width: 200px; height: 100px;" alt="Active Icon preview" src="https://placehold.jp/200x200.png">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="formFile2">Default Icon</label>
                                    <small style="color:red">* (Ratio 3:1)</small>
                                    <div class="input_file d-flex align-items-center">
                                        <label for="formFile2" class="custom-file-label-t2">Browse File</label>
                                        <input class="custom-file-input @error('default_icon_path') is-invalid @enderror" name="default_icon_path" type="file" id="formFile2" onchange="updateFileName('formFile2', 'file-chosen2', 'image-preview2')">
                                        <span id="file-chosen2" class="file-chosen">No file chosen</span>
                                    </div>
                                    @error('default_icon_path')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-3">
                                        <img id="image-preview2" style="margin-top: 10px; width: 200px; height: 100px;" alt="Default Icon preview" src="https://placehold.jp/200x200.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Add Category</button>
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