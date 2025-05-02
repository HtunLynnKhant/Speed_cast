@extends ('admin.layout.master')
@section('bootstrap')
@endsection
@section('title')
    Dashboard
@endsection

@section ('content')
    <div class="row">
        <div class="col-12">
            <div class="banner d-flex align-items-center">
                <div class="banner-text">
                    <h1>Hi, {{ Auth::user()->name }}</h1>
                    <p>Ready to start your day with some pitch decks?</p>
                </div>
                
                <!-- Overlapping Image -->
                <!-- <div class="banner-img">
                    <img src="{{asset('assets/images/banner.png')}}" alt="Illustration" class="img-fluid">
                </div> -->
            </div>
        </div>
    </div>
    

@endsection

@include('admin.partials.js')
