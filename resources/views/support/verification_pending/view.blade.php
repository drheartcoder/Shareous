`@extends('support.layout.master')
@section('main_content')

<div class="page-title">
   <div>
   </div>
</div>
<!-- END Page Title -->
<!-- BEGIN Breadcrumb -->
<div id="breadcrumbs">
   <ul class="breadcrumb">
      <li>
         <i class="fa fa-home">
         </i>
         <a href="{{ url($support_panel_slug.'/dashboard') }}">Dashboard
         </a>
      </li>
      <span class="divider">
      <i class="fa fa-angle-right">
      </i>
      <i class="{{$module_icon or ''}}">
      </i>
      </span> 
      <li >  
      <a href="{{$module_url_path}}">{{ isset($previous_page_title)?$previous_page_title:"" }}</a>
      </li>
      <span class="divider">
      <i class="fa fa-angle-right">
      </i>
     <!--  <i class="{{$page_icon or ''}}">
      </i> -->
      </span> 
      <li class="active"> {{ isset($page_title)?$page_title:"" }}
      </li>
   </ul>
</div>

<!-- END Breadcrumb -->
<!-- BEGIN Main Content -->
<div class="row">
<div class="col-md-12">
   <div class="box {{support_navbar_color()}}">
      <div class="box-title">
         <h3>
            <i class="{{$page_icon or ''}}">
            </i>{{ isset($page_title)?$page_title:"" }} 
         </h3>
         <div class="box-tool">
         </div>
      </div>
      <div class="box-content">
         @include('support.layout._operation_status')

         @if(isset($arr_user) && sizeof($arr_user)>0)
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="panel panel-default">
                              <div class="panel-heading font-bold">Profile Image</div>
                                <div class="panel-body">    
                                  <div class="form-group">
                                      <div class="col-sm-3">
                                       <div class="thumbnail">
                                          @if(isset($arr_user['user_details']['profile_image']) && $arr_user['user_details']['profile_image']!="" && file_exists($profile_image_base_path.$arr_user['user_details']['profile_image']))
                                          <img src="{{$profile_image_public_path.$arr_user['user_details']['profile_image'] }}" class="img-responsive">
                                          @else
                                          <img src="{{url('/uploads').'/default-profile.png' }}" class="img-responsive">
                                          @endif
                                       </div>
                                     </div>                                 
                                  </div>
                                </div>
                           </div>
                           <div class="panel panel-default">
                              <div class="panel-heading font-bold">Document Details</div>
                                <div class="panel-body">
                                  <div class="form-group">
                                     <div class="form-group col-sm-12">
                                        <label class="col-lg-4 control-label">Request Id:</label>
                                        <div class="col-lg-8">
                                          <div class="col-lg-5">
                                          {{isset($arr_user['request_id']) && $arr_user['request_id']!=""? $arr_user['request_id']:'NA'}}
                                          </div>
                                        </div>
                                     </div>
                                     <div class="form-group col-sm-12">
                                        <label class="col-lg-4 control-label">Id Proof:</label>
                                        <div class="col-lg-8">                                     
                                            @if(isset($arr_user['id_proof']) && ($arr_user['id_proof']!="") && file_exists($id_proof_base_path.$arr_user['id_proof']))
                                   
                                              <div class="col-lg-12">
                                                {{isset($arr_user['id_proof'])? $arr_user['id_proof']:'NA'}}
                                                <a href="{{$id_proof_public_path}}{{$arr_user['id_proof']}}" download class="btn btn-circle show-tooltip" title="Download"> <i class="fa fa-download"></i> </a>
                                              </div>
                                            
                                            @else
                                            <div class="col-lg-12">
                                            {{'NA'}} 
                                            </div>
                                            @endif
                                        </div>
                                     </div>
                                     <div class="form-group col-sm-12">
                                        <label class="col-lg-4 control-label">Photo:</label>
                                        <div class="col-lg-8">
                                          
                                          @if(isset($arr_user['photo']) && !empty($arr_user['photo']) && file_exists($photo_base_path.$arr_user['photo']))
                                            <div class="col-lg-5">
                                              {{isset($arr_user['photo']) && $arr_user['photo']!=""? $arr_user['photo']:'NA'}}
                                               <a href="{{$photo_public_path}}{{$arr_user['photo']}}" download class="btn btn-circle show-tooltip" title="Download"> <i class="fa fa-download"></i> </a>
                                            </div>
                                            
                                            @else
                                            <div class="col-lg-12">
                                              {{'NA'}}
                                            </div>
                                            @endif
                                        </div>
                                     </div>
                               </div>
                             </div>
                           </div>                             
                        </div>

                        <div class="col-sm-6">
                           <div class="panel panel-default">
                              <div class="panel-heading font-bold">Personal Details</div>
                              <div class="panel-body">
                                  <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">User Name:</label>
                                    <div class="col-lg-8">
                                      {{isset($arr_user['user_details']['user_name']) && $arr_user['user_details']['user_name']!=""? ucfirst($arr_user['user_details']['user_name']):'NA'}}
                                    </div>
                                 </div>

                                 <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Display Name:</label>
                                    <div class="col-lg-8">
                                      {{isset($arr_user['user_details']['display_name']) && $arr_user['user_details']['display_name']!=""? ucfirst($arr_user['user_details']['display_name']):'NA'}}
                                    </div>
                                 </div>
 

                                 <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">First Name:</label>
                                    <div class="col-lg-8">
                                      {{isset($arr_user['user_details']['first_name']) && $arr_user['user_details']['first_name']!=""? ucfirst($arr_user['user_details']['first_name']):'NA'}}
                                    </div>
                                 </div>

                                 <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Last Name:</label>
                                    <div class="col-lg-8">
                                      {{isset($arr_user['user_details']['last_name']) && $arr_user['user_details']['last_name']!=""? ucfirst($arr_user['user_details']['last_name']):'NA'}}
                                    </div>
                                 </div>

                                  <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Email:</label>
                                    <div class="col-lg-8">
                                      {{isset($arr_user['user_details']['email']) && $arr_user['user_details']['email']!=""?$arr_user['user_details']['email']:'NA'}}
                                    </div>
                                 </div>

                                 <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Mobile:</label>
                                    <div class="col-lg-8">
                                      {{isset($arr_user['user_details']['mobile_number']) && $arr_user['user_details']['mobile_number']!=""?$arr_user['user_details']['mobile_number']:'NA'}}
                                    </div>
                                 </div>
                         
                               <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Birthdate:</label>
                                    <div class="col-lg-8">
                                      {{ isset($arr_user['user_details']['birth_date']) && $arr_user['user_details']['birth_date'] != "" && $arr_user['user_details']['birth_date'] != 0000-00-00 ? date('d-M-Y',strtotime($arr_user['user_details']['birth_date'])) : 'NA' }}
                                    </div>
                                 </div>

                                 <div class="form-group col-sm-12">
                                    <label class="col-lg-4 control-label">Gender:</label>
                                    <div class="col-lg-8">
                                      <?php
                                          $gender = isset($arr_user['user_details']['gender']) && !empty($arr_user['user_details']['gender']) ? $arr_user['user_details']['gender'] : '-';
                                          if($gender == '0')
                                          {
                                              $gender_val = "Female";
                                          }
                                          elseif($gender == '1')
                                          {
                                              $gender_val = "Male";
                                          }
                                          else
                                          {
                                              $gender_val = "-";
                                          }
                                      ?>
                                      {{ $gender_val }}
                                    </div>
                                 </div>
                         
                              </div>
                           </div>
                         </div>
                      
                     </div>
                     
                     <div class="row">
                         <div class="col-sm-6">
                              <div class="panel panel-default">
                                <div class="panel-heading font-bold">Address Details</div>
                                 <div class="panel-body">
                                    <div class="form-group col-sm-12">
                                       <label class="col-lg-4 control-label">City:</label>
                                       <div class="col-lg-8">
                                         {{isset($arr_user['user_details']['city']) && $arr_user['user_details']['city']!=""?$arr_user['user_details']['city']:'NA'}}
                                       </div>
                                    </div>    

                                    <div class="form-group col-sm-12">
                                       <label class="col-lg-4 control-label">Address:</label>
                                       <div class="col-lg-8">
                                         {{isset($arr_user['user_details']['address']) && $arr_user['user_details']['address']!=""?$arr_user['user_details']['address']:'NA'}}
                                       </div>
                                    </div>                             
                                 </div>
                           </div>
                         </div>        
           
                          @if( isset($arr_user['bank_details']) && !empty($arr_user['bank_details']) )
                          <div class="col-sm-6">
                             <div class="panel panel-default">
                                <div class="panel-heading font-bold">Bank Details</div>
                                <div class="panel-body">                                 
                                   <div class="form-group col-sm-12">
                                      <label class="col-lg-4 control-label">Bank Name:</label>
                                      <div class="col-lg-8">
                                        {{isset($arr_user['bank_details']['bank_name']) && $arr_user['bank_details']['bank_name']!=""?$arr_user['bank_details']['bank_name']:'NA'}}
                                      </div>
                                   </div>

                                   <div class="form-group col-sm-12">
                                      <label class="col-lg-4 control-label">Bank Account No:</label>
                                      <div class="col-lg-8">
                                        {{isset($arr_user['bank_details']['account_number']) && $arr_user['bank_details']['account_number']!=""?$arr_user['bank_details']['account_number']:'NA'}}
                                      </div>
                                   </div>

                                   <div class="form-group col-sm-12">
                                      <label class="col-lg-4 control-label">Bank IFSC No.:</label>
                                      <div class="col-lg-8">
                                        {{isset($arr_user['bank_details']['ifsc_code']) && $arr_user['bank_details']['ifsc_code']!=""?$arr_user['bank_details']['ifsc_code']:'NA'}}
                                      </div>
                                   </div>

                                   <div class="form-group col-sm-12">
                                      <label class="col-lg-4 control-label">Account Type:</label>
                                      <div class="col-lg-8">
                                        @if($arr_user['bank_details']['account_type'] == 1) Saving Account @elseif($arr_user['bank_details']['account_type'] == 2) Current Account @elseif($arr_user['bank_details']['account_type'] == 3) Recurring Account @elseif($arr_user['bank_details']['account_type'] == 4) Demat Account @else NRI Account @endif
                                      </div>
                                   </div>
                                </div>
                             </div>
                          </div>
                          @endif

                          <form id="verification_form" method="post" name="verification_form">
                           {{ csrf_field() }}
                             <div class="form-group">
                               <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                  <a  href="javascript:void(0)" onclick='accept("{{base64_encode($arr_user['id'])}}")'  class="btn btn-success"> <i class='fa fa-check-circle' ></i> Approve</a>
                                  <a  href="javascript:void(0)" onclick='reject("{{base64_encode($arr_user['id'])}}")'  class=" btn btn-danger btn-preview"> <i class="fa fa-times"></i> Reject</a>
                                  <button type="button" onclick="location.href='{{ $module_url_path }}'" class="btn btn-cancel"> <i class="fa fa-arrow-left"></i> Back</button>
                               </div>
                            </div>
                        </form>
                     </div>
                         
                  @endif
        
      </div>
   </div>
</div>
<script type="text/javascript">


function accept(id)
{
  swal({
    title: "Are you sure",
    text: " Do you want to approve verification of user?",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Confirm",
    closeOnConfirm: false
  },
  function(){
    location.href="{{url($module_url_path)}}/accept/"+id;
  });
}

function reject(id)
{
  swal({
    title: "Are you sure",
    text: " Do you want to reject verification of user?",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Confirm",
    closeOnConfirm: false
  },
  function(){
    location.href="{{url($module_url_path)}}/reject/"+id;
  });
}

</script>

@endsection