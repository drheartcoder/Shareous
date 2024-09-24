
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

</head>
  
   <body>
    <div id="main"></div>
    <!--Header section start here-->
    <header class="">
        <div id="header-home"></div>
        <div class="blank-div"></div>
    </header>
    <!--Header section end here-->
   <div class="banner-404 thank" style="background-image:url({{url('/front/images/404-bg.jpg')}});background-repeat:no-repeat;background-size:cover;/*height:100vh;*/ background-attachment: fixed;">
        <div class="container">
            <div class="contruction-img-main">
                <div class="row">
                    <div class="col-sm-6 col-md-5 col-lg-5">
                        <div class="contruction-img-blo">
                            <img src="{{url('front/images/under-construction-img.png')}}" alt="">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-7 col-lg-7">
                        <div class="contruction-text-blo">
                            <span>Under<span class="under-green"> Construction</span></span>
                            <h1>Yes ! Currently Working In Hard On Awesome New Website.</h1>
                            <h3>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever..</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
 