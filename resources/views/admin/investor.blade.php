{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Daftar Investor')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('assets/datatables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/metroadmin/vendor/select2/css/select2.min.css') }}">
    <style>
        @media screen and (max-width: 599px) {
            .btn-edit {
                margin-right: 0px;
                margin-bottom: 2px;
            }
            .btn-reset {
                margin-left: 0px;
                margin-top: 2px;
            }
        }

        @media screen and (min-width: 600px) {
            .btn-edit {
                margin-right: 2px;
            }
            .btn-reset {
                margin-left: 2px;
            }
        }
    </style>
@endsection

{{-- Content --}}
@section('content')

<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Hi {{Auth::user()->NAMA}}, welcome back!</h4>
                <!-- <p class="mb-0">Your business dashboard template</p> -->
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Timetable</a></li>
                <li class="breadcrumb-item active">Daftar Investor</li>
            </ol>
        </div>
    </div>
        
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Investor</h4>
                    <div class="card-action">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah-investor">
                            <i class="fa fa-plus-circle mr-1" aria-hidden="true"></i>
                            Tambah
                        </button>
                    </div>
                </div><hr>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="user" class="table table-striped table-responsive-sm">
                            <thead class="thead-dark" align="center">
                                <th scope="col">Nama</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">No. Telp</th>
                                <th scope="col">Aksi</th>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                <tr>
                                    <td>{{$d->NAMA}}</td>
                                    <td>{{$d->username}}</td>
                                    <td>{{$d->EMAIL}}</td>
                                    <td>{{$d->NO_TELP}}</td>
                                    <td colspan="2" align="center">
                                        <button type="button" class="btn btn-sm btn-warning btn-edit" data-toggle="modal" data-target="#edit-investor-{{$d->ID_USER}}">
                                            <i class="fa fa-pencil mr-1" aria-hidden="true"></i>
                                            EDIT
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-reset" data-toggle="modal" data-target="#reset-pass-{{$d->ID_USER}}">
                                            <i class="fa fa-repeat mr-1" aria-hidden="true"></i>
                                            RESET
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Start Input Investor Modal --}}
<div class="modal fade show" id="tambah-investor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Tambah Investor</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times-circle" style="color: white" aria-hidden="true"></i>
                </button>
            </div>
            <div class="basic-form">
                <form action="{{url('admin/tambah-investor')}}" method="POST">
                @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control input-default" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control input-default" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label>No. Telp</label>
                            <input type="text" name="no_telp" class="form-control input-default" placeholder="No. Telp" required>
                        </div>
                        <div class="form-group">
                            <label>Outlet</label>
                            <select class="form-control" name="outlet" required>
                                <option selected disabled>Pilih Outlet..</option>
                                @foreach($outlet as $o)
                                    <option value="{{$o->ID_OUTLET}}">{{$o->NAMA}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Username (tanpa spasi)</label>
                            <input type="text" name="username" class="form-control input-default" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" id="pass1" class="form-control input-default" placeholder="Re-Password" required>
                        </div>
                        <div class="form-group">
                            <label>Re-Password</label>
                            <input type="password" id="pass2" class="form-control input-default" placeholder="Re-Password" required>
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
{{-- End of Input Investor Modal --}}
@foreach($data as $d)
{{-- Start Edit Investor Modal --}}
<div class="modal fade show" id="edit-investor-{{$d->ID_USER}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-light">Edit Investor</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times-circle" style="color: white" aria-hidden="true"></i>
                </button>
            </div>
            <div class="basic-form">
                <form action="{{url('admin/edit-investor')}}" method="POST">
                @csrf
                    <input type="hidden" name="id" value="{{$d->ID_USER}}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" value="{{$d->NAMA}}" class="form-control input-default" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{$d->EMAIL}}" class="form-control input-default" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label>No. Telp</label>
                            <input type="text" name="no_telp" value="{{$d->NO_TELP}}" class="form-control input-default" placeholder="No. Telp" required>
                        </div>
                        <div class="form-group">
                            <label>Username (tanpa spasi)</label>
                            <input type="text" name="username" value="{{$d->username}}" class="form-control input-default" placeholder="Username" required>
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
{{-- End of Edit Investor Modal --}}
@endforeach
@foreach($data as $d)
{{-- Start Reset Password Modal --}}
<div class="modal fade show" id="reset-pass-{{$d->ID_USER}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-light">Reset Password Investor</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times-circle" style="color: white" aria-hidden="true"></i>
                </button>
            </div>
            <div class="basic-form">
                <form action="{{url('admin/reset-pass-investor')}}" method="POST">
                @csrf
                    <input type="hidden" name="id" value="{{$d->ID_USER}}">
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin reset password user {{$d->NAMA}} ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End of Reset Password Modal --}}
@endforeach		
@endsection



@section('extra-script')
    <script src="{{ asset('assets/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/datatable/dataTable/Sorting-1.10.20/any-number-sorting.js') }}"></script>
    <script src="{{ asset('/metroadmin/vendor/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            const table = document.getElementById('user');
            $(table).DataTable();

            $('select').select2();
        });
    </script>
@endsection