@extends('layouts.login')

@section('content')
<div class="card login">
    <div class="card-header">
        <h3 class="text-center">E-Learning System</h3>
    </div>
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group row{{ $errors->has('username') ? ' has-error' : '' }}">
                <div class="col-md-12">
                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter username">

                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="col-md-12">
                    <input id="password" type="password" class="form-control" name="password" required placeholder="Enter password">

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="remember" {{ old('remember') ? 'checked' : '' }} id="remember">
                        <label class="custom-control-label" for="remember">Remember Me</label>
                    </div>
                </div>
                <div class="offset-md-2 col-md-4">
                    <button type="submit" class="btn btn-block btn-sm btn-primary">
                        Login
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
