@extends ('admin.layout.master')

@section('title')
Edit Banner
@endsection

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.banner.list' : (auth()->user()->hasRole('admin') ? 'admin.banner.list' : 'client.banner.list')) }}">Main Banner</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Main Banner</li>
        
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <form action="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.banner.update' : (auth()->user()->hasRole('admin') ? 'admin.banner.update' : 'client.banner.update'), ['id' => $banner->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                

                <div class="row mb-3">
                    <div class="col-lg-6 col-md-12 mb-3">
                        <label for="title" class="form-label">Banner Title</label>
                        <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter Banner Title" value="{{ old('title', $banner->title) }}">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 mb-3">
                        <label class="form-label" for="formFile">Image</label>
                        <small style="color:red">* (Ratio 3:1)</small>
                        <div class="input_file d-flex align-items-center">
                            <label for="formFile" class="custom-file-label">Browse File</label>
                            <input class="custom-file-input @error('image') is-invalid @enderror" type="file" name="image" id="formFile" onchange="updateFileName()">
                            <span id="file-chosen" class="file-chosen">No file chosen</span>
                        </div>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                        @if($banner->contents->firstWhere('type', \App\Enums\ContentTypes::IMAGE->value))
                            <img id="image-preview" style="margin-top: 10px; width: 200px; height: 100px;" alt="Image preview" src="{{ config('services.s3host') . '/' . $banner->contents->firstWhere('type', \App\Enums\ContentTypes::IMAGE->value)->path }}">
                        @else
                            <img id="image-preview" style="margin-top: 10px; width: 200px; height: 100px;" alt="Image preview" src="https://placehold.jp/100x200.png">
                        @endif
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 mb-3">
                        <label for="videoLink" class="form-label">Video Link</label>
                        <input type="url" name="video_link" class="form-control @error('video_link') is-invalid @enderror" id="videoLink" placeholder="Enter Video Link (YouTube, Vimeo, etc.)" value="{{ old('video_link', $banner->video_link) }}"">
                        @error('video_link')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 mb-3">
                        <label class="form-label">Status</label>
                        <small style="color:red">*</small>
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="radio" name="status" value="{{ \App\Enums\RecordStatus::ACTIVE->value }}" {{ $banner->status === 'active' ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" type="radio" name="status" value="{{ \App\Enums\RecordStatus::INACTIVE->value }}" {{ $banner->status === 'inactive' ? 'checked' : '' }}>
                            <label class="form-check-label">Inactive</label>
                        </div>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 mb-3">
                        <label for="bannerDescription" class="form-label">Description</label>
                        <textarea name="description" class="form-control form-control-lg @error('description') is-invalid @enderror" id="bannerDescription" placeholder="Enter Description">{{ old('description', $banner->description ?? '') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12">
                    <label for="banneralert" class="form-label" style="color: red;">* You cannot fill both image and video link. Please choose one.</label>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Update Main Banner</button>
            </form>

            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    function updateFileName() {
        const fileInput = document.getElementById('formFile');
        const fileChosen = document.getElementById('file-chosen');
        const imagePreview = document.getElementById('image-preview');
        
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            fileChosen.textContent = file.name;

            // Check if the file is an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = 'https://placehold.jp/400x200.png';
                fileChosen.textContent = 'Please select a valid image file.';
            }
        } else {
            fileChosen.textContent = 'No file chosen';
            imagePreview.src = 'https://placehold.jp/400x200.png';
        }
    }

    document.getElementById('formFile').addEventListener('change', updateFileName);

    document.getElementById('videoLink').addEventListener('input', function () {
        document.getElementById('formFile').value = ''; // Clear file input
        document.getElementById('file-chosen').textContent = 'No file chosen';
        document.getElementById('image-preview').src = 'https://placehold.jp/400x200.png';
    });
</script>
@endsection