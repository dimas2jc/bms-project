{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Detail Calendar')
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
                    <li class="breadcrumb-item">Detail Calendar</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Detail Calendar</h4>
                    </div><hr>
                    <div class="card-body">
                        <form method="post" action="{{url('admin/edit-calendar')}}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$data->ID_CALENDAR}}"
                            <div class="form-group col-md-4">
                                <label>Tanggal Mulai</label>
                                <input class="form-control input" type="date" name="date_start" value="{{date('Y-m-d', strtotime($data->TANGGAL_START))}}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tanggal Selesai</label>
                                <input class="form-control input" type="date" name="date_end" value="{{date('Y-m-d', strtotime($data->TANGGAL_END))}}" readonly>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Judul</label>
                                <input type="text" name="judul" class="form-control input-default input" value="{{$data->JUDUL}}" readonly>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Deskripsi</label>
                                <textarea class="ckeditor form-control input" name="deskripsi">{{$data->DESKRIPSI}}</textarea>
                            </div>
                            <div class="row justify-content-center">
                                <button type="button" class="btn btn-sm btn-warning" id="btn-edit"><i class="fas fa-edit mr-2" aria-hidden="true"></i>EDIT</button>
                                <button type="submit" class="btn btn-sm btn-primary" style="display: none" id="btn-simpan"><i class="fas fa-save mr-2" aria-hidden="true"></i>SIMPAN</button>
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
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#category").select2();

            // Update
            $('#btn-edit').on('click', function(){
                document.getElementById('btn-edit').style.display = "none";
                document.getElementById('btn-simpan').style.display = "block";

                $('.input').prop("readonly", false);
            })
        });
    </script>
@endsection