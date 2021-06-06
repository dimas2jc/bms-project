{{-- Extends layout --}}
@extends('layout.fullwidth')
@section('title', 'Ganti Password')

{{-- Content --}}
@section('content')
	<div class="col-md-6">
		<div class="authincation-content">
			<div class="row no-gutters">
				<div class="col-xl-12">
					<div class="auth-form">
						<h4 class="text-center mb-4">Ganti Password</h4>
						<form action="{{url('/ganti-password')}}" method="POST">
							<div class="form-group">
								<label><strong>Password Lama</strong></label>
								<input type="password" name="old_password" class="form-control" required>
							</div>
							<div class="form-group">
								<label><strong>Password Baru</strong></label>
								<input type="password" name="new_password" id="pass1" class="form-control" required>
							</div>
                            <div class="form-group">
								<label><strong>Re-Password</strong></label>
								<input type="password" class="form-control" oninput="check()" id="pass2" required>
							</div>
							<p style="color: red; visibility: hidden" id="alert">Password tidak sama!</p>
							<div class="text-center">
								<button type="submit" class="btn btn-primary btn-block simpan" id="submit" disabled>Simpan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		function check(){
			let input = document.getElementById('pass2').value;
			let password = document.getElementById('pass1').value;

			if(input != password){
				document.getElementById('alert').style.visibility = "visible";
			}
			else{
				document.getElementById('alert').style.visibility = "hidden";
				document.getElementById('submit').disabled = false;
			}
		}
	</script>
@endsection