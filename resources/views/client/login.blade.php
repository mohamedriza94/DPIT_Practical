@extends('layouts.auth')

@section('content')
<div class="login-box card">
    <div class="card-body">
        
        {{-- Login --}}
        <form class="form-horizontal form-material text-center" id="loginform" action="{{ route('client.login.submit') }}" method="post">
            @csrf
            
            {{-- display error --}}
            @error('password')
            <div class="col-lg-12 col-md-12">
                <div class="alert alert-danger">{{ $message }}</div>
            </div>
            @enderror
            
            <div class="form-group m-t-40">
                <div class="col-xs-12">
                    <input class="form-control" type="email" name="email" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <input class="form-control" type="password" name="password" placeholder="Password">
                </div>
            </div>
            
            <div class="form-group text-center m-t-20">
                <div class="col-xs-12">
                    <button class="btn btn-info btn-lg w-100 text-uppercase btn-rounded text-white" type="submit">Login</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
