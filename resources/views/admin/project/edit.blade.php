@extends ('admin.layout.master')
@section('title')
Edit Project
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.project.list' : 'superadmin.project.list') }}">Project</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit-Project</li>
    </ol>
</nav>

<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.project.update' : 'superadmin.project.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-lg-6 col-md-12 mb-3">
                        <!-- Project Name Input -->
                        <div class="mb-3">
                            <label for="projectTitle" class="form-label">Project Name</label>
                            <small style="color:red">*</small>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" id="projectTitle" value="{{ old('name', $project->name) }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Status Radio Buttons -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <small style="color:red">*</small>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="status" value="{{ \App\Enums\RecordStatus::ACTIVE->value }}" {{ $project->status === \App\Enums\RecordStatus::ACTIVE->value ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="status" value="{{ \App\Enums\RecordStatus::INACTIVE->value }}" {{ $project->status === \App\Enums\RecordStatus::INACTIVE->value ? 'checked' : '' }}>
                                <label class="form-check-label">Inactive</label>
                            </div>
                        </div>
                        
                        <!-- Description Textarea -->
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-lg @error('description') is-invalid @enderror" id="categoryDescription">{{ old('description', $project->description ?? '') }}</textarea>  
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 mb-3">
                        <!-- Additional fields or content can be added here -->
                    </div>
                </div>
                    <button type="submit" class="btn btn-primary mb-3">Update Project</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection