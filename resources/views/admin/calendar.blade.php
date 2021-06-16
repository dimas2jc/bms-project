{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Calendar')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('/metroadmin/icons/fontawesome/css/all.min.css') }}">
    <link href="{{asset('metroadmin/vendor/fullcalendar/css/fullcalendar.min.css')}}" rel="stylesheet">
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
                <li class="breadcrumb-item"><a href="{{route('admin')}}">Timetable</a></li>
                <li class="breadcrumb-item active">Calendar</li>
            </ol>
        </div>
    </div>
    <!-- row -->


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row col-lg-12 justify-content-between">
                        <div class="col-sm-3">
                            <select class="form-control" name="outlet" id="outlet">
                                <option selected disabled>Pilih Outlet..</option>
                                @foreach ($outlet as $outlet)
                                    <option value="{{ $outlet['ID_OUTLET'] }}">{{ $outlet['NAMA'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{url('admin/tambah-calendar')}}"><button type="button" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle mr-2" aria-hidden="true"></i>
                            TAMBAH
                        </button></a>
                    </div>
                </div><hr>
                <div class="card-body">
                    
                    
                    <div id="calendar" class="app-fullcalendar"></div>
                </div>
            </div>
        </div>

        <!-- BEGIN MODAL -->
        <div class="modal fade none-border" id="event-modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><strong>Add New Event</strong></h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success save-event waves-effect waves-light">Create
                            event</button>

                        <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Add Category -->
        <div class="modal fade none-border" id="add-category">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><strong>Add a category</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Category Name</label>
                                    <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name">
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Choose Category Color</label>
                                    <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                        <option value="success">Success</option>
                                        <option value="danger">Danger</option>
                                        <option value="info">Info</option>
                                        <option value="pink">Pink</option>
                                        <option value="primary">Primary</option>
                                        <option value="warning">Warning</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
			
@endsection

@section('extra-script')
    <script src="{{asset('metroadmin/vendor/jqueryui/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/moment/moment.min.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/fullcalendar/js/fullcalendar.min.js')}}"></script>
    <script src="{{ asset('/metroadmin/vendor/select2/js/select2.min.js') }}"></script>
    <script>
        const DATA = {!! json_encode($data) !!}
        const urlDetail = "{{url('admin/calendar/detail')}}"
        const urlCalendar = "{{url('admin/calendar')}}"
        const token = $('meta[name="csrf-token"]').attr('content')
        console.log(DATA)
    </script>
    <script src="{{asset('assets/admin/js/calendar.js')}}"></script>
@endsection