@extends ('admin.layout.master')

@section('title')
Edit Pages
@endsection

@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.category.list' : (auth()->user()->hasRole('admin') ? 'admin.category.list' : 'client.category.list')) }}">Category</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit-Page</li>
        
    </ol>
</nav>

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route(auth()->user()->hasRole('superadmin') ? 'superadmin.category.page.update' : (auth()->user()->hasRole('admin') ? 'admin.category.page.update' : 'client.category.page.update'), $page->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <!-- Page Number is auto-assigned, no need for user input -->
                        <div class="col-12 col-md-6 mb-3">
                            <label for="pageNo" class="form-label">Page Number</label>
                            <input type="text" name="page_no" class="form-control form-control-lg" id="pageNo" value="{{ old('page_no', $page->page_no ?? '') }}" readonly>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label for="categoryName" class="form-label">Category</label>
                            <span id="categoryName" class="form-control form-control-lg">{{ $category->name }}</span>
                        </div>
                        <!-- Hidden Category ID -->
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <label for="bannerDescription" class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-lg" id="bannerDescription" placeholder="Enter Description">{{ old('description', $page->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <small style="color:red">*</small>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="status" value="{{ \App\Enums\RecordStatus::ACTIVE->value }}" {{ $page->status === \App\Enums\RecordStatus::ACTIVE->value ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>
                            <div class="form-check form-check-sm">
                                <input class="form-check-input" type="radio" name="status" value="{{ \App\Enums\RecordStatus::INACTIVE->value }}" {{ $page->status === \App\Enums\RecordStatus::INACTIVE->value ? 'checked' : '' }}>
                                <label class="form-check-label">Inactive</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mb-3">Update Page</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
