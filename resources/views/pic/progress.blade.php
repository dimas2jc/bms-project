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
                                <th scope="col">Keterangan</th>
                                <th scope="col">File</th>
                                <th scope="col">Aksi</th>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                <tr>
                                    <td>{{$d->NAMA_AKTIFITAS}}</td>
                                    <td>{{$d->PROGRESS}} %</td>
                                    <td>{{$d->KETERANGAN}}</td>
                                    <td>{{$d->FILE}}</td>
                                    <td align="center">
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#update-progress-{{$d->ID_PROGRESS}}">
                                            <i class="fa fa-pencil mr-1" aria-hidden="true"></i>
                                            UPDATE
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
@foreach($data as $d)
{{-- Start Update Progress Modal --}}
<div class="modal fade show" id="update-progress-{{$d->ID_PROGRESS}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-light">Update Progress</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times-circle" style="color: white" aria-hidden="true"></i>
                </button>
            </div>
            <div class="basic-form">
                <form action="{{url('/pic/activity/progress')}}" method="POST" enctype="multipart/form-data">
                @csrf
                    <input type="hidden" name="id_detail_activity" value="{{$d->ID_DETAIL_ACTIVITY}}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Progress</label>
                            <div class="input-group mb-3">
                                <input type="number" name="progress" class="form-control input-default" value="{{$d->PROGRESS}}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" value="{{$d->KETERANGAN}}" class="form-control input-default"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Dokumen</label><br>
                            <input type="file" name="file" accept=".pdf, image/*"><br>
                            <small style="color: red">Format file .pdf, image</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-light btn-rounded px-3" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-sm btn-secondary btn-rounded px-3">
                            <i class="fas fa-save mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End of Update Progress Modal --}}
@endforeach

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