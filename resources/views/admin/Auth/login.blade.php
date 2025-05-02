<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{asset('/images/logo_.png')}}" type="image/x-icon">
    <title>Speedcast | Login</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body>

    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">
                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-7 col-lg-5 mx-auto">
                        <div class="card">
                            <div class="container p-2">
                                <div class="row">
                                    <div class="auth-form-wrapper px-4 py-4">
                                        <a href="#" class="nobleui-logo text-center d-block mb-2">
                                            <img src="{{ asset('/images/logo_.png') }}" style="max-width: 100%; max-height: 100px; width: auto; height: auto;" alt="Logo" />
                                        </a>
                                        <h5 class="text-secondary text-center fw-normal mb-4">Welcome back! Log in to your account.</h5>

                                        @if(session('error'))
                                            <div class="alert alert-danger text-center">
                                                {{ session('error') }}
                                            </div>
                                        @endif

                                        <form class="forms-sample" action="{{ route('login.post') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="userEmail" class="form-label text-capitalize">Email address</label>
                                                <input type="text" class="form-control form-control-lg" id="email" name="email" placeholder="Email" />
                                                @if($errors->has('email'))
                                                    <small style="color:red">{{ $errors->first('email') }}</small>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <label for="userPassword" class="form-label">Password</label>
                                                <div class="input-group password-toggle">
                                                    <input type="password" class="form-control form-control-lg" id="password" name="password" autocomplete="current-password" placeholder="Password" />
                                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                                        <i data-feather="eye" class="icon-sm" id="toggleIcon"></i>
                                                    </button>
                                                </div>
                                                @if($errors->has('password'))
                                                    <small style="color:red">{{ $errors->first('password') }}</small>
                                                @endif
                                            </div>
                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="authCheck" />
                                                <label class="form-check-label" for="authCheck">Remember me</label>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-lg btn-block btn-primary w-100 mb-2 mb-md-0" style="text-align: center;">
                                                    Login
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- bootstrap js -->
    <script src="{{ mix('js/dashboard.js') }}"></script>
    <script src="{{ mix('js/vendor.bundle.base.js') }}"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            // Toggle the type attribute
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the icon
            const toggleIcon = document.getElementById('toggleIcon');
            if (toggleIcon) {
                const isPassword = type === 'password';
                toggleIcon.setAttribute('data-feather', isPassword ? 'eye' : 'eye-off');
            }
        });
    </script>


</body>

</html>