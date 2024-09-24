<style type="text/css"> /* image upload demo css */
  .upload-pic {border: 2px solid #dddfe2;/*border-radius: 4px;*/height: 120px;width: 120px;}
  .backclass{background-color: black}   
  .loc_add_pht{margin:5px !important} 
   .add_pht {
   background-repeat: no-repeat;
   background-position: top 15px center;
   background-size: 50px;
   border:1px solid #ccc;
   color: #ccc;
   margin:0px;
   padding: 0;
   text-align: center;
   cursor: pointer;
     float: left;
       height: 120px !important;
       overflow: hidden;
   }


    .photo_view1    {          
    overflow:hidden;
    cursor:pointer;
    float: left;
       margin: 4px;
  }

   .overlay1 {  
    background: rgba(0, 0, 0, 0.75);
    bottom: 0;
    left: 0;
    opacity: 0;
    padding: 0;
    position: absolute;
    right: 0;
    text-align: center;
    top: 0;
    transition: opacity 0.25s ease 0s;
  }


  .photo_view1:hover .overlay1 {
    opacity:0.5;
  }

  .plus1 {
    font-family:Helvetica;
    font-weight:900;
    color:rgba(255,255,255,.85);
    font-size:65px;
  }

  .overlay2 {  
    background: rgba(0, 0, 0, 0.75);
    bottom: 0;
    left: 0;
    opacity: 0;
    padding: 0;
    position: absolute;
    right: 0;
    text-align: center;
    top: 0;
    transition: opacity 0.25s ease 0s;
  }


  .photo_view2:hover .overlay2 {
    opacity:0.5;
  }
    .photo_view2{margin: 5px;float: left;height: 120px !important;}

  .plus2 {
    font-family:Helvetica;
    font-weight:900;
    color: rgb(68, 45, 85);
    font-size: 14px;
    background-color: #ccc;
    width: 30px;
    height: 30px;cursor: pointer;
    display: block;
    border-radius: 50%;
    line-height: 30px;
    margin-right: 5px;
    margin-top: 5px;
    float: right;
  }   
</style>

@extends('front.layout.master')                
@section('main_content')
<div id="main"></div>
<div class="clearfix"></div>
<div class="title-common">
   <h1>{{ $page_title or '' }}</h1>
</div>
<form action="{{ $module_url_path }}/update_step2" method="post" name="frm_post_property_step2" enctype="multipart/form-data" id="frm_post_property_step2">
   {{ csrf_field() }}
   <input type="hidden" name="enc_property_id" id="enc_property_id" value="{{ $enc_property_id or '' }}">
   <div class="change-pass-bg padding step">
      <div class="container">
            <div class="transact-deta-back button outer">
                <a href="{{url('/property/edit_step1/'.$enc_property_id)}}" class="bookib-detai-back"> <i class="fa fa-long-arrow-left"></i> Back</a>
            </div>
           <div class="clearfix"></div> 
         <div class="change-pass-bady">
            <div class="row">
               @include('front.layout._operation_status')
               <div id="show_message"></div>
               <div class="clearfix"></div>
                
               <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="update-pro-img-main">
                    <div class="comments-title main topi">Uploaded Images</div> 
                    <?php 
                      if(isset($arr_property['property_images']) && count($arr_property['property_images'])>0)
                       {  $i= 1;?> 
                          <div class="lab_img" id="lab_<?php echo $i; ?>">
                           <div class="col-sm-12 col-lg-12 col-lg-12" style="float:right;">
                              <span>
                                <a href="javascript:void(0);" style="display:none;" >
                                    <span class="glyphicon glyphicon-minus-sign" style="font-size: 20px;"></span>
                                </a>
                                <img>
                              </span>
                           </div>
                           <div class="" id="add_lab_div">
                              <div class="add_pht upload-pic loc_add_pht" id="div_blank" onclick="return addpictures(this)"  style="height: 120px;width: 120px; float: left; "><img src="{{url('/')}}/front/images/plus-img.jpg" alt="user pic"  style="width:100%;height:100%;" /></div>
                              <?php 
                              foreach($arr_property['property_images'] as $images) 
                              { 
                                ?>
                                <input type="hidden" name="ad_images_old[]" value="{{$images['id']}}">
                                <div class='photo_view2' onclick='remove_photos(this);' style='width:120px;height: 120px;position:relative;display: inline-block;' >
                              
                                  @if(isset($images['image']) && file_exists($property_image_base_path.$images['image']))
                                  <img name="uploded_photos[]" class='add_pht' style='float: left; padding: 0px ! important; margin:0 height: 120px;width: 120px;' src="{{$property_image_public_path.$images['image']}}" data-updatePhotoId="<?php echo base64_encode($images['id']); ?>" data-colname="<?php echo $images['image'];?>">                                                
                                  @else
                                  <img class='add_pht' style='float: left; padding: 0px ! important; margin:0 height: 120px;width: 120px;' src="http://192.168.1.6/shareous/front/images/Listing-page-no-image.jpg"> 
                                  @endif

                                   <div class='overlay2'>
                                    <a href="{{ $module_url_path }}/delete_property_image/{{ isset($images['id'])?base64_encode($images['id']):'' }}">
                                    <span class='plus2'>X</span></a>
                                  </div>
                                </div>
                              <?php 
                              } 
                              $i++; 
                              ?>
                              <div class="show_photos" id="show_photos" style="width: auto; display: initial;float: none;"></div>
                              <div id="div_hidden_photo_list" class="div_hidden_photo_list">
                                 <input type="file" name="property_images[]" id="property_images" class="property_images" style="display:none" />
                              </div>
                           </div>
                        </div>
                      <?php  
                      }
                      else 
                      { ?>

                       <div class="lab_img" id="lab_1">
                         <div class="col-sm-12 col-lg-12 col-lg-12" style="float:right;">
                            <span>
                              <a href="javascript:void(0);" id='remove_project' class="remove_project" style="display:none;" >
                                <span class="glyphicon glyphicon-minus-sign" style="font-size: 20px;"></span>
                              </a>
                            </span>
                         </div>
                         <div class="" id="add_lab_div">
                            <div class="add_pht upload-pic loc_add_pht" id="div_blank" onclick="return addpictures(this)"  style="height: 120px;width: 120px; float: left;"> 
                              <img src="{{ url('/') }}/front/images/plus-img-2.jpg" alt="user pic"  style="width:100%;height:100%;" /></div>
                            <div class="show_photos" id="show_photos" style="width: auto; display: initial;float: none;"></div>
                            <div id="div_hidden_photo_list" class="div_hidden_photo_list">
                               <input type="file" name="property_images[]"  id="property_images" class="property_images" style="display:none" />
                            </div>
                         </div>
                      </div>
                       <?php 
                     } 
                    ?>
                   <div class="clearfix"></div>                     
                    <input type="hidden" name="file_name_lab" id="file_name_lab"  >
                  </div> 
                    <div class="clearfix"></div>
                      <span class="badge" style="background-color: red;"><i class="fa fa-exclamation-triangle" style="color: white;"></i> Note: </span> Minimum 3 and maximum 6 jpg | png | jpeg type of images alllowed.<br />
                    <div style="color: red; font-size: 13px;"id="err_other_images"></div>
               </div> 
               <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="price-per-night-blo no-padding">
                  <div class="comments-title main topi">Amenities</div>
                  <div class="row">
                     @if(isset($arr_aminities) && sizeof($arr_aminities)>0)
                     @foreach($arr_aminities as $aminities)
                     <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="user_box1 add">
                           <div class="check-box inline-checkboxs singup-tp top-less">
                              <input id="{{ isset($aminities['aminity_name']) ? $aminities['aminity_name'] : '' }}" value="{{ $aminities['id'] or '' }}" name="aminities[]" class="filled-in aminities-chk" slug="<?php echo strtolower(trim(str_slug($aminities['aminity_name'], '-'))); ?>" type="checkbox" <?php if(count($tempArr)>0){ if(in_array($aminities['id'],$tempArr)) { ?> checked="" <?}}?> />
                              <label for="{{ isset($aminities['aminity_name'])?$aminities['aminity_name']:'' }}">{{ isset($aminities['aminity_name'])?ucwords($aminities['aminity_name']):'' }}</label>
                           </div>
                           <?php 
                              $value            = "";
                              $aminities_style  = "display:none"; 
                              if(in_array($aminities['id'],$tempArr) && str_slug($aminities['aminity_name']) == 'nearest-railway-station')
                              {
                                  $value            = $arr_property['nearest_railway_station'];
                                  $aminities_style  = "display:block"; 
                              }

                              if(in_array($aminities['id'],$tempArr) && str_slug($aminities['aminity_name']) == 'nearest-national-highway')
                              {
                                  $value            = $arr_property['nearest_national_highway'];
                                  $aminities_style  = "display:block"; 
                              }

                              if(in_array($aminities['id'],$tempArr) && str_slug($aminities['aminity_name']) == 'working-days')
                              {
                                  $value            = $arr_property['working_days'];
                                  $aminities_style  = "display:block"; 
                              }
                              if(in_array($aminities['id'],$tempArr) && str_slug($aminities['aminity_name']) == 'working-hours')
                              {
                                  $value            = $arr_property['working_hours'];
                                  $aminities_style  = "display:block"; 
                              }
                              if(in_array($aminities['id'],$tempArr) && str_slug($aminities['aminity_name']) == 'nearest-bus-stop')
                              {
                                  $value            = $arr_property['nearest_bus_stop'];
                                  $aminities_style  = "display:block"; 
                              }
                           ?>
                           <div class="form-group">
                            <input style="{{ $aminities_style }}" type="text" name="<?php echo trim(str_slug($aminities['aminity_name'], '-')); ?>" id="<?php echo trim(str_slug($aminities['aminity_name'], '-')); ?>" value="{{ $value }}"  maxlength="100" data-rule-maxlength="100" placeholder="<?php echo ucwords($aminities['aminity_name']); ?>" >
                            </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="col-sm-6 col-md-6 col-lg-6">
                     {{ 'No Aminities are present' }}
                     </div>
                     @endif
                     <div id="err_aminities"></div>
                     <div class="clearfix"></div>      
                  </div>
                  </div>
               </div>
               <?php
                $property_slug = get_property_type_slug($arr_property['property_type_id']);
               ?>
               <div class="col-sm-12 col-md-12 col-lg-12">
               @if($property_slug != 'warehouse' && $property_slug != 'office-space')
                  <div class="sleeping-srrangement-main margin">
                    <div class="comments-title main">Add Unavailability</div>
                     <div class="clearfix"></div>
                     <div class="row">
                        @if(isset($arr_property['property_unavailability']) && sizeof($arr_property['property_unavailability'])>0)
                          @foreach($arr_property['property_unavailability'] as $key => $unavailability)   
                        <div id="monthly_date_div" class="clone-datepicker-section-edit">
                           <div class="col-sm-6 col-md-6 col-lg-12">
                              <div class="comments-title bed date">Date</div>
                              <div class="row ">
                                 <div class="col-sm-6 col-md-6 col-lg-5">
                                    <div class="comments-title-bed-main">
                                       <div class="form-group mrgin-extra">
                                           <input value="{{ isset($unavailability['from_date'])?date('d-m-Y',strtotime($unavailability['from_date'])):'' }}" name="old_from_date[{{ $unavailability['id'] }}]" type="text" class="datepicker-input from_date" placeholder="From Date" readonly=""/>
                                          <span class="calendar-icon"><i class="fa fa-calendar"></i></span>
                                          <div id="err_from_datepicker" class="error err_from_date"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-sm-6 col-md-6 col-lg-5" id="to-datepicker-div">
                                    <div class="comments-title-bed-main">
                                       <div class="form-group mrgin-extra">
                                          <input  value="{{ isset($unavailability['to_date'])?date('d-m-Y',strtotime($unavailability['to_date'])):'' }}"  name="old_to_date[{{ $unavailability['id'] }}]" type="text"  class="datepicker-input to_date" placeholder="To Date" readonly=""/>
                                          <span class="calendar-icon"><i class="fa fa-calendar"></i></span>
                                          <div id="err_to_datepicker" class="error err_to_date"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- <div class="button-plus-step-2-wraper">
                                    <button type="button" class="button-plus-step-2" onclick="deleteUnavailability('{{ base64_encode($unavailability['id']) }}')" name="btn_remove_unavailibility" id="btn_remove_unavailibility">-</button>
                                 </div> -->
                                 @if($key >0)
                                 <div class="button-plus-step-2-wraper">
                                    <button type="button" class="button-plus-step-2" onclick="deleteUnavailability('{{ base64_encode($unavailability['id']) }}')" name="btn_remove_unavailibility" id="btn_remove_unavailibility">-</button>
                                 </div>
                                 @endif
                              </div>
                           </div>
                        </div>                       
                          @endforeach
                        @endif
                        <div class="minu-pluss-wrapper">  
                        <div id="monthly_date_div" class="clone-datepicker-section" style="">
                           <div class="col-sm-12 col-md-12 col-lg-12" id="first_date_dive">
                              <div class="comments-title bed date">Date</div>
                              <div class="row">
                                 <div class="col-sm-5 col-md-5 col-lg-5">
                                    <div class="comments-title-bed-main date-width">
                                       <div class="form-group mrgin-extra">
                                          <input id="from-datepicker" name="from_date[]" type="text" class="datepicker-input from_date from_date1" placeholder="From Date" readonly=""/>
                                          <span class="calendar-icon"><i class="fa fa-calendar"></i></span>
                                          <div id="err_from_datepicker" class="error err_from_date"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-sm-5 col-md-5 col-lg-5" id="to-datepicker-div">
                                    <div class="comments-title-bed-main date-width">
                                       <div class="form-group mrgin-extra">
                                          <input id="to-datepicker" name="to_date[]" type="text"  class="datepicker-input to_date to_date1" placeholder="To Date" readonly=""/>
                                          <span class="calendar-icon"><i class="fa fa-calendar"></i></span>
                                          <div id="err_to_datepicker" class="error err_to_date"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="button-plus-step-2-wraper">
                                    <button type="button" class="button-plus-step-2" onclick="removeUnavailability()" name="btn_remove_unavailibility" id="btn_remove_unavailibility">-</button>
                                 </div>
                              </div>
                           </div>
                          </div>
                          <div class="button-plus-step-2-wraper twwo">
                             <input type="button" name="btn_add_unavailibility" id="btn_add_unavailibility" value="+"  style="margin-left: -95px;">
                          </div>
                         </div>
                     </div>
                     <input type="hidden" name="index" id="index" value="1">
                     <div id="add-unavailibility-div"></div>
                  </div>
                  <div class="price-per-night-blo no-border" id="add-rule-div">
                     <div class="comments-title main">House Rules</div>
                     <div class="ad-rul-block-main">
                        <div class="form-group">
                           <div class="ad-rul-block">
                              <a onclick="addRules()">Add Rules</a>
                           </div>
                           <input name="house_rules" id="house_rules" type="text" />
                           <label for="house-rules">Enter your House Rules...</label>                           
                           <div id="err_house_rules" class="error"></div>
                           <!--<div class="error">this field is required</div>-->
                        </div>
                        <div id="add-rule-div-id"></div>
                     </div>
                     <div class="clearfix"></div>
                  </div>
                  <div class="price-per-night-blo no-border" id="edit-rule-div" style="display:none">
                     <div class="comments-title main">House Rules</div>
                     <div class="ad-rul-block-main">
                        <div class="form-group">
                           <div class="ad-rul-block">
                              <a onclick="updateRules()">Update Rules</a>
                           </div>
                           <input type="hidden" name="enc_rule_id" id="enc_rule_id">
                           <input name="new_house_rules" id="new_house_rules" type="text" />
                           <label for="house-rules">Enter your House Rules...</label>
                            <a onclick="addRules1()">Add Rules</a>                            
                           <div id="err_house_rules1" class="error"></div>
                           <!--<div class="error">this field is required</div>-->
                        </div>
                        <div id="add-rule-div-id"></div>
                     </div>
                     <div class="clearfix"></div>
                  </div>
                  <div id="property-step2-div">
                     <div class="price-per-night-blo no-padding" id="rules-div">
                        <div class="comments-title main topi">Added Rules</div>
                        <div id="show_rules"></div>
                        @if(isset($arr_property['property_rules']) && sizeof($arr_property['property_rules'])>0)
                          @foreach($arr_property['property_rules'] as $rules)                          
                              <div class="added-rules-main">
                                  <div class="added-rules-text">{{ isset($rules['rules'])?$rules['rules']:'' }}</div>
                                  <div class="added-rules-text-right">
                                      <div class="added-rules-text-right-edit">
                                          <div class="right-edit-pencile">
                                              <a onclick="edit_rules('{{base64_encode($rules['id'])}}')" > <i class="fa fa-pencil"></i></a>

                                          </div>
                                          <div class="right-edit-close">
                                              <div class="contact-left-img" onclick="delete_rules('{{base64_encode($rules['id'])}}')"></div>
                                          </div>
                                          <input type="hidden" name="property_id" id="property_id" value="{{$rules['property_id']}}">
                                      </div>

                                  </div>
                                  <div class="clearfix"></div>
                              </div>
                          @endforeach
                        @else
                              {{ 'No rules are added yet.' }}
                        @endif
                     </div>
                  </div>
               @endif
                  <div class="viewmores-btn-main margin">
                    <!-- <input type="submit" class="viewmores" name="btn_add_property_step2" id="btn_add_property_step2" value="List Your Property"> -->
                    <input type="button" class="viewmores" name="btn_add_property_step2" id="btn_add_property_step2" value="List Your Property">
                  </div>
                  <input type="hidden" name="rule_index_id" id="rule_index_id" value="1">
               </div>
            </div>
         </div>
      </div>
   </div>
</form>
<script>

   $(document).ready(function()
    {
        $('.aminities-chk').on('click' ,function() 
        {
            var slug = $(this).attr('slug'); 
            
            if ($(this).is(':checked') == true && $.trim(slug) == 'nearest-railway-station') 
            {
               $('#'+slug).show();
            }
            else if ($(this).is(':checked') == false && $.trim(slug) == 'nearest-railway-station') 
            {
               $('#'+slug).val('');
               $('#'+slug).hide();
            }

            if ($(this).is(':checked') == true && $.trim(slug) == 'working-hours') 
            {
               $('#'+slug).show();
            }
            else if ($(this).is(':checked') == false && $.trim(slug) == 'working-hours') 
            {
               $('#'+slug).val('');
               $('#'+slug).hide();
            }
            if ($(this).is(':checked') == true && $.trim(slug) == 'working-days') 
            {
               $('#'+slug).show();
            }
            else if ($(this).is(':checked') == false && $.trim(slug) == 'working-days') 
            {
               $('#'+slug).val('');
               $('#'+slug).hide();
            }
            if ($(this).is(':checked') == true && $.trim(slug) == 'nearest-bus-stop') 
            {
               $('#'+slug).show();
            }
            else if ($(this).is(':checked') == false && $.trim(slug) == 'nearest-bus-stop') 
            {
               $('#'+slug).val('');
               $('#'+slug).hide();
            }
            if ($(this).is(':checked') == true && $.trim(slug) == 'nearest-national-highway') 
            {
               $('#'+slug).show();
            }
            else if ($(this).is(':checked') == false && $.trim(slug) == 'nearest-national-highway') 
            {
               $('#'+slug).val('');
               $('#'+slug).hide();
            }

        });
    });
    
  function addpictures(ref)
   {  
        var imagecount = $(".photo_view2").length;
        
        if(imagecount>=6)
        {
           swal('You can select only max 6 images of the property.');
           return false;
        }
        var image_id = $(ref).closest('.lab_img').attr('id');
        var length = $('.lab_img').length;

        var view_photo_cnt = jQuery('#'+image_id).find('.photo_view').length                                
        jQuery('#'+image_id).last().find( ".div_hidden_photo_list" ).last().find( "input[name='property_images[]']:last" ).click(); 
        jQuery('#'+image_id).last().find( ".div_hidden_photo_list" ).last().find( "input[name='property_images[]']:last" ).change(function()
        {
            var fileSize = 0;
            var files = this.files;
            var exist_file = $('#file_name_lab').val();            
            if(exist_file == files[0]['name']) 
            { return false; }
            else 
            {
               $('#file_name_lab').val(files[0]['name']);
               var total_file =  (parseInt(files.length) + parseInt(imagecount));
               if(total_file > 7)
               {
                   swal('You can select only max 6 images of the property.');
                   return false;
               }
               for (var i=0, l=files.length; i<l; i++) 
               {

                  var file = files[i];
                  var prjct_id = image_id.split('_');
                  jQuery('#'+image_id).find('#image'+prjct_id[1]+'_'+(view_photo_cnt+1)).attr('value',files[i]['name']);
                  var img, reader, xhr;
                  img = document.createElement("img");
                  reader = new FileReader();
                  img = new Image();      
                  fileSize = fileSize+files[i].size; 
                  var ext      =   files[i]['name'].split('.').pop();  

                  if(fileSize > 2097152) 
                  {
                    swal('File size must not be more than 2 MB');
                    return false;
                  }
                  else
                  {
                    img.onload = function()
                    {
                        //uploadImage(file);                    
                    }
                  }                                   
                  if(ext=="jpg" || ext=="png" || ext=="gif" || ext=="jpeg" || ext=="JPG" || ext=="PNG" || ext=="JPEG" || ext=="GIF")
                  {                                                
                     img.onload = function()
                     {
                         //uploadImage(file);  
                     }
                  }
                  else
                  {
                     swal('Only jpg, png, gif, jpeg type images are allowed');
                     return false;
                  }
                  reader.onload = (function (theImg) 
                  {
                     var new_files = file;
                     var new_image_id = image_id;
                     return function (evt) 
                     {   
                        theImg.src = evt.target.result;                                
                        /*var html = "<div class='photo_view2' onclick='remove_this(this);' style='width:120px;height:120%;position:relative;display: inline-block;'><img src="+ evt.target.result +" class='add_pht' id='add_pht upload-pic' style='float: left; padding: 0px ! important; margin:0' width='120' height='120'><div class='overlay2'><span class='plus2'>X</span></div></div>";
                        jQuery('#'+image_id).last().find('.show_photos').append(html);
                        jQuery('#'+image_id).last().find('.div_hidden_photo_list').append('<input type="file" name="property_images[]" id="property_images" class="property_images" style="display:none" />');             
                        $('#file_name_lab').val('');*/
                        uploadImage(new_files,theImg.src,new_image_id);
                     };
                  }(img));
                  reader.readAsDataURL(file);     
                };
            }        
        });          
   } 
   function remove_this(elm)
   {
       var token    = '{{ csrf_token() }}'; 
       var data     = new FormData();
       data.append('_token', token);
       data.append('image_id',elm);

       $.ajax({
            url:"{{ $module_url_path }}/remove_property_image",
            method:"POST",
            data:data,
            contentType: false,     
            cache: false,          
            processData:false, 
            beforeSend:showProcessingOverlay(), 
            success:function(response)
            { 
               console.log(response); 
               hideProcessingOverlay();  
               if(response.status!="" && response.status=='success')
               {
                   /*var this_index = jQuery(elm).index();
                   jQuery('.lab_img').find(".div_hidden_photo_list").find("input").eq(this_index).remove();*/
                   $('.photo_v'+elm).remove();
               }
               else{
                //swal(response.msg, "error");
               }
            } 
       });
   } 
</script>

<script type="text/javascript">
   $(function () {
       var date = new Date();
       date.setDate(date.getDate());
       $(".from_date").datepicker({
           todayHighlight: true,
           autoclose: true,
           clearBtn: true,
           startDate: date,
           format:'dd-mm-yyyy'
       }).on('changeDate', function (selected) {
         var minDate = new Date(selected.date.valueOf());
         $('.to_date').datepicker('setStartDate', minDate);
       });
   
       $(".to_date").datepicker({
           todayHighlight: true,
           autoclose: true,
           clearBtn: true,
           startDate: date,
           format:'dd-mm-yyyy'
       }).on('changeDate', function (selected) {
         var maxDate = new Date(selected.date.valueOf());
         $('.from_date').datepicker('setEndDate', maxDate);
       });
       $("#weekly-datepicker").datepicker({
           todayHighlight: true,
           autoclose: true,
           clearBtn: true,
           startDate: date,
           format:'dd-mm-yyyy'
       });

       var date1 = new Date();
       date.setDate(date1.getDate());
       $(".from_date1").datepicker({
           todayHighlight: true,
           autoclose: true,
           clearBtn: true,
           startDate: date,
           format:'dd-mm-yyyy'
       }).on('changeDate', function (selected) {
         var minDate = new Date(selected.date.valueOf());
         $('.to_date1').datepicker('setStartDate', minDate);
       });
   
       $(".to_date1").datepicker({
           todayHighlight: true,
           autoclose: true,
           clearBtn: true,
           startDate: date,
           format:'dd-mm-yyyy'
       }).on('changeDate', function (selected) {
         var maxDate = new Date(selected.date.valueOf());
         $('.from_date1').datepicker('setEndDate', maxDate);
       });
   
   });
   $(document).ready(function () 
   {
       var placeSearch, autocomplete;
       $('#frm_post_property_step2').validate({
           ignore: [],
           errorClass: "error",
           errorElement : 'span',
           errorPlacement: function(error, element) {
               var name = $(element).attr("name");
               if(name === "aminities[]") 
               {
                 error.insertAfter('#err_aminities');
               }
               else if(name === "house_rules[]")
               {
                 error.insertAfter('#err_house_rules');
               }
               else if(name === "from_date")
               {
                 error.insertAfter('#err_from_datepicker');
               }
               else if(name === "to_date")
               {
                 error.insertAfter('#err_to_datepicker');
               }
               else if(name === "weekly_date")
               {
                 error.insertAfter('#err_from_weekly_datepicker');
               }
               else if(name==='unavailability_type')
               {
                 error.insertAfter('#err_unavailblitity_type');
               }
               else
               {
                    error.insertAfter(element);
               }
           }
   
       });
   
   });
   function checkType(ref)
   {
       var type = $(ref).val();
       if(type=='WEEKLY')
       {
           $('#weekly-datepicker').attr('required',true);
           $('#from-datepicker').removeAttr('required',false);
           $('#to-datepicker').removeAttr('required',false);
   
           $('#monthly_date_div').hide();
           $('#weekly_date_div').show();
   
       }
       else if(type=='MONTHLY')
       {
           $('#from-datepicker').attr('required',true);
           $('#to-datepicker').attr('required',true);
           $('#weekly-datepicker').removeAttr('required',true);
   
           $('#monthly_date_div').show();
           $('#weekly_date_div').hide();
       }
   }

    function addRules1()
    {
      $('#edit-rule-div').hide();
      $('#add-rule-div').show();
    }

   function addRules()
   {
      var rule_text   = $('#house_rules').val();
      if(rule_text=="")
      {
           showAlert('Please enter rules.','error');
           return false;
      }

      var property_id = $('#enc_property_id').val();
      var token       = $("input[name='_token']").val();
      var data        = new FormData();

      data.append('_token', token);
      data.append('rules',rule_text);
      data.append('property_id',property_id);
   
      $.ajax({
        url:"{{ $module_url_path }}/add_rules",
        method:"POST",
        data:data,
        contentType: false,     
        cache: false,          
        processData:false, 
        beforeSend:showProcessingOverlay(), 
        success:function(response)
        { 
          hideProcessingOverlay();
          if(response.status=='success')
          {
            var msg = makeStatusMessageHtml('success',response.message);
            $("#show_message").html(msg);
            $("#show_message").focus();        
            $("#house_rules").val('');              
          } else {
            $("#err_house_rules").html(response.message);
            $("#err_house_rules").focus();                      
            $("#house_rules").keyup(function(){$("#err_house_rules").html('');});
          }
          $("#property-step2-div").load(location.href + " #property-step2-div");
        } 
      });
   }  
   function removeRule(index) {
       $('#rules-block-'+index).remove();
   } 
   function makeStatusMessageHtml(status, message)
   {
       str = '<div class="alert alert-'+status+'">'+
       '<a aria-label="close" data-dismiss="alert" class="close" href="#">'+'×</a>'+message+
       '</div>';
       return str;
   }
   function showRules(arr_data)
   {
       html = '';
       for(var i=0;i<arr_data.length;i++)
       {
           html+= '<div class="added-rules-main">';
           html+= '<div class="added-rules-text">'+arr_data[i].rules+'</div>';
           html+= '<div class="added-rules-text-right">';
           html+= '<div class="added-rules-text-right-edit">';
           html+= '<div class="right-edit-pencile"><a href="javascript:void(0)"> <i class="fa fa-pencil"></i></a></div>';
           html+= '<div class="right-edit-close"><div class="contact-left-img"></div></div>';
           html+= '</div></div><div class="clearfix"></div></div>';
       }
       jQuery("#show_rules").append(html);
   
   }
   function edit_rules(enc_id)
   {
       $('#add-rule-div').hide();
       $('#edit-rule-div').show();
       $.ajax({
                       
                  url:"{{ $module_url_path }}/edit_rules/"+enc_id,
                  method:"get",
                  contentType: false,     
                  cache: false,          
                  processData:false, 
                  beforeSend:showProcessingOverlay(), 
                  success:function(response)
                  { 
                      hideProcessingOverlay();
                       if(response.rule!="")
                       {
                           $('#enc_rule_id').val(enc_id);
                           $('#new_house_rules').parent().parent().find('.form-group ').addClass('active');
                           $('#new_house_rules').val(response.rule);
                       }
                  } 
             });     
   }
   function updateRules()
   {
       var enc_rule_id = $('#enc_rule_id').val();
       var rules       = $('#new_house_rules').val();
       var token       = $("input[name='_token']").val(); 
       var property_id = $("#property_id").val();
   
       var data        = new FormData();
       data.append('_token', token);
       data.append('enc_rule_id',enc_rule_id);
       data.append('rules',rules);
       data.append('property_id',property_id);       
       console.log(data);
      
         $.ajax({
                       
                  url:"{{ $module_url_path }}/update_rules",
                  method:"POST",
                  data:data,
                  contentType: false,     
                  cache: false,          
                  processData:false, 
                  beforeSend:showProcessingOverlay(), 
                  success:function(response)
                  {                     
                    hideProcessingOverlay();
                    console.log(response);
                    if(response.status=='error')
                    {
                      $("#err_house_rules1").html(response.message);
                      $("#err_house_rules1").focus();                      
                      $("#new_house_rules").click(function(){$("#err_house_rules1").html('');});
                    }
                    $("#property-step2-div").load(location.href + " #property-step2-div");
                  } 
             });     
   }
   function delete_rules(enc_id)
   {
       swal({
         title: "Are you sure?",
         text: "You want to delete this rule",
         type: "warning",
         showCancelButton: true,
         confirmButtonClass: "btn-danger",
         confirmButtonText: "Yes, delete it!",
         cancelButtonText: "No, cancel!",
         closeOnConfirm: true,
         closeOnCancel: true
       },
       function(isConfirm) {
         if (isConfirm) {
             
               if(enc_id!="")
               {
                  var site_url = "{{ $module_url_path }}/delete_rules/"+enc_id;
                  $.ajax({
                               
                          url:site_url,
                          method:"get",
                          contentType: false,     
                          cache: false,          
                          processData:false, 
                          beforeSend:showProcessingOverlay(), 
                          success:function(response)
                          { 
                              hideProcessingOverlay();
                              $("#property-step2-div").load(location.href + " #property-step2-div");
                          } 
                  });    
               }
         } 
       });
   
     
   }

   $("#btn_add_property_step2").click(function()
   {
      var flag = 1;
      var imagecount = $(".photo_view2").length;
      var date = new Date();     
      date.setDate(date.getDate());
      var temp_arr = [];
      var temp_from_arr = [];
      var temp_to_arr = []; 
      if (imagecount < 3) 
      {
        $('#err_other_images').html("Please select at least 3 images");
        $(".add_pht").focus();
        $(".add_pht").change(function() 
        {
            //$("#err_other_image").html('');
        });
        flag = 0;
      }
      if(imagecount>6)
      {
        $('#err_other_images').html('Please select min 6 images of the property.');
        $(".add_pht").focus();
        $(".add_pht").change(function(){/*$("#err_other_image").html('');*/});
        flag = 0;
      }
       
      $(".clone-datepicker-section").each(function(){
       var from_date  =  $(this).find('.from_date').val();
       var to_date   =  $(this).find('.to_date').val();

       if ($.trim(from_date) == '' && $.trim(to_date) != '') {
           $(this).last().find('.err_from_date').show().html('Please select from date.');
           $(this).find('.from_date').on('click',function(){ $(this).find('.err_from_date').html("");});            
           flag = 0;
       } else {
           $(this).last().find('.err_from_date').hide().html('');
       }
       
       if ($.trim(from_date) != '' && $.trim(to_date)=='') {
           $(this).last().find('.err_to_date').show().html('Please select to date.');
           $(this).find('.to_date').on('click',function(){ $(this).find('.err_to_date').html("");});
           flag = 0;
       } else {
           $(this).last().find('.err_to_date').hide().html('');
       }
       
       if ($.trim(from_date)!='' && $.trim(to_date)!='') {
          if ($.inArray($.trim(from_date)+' - '+$.trim(to_date), temp_arr)=='-1' && $.inArray($.trim(from_date), temp_from_arr)=='-1' && $.inArray($.trim(to_date), temp_to_arr)=='-1') {
            temp_arr.push($.trim(from_date)+' - '+$.trim(to_date));
            temp_from_arr.push($.trim(from_date));
            temp_to_arr.push($.trim(to_date));

            $(this).last().find('.err_to_date').hide().html('');
            $(this).last().find('.err_from_date').hide().html('');
          } else {
            if ($.inArray($.trim(from_date), temp_from_arr)=='-1') {
              $(this).last().find('.err_to_date').show().html('You have already selected same to date.');
              $(this).find('.to_date').on('click',function(){ $(this).find('.err_to_date').html("");});          
            } else if($.inArray($.trim(to_date), temp_to_arr)=='-1') {
              $(this).last().find('.err_from_date').show().html('You have already selected same from date.');
              $(this).find('.from_date').on('click',function(){ $(this).find('.err_from_date').html("");});      
            } else {
              $(this).last().find('.err_from_date').show().html('You have already selected same date.');
              $(this).find('.from_date').on('click',function(){ $(this).find('.err_from_date').html("");});

              $(this).last().find('.err_to_date').show().html('You have already selected same date.');
              $(this).find('.to_date').on('click',function(){ $(this).find('.err_to_date').html("");});  
            }
            flag = 0;
          }
        }
      });

      if (flag == 1) {

          if( $("[name='aminities[]']:checked").length == 0 ) {
              swal({
                title: "",
                text: "Do you want to proceed without selecting amenties?",
                //type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-cancel",
                confirmButtonText: "Yes",
                confirmButtonColor: "#ff4747",
                closeOnConfirm: false
              },
              function(){
                $('#frm_post_property_step2').submit();
              });
          } else {
            $('#frm_post_property_step2').submit();
          }

      } else {
           return false;
      }
   });


   function uploadImage(file,result,image_id)
   {      

          var file_data       = file; //$("#property_images").prop("files")[0];

          var enc_property_id = $("#enc_property_id").val();
          var token           = '{{ csrf_token() }}'; 
          var data            = new FormData();
          data.append('_token', token);
          data.append('property_image',file_data);
          data.append('enc_property_id',enc_property_id);

          
          $.ajax({
                url:"{{ $module_url_path }}/upload_property_image",
                method:"POST",
                data:data,
                contentType: false,     
                cache: false,          
                processData:false, 
                beforeSend:showProcessingOverlay(), 
                success:function(response)
                { 
                   hideProcessingOverlay();  
                   if(response.status!="" && response.status=='success')
                   {
                        console.log(response);
                        var html = "<div class='photo_view2 photo_v"+response.insert_id+" ' onclick='remove_this("+response.insert_id+");' style='width:120px;height:120%;position:relative;display: inline-block;'><img src="+ result +" class='add_pht' id='add_pht upload-pic' style='float: left; padding: 0px ! important; margin:0' width='120' height='120'><div class='overlay2'><span class='plus2'>X</span></div></div>";
                        jQuery('#'+image_id).last().find('.show_photos').append(html);
                        jQuery('#'+image_id).last().find('.div_hidden_photo_list').append('<input type="file" name="property_images[]" id="property_images" class="property_images" style="display:none" />');             
                        $('#file_name_lab').val('');
                        //swal(response.msg, "success");
                        //location.reload();
                        //$("#property-upload-image-div").load(location.href + "#property-upload-image-div");
                   }
                   else{
                    //swal(response.msg, "error");
                   }
                } 
           });
   }

   $("#btn_add_unavailibility").on('click',function(){

     if($('.clone-datepicker-section').length == 1 && $('.clone-datepicker-section:first').attr('style','display') == 'none'){
        $('.clone-datepicker-section:first').css('display','block'); 
        return false;
     }
   
     var date = new Date();      
     date.setDate(date.getDate());
     var temp_arr = [];
     var temp_from_arr = [];
     var temp_to_arr = [];
     var flag = 1;
     $(".clone-datepicker-section").each(function(){
       var from_date  =  $(this).find('.from_date').val();
       var to_date   =  $(this).find('.to_date').val();
       if($.trim(from_date)=='')
       {
           $(this).last().find('.err_from_date').show().html('Please select from date.');
           $(this).find('.from_date').on('click',function(){ $(this).find('.err_from_date').html("");});            
           flag = 0;
       }
       else
       {
           $(this).last().find('.err_from_date').hide().html('');
       }
       
       if($.trim(to_date)=='')
       {
           $(this).last().find('.err_to_date').show().html('Please select to date.');
           $(this).find('.to_date').on('click',function(){ $(this).find('.err_to_date').html("");});
           flag = 0;
       }
       else
       {
           $(this).last().find('.err_to_date').hide().html('');
       }
       

       if($.trim(from_date)!='' && $.trim(to_date)!='')
       {
          if($.inArray($.trim(from_date)+' - '+$.trim(to_date), temp_arr)=='-1' && $.inArray($.trim(from_date), temp_from_arr)=='-1' && $.inArray($.trim(to_date), temp_to_arr)=='-1')
          {
            temp_arr.push($.trim(from_date)+' - '+$.trim(to_date));
            temp_from_arr.push($.trim(from_date));
            temp_to_arr.push($.trim(to_date));

            $(this).last().find('.err_to_date').hide().html('');
            $(this).last().find('.err_from_date').hide().html('');
          }          
          else
          {

            if($.inArray($.trim(from_date), temp_from_arr)=='-1')
            {
              $(this).last().find('.err_to_date').show().html('You have already selected same to date.');
              $(this).find('.to_date').on('click',function(){ $(this).find('.err_to_date').html("");});          
            }
            else if($.inArray($.trim(to_date), temp_to_arr)=='-1')
            {
              $(this).last().find('.err_from_date').show().html('You have already selected same from date.');
              $(this).find('.from_date').on('click',function(){ $(this).find('.err_from_date').html("");});      
            }
            else
            {
              $(this).last().find('.err_from_date').show().html('You have already selected same date.');
              $(this).find('.from_date').on('click',function(){ $(this).find('.err_from_date').html("");});                       

              $(this).last().find('.err_to_date').show().html('You have already selected same date.');
              $(this).find('.to_date').on('click',function(){ $(this).find('.err_to_date').html("");});  
            }
            
            flag = 0;
          }
       }
   
     });
   
       if(flag==1)
       {            
           $('.clone-datepicker-section').last().clone().insertAfter($('.clone-datepicker-section').last());
           $('.clone-datepicker-section').last().find('input').val('');
   
           $('.clone-datepicker-section').last().find('.comments-title').html('');
   
   
           $('.clone-datepicker-section').last().find(".from_date").datepicker({
                   todayHighlight: true,
                   autoclose: true,
                   clearBtn: true,
                   startDate: date,
                   format:'dd-mm-yyyy'
               }).on('changeDate', function (selected) {
                 var minDate = new Date(selected.date.valueOf());
                 $('.clone-datepicker-section').last().find('.to_date').datepicker('setStartDate', minDate);
               });
   
           $('.clone-datepicker-section').last().find(".to_date").datepicker({
                   todayHighlight: true,
                   autoclose: true,
                   clearBtn: true,
                   startDate: date,
                   format:'dd-mm-yyyy'
               }).on('changeDate', function (selected) {
                 var maxDate = new Date(selected.date.valueOf());
                 $('.clone-datepicker-section').last().find('.from_date').datepicker('setEndDate', maxDate);
               });
      } else {
        return false;
      }
   });

  function removeUnavailability() {
    if($('.clone-datepicker-section').length == 1) {
      $('.clone-datepicker-section:first').css('display','none');
      $('#from-datepicker:first').val('');
      $('#to-datepicker:first').val('');
      $('#err_from_datepicker:first').html('');
      $('#err_to_datepicker:first').html('');
    } else {
      $('.clone-datepicker-section').last().remove();
    }
  }

  function deleteUnavailability(id) {
    location.href='{{ $module_url_path }}/delete_unavailbility/'+id;
  }

</script>
@endsection

