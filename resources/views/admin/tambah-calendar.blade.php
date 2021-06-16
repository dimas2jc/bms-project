{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Tambah Calendar')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('/metroadmin/icons/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/metroadmin/vendor/select2/css/select2.min.css') }}">
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
                    <li class="breadcrumb-item"><a href="{{url('admin/calendar')}}">Calendar</a></li>
                    <li class="breadcrumb-item">Tambah Calendar</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Calendar</h4>
                    </div><hr>
                    <div class="card-body">
                        <form method="post" action="{{url('admin/tambah-calendar')}}" enctype="multipart/form-data">
                        @csrf
                            <div class="form-group col-md-4">
                                <label>Tanggal Mulai</label>
                                <input class="form-control" type="date" name="date_start" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tanggal Selesai</label>
                                <input class="form-control" type="date" name="date_end" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Pilih Outlet</label><br>
                                @foreach($outlet as $o)
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="outlet[]" class="form-check-input" value="{{ $o->ID_OUTLET }}">
                                    <label class="form-check-label">{{$o->NAMA}}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group col-md-4">
                                <label>Pilih Category</label><br>
                                <select class="form-control" name="category" id="category">
                                    <option selected disabled>Pilih Category..</option>
                                    @foreach ($category as $c)
                                        <option value="{{ $c->ID_CATEGORY_CALENDAR }}">{{ $c->NAMA }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Judul</label>
                                <input type="text" name="judul" class="form-control input-default" placeholder="Judul" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Deskripsi</label>
                                <textarea class="ckeditor form-control" name="deskripsi" required></textarea>
                            </div>
                            <div class="row justify-content-center">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2" aria-hidden="true"></i>SIMPAN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('extra-script')
    <script src="{{ asset('/metroadmin/vendor/select2/js/select2.min.js') }}"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            $("#category").select2();
        });
    </script>
@endsection