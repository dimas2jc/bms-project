<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Solusi Master | @yield('title')</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('metroadmin/images/favicon.png')}}">
    <!-- <link href="{{asset('metroadmin/vendor/jqvmap/css/jqvmap.min.css')}}" rel="stylesheet"> -->
	<!-- <link rel="stylesheet" href="{{asset('metroadmin/vendor/chartist/css/chartist.min.css')}}"> -->
    <link href="{{asset('metroadmin/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{asset('metroadmin/css/style.css')}}" rel="stylesheet">

@yield('extra-css')

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="index.html" class="brand-logo">
                <img class="logo-abbr" src="{{asset('metroadmin/images/logo.png')}}" alt="">
                <img class="logo-compact" src="{{asset('metroadmin/images/logo-text.png')}}" alt="">
                <img class="brand-title" src="{{asset('metroadmin/images/logo-text.png')}}" alt="">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        
		@include('elements.header')
		
		
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        @include('elements.sidebar')
        <!--**********************************
            Sidebar end
        ***********************************-->

		
		
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            @yield('content')
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>All Rights Reserved @php echo date('Y'); @endphp</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{asset('metroadmin/vendor/global/global.min.js')}}"></script>
	<script src="{{asset('metroadmin/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('metroadmin/js/custom.min.js')}}"></script>
    <script src="{{asset('metroadmin/js/deznav-init.js')}}"></script>

    <!-- Vectormap -->
    <!-- <script src="{{asset('metroadmin/vendor/jqvmap/js/jquery.vmap.min.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/jqvmap/js/jquery.vmap.world.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/circle-progress/circle-progress.min.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/chart.js/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/gaugeJS/dist/gauge.min.js')}}"></script> -->

    <!-- Chartist -->
    <!-- <script src="{{asset('metroadmin/vendor/chartist/js/chartist.min.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js')}}"></script> -->
	
    <!-- Flot -->
    <!-- <script src="{{asset('metroadmin/vendor/flot/jquery.flot.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/flot/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/flot-spline/jquery.flot.spline.min.js')}}"></script> -->
	
    <!-- Counter Up -->
    <!-- <script src="{{asset('metroadmin/vendor/waypoints/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/jquery.counterup/jquery.counterup.min.js')}}"></script> -->

	<!-- Demo scripts -->
    <!-- <script src="{{asset('metroadmin/js/dashboard/dashboard-1.js')}}"></script> -->

	<!-- Svganimation scripts -->
    <!-- <script src="{{asset('metroadmin/vendor/svganimation/vivus.min.js')}}"></script>
    <script src="{{asset('metroadmin/vendor/svganimation/svg.animation.js')}}"></script> -->
    
    @yield('extra-script')

</body>
</html>