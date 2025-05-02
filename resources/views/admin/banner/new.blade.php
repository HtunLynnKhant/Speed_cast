@extends ('admin.layout.master')
@section('title')
New Banner
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.banner.list' : (auth()->user()->hasRole('admin') ? 'admin.banner.list' : 'client.banner.list')) }}">Main Banner</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Main Banner</li>
        </ol>
</nav>

<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <form action="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.banner.store' : (auth()->user()->hasRole('admin') ? 'admin.banner.store' : 'client.banner.store')) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-6 col-md-12 mb-3">
                        <label for="bannerTitle" class="form-label">Banner Title</label>
                        <small style="color:red">*</small>
                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" id="bannerTitle" placeholder="Enter Title" value="{{ old('title') }}">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 mb-3">
                        <label class="form-label" for="formFile">Image</label>
                        <small style="color:red">* (Ratio 3:1)</small>
                        <div class="input_file d-flex align-items-center">
                            <label for="formFile" class="custom-file-label">Browse Image File</label>
                            <input class="custom-file-input @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png,.gif" name="image" type="file" id="formFile" onchange="updateFileName()">
                            <span id="file-chosen" class="file-chosen">No file chosen</span>
                        </div>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <img id="image-preview" style="margin-top: 10px; width: 200px; height: 100px; display: none;" alt="Image preview">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 mb-3">
                        <label for="videoLink" class="form-label">Video Link</label>
                        <input type="url" name="video_link" class="form-control @error('video_link') is-invalid @enderror" id="videoLink" placeholder="Enter Video Link (YouTube, Vimeo, etc.)" value="{{ old('video_link') }}">
                        @error('video_link')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    

                    <div class="col-lg-6 col-md-12 mb-3">
                        <label for="bannerStatus" class="form-label">Status</label>
                        <small style="color:red">*</small>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" checked>
                                <label class="form-check-label" for="statusInactive">Inactive</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusActive" value="1">
                                <label class="form-check-label" for="statusActive">Active</label>
                            </div>
                        </div>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-12 mb-3">
                        <label for="bannerDescription" class="form-label">Description</label>
                        <textarea name="description" class="form-control form-control-lg @error('description') is-invalid @enderror" id="bannerDescription" placeholder="Enter Description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12">
                    <label for="banneralert" class="form-label" style="color: red;">* You cannot fill both image and video link. Please choose one.</label>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Add Main Banner</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
@section('js')
<script>
    function updateFileName() {
        var input = document.getElementById('formFile');
        var fileChosen = document.getElementById('file-chosen');
        fileChosen.textContent = input.files.length > 0 ? input.files[0].name : 'No file chosen';

        var imagePreview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            imagePreview.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const videoLinkInput = document.getElementById('videoLink');
        const hasErrors = @json($errors->any());

        if (hasErrors) {
            const imageError = @json($errors->first('image'));
            const videoLinkError = @json($errors->first('video_link'));

            // If there are errors related to both image and video link, clear the video link field
            if (imageError && videoLinkError) {
                videoLinkInput.value = '';
            }
        }
    });
</script>
@endsection