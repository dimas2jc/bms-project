{{-- Extends layout --}}
@extends('layout.default')
@section('title', 'Calendar')

@section('extra-css')
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
                <li class="breadcrumb-item"><a href="{{route('investor')}}">Timetable</a></li>
                <li class="breadcrumb-item active">Calendar</li>
            </ol>
        </div>
    </div>
    <!-- row -->


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
            <div class="card-body">
                    <!-- <div class="row mb-3">
                        <div class="col-2">
                            <label class="mr-3">Select Outlet</label>
                        </div>
                        <div class="col-md-4 col-sm-8">
                            <select class="form-control" name="outlet" id="outlet">
                                <option selected disabled>Pilih Outlet..</option>
                                
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-2">
                            <label class="mr-3">Select Category</label>
                        </div>
                        <div class="col-md-4 col-sm-8">
                            <select class="form-control" name="category" id="category">
                                <option selected disabled>Pilih Kategori..</option>
                                
                            </select>
                        </div>
                    </div> -->
                    <h4 class="card-title">Outlet {{$outlet->NAMA}}</h4>
                    <hr>
                    <div id="calendar" class="app-fullcalendar"></div>
                </div>
            </div>
        </div>

        <!-- BEGIN MODAL -->
        <div class="modal fade none-border" id="event-modal">
            <div class="modal-dialog">
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
        const DETAIL_ACTIVITY = {!! json_encode($detail_activity) !!}
        const urlCalendar = "{{url('investor/calendar')}}"
        const token = $('meta[name="csrf-token"]').attr('content')
        console.log(DETAIL_ACTIVITY)
    </script>
    <script src="{{asset('assets/investor/js/calendar.js')}}"></script>
@endsection