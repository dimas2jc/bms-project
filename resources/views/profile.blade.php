{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Profile')

{{-- Content --}}
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center h-100 align-items-center">
        <div class="col-md-6">
            <div class="authincation-content">
                <div class="row no-gutters">
                    <div class="col-xl-12">
                        <div class="auth-form">
                            
                            <h4 class="text-center mb-4">Selamat Datang {{$data->NAMA}} !</h4>
                            
                            <div class="form-group">
                                <label><strong>Nama</strong></label>
                                <input type="text" class="form-control" value="{{$data->NAMA}}" readonly>
                            </div>
                            <div class="form-group">
                                <label><strong>Email</strong></label>
                                <input type="text" class="form-control" value="{{$data->EMAIL}}" readonly>
                            </div>
                            <div class="form-group">
                                <label><strong>No Telp</strong></label>
                                <input type="text" class="form-control" value="{{$data->NO_TELP}}" readonly>
                            </div>
                            <div class="form-group">
                                <label><strong>Username</strong></label>
                                <input type="text" class="form-control" value="{{$data->username}}" readonly>
                            </div>
                            
                        </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit-{{$data->ID_USER}}">
                                    <i class="fa fa-pencil mr-1" aria-hidden="true"></i>
                                    EDIT
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Start Edit Modal --}}
<div class="modal fade show" id="edit-{{$data->ID_USER}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-light">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times-circle" style="color: white" aria-hidden="true"></i>
                </button>
            </div>
            <div class="basic-form">
                <form action="{{url('edit-profile')}}" method="POST">
                @csrf
                    <input type="hidden" name="id" value="{{$data->ID_USER}}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" value="{{$data->NAMA}}" class="form-control input-default" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{$data->EMAIL}}" class="form-control input-default" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label>No. Telp</label>
                            <input type="text" name="no_telp" value="{{$data->NO_TELP}}" class="form-control input-default" placeholder="No. Telp" required>
                        </div>
                        <div class="form-group">
                            <label>Username (tanpa spasi)</label>
                            <input type="text" name="username" value="{{$data->username}}" class="form-control input-default" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End of Edit Modal --}}

@endsection