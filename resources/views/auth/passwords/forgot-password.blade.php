@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.auth.reset-password') }}">
                        @csrf

                        <div class="block">
                            <label for="email" value="{{ __('Email') }}"></label>
                            <input id="email" class="block mt-1 w-full" type="email" name="email"  required autofocus />
                            <!-- :value="old('email')" -->
                        </div>

                        <div class="flex items-center justify-end mt-4">
            
                            <button type="submit" class="btn btn-primary">
                                {{ __('Reset') }}
                            </button>
                        
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

