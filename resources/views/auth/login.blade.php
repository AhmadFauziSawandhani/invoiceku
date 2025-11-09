<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rohim
 * Date: 8/13/2023
 * Time: 10:34 PM
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets')}}/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-img-bottom">

    </div>
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        <!-- <div class="card-header text-center">
            <div class="row">
                <div class="col-3">
                    <img src="{{ asset('assets') }}/dist/img/AdminLogo.png" class="brand-image img-circle elevation-1" style="opacity: .8; width: 100%; height: 75%;">
                </div> 
                <div class="col-8">
                    <a href="" class="h1"><b>My</b> INVOICE</a>
                </div>
            </div>


        </div> -->
        <div class="card-header d-flex justify-content-center align-items-center">
            <h1 class="mb-0">
                <b>My</b> INVOICE
            </h1>
        </div>

        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <form action="{{url('login')}}" method="post">
                {{ csrf_field() }}
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                @if ($errors->has('email'))
                    <label class="text-danger">{{ $errors->first('email') }}</label>
                @endif
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                @if ($errors->has('password'))
                    <label class="text-danger">{{ $errors->first('password') }}</label>
                @endif
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<script src="{{asset('assets')}}/plugins/jquery/jquery.min.js"></script>
<script src="{{asset('assets')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('assets')}}/dist/js/adminlte.min.js"></script>
</body>
</html>
