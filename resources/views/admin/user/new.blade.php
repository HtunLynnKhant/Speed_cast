@extends ('admin.layout.master')
@section('title')
New Admin
@endsection
@section('content')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.user.list' : 'superadmin.user.list') }}">User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add-User</li>
    </ol>
</nav>

<div class="row">

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <form action="{{ route(auth()->user()->hasRole('admin') ? 'admin.user.store' : 'superadmin.user.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="projectTitle" class="form-label">Name</label>
                            <small style="color:red">*</small>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" id="UserTitle" placeholder="Enter User Name" value="{{ old('name') }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="projectTitle" class="form-label">Mail</label>
                            <small style="color:red">*</small>
                            <input type="text" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="Email" placeholder="Enter User Email" value="{{ old('email') }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="projectTitle" class="form-label">Password</label>
                            <small style="color:red">*</small>
                            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" placeholder="Enter User password" value="{{ old('password') }}">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <small style="color:red">*</small>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Confirm Password">
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-control" required>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Add Admin</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection