{{-- Extends layout --}}
@extends('layout.fullwidth')
@section('title', 'Login')
{{-- Content --}}
@section('content')
	<div class="col-md-6">
		<div class="authincation-content">
			<div class="row no-gutters">
				<div class="col-xl-12">
					<div class="auth-form">
						<h4 class="text-center mb-4">Sign in your account</h4>
						@if(session()->has('error'))
							<div class="alert alert-danger" style="color: white">{{ session('error') }}</div>
						@endif 
						<form action="{{url('/post-login')}}" method="POST">
						@csrf
							<div class="form-group">
								<label><strong>Username</strong></label>
								<input type="text" name="username" class="form-control" placeholder="Username" required>
							</div>
							<div class="form-group">
								<label><strong>Password</strong></label>
								<input type="password" name="password" class="form-control" placeholder="Password" required>
							</div>
							
							<div class="text-center">
								<button type="submit" class="btn btn-primary btn-block">Sign me in</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection