@extends('front.layout.master')                
@section('main_content')

    <div class="clearfix"></div>
    <div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions">
                    <h1>My Documents</h1>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="change-pass-bg">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                        <div id="left-bar-host">                   
                            @include('front.layout.left_bar_host')
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">

                        <div class="box-white-user">
                            <div class="row">
                              
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="box-main-bx">
                                        <div class="li-boxss"> </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-12">
                                    ID Proof : 
                                    @if(isset($arr_doc['id_proof']) && !empty($arr_doc['id_proof']))
                                        <?php $extention = explode('.', $arr_doc['id_proof']); ?>

                                            @if($arr_doc['id_proof'] != null && file_exists($id_proof_base_path.$arr_doc['id_proof']))
                                                <div class="profile-img-block">
                                                    <a href="{{ $id_proof_public_path.$arr_doc['id_proof'] }}" download >
                                                        @if(in_array($extention['1'],['png','jpg','jpeg'])) 
                                                            <img src="{{ $id_proof_public_path.$arr_doc['id_proof'] }}" alt="" />
                                                        @elseif(in_array($extention['1'],['pdf','PDF'])) 
                                                             <img src="{{ url('front/images/pdf.jpg') }}" alt="" />
                                                        @else
                                                            <img src="{{ url('front/images/document-logo.jpg') }}" alt="" />
                                                        @endif
                                                    </a>
                                                </div>
                                            @else
                                                This file doesn't exist
                                            @endif
                                    @endif
                                </div>
                                        

                                <div class="col-sm-6 col-md-6 col-lg-12">
                                    Photo : 
                                    @if(isset($arr_doc['photo']) && !empty($arr_doc['photo']))
                                        <?php $extention = explode('.', $arr_doc['photo']); ?>

                                        @if($arr_doc['photo']!=null && file_exists($photo_base_path.$arr_doc['photo']))
                                            <div class="profile-img-block">
                                               <a href="{{ $photo_public_path.$arr_doc['photo'] }}" download>
                                                    @if(in_array($extention['1'],['png','jpg','jpeg']))
                                                        <img src="{{ $photo_public_path.$arr_doc['photo'] }}" alt=""  />
                                                    @elseif(in_array($extention['1'],['pdf','PDF'])) 
                                                        <img src="{{ url('front/images/pdf.jpg')}}" alt="" />
                                                    @else
                                                        <img src="{{ url('front/images/document-logo.jpg')}}" alt="" />
                                                    @endif
                                               </a>
                                            </div>
                                        @else
                                            This file doesn't exist
                                        @endif
                                    @endif
                                </div>

                            @if(empty($arr_doc['id_proof']) && empty($arr_doc['photo']))
                                <div class="content-list-vact"> <p>Sorry, we couldn't find any matches.</p> </div>
                            @endif
                            
                            <div class="change-pass-btn">
                                <a href="{{ url('/') }}/profile" class="login-btn" name="btn_submit" id="btn_submit">Back</a>
                            </div>

                            <div class="clearfix"></div>
                        </div>

                    </div>
             
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
