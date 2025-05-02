@extends ('admin.layout.master')
@section('title')
New Project
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.project.list' : 'superadmin.project.list') }}">Project</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add-Project</li>
    </ol>
</nav>

<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.project.store' : 'superadmin.project.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-6 col-md-12 mb-3">
                        <div class="mb-3">
                            <label for="projectTitle" class="form-label">Project Name</label>
                            <small style="color:red">*</small>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" id="projectTitle" placeholder="Enter Project Name" value="{{ old('name') }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-lg" id="categoryDescription" placeholder="Enter Description"></textarea>  
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 mb-3">
                        
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Add Project</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection