{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Timetable')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('/metroadmin/icons/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/metroadmin/vendor/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/metroadmin/vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/pic/css/timetable.css') }}">
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
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header justify-content-center">
                                <h4 class="card-title">
                                    Time Table Outlet
                                    <span id="title-outlet-name">{{ $outlet[0]['NAMA'] }}</span>
                                    Tahun
                                    <span class="display-tahun-aktif">{{ date('Y') }}</span>
                                </h4>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row mb-5">
                                    <div class="col-2">
                                        <label class="mr-3">Select Outlet</label>
                                    </div>
                                    <div class="col-md-5 col-sm-10">
                                    <select id="select-outlet" class="form-control" onchange="switch_outlet(this)">
                                        @for ($i = 0; $i < count($outlet); $i++)
                                            <option @if($i == 0) selected @endif value="{{ $outlet[$i]['ID_OUTLET'] }}">{{ $outlet[$i]['NAMA'] }}</option>
                                        @endfor
                                    </select>
                                    </div>
                                </div>

                                <!-- <div id="timetable-control-btn" class="my-3">
                                    <button class="btn btn-primary btn-rounded btn-sm px-3" data-toggle="modal" data-target="#add-activity-modal">
                                        <i class="fas fa-plus-circle mr-2"></i>
                                        Activity
                                    </button>
                                </div> -->

                                <div id="timetable-div">
                                    <!-- Main time table -->
                                    <table class="table table-bordered">
                                        <thead class="bg-warning">
                                            <tr>
                                                <th rowspan="3">Activities</th>
                                                <th rowspan="3">Durasi</th>
                                                {{-- <th colspan="2">
                                                    <i onclick="switch_year(this)" data-navigation="prev" class="fas fa-lg fa-caret-left" style="cursor: pointer;" data-toggle="tooltip" data-placement="right" title="Prev year"></i>
                                                </th> --}}
                                                <th colspan="48">
                                                    <input type="hidden" value="{{ date('Y') }}" id="tahun-aktif">
                                                    <span class="display-tahun-aktif">{{ date('Y') }}</span>
                                                </th>
                                                {{-- <th colspan="2">
                                                    <i onclick="switch_year(this)" data-navigation="next" class="fas fa-lg fa-caret-right" style="cursor: pointer;" data-toggle="tooltip" data-placement="left" title="Next year"></i>
                                                </th> --}}
                                            </tr>
                                            <tr>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <th colspan="4">{{ date('M', strtotime(date('Y') . "-" . $i . "-01")) }}</th>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    @for ($j = 1; $j <= 4; $j++)
                                                        <th>{{ $j }}</th>
                                                    @endfor
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody id="timetable-tbody"></tbody>
                                    </table>
                                    <!-- ./Main time table -->
                                </div>

                                <!-- Tanggal penting -->
                                <table class="table table-bordered mt-5">
                                    <thead class="bg-warning">
                                        <tr>
                                            <th colspan="3">Tanggal-tanggal Penting</th>
                                        </tr>
                                        <tr>
                                            <th class="w-50">Activity</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tanggal-penting-tbody"></tbody>
                                </table>
                                <!-- ./Tanggal penting -->

                            </div>
                        </div>
                    </div>
                </div>

            </div>
			
            <!-- Add timeplan activity modal -->
            <!-- <div class="modal fade" id="add-activity-modal" tabindex="-1" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Activity</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ url('/admin/activity') }}" method="post" onsubmit="return add_activity_form_check()">
                            @csrf

                            <div class="form-group">
                                <label>Outlet</label>
                                <input type="text" id="input_nama_outlet" class="form-control" required readonly style="cursor: not-allowed;">
                                <input type="hidden" name="input_id_outlet" id="input_id_outlet">
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <select name="input_category" id="input_category" class="form-control" required>
                                    @for ($i = 0; $i < count($category_activity); $i++)
                                        @if ($category_activity[$i]['ID_OUTLET'] == $outlet[0]['ID_OUTLET'])
                                            <option value="{{ $category_activity[$i]['ID_CATEGORY'] }}">{{ $category_activity[$i]['NAMA'] }}</option>
                                        @endif
                                    @endfor
                                </select>
                                <small id="input_category_error_msg" class="text-danger" style="display: none;">Anda belum memilih kategori</small>
                            </div>

                            <div class="form-group">
                                <label>Nama Activity</label>
                                <input type="text" name="input_nama_activity" id="input_nama_activity" class="form-control" required>
                                <small id="input_nama_activity_error_msg" class="text-danger" style="display: none;">Anda belum mengisi nama activity</small>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="input_tanggal_mulai" id="input_tanggal_mulai" onchange="calculate_activity_duration()" class="form-control" required>
                                <small id="input_tanggal_mulai_error_msg" class="text-danger" style="display: none;">Anda belum menentukan tanggal mulai</small>
                            </div>
                            
                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="input_tanggal_selesai" id="input_tanggal_selesai" onchange="calculate_activity_duration()" class="form-control" required>
                                <small id="input_tanggal_selesai_error_msg" class="text-danger" style="display: none;">Anda belum menentukan tanggal selesai</small>
                            </div>

                            <div class="form-group">
                                <label>Durasi</label>
                                <input type="text" id="input_durasi" class="form-control" readonly style="cursor: not-allowed;" value="Tentukan tanggal mulai dan tanggal selesai">
                                <small id="input_durasi_error_msg" class="text-danger" style="display: none;">Durasi tidak valid</small>
                            </div>

                            <div class="form-group">
                                <label>PIC</label>
                                <select name="input_pic" id="input_pic" class="form-control" required>
                                    @for ($i = 0; $i < count($pic); $i++)
                                        <option value="{{ $pic[$i]['ID_USER'] }}">{{ $pic[$i]['NAMA'] }}</option>
                                    @endfor
                                </select>
                                <small id="input_pic_error_msg" class="text-danger" style="display: none;">Anda belum menentukan PIC</small>
                            </div>

                            <input type="checkbox" name="input_event_penting" id="input_event_penting">
                            <span class="ml-2">
                                <label for="input_event_penting">Event penting</label>
                            </span>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-light btn-rounded px-3" data-dismiss="modal">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary btn-rounded px-3">
                                    <i class="fas fa-save mr-2"></i>
                                    Save
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- ./Add timeplan activity modal -->
            
            <!-- Progress activity modal -->
            <div class="modal fade" id="progress-activity-modal" tabindex="-1" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Progress Activity</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Activity</label>
                                <input type="text" id="progress_nama_activity" class="form-control" readonly style="cursor: not-allowed;">
                            </div>
                            
                            <div class="form-group">
                                <label>Status</label>
                                <input type="text" id="progress_status" class="form-control" readonly style="cursor: not-allowed;">
                            </div>
                            
                            <div class="form-group">
                                <label>Progress</label>
                                <div class="progress" style="position: relative; height:20px;">
                                    <div id="progress-percentage-bar" class="progress-bar" role="progressbar" style="width: 30%;"></div>
                                    <div id="progress-percentage-text" class="progress-bar-title"
                                                    style="position: absolute;
                                                            text-align: center;
                                                            line-height: 20px;
                                                            overflow: hidden;
                                                            color: #fff;
                                                            right: 0;
                                                            left: 0;
                                                            top: 0;
                                                            color: black;">
                                    30%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-3">
                                <label>Deadline: </label>
                                <span id="progress-deadline"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-light btn-rounded px-3" data-dismiss="modal">
                                <i class="fas fa-times-circle mr-2"></i>
                                Close
                            </button>
                            <button type="button" id="progress-detail-btn" onclick="view_detail_activity(this)" class="btn btn-sm btn-secondary btn-rounded px-3" data-id="">
                                <i class="fas fa-info-circle mr-2"></i>
                                Detail
                            </button>
                            <button type="button" id="update-progress-btn" onclick="update_progress(this)" class="btn btn-sm btn-secondary btn-rounded px-3" data-id="">
                                <i class="fas fa-edit mr-2"></i>
                                Progress
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./Progress activity modal -->
            
            <!-- Update progress activity modal -->
            <div class="modal fade" id="update-progress-modal" tabindex="-1" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <form action="{{ url('/pic/activity/progress') }}" method="POST" class="modal-content" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Update Progress</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" id="update-progress-id-detail-activity" name="id_detail_activity" required readonly>

                            <div class="form-group">
                                <label>Nama Activity</label>
                                <input type="text" id="update-progress-nama-activity" class="form-control" readonly style="cursor: not-allowed;">
                            </div>

                            <div class="form-group">
                                <label>Progress</label>
                                <div class="input-group mb-3">
                                    <input type="number" name="progress" class="form-control input-default" placeholder="Persentase" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control input-default"></textarea>
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
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-sm btn-secondary btn-rounded px-3">
                                <i class="fas fa-save mr-2"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- ./Update Progress activity modal -->

            <!-- Detail activity modal -->
            <div class="modal fade" id="detail-activity-modal" tabindex="-1" data-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Activity</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Nav tabs -->
                            <div class="default-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#log">Log Jadwal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#progress">Progress</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="log" role="tabpanel">
                                        <div class="pt-4">
                                            <h5 class="detail_nama_activity text-center mb-4"></h5>

                                            <div class="form-group">
                                                <label>PIC</label>
                                                <input type="text" id="detail_pic" class="form-control" readonly style="cursor: not-allowed;">
                                            </div>

                                            <div class="form-group">
                                                <label>Timeline</label>
                                                <input type="text" id="detail_tanggal" class="form-control" readonly style="cursor: not-allowed;">
                                            </div>

                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="bg-secondary"  style="color: white;">
                                                        <tr>
                                                            <td>No.</td>
                                                            <th>Created At</th>
                                                            <th>Tanggal Mulai</th>
                                                            <th>Tanggal Selesai</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="log-jadwal-tbody"></tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="progress">
                                        <div class="pt-4">
                                            <h5 class="detail_nama_activity text-center mb-4"></h5>

                                            <div class="form-group">
                                                <label>Total Progress</label>
                                                <div class="progress" style="position: relative; height:20px;">
                                                    <div id="detail-percentage-bar" class="progress-bar" role="progressbar" style="width: 30%;"></div>
                                                    <div id="detail-percentage-text" class="progress-bar-title"
                                                                    style="position: absolute;
                                                                            text-align: center;
                                                                            line-height: 20px;
                                                                            overflow: hidden;
                                                                            color: #fff;
                                                                            right: 0;
                                                                            left: 0;
                                                                            top: 0;
                                                                            color: black;">
                                                    0%
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div class="form-group">
                                                <label>Keterangan</label>
                                                <textarea id="keterangan" class="form-control input-default" style="cursor: not-allowed;">Tidak ada keterangan</textarea>
                                            </div> --}}
                                            
                                            {{-- <div class="form-group">
                                                <label>File</label>
                                                <input type="text" id="nama_file" class="form-control" value="Tidak ada file" readonly style="cursor: not-allowed;"><br>
                                                <form action="{{url('admin/download')}}" method="POST">
                                                    <input type="hidden" name="id" id="id_progress">
                                                    <button type="submit" id="download" class="btn btn-sm btn-success">
                                                        <i class="fa fa-download mr-1" aria-hidden="true"></i>
                                                        DOWNLOAD
                                                    </button>
                                                </form>
                                            </div> --}}

                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="bg-secondary"  style="color: white;">
                                                        <tr>
                                                            <td>No.</td>
                                                            <th>Created At</th>
                                                            <th>Progress</th>
                                                            <th>Keterangan</th>
                                                            <th>File</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="detail-progress-tbody"></tbody>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-light btn-rounded px-3" data-dismiss="modal">
                                <i class="fas fa-times-circle mr-2"></i>
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./Detail activity modal -->
@endsection

@section('extra-script')
    <script src="{{ asset('/metroadmin/vendor/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('/metroadmin/vendor/select2/js/select2.min.js') }}"></script>
    <script>
        const CATEGORY_ACTIVITY = {!! json_encode($category_activity) !!}
        const DETAIL_ACTIVITY = {!! json_encode($detail_activity) !!}
        const OUTLET = {!! json_encode($outlet) !!}
        const TIMEPLAN = {!! json_encode($timeplan) !!}
        const LOG = {!! json_encode($log) !!}
        const BASE_URL = "{{ url('/') }}"
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
        var timeline
        console.log(DETAIL_ACTIVITY)
    </script>
    <script src="{{ asset('/assets/pic/js/timetable.js') }}"></script>
    <script src="{{ asset('/assets/pic/js/timeline.js') }}"></script>
    
    @if (session('toast_msg_success'))
        <script defer>
            toastr.success('{{ session('toast_msg_success') }}')
        </script>
    @endif
@endsection