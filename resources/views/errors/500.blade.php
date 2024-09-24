
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <title>{{config('app.project.name')}}</title>
    <!-- ======================================================================== -->
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <!-- Bootstrap CSS -->
    <link href="{{url('/front/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!--font-awesome-css-start-here-->
    <link href="{{url('/front/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <!--Custom Css-->
    <link href="{{url('/front/css/vacationhomerental.css')}}" rel="stylesheet" type="text/css" />
    <!-- Datepicker-->
    <link rel="stylesheet" href="{{url('/front/css/kendo.common-material.min.css')}}" />
    <link rel="stylesheet" href="{{url('/front/css/kendo.material.min.css')}}" />
    <!--Main JS-->
    <script type="text/javascript" src="{{url('/front/js/jquery-1.11.3.min.js')}}"></script>
    <!-- Datepicker-->
    <script src="{{url('/front/js/kendo.all.min.js')}}"></script>
    <!--common header footer script start -->
    
    <style>
        body{background-image:url({{url('/front/images/404-banner.jpg')}});background-repeat:no-repeat;background-size:cover;background-attachment: fixed;background-position: center center;}
        body:before{position: fixed;content: "";width: 100%;height: 100%;top: 0;left: 0;background-image: url("{{url('/front/images/404-banner.jpg')}}");
        display: block;}
        p{ color: #fff; }
    </style>

</head>


<body>
    <div class="container">
        <div class="wrapper-404">
            <div class="direction"></div>
            <h1>500</h1>
            <h4>Internal Server Error</h4>
            <h5>Please try after some time</h5>
            
            <p class="m-t-30 m-b-30">
                @if (isset($message))
                {{ $message }}
                @endif
            </p>
            <div class="index-fore-btn-main">
                <a href="{{ url('/') }}" class="back-btn-foure">Go Back</a>
            </div>
        </div>
    </div>

</div>

</body>
</html>