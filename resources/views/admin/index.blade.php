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
                                <h4 class="card-title">Time Table Outlet {{ $outlet[0]['NAMA'] }}</h4>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row mb-5">
                                    <div class="col-2">
                                        <label class="mr-3">Select Outlet</label>
                                    </div>
                                    <div class="col-md-5 col-sm-10">
                                    <select class="form-control">
                                        @for ($i = 0; $i < count($outlet); $i++)
                                            <option @if($i == 0) selected @endif value="{{ $outlet[$i]['ID_OUTLET'] }}">{{ $outlet[$i]['NAMA'] }}</option>
                                        @endfor
                                    </select>
                                    </div>
                                </div>

                                <div id="timetable-control-btn" class="my-3">
                                    <button type="button" id="add-category-btn" class="btn btn-primary btn-rounded btn-sm px-3" data-toggle="modal" data-target="#add-category-modal">
                                        <i class="fas fa-plus-circle mr-2"></i>
                                        Category
                                    </button>
                                    <button class="btn btn-primary btn-rounded btn-sm px-3">
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
			
<!-- Add category modal -->
<div class="modal fade" id="add-category-modal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h5 class="modal-title">Add Category</h5>
            </div>
            <div class="modal-body">
                <input type="text" id="input-new-category" class="form-control" placeholder="category name">
            </div>
            <div class="modal-footer">
                <button type="button" id="cancel-new-category-btn" onclick="clear_add_category_modal()" class="btn btn-sm btn-danger btn-rounded px-3" data-dismiss="modal">
                    Cancel
                </button>
                <button type="button" id="save-new-category-btn" onclick="add_new_category()" class="btn btn-sm btn-primary btn-rounded px-3">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ./Add category modal -->
@endsection

@section('extra-script')
    <script src="{{ asset('/metroadmin/vendor/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('/metroadmin/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('/assets/admin/js/timetable.js') }}"></script>
@endsection