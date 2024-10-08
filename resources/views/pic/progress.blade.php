{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Progress')
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
                <li class="breadcrumb-item active">Progress</li>
            </ol>
        </div>
    </div>
        
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Activity</h4>
                </div><hr>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="activity" class="table table-striped table-responsive-sm">
                            <thead class="thead-dark" align="center">
                                <th scope="col">Activity</th>
                                <th scope="col">Progress</th>
                                <th scope="col">Aksi</th>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                <tr>
                                    <td>{{$d->NAMA_AKTIFITAS}}</td>
                                    <td>{{$d->TOTAL_PROGRESS}} %</td>
                                    <td align="center">
                                        <a href="{{url('/pic/detail-progress/'.$d->ID_DETAIL_ACTIVITY)}}"><button type="button" class="btn btn-sm btn-info">
                                            <i class="fa fa-info-circle mr-1" aria-hidden="true"></i>
                                            DETAIL
                                        </button></a>
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

@endsection

@section('extra-script')
    <script src="{{ asset('assets/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            const table = document.getElementById('activity');
            $(table).DataTable();
        });
    </script>
@endsection