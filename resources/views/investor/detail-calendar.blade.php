{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Detail Calendar')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('/metroadmin/icons/fontawesome/css/all.min.css') }}">
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
                    <li class="breadcrumb-item"><a href="{{url('investor/calendar')}}">Calendar</a></li>
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
                        
                        <div class="form-group col-md-4">
                            <label>Tanggal Mulai</label>
                            <input class="form-control input" type="date" name="date_start" value="{{date('Y-m-d', strtotime($data->TANGGAL_START))}}" readonly style="cursor: not-allowed;">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Tanggal Selesai</label>
                            <input class="form-control input" type="date" name="date_end" value="{{date('Y-m-d', strtotime($data->TANGGAL_END))}}" readonly style="cursor: not-allowed;">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Judul</label>
                            <input type="text" name="judul" class="form-control input-default input" value="{{$data->JUDUL}}" readonly style="cursor: not-allowed;">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Deskripsi</label>
                            <textarea class="ckeditor form-control input" name="deskripsi" readonly style="cursor: not-allowed;">{{$data->DESKRIPSI}}</textarea>
                        </div>
                        <div class="row justify-content-center">
                            <a href="{{url('/investor/calendar')}}"><button type="button" class="btn btn-sm btn-primary"><i class="far fa-arrow-alt-circle-left mr-2" aria-hidden="true"></i>KEMBALI</button></a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('extra-script')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
@endsection