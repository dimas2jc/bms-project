{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Daftar Kategori')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('assets/datatables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('metroadmin/vendor/select2/css/select2.min.css') }}">
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
                <li class="breadcrumb-item active">Category</li>
            </ol>
        </div>
    </div>
        
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Kategori</h4>
                    <div class="card-action">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah-kategori">
                            <i class="fa fa-plus-circle mr-1" aria-hidden="true"></i>
                            Tambah
                        </button>
                    </div>
                </div><hr>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="user" class="table table-striped table-responsive-sm">
                            <thead class="thead-dark" align="center">
                                <th scope="col">No.</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Outlet</th>
                                <th scope="col">Aksi</th>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$d->CATEGORY}}</td>
                                    <td>{{$d->OUTLET}}</td>
                                    <td colspan="2" align="center">
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit-{{$d->ID_CATEGORY}}">
                                            <i class="fa fa-pencil mr-1" aria-hidden="true"></i>
                                            EDIT
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

{{-- Start Input Kategori Modal --}}
<div class="modal fade show" id="tambah-kategori" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light">Tambah Investor</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times-circle" style="color: white" aria-hidden="true"></i>
                </button>
            </div>
            <div class="basic-form">
                <form action="{{url('admin/tambah-kategori')}}" method="POST">
                @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Outlet</label>
                            <select class="form-control select-component" name="outlet" required>
                                <option selected disabled>Pilih Outlet . .</option>
                                @foreach($outlet as $o)
                                <option value="{{ $o->ID_OUTLET }}">{{ $o->NAMA }}</option>
                                @endforeach
                            </select>    
                        </div>
                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" name="nama" class="form-control input-default" placeholder="Nama Kategori" required>
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
{{-- End of Input Kategori Modal --}}
@foreach($data as $d)
{{-- Start Edit Kategori Modal --}}
<div class="modal fade show" id="edit-{{$d->ID_CATEGORY}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-light">Edit Kategori</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times-circle" style="color: white" aria-hidden="true"></i>
                </button>
            </div>
            <div class="basic-form">
                <form action="{{url('admin/edit-kategori')}}" method="POST">
                @csrf
                    <input type="hidden" name="id" value="{{$d->ID_CATEGORY}}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Outlet</label>
                            <input type="text" value="{{$d->OUTLET}}" class="form-control input-default" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" name="nama" value="{{$d->CATEGORY}}" class="form-control input-default" placeholder="Nama Kategori" required>
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
{{-- End of Edit Kategori Modal --}}
@endforeach
	
@endsection



@section('extra-script')
    <script src="{{ asset('assets/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/datatable/dataTable/Sorting-1.10.20/any-number-sorting.js') }}"></script>
    <script src="{{ asset('metroadmin/vendor/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            const table = document.getElementById('user');
            $(table).DataTable({
                "order": [[ 0, "asc" ]],
                "columnDefs": [
                    { "type": "any-number", "targets": 0 }
                ],
            });
        });
    </script>
@endsection