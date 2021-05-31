{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Timetable')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('/metroadmin/icons/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/metroadmin/vendor/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/metroadmin/vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/admin/css/timetable.css') }}">
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
                                </h4>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row mb-5">
                                    <div class="col-2">
                                        <label class="mr-3">Select Outlet</label>
                                    </div>
                                    <div class="col-md-5 col-sm-10">
                                    <select class="form-control" onchange="switch_outlet(this)">
                                        @for ($i = 0; $i < count($outlet); $i++)
                                            <option @if($i == 0) selected @endif value="{{ $outlet[$i]['ID_OUTLET'] }}">{{ $outlet[$i]['NAMA'] }}</option>
                                        @endfor
                                    </select>
                                    </div>
                                </div>

                                <div id="timetable-control-btn" class="my-3">
                                    <button class="btn btn-primary btn-rounded btn-sm px-3" data-toggle="modal" data-target="#add-activity-modal">
                                        <i class="fas fa-plus-circle mr-2"></i>
                                        Activity
                                    </button>
                                </div>

                                <div id="timetable-div">
                                    <table class="table table-bordered">
                                        <thead class="bg-warning">
                                            <tr>
                                                <th rowspan="2">Activities</th>
                                                <th rowspan="2">Durasi</th>
                                                <th>
                                                    <i class="fas fa-lg fa-caret-left" style="cursor: pointer;" data-toggle="tooltip" data-placement="right" title="Prev year"></i>
                                                </th>
                                                <th colspan="10">
                                                    {{ date('Y') }}
                                                </th>
                                                <th>
                                                    <i class="fas fa-lg fa-caret-right" style="cursor: pointer;" data-toggle="tooltip" data-placement="left" title="Next year"></i>
                                                </th>
                                            </tr>
                                            <tr>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <th>{{ date('M', strtotime(date('Y') . "-" . $i . "-01")) }}</th>
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody id="timetable-tbody"></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
			
            <!-- Add timeplan activity modal -->
            <div class="modal fade" id="add-activity-modal" tabindex="-1" data-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-center">
                            <h5 class="modal-title">Tambah Activity</h5>
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
                                <button type="button" class="btn btn-sm btn-danger btn-rounded px-3" data-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary btn-rounded px-3">
                                    Save
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./Add timeplan activity modal -->
@endsection

@section('extra-script')
    <script src="{{ asset('/metroadmin/vendor/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('/metroadmin/vendor/select2/js/select2.min.js') }}"></script>
    <script>
        const CATEGORY_ACTIVITY = {!! json_encode($category_activity) !!}
        const DETAIL_ACTIVITY = {!! json_encode($detail_activity) !!}
        const OUTLET = {!! json_encode($outlet) !!}
        const TIMEPLAN = {!! json_encode($timeplan) !!}
        console.log(DETAIL_ACTIVITY)
    </script>
    <script src="{{ asset('/assets/admin/js/timetable.js') }}"></script>
    
    @if (session('toast_msg_success'))
        <script defer>
            toastr.success('{{ session('toast_msg_success') }}')
        </script>
    @endif
@endsection