{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Detail Progress')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('/metroadmin/icons/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/css/jquery.dataTables.min.css') }}">
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
                <li class="breadcrumb-item"><a href="{{route('pic')}}">Timetable</a></li>
                <li class="breadcrumb-item"><a href="{{url('/pic/progress')}}">Progress</a></li>
                <li class="breadcrumb-item active">Detail Progress</li>
            </ol>
        </div>
    </div>
        
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Progress {{$activity->NAMA_AKTIFITAS}}</h4>
                </div><hr>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="detail" class="table table-striped table-responsive-sm">
                            <thead class="thead-dark" align="center">
                                <th scope="col">Created At</th>
                                <th scope="col">Progress</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">File</th>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                <tr>
                                    <td>{{$d->created_at}}</td>
                                    <td>{{$d->PROGRESS}} %</td>
                                    <td>{{$d->KETERANGAN}}</td>
                                    <td align="center">
                                        @if($d->FILE != null)
                                            <form action="{{url('/pic/download')}}" method="POST">
                                            @csrf
                                                <input type="hidden" name="id" value="{{$d->ID_PROGRESS}}">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-download mr-1" aria-hidden="true"></i>
                                                    DOWNLOAD
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{url('/pic/progress')}}"><button type="button" class="btn btn-sm btn-primary" style="float: right; margin-top: 20px">
                    <i class="far fa-arrow-alt-circle-left mr-2" aria-hidden="true"></i>
                        KEMBALI
                    </button></a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('extra-script')
    <script src="{{ asset('assets/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            const table = document.getElementById('detail');
            $(table).DataTable();
        });
    </script>
@endsection