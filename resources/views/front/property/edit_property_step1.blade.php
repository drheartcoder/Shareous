@extends('front.layout.master')                
@section('main_content')

<div id="main"></div>
<div class="clearfix"></div>
<div class="title-common">
    <h1>{{ $page_title or '' }}</h1>
</div>

<div id="property-step-1">
    <form action="{{ $module_url_path }}/update_step1" method="post" name="frm_edit_property_step1" enctype="multipart/form-data" id="frm_edit_property_step1">
        {{ csrf_field() }}
        <input type="hidden" name="enc_property_id" id="enc_property_id" value="{{ $enc_property_id or '' }}">
        <div class="change-pass-bg padding">
            <div class="container">
                <div class="transact-deta-back button outer">
                <a href="{{url('/property/listing')}}" class="bookib-detai-back"> <i class="fa fa-long-arrow-left"></i> Cancel</a>
                </div>    
                <div class="clearfix"></div> 
                <div class="change-pass-bady">
                    <div class="add-listing-one-head">{{ $page_title or '' }}</div>
                    <div class="row">
                        @include('front.layout._operation_status')
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <div class="select-style">                                
                                    <select id="property_type"  name="property_type">
                                        <option value="">Select Property Type</option>
                                        @if(isset($arr_property_type) && sizeof($arr_property_type)>0)
                                            @foreach($arr_property_type as $property_type)
                                                <option @if(isset($arr_property['property_type_id']) && $arr_property['property_type_id'] == $property_type['id']) selected @endif value="{{ $property_type['id'] or '' }}" slug="<?php echo strtolower(trim(str_slug($property_type['name'], '-'))); ?>">{{ $property_type['name'] or '' }}</option>
                                            @endforeach
                                        @endif
                                    </select>    
                                    <div class="clearfix"></div>                         
                                    <span class="error" id="err_property_type">{{ $errors->first('property_category') }}</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <input type="text" value="{{ isset($arr_property['property_name']) ? $arr_property['property_name'] : '' }}" data-rule-maxlength="250" name="property_name" id="property_name" maxlength="250" />
                                <label for="property">Your Property Name</label>
                                <span class="error" id="err_property_name">{{ $errors->first('property_name') }}</span>
                            </div>

                            <div class="form-group">
                                <textarea rows="2" data-rule-maxlength="1000" class="text-area" name="description" id="description">{{ isset($arr_property['description']) ? $arr_property['description'] : '' }}</textarea>
                                <label for="description">Description</label>
                                <span class="error" id="err_description">{{ $errors->first('description') }}</span>
                            </div>
                        </div>

                    <?php
                        $no_of_guest_div             = 'display:none;';
                        $no_of_bedrooms_div          = 'display:none;';
                        $no_of_beds_div              = 'display:none;';
                        $bathrooms_div               = 'display:none;';
                        $price_per_night_div         = 'display:none;';
                        $property_working_status_div = 'display:none;';
                        $property_area_div           = 'display:none;';
                        $total_build_area_div        = 'display:none;';
                        $custom_type_div             = 'display:none;';
                        $management_div              = 'display:none;';
                        $good_storage_div            = 'display:none;';
                        $admin_area_div              = 'display:none;';
                        $custom_type_div             = 'display:none;';
                        $build_type_div              = 'display:none;';
                        $property_remark_div         = 'display:none;';
                        $price_per_sqft_div          = 'display:none;';
                        $price_per_office_div        = 'display:none;';
                        $price_per_div               = 'display:none;';
                        $no_of_slots_div             = 'display:none;';
                        $no_of_employee_div          = 'display:none;';

                        if(get_property_type_slug($arr_property['property_type_id']) == 'warehouse')
                        {
                            $property_working_status_div = 'display:block;';
                            $property_area_div           = 'display:block;';
                            $total_build_area_div        = 'display:block;';
                            $custom_type_div             = 'display:block;';
                            $management_div              = 'display:block;';
                            $good_storage_div            = 'display:block;';
                            $admin_area_div              = 'display:block;';
                            $build_type_div              = 'display:block;';
                            $property_remark_div         = 'display:block;';
                            $custom_type_div             = 'display:block;';
                            $price_per_sqft_div          = 'display:block;';
                            $no_of_slots_div             = 'display:block;';
                            $no_of_slots_div             = 'display:block;';
                        }
                        else if(get_property_type_slug($arr_property['property_type_id']) == 'office-space')
                        {
                            $property_working_status_div = 'display:block;';
                            $property_area_div           = 'display:block;';
                            $total_build_area_div        = 'display:block;';
                            $admin_area_div              = 'display:block;';
                            $build_type_div              = 'display:block;';
                            $property_remark_div         = 'display:block;';
                            $price_per_office_div        = 'display:block;';
                            $price_per_div               = 'display:block;';
                            $no_of_employee_div          = 'display:block;';
                        }
                        else
                        {
                            $no_of_guest_div     = 'display:block;';
                            $no_of_bedrooms_div  = 'display:block;';
                            $no_of_beds_div      = 'display:block;';
                            $bathrooms_div       = 'display:block;';
                            $price_per_night_div = 'display:block;';
                        }
                    ?>

                    <div class="col-sm-6 col-md-6 col-lg-6" id="no_of_guest_div" style="{{$no_of_guest_div}}">
                        <div class="form-group">
                            <input  type="text" name="no_of_guest" id="no_of_guest" value="{{$arr_property['number_of_guest']}}" data-rule-maxlength="6" data-rule-max="999999" data-msg-maxlength="Please enter no more than 6 digits." maxlength="6" data-rule-number="true"/>
                            <label for="property">No Of Guest</label>
                            <span class="error" id="err_no_of_guest">{{ $errors->first('no_of_guest') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6" id="no_of_bedrooms_div" style="{{$no_of_bedrooms_div}}">
                        <div class="form-group">
                            <input type="text" onkeyup="addSleepingArrangement(this)" name="no_of_bedrooms" id="no_of_bedrooms" value="{{$arr_property['number_of_bedrooms']}}" data-rule-maxlength="2" data-rule-max="10" data-msg-maxlength="Please enter no more than 2 digits." maxlength="2" data-rule-number="true" />
                            <label for="property">No Of Bedrooms</label>
                            <span class="error" id="err_no_of_bedrooms">{{ $errors->first('no_of_bedrooms') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6" id="bathrooms_div" style="{{$bathrooms_div}}">
                        <div class="form-group">
                            <input  type="text" name="bathrooms" id="bathrooms" value="{{$arr_property['number_of_bathrooms']}}"  data-rule-maxlength="6" data-rule-max="999999" data-msg-maxlength="Please enter no more than 6 digits." maxlength="6" data-rule-number="true" />
                            <label for="property">No Of Bathrooms</label>
                            <span class="error" id="err_bathrooms">{{ $errors->first('bathrooms') }}</span>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-md-6 col-lg-6" id="no_of_beds_div" style="{{$no_of_beds_div}}">
                        <div class="form-group">
                            <input  type="text" name="no_of_beds" id="no_of_beds" value="{{$arr_property['number_of_beds']}}"  data-rule-maxlength="6" data-rule-max="999999" data-msg-maxlength="Please enter no more than 6 digits." maxlength="6" data-rule-number="true" />
                            <label for="property">No Of Beds</label>
                            <span class="error" id="err_no_of_beds">{{ $errors->first('no_of_beds') }}</span>
                        </div>
                    </div>  
                      <div class="col-sm-6 col-md-6 col-lg-6" id="property_working_status_div" style="{{$property_working_status_div}}">
                     <div class="form-group quetio">
                        <div class="select-style">
                           <select id="property_working_status" name="property_working_status">
                              <option value="">Select Property status</option>
                              <option value="open" @if($arr_property['property_working_status'] == 'open') selected="" @endif>Open</option>
                              <option value="closed" @if($arr_property['property_working_status'] == 'closed') selected="" @endif>Closed</option>
                           </select>
                            <div class="clearfix"></div>
                           <span class="error" id="err_property_working_status">{{ $errors->first('property_working_status') }}</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="property_area_div" style="{{$property_area_div}}"> 
                     <div class="form-group quetio">
                        <input  type="text" name="property_area" id="property_area" value="{{$arr_property['property_area']}}" maxlength="10" data-rule-number="true" data-rule-maxlength="10" />
                        <label for="property">Total Area in Sq.Ft</label>
                        <span class="error" id="err_property_area">{{ $errors->first('property_area') }}</span>
                     </div>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="total_build_area_div" style="{{$total_build_area_div}}"> 
                     <div class="form-group quetio">
                        <input  type="text" name="total_build_area" id="total_build_area"  value="{{$arr_property['total_build_area']}}"   maxlength="10" data-rule-number="true" data-rule-maxlength="10"/>
                        <label for="property">Total Build Up Area</label>
                        <span class="error" id="err_total_build_area">{{ $errors->first('total_build_area') }}</span>
                     </div>
                  </div>
                  
                     
                    <div class="col-sm-6 col-md-6 col-lg-6" id="admin_area_div" style="{{$admin_area_div}}"> 
                         <div class="form-group quetio admin-area">
                            <input  type="text" name="admin_area" id="admin_area" value="{{$arr_property['admin_area']}}" maxlength="10" data-rule-number="true" data-rule-maxlength="10"/>
                            <label for="property">Admin Area</label>
                            <span class="error" id="err_admin_area">{{ $errors->first('admin_area') }}</span>
                         </div>
                      </div>
                      <div class="col-sm-6 col-md-6 col-lg-6" id="no_of_slots_div" style="{{$no_of_slots_div}}"> 
                         <div class="form-group quetio">
                            <input  type="text" name="no_of_slots" id="no_of_slots" value="{{$arr_property['no_of_slots']}}"  maxlength="10" data-rule-number="true" data-rule-maxlength="10"/>
                            <label for="property">No.Of Slots</label>
                            <span class="error" id="err_no_of_slots">{{ $errors->first('no_of_slots') }}</span>
                         </div>
                      </div>
                 
                    <div class="clearfix"></div>

                    <div class="col-sm-6 col-md-6 col-lg-6" id="custom_type_div" style="{{$custom_type_div}}">
                    <div class="form-group">
                        <div class="genders">Custom Type : </div>
                        <div class="radio-btns">
                            <div class="radio-btn">
                                <input id="bonded" name="custom_type" class="custom_type" type="radio" value="bonded"  @if($arr_property['custom_type'] == 'bonded') checked="" @endif>
                                <label for="bonded">Bonded</label>
                                <div class="check"></div>
                            </div>

                            <div class="radio-btn">
                                <input id="non-bonded" name="custom_type" class="custom_type" type="radio" value="non-bonded" @if($arr_property['custom_type'] == 'non-bonded') checked="" @endif>
                                <label for="non-bonded" >Non Bonded</label>
                                <div class="check">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <span class="error" id="err_custom_type"></span>
                    </div>
                  </div>
                   <div class="col-sm-6 col-md-6 col-lg-6" id="management_div" style="{{$management_div}}">
                    <div class="form-group">
                        <div class="genders">Management : </div>
                        <div class="radio-btns">
                            <div class="radio-btn">
                                <input id="yes" name="management" class="management" type="radio" value="yes"  @if($arr_property['management'] == 'yes') checked="" @endif>
                                <label for="yes" >Yes</label>
                                <div class="check"></div>
                            </div>

                            <div class="radio-btn">
                                <input id="no" name="management" class="management" type="radio" value="no" @if($arr_property['management'] == 'no') checked="" @endif>
                                <label for="no">No</label>
                                <div class="check">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <span class="error" id="err_management"></span>
                    </div>
                </div>
                  <div class="col-sm-6 col-md-6 col-lg-6" id="good_storage_div" style="{{$good_storage_div}}"> 
                     <div class="form-group quetio">
                        <input  type="text" name="good_storage" id="good_storage"  value="{{$arr_property['good_storage']}}" data-rule-maxlength="50" maxlength="50"/>
                        <label for="property">Available good storage</label>
                        <span class="error" id="err_good_storage">{{ $errors->first('good_storage') }}</span>
                     </div>
                  </div>
                    <div class="col-sm-6 col-md-6 col-lg-6" id="build_type_div" style="{{$build_type_div}}">
                     <div class="form-group quetio">
                        <div class="select-style">
                           <select id="build_type" name="build_type">
                              <option value="">Build Type</option>
                              <option value="RCC" @if($arr_property['build_type'] == 'RCC') selected="" @endif>RCC</option>
                              <option value="PEB" @if($arr_property['build_type'] == 'PEB') selected="" @endif>PEB</option>
                              <option value="Shed" @if($arr_property['build_type'] == 'Shed') selected="" @endif>Shed</option>
                              <option value="Open" @if($arr_property['build_type'] == 'Open') selected="" @endif>Open</option>
                              <option value="Closed" @if($arr_property['build_type'] == 'Closed') selected="" @endif>closed</option>
                           </select>
                           <div class="clearfix"></div>
                           <span class="error" id="err_build_type">{{ $errors->first('build_type') }}</span>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-12" id="property_remark_div" style="{{ $property_remark_div }}"> 
                     <div class="form-group quetio">
                        <textarea name="property_remark" id="property_remark">{{ $arr_property['property_remark'] }}</textarea>  
                        <label for="property">Remarks</label>
                        <span class="error">{{ $errors->first('property_remark') }}</span>
                     </div>
                  </div>

                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div id="sleeping-arrangement-div" class="prop-step-respo">
                            @if(isset($arr_property['property_bed_arrangment']) && sizeof($arr_property['property_bed_arrangment'])>0)
                            <div class="sleeping-srrangement-main" id="no-of-bedroom-div">

                                <div class="comments-title main">Sleeping Arrangement</div>
                                <div class="comments-title-small" ><span id="no-of-bedroom"></span> Bedroom</div>
                                <div class="clearfix"></div>
                                <div class="addbeds-min-block">
                                    <div class="row">
                                        <?php
                                            $get_sleeping_arrangement = [];
                                            $get_sleeping_arrangement = get_sleeping_arrangement();
                                        ?>

                                        @foreach($arr_property['property_bed_arrangment'] as $property_bed_arrangment)

                                            <div class="col-sm-6 col-md-3 col-lg-3">
                                                <div class="comments-title-bed-main">
                                                    <div class="comments-title bed">Double</div>
                                                    <div class="form-group">
                                                        <div class="select-style">
                                                            <select id="double_bed" name="old_double_bed[{{ $property_bed_arrangment['id'] }}]">
                                                                @if(isset($get_sleeping_arrangement) && count($get_sleeping_arrangement)>0)
                                                                    @foreach($get_sleeping_arrangement as $row)
                                                                        <option @if(isset($property_bed_arrangment['double_bed']) && $property_bed_arrangment['double_bed'] == $row['id']) selected="" @endif value="{{ $row['id'] }}">{{ ucwords(get_sleeping_arrangment_name($row['id'])) }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-3 col-lg-3">
                                                <div class="comments-title-bed-main">
                                                    <div class="comments-title bed">Single</div>
                                                    <div class="form-group">
                                                        <div class="select-style">
                                                            <select id="single_bed" name="old_single_bed[{{ $property_bed_arrangment['id'] }}]">
                                                                @if(isset($get_sleeping_arrangement) && count($get_sleeping_arrangement)>0)
                                                                    @foreach($get_sleeping_arrangement as $row)
                                                                        <option @if(isset($property_bed_arrangment['single_bed']) && $property_bed_arrangment['single_bed'] == $row['id']) selected="" @endif value="{{ $row['id'] }}">{{ ucwords(get_sleeping_arrangment_name($row['id'])) }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-3 col-lg-3">
                                                <div class="comments-title-bed-main">
                                                    <div class="comments-title bed">Queen</div>
                                                    <div class="form-group">
                                                        <div class="select-style">
                                                            <select id="queen_bed" name="old_queen_bed[{{ $property_bed_arrangment['id'] }}]">
                                                                @if(isset($get_sleeping_arrangement) && count($get_sleeping_arrangement)>0)
                                                                    @foreach($get_sleeping_arrangement as $row)
                                                                        <option @if(isset($property_bed_arrangment['queen_bed']) && $property_bed_arrangment['queen_bed'] == $row['id']) selected="" @endif value="{{ $row['id'] }}">{{ ucwords(get_sleeping_arrangment_name($row['id'])) }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-3 col-lg-3">
                                                <div class="comments-title-bed-main">
                                                    <div class="comments-title bed">Sofa Bed</div>
                                                    <div class="form-group">
                                                        <div class="select-style">
                                                            <select id="sofa_bed" name="old_sofa_bed[{{ $property_bed_arrangment['id'] }}]">
                                                                @if(isset($get_sleeping_arrangement) && count($get_sleeping_arrangement)>0)
                                                                    @foreach($get_sleeping_arrangement as $row)
                                                                        <option @if(isset($property_bed_arrangment['sofa_bed']) && $property_bed_arrangment['sofa_bed'] == $row['id']) selected="" @endif value="{{ $row['id'] }}">{{ ucwords(get_sleeping_arrangment_name($row['id'])) }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            <input type="hidden" name="total_cnt" id="total_cnt" value="{{ count($arr_property['property_bed_arrangment']) }}">
                            <input type="hidden" name="temp_cnt" id="temp_cnt" value="">
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="step-three-filds">
                                    <div class="row">
                                        
                                        <div class="col-sm-4 col-md-4 col-lg-6">
                                            <div class="form-group">
                                                <div class="select-style">
                                                    <select id="currency" name="currency">
                                                        <option value="">Select Currency</option>
                                                        @if(isset($currency_list) && sizeof($currency_list)>0)
                                                            @foreach($currency_list as $currency)
                                                                <option @if($arr_property['currency'] == $currency['currency']) selected="" @endif value="{{ $currency['id'] or '' }}">{{ $currency['currency_code'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="error" id="err_currency">{{ $errors->first('service_charge') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-md-4 col-lg-6" id="price_per_night_div" style="{{ $price_per_night_div }}"> 
                                            <div class="price-per-night-blo">
                                                <div class="form-group">
                                                    <input  name="price" value="{{ isset($arr_property['price_per_night']) ? $arr_property['price_per_night'] : '' }}" maxlength="10" data-rule-number="true" data-rule-maxlength="10" id="price" type="text" />
                                                    <label for="property">Price Per Night</label>
                                                  <span class="error" id="err_price">{{ $errors->first('price') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-md-4 col-lg-6" id="price_per_sqft_div" style="{{ $price_per_sqft_div }}">
                                            <div class="price-per-night-blo">
                                               <div class="form-group">
                                                  <input  name="price_per_sqft" value="{{ isset($arr_property['price_per_sqft']) ? $arr_property['price_per_sqft'] : '' }}" id="price_per_sqft" type="text"   maxlength="10" data-rule-number="true" data-rule-maxlength="10"/>
                                                  <label for="property">Price Per Sq.ft</label>
                                                   <span class="error" id="err_price_per_sqft">{{ $errors->first('price_per_sqft') }}</span>
                                               </div>
                                            </div>
                                        </div>

                                        <?php
                                        $room = isset($arr_property['room']) ? $arr_property['room'] : '';
                                        $desk = isset($arr_property['desk']) ? $arr_property['desk'] : '';
                                        $cubicles = isset($arr_property['cubicles']) ? $arr_property['cubicles'] : '';

                                        $no_of_room = isset($arr_property['no_of_room']) ? $arr_property['no_of_room'] : '';
                                        $no_of_desk = isset($arr_property['no_of_desk']) ? $arr_property['no_of_desk'] : '';
                                        $no_of_cubicles = isset($arr_property['no_of_cubicles'])?$arr_property['no_of_cubicles']:'';

                                        $room_price = isset($arr_property['room_price']) ? $arr_property['room_price'] : '';
                                        $desk_price = isset($arr_property['desk_price']) ? $arr_property['desk_price'] : '';
                                        $cubicles_price = isset($arr_property['cubicles_price'])?$arr_property['cubicles_price']:'';
                                        ?>

                                        <div class="col-sm-12 col-md-12 col-lg-12" id="price_per_div" style="{{ $price_per_div }}">

                                            <div class="col-sm-4 col-md-4 col-lg-4">
                                                <div class="user_box1 add">
                                                    <div class="check-box inline-checkboxs">
                                                        <input id="office_private_room" name="office_private_room" class="filled-in aminities-chk" type="checkbox" @if( $room == 'on' ) checked @endif />
                                                        <label for="office_private_room">Private Room</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-md-4 col-lg-4">
                                                <div class="user_box1 add">
                                                    <div class="check-box inline-checkboxs">
                                                        <input id="office_dedicated_desk" name="office_dedicated_desk" class="filled-in aminities-chk" type="checkbox" @if( $desk == 'on' ) checked @endif />
                                                        <label for="office_dedicated_desk">Dedicated Desk</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-md-4 col-lg-4">
                                                <div class="user_box1 add">
                                                    <div class="check-box inline-checkboxs">
                                                        <input id="office_cubicles" name="office_cubicles" class="filled-in aminities-chk" type="checkbox" @if( $cubicles == 'on' ) checked @endif />
                                                        <label for="office_cubicles">Cubicles</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="col-sm-4 col-md-4 col-lg-4">
                                                <div class="price-per-night-blo">
                                                   <div class="form-group">
                                                        <input name="no_of_room" value="{{ $no_of_room }}" true" min=1 id="no_of_room" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                        <label for="no_of_room">No. Of Room</label>
                                                        <span class="error" id="err_no_of_room">{{ $errors->first('no_of_room') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-md-4 col-lg-4">
                                                <div class="price-per-night-blo">
                                                   <div class="form-group">
                                                        <input name="no_of_desk" value="{{ $no_of_desk }}" true" min=1 id="no_of_desk" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                        <label for="no_of_desk">No. Of Desk</label>
                                                        <span class="error" id="err_no_of_desk">{{ $errors->first('no_of_desk') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-md-4 col-lg-4">
                                                <div class="price-per-night-blo">
                                                   <div class="form-group">
                                                        <input name="no_of_cubicles" value="{{ $no_of_cubicles }}" true" min=1 id="no_of_cubicles" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                        <label for="no_of_cubicles">No. Of Cubicles</label>
                                                        <span class="error" id="err_no_of_cubicles">{{ $errors->first('no_of_cubicles') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>

                                            <div class="col-sm-4 col-md-4 col-lg-4">
                                                <div class="price-per-night-blo">
                                                   <div class="form-group">
                                                        <input name="room_price" value="{{ $room_price }}" true" min=1 id="room_price" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                        <label for="no_of_room">Price per Room</label>
                                                        <span class="error" id="err_room_price">{{ $errors->first('room_price') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-md-4 col-lg-4">
                                                <div class="price-per-night-blo">
                                                   <div class="form-group">
                                                        <input name="desk_price" value="{{ $desk_price }}" true" min=1 id="desk_price" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                        <label for="desk_price">Price per Desk</label>
                                                        <span class="error" id="err_desk_price">{{ $errors->first('desk_price') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-md-4 col-lg-4">
                                                <div class="price-per-night-blo">
                                                   <div class="form-group">
                                                        <input name="cubicles_price" value="{{ $cubicles_price }}" true" min=1 id="cubicles_price" type="text" data-rule-number="true" data-rule-maxlength="10" data-rule-max="99999999" disabled maxlength="8" />
                                                        <label for="cubicles_price">Price per Cubicles</label>
                                                        <span class="error" id="err_cubicles_price">{{ $errors->first('cubicles_price') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            
                                            <span class="error" id="err_office_has" style="position: sticky;"></span>
                                            <div class="clearfix"></div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="price-per-night-blo">
                        <div class="comments-title main">Address</div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <input id="autocomplete" class="address" placeholder="" value="{{ isset($arr_property['address']) ? $arr_property['address'] : '' }}" onFocus="geolocate()" name="address" type="text" />
                                    <label for="address">Street Address</label>
                                    <span class="error">{{ $errors->first('address') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <input  name="country" value="{{ isset($arr_property['country']) ? $arr_property['country'] : '' }}" readonly="" id="country" type="text" />
                                    <label for="country">Country</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <input  name="state" value="{{ isset($arr_property['state']) ? $arr_property['state'] : '' }}" readonly="" id="administrative_area_level_1" type="text" />
                                    <label for="administrative_area_level_1">State</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <input name="city" value="{{ isset($arr_property['city']) ? $arr_property['city'] : '' }}" readonly="" id="locality" type="text" />
                                    <label for="locality">City</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <input id="postal_code" value="{{ isset($arr_property['postal_code']) && $arr_property['postal_code'] != 0 ? $arr_property['postal_code'] : '' }}"  name="postal_code" type="text"/>
                                    <label for="postal-code">Postal Code</label>
                                    <span class="error" id="err_postal_code">{{ $errors->first('postal_code') }}</span>
                                </div>
                            </div>

                            <input type="hidden" name="latitude" id="latitude" value="{{ isset($arr_property['property_latitude']) && $arr_property['property_latitude'] != 0 ? $arr_property['property_latitude'] : '' }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ isset($arr_property['property_longitude']) && $arr_property['property_longitude'] != 0 ? $arr_property['property_longitude'] : '' }}">

                        </div>
                    </div>
                    <input type="hidden" name="category_id" id="category_id">
                    <div class="clearfix"></div>
                    <div class="viewmores-btn-main">
                        <input value="continue" type="submit" class="viewmores" name="btn_continue" id="btn_continue">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
</div>

<input type="hidden" name="rule_index_id" id="rule_index_id" value="1">
<input type="hidden" name="property_id" id="property_id" value="{{ isset($arr_property['id']) ? $arr_property['id'] : '' }}">

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYfeB69IwOlhuKbZ1pAOwcjEAz3SYkR-o&libraries=places&callback=initAutocomplete"
async defer></script>

<script type="text/javascript">
    $(document).ready(function () 
    {
        check_office_private_room();
        check_office_dedicated_desk();
        check_office_cubicles();

        $('#btn_continue').click(function() {
            var no_of_bedrooms = $('#no_of_bedrooms').val();
            if(no_of_bedrooms > 10) {
                $('for[no_of_bedrooms]').html('Please enter a value less than or equal to 10.');
                $('#err_no_of_bedrooms').html('Please enter a value less than or equal to 10.');
                $('#no-of-bedroom').html('0');
                $("#sleeping-arrangement-div").empty();   
                return false;
            }
            else
            {
                return true;
            }  
       });

       // $('#autocomplete').removeAttr('placeholder');
       var placeSearch, autocomplete;
       /*$('#frm_edit_property_step1').validate({
            ignore: [],
            errorClass: "error",
            errorElement : 'div',
       });*/

        $("#office_private_room").click(function() {
            check_office_private_room();
        });

        $("#office_dedicated_desk").click(function() {
            check_office_dedicated_desk();
        });

        $("#office_cubicles").click(function() {
            check_office_cubicles();
        });

        function check_office_private_room(){
            var office_private_room = $("#office_private_room").prop("checked");
            if( office_private_room == true ) {
                $("#no_of_room").attr('disabled', false);
                $("#room_price").attr('disabled', false);
            }
            else {
                $("#no_of_room").attr('disabled', true);
                $("#no_of_room").val('');

                $("#room_price").attr('disabled', true);
                $("#room_price").val('');
            }
        }

        function check_office_dedicated_desk(){
            var office_dedicated_desk = $("#office_dedicated_desk").prop("checked");
            if( office_dedicated_desk == true ) {
                $("#no_of_desk").attr('disabled', false);
                $("#desk_price").attr('disabled', false);
            }
            else {
                $("#no_of_desk").attr('disabled', true);
                $("#no_of_desk").val('');

                $("#desk_price").attr('disabled', true);
                $("#desk_price").val('');
            }
        }

        function check_office_cubicles(){
            var office_cubicles = $("#office_cubicles").prop("checked");
            if( office_cubicles == true ) {
                $("#no_of_cubicles").attr('disabled', false);
                $("#cubicles_price").attr('disabled', false);
            }
            else {
                $("#no_of_cubicles").attr('disabled', true);
                $("#no_of_cubicles").val('');

                $("#cubicles_price").attr('disabled', true);
                $("#cubicles_price").val('');
            }
        }

        $('#property_type').on('change',function()
        {
            var slug = $('option:selected',this).attr('slug');
            if (slug != '' && slug == 'warehouse')
            {
                $('#no_of_guest_div').hide();
                $('#no_of_bedrooms_div').hide();
                $('#bathrooms_div').hide();
                $('#no_of_beds_div').hide();
                $('#price_per_night_div').hide();
                $('#price_per_div').hide();
                $('#price_per_office_div').hide();
                $('#no-of-bedroom-div').hide();

                $('#property_working_status_div').show();
                $('#property_area_div').show();
                $('#price_per_sqft_div').show();
                $('#total_build_area_div').show();
                $('#custom_type_div').show();
                $('#management_div').show();
                $('#good_storage_div').show();
                $('#property_remark_div').show();
                $('#admin_area_div').show();
                $('#build_type_div').show();
                $('#no_of_slots_div').show();
            } 
            if (slug != '' && slug == 'office-space') 
            {
                $('#no_of_guest_div').hide();
                $('#no_of_bedrooms_div').hide();
                $('#bathrooms_div').hide();
                $('#no_of_beds_div').hide();
                $('#price_per_night_div').hide();
                $('#price_per_sqft_div').hide();
                $('#custom_type_div').hide();
                $('#management_div').hide();
                $('#good_storage_div').hide();
                $('#no_of_slots_div').hide();
                $('#no-of-bedroom-div').hide();

                $('#property_working_status_div').show();
                $('#property_area_div').show();
                $('#total_build_area_div').show();
                $('#property_remark_div').show();
                $('#admin_area_div').show();
                $('#build_type_div').show();
                $('#price_per_div').show();
                $('#price_per_office_div').show();
            }
            if (slug != 'warehouse' && slug != 'office-space') 
            {
                $('#no_of_guest_div').show();
                $('#no_of_bedrooms_div').show();
                $('#bathrooms_div').show();
                $('#no_of_beds_div').show();
                $('#price_per_night_div').show();
                $('#no-of-bedroom-div').show();

                $('#property_working_status_div').hide();
                $('#property_area_div').hide();
                $('#price_per_sqft_div').hide();
                $('#total_build_area_div').hide();
                $('#custom_type_div').hide();
                $('#management_div').hide();
                $('#good_storage_div').hide();
                $('#property_remark_div').hide();
                $('#admin_area_div').hide();
                $('#build_type_div').hide();
                $('#no_of_slots_div').hide();
                $('#price_per_office_div').hide();
                $('#price_per_div').hide();
           }
        });
    });

    function addSleepingArrangement()
    {
        var html           = '';
        var temp_cnt       = 0;
        var total_cnt      = $("#total_cnt").val();
        var property_id    = $("#property_id").val();
        var no_of_bedrooms = $("#no_of_bedrooms").val();

        $('#no-of-bedroom-div').hide();
        $('#no-of-bedroom').html(no_of_bedrooms);
        $("#sleeping-arrangement-div").html('');  
        $('#err_no_of_bedrooms').html('');

        if(no_of_bedrooms!='')
        { 
            if(no_of_bedrooms > 10)
            {
                $('for[no_of_bedrooms]').html('Please enter a value less than or equal to 10.');
                $('#err_no_of_bedrooms').html('Please enter a value less than or equal to 10.');
                $('#no-of-bedroom').html('0');
                $("#sleeping-arrangement-div").empty();   
                return false;
            }
            else
            {
                $('#no-of-bedroom-div').show();
                $('#no-of-bedroom').html(no_of_bedrooms);
                $.ajax({
                    headers: {'X-CSRF-Token': csrf_token},
                    url: SITE_URL+'/property/get_sleeping_arrangement',
                    data: { no_of_bedrooms: no_of_bedrooms, property_id: property_id },
                    type: 'post',
                    dataType: 'json',
                    success:function(res) {
                        $('#sleeping-arrangement-div').html(res.string);                
                    }          
                });
            }    
        }
    }
    

    $('#postal_code').blur(function()
    {
        var zip = $('#postal_code').val();
        
        if(zip != '' && zip != null)
        {
            if ((zip.length) < 4 || (zip.length) > 8 )
            {
                $('#err_postal_code').show();
                $('#err_postal_code').html('Enter a vaild postal code');
                $('#err_postal_code').fadeOut(8000);
                $('#postal_code').focus();
                return false;   
            }
        }
    });

    // Allow only Alphanumeric Characters
    $('#postal_code').keyup(function() {
        if (this.value.match(/[^a-zA-Z0-9-]/g)) {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        }
    });

    // Allow only Numeric Characters
    $('#no_of_guest, #no_of_bedrooms, #bathrooms, #no_of_beds, #price, #no_of_employee, #no_of_room, #no_of_desk, #no_of_cubicles').keyup(function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

</script>
@endsection