@extends('front.layout.master')                
@section('main_content')
  <div class="clearfix"></div>


    <div class="overflow-hidden-section">
        <div class="titile-user-breadcrum">
            <div class="container">
                <div class="col-md-9 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-4 col-xs-offset-0 user-positions"><h1>Bank Account Details </h1> <div class="clearfix"></div></div>
            </div> 
        </div>
        <div class="change-pass-bg main-hidden">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 left-bar-dashboard">    
                       @include('front.layout.left_bar_host')
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">

                        @if(Session::has('success'))
                          <div class="alert alert-success alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{Session::get('success')}}
                          </div>
                          @endif
                          @if(Session::has('error'))
                          <div class="alert alert-danger alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{Session::get('error')}}
                          </div>
                          @endif
  
                        <div class="row">
                            @if(isset($bank_details) && sizeof($bank_details)>0)
                            @foreach($bank_details as $account)

                            <div class="col-sm-12 col-md-6 col-lg-6" >
                                <div class="white-box-bank bank @if($account['selected']==1) check-status @endif"  >
                                    <div class="circle-bank-nm"></div>
                                    <div class="title-sections">
                                        <h3>{{ $account['bank_name'] or '' }}</h3>
                                        <p><span>A/c No:</span> {{ $account['account_number']  or ''}}</p>
                                        <p class="ifc-top"><span>IFSC code:</span> {{ $account['ifsc_code'] or '' }}</p>
                                    </div>
                                    <a  href="javascript:void(0)" class="usr-add-bnk" data-toggle=modal data-target="#editBankAccountDetail"  onclick='get_record("{{encrypt($account['id'])}}")' ></a>
                                    <a href="javascript:void(0)" class="delet-link-txt" onclick='delete_record("{{encrypt($account['id'])}}")'>Delete Account</a>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            @endforeach
                            @else 
                                
                            @endif
                            
                            @if(sizeof($bank_details) < 4)
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <a href="javascript:void(0)" class="add-details-ads" data-toggle=modal data-target="#AddBankAccountDetail">
                                    <div class="user-add-bnks-cnt"> <div class="circle-bank-nm"></div>
                                    <div class="plus-circle"></div>
                                </div>
                                <div class="bank-add-ac">Add Bank Account</div>
                                <div class="clearfix"></div>
                              </a>
                            </div>
                            @else
                            <div class="col-sm-12 col-md-6 col-lg-6">
                              <i style="color: red;" class="red"> NOTE! Allowed only 4 banks details. </i>  
                              
                            </div>
                            @endif 
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

  <!--Add Bank Account Detail popup start here-->
   <div class="payment-bank-for">
    <div class="host-contact-popup upgrade payment">
        <div class="popup-inquiry-form">
            <div id=editBankAccountDetail class="modal fade" data-backdrop="static" role=dialog>
                <div class=modal-dialog>
                    <div class="modal-content">
                        <div class="modal-header black-close">
                            <button type=button class=close data-dismiss=modal>
                                <span class="contact-left-img popup-close nonebg"><img src="{{url('/')}}/front/images/popup-close-btn.png" alt=""></span>
                            </button>
                        </div>
                        <div class=modal-body>
                           
                            <div class="payment-detail-tab-one">
                               <div class="icn-vactino"></div>
                              <form name="frm_update_account" action="{{ $module_url_path.'/update' }}" id="frm_update_account" method="POST">
                                 {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">   
                                        <input type="hidden" id="user_id1" name="user_id" value="">
                                        <input type="hidden" id="id1" name="id" value="" >
                                        <div class="form-group">
                                            <input id="bank_name1" name="bank_name" type="text" data-rule-required="true" value="" data-rule-minlength="3" data-rule-maxlength="50" />
                                            <label for="card-one">Bank Name</label> 
                                            <span class='error help-block'>{{ $errors->first('bank_name') }}</span>    
                                        </div>

                                        <div class="form-group">
                                            <input id="account_number1" name="account_number" type="text" data-rule-required="true" value="" data-rule-digits="true" data-rule-minlength="9" data-rule-maxlength="18" />
                                            <label for="card-one">Account Number</label>
                                            <span class='error help-block'>{{ $errors->first('account_number') }}</span>    
                                        </div>                                     
                                       
                                        <div class="form-group">
                                            <input id="ifsc_code1" name="ifsc_code" type="text" data-rule-required="true" data-rule-minlength="6" data-rule-maxlength="15" value="" />
                                            <label for="holder-ones">IFSC Code </label> 
                                            <span class='error help-block'>{{ $errors->first('ifsc_code') }}</span>
                                        </div>

                                        <div class="form-group">
                                            <div class="select-style">
                                                <select id="account_type1" name="account_type" data-rule-required="true">
                                                    <option value="1" >Saving Account</option>
                                                    <option value="2" >Current Account</option>
                                                    <option value="3" >Recurring Deposit Account</option>
                                                    <option value="4" >DEMAT Account</option>
                                                    <option value="5" >NRI Account</option>
                                                </select>
                                                <span class='error help-block'>{{ $errors->first('account_type') }}</span>
                                            </div>
                                        </div>                                          
                                    </div>
                                    <div class="form-group">
                                        <div class="check-box inline-checkboxs check-margi">
                                            <input id="filled-in-box4" class="filled-in" name="account_check" type="checkbox" checked="" />
                                            <label for="filled-in-box4">Make default account for bank account</label>
                                        </div> 
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="change-pass-btn">
                                            <a class="login-btn cancel" data-dismiss=modal href="javascript:void(0)">Cancel</a>
                                            <input type="submit" class="login-btn" name="btn_update_bank_details" value="Update" id="btn_update_bank_details">
                                        </div>
                                    </div>
                                </div>    
                              </form>                            
                            </div>
                        </div>
                        <div class=clearfix></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   </div> 
    <!--Add Bank Account Detail popup end here-->   
 
   <!--Add Bank Account Detail popup start here-->
    <div class="host-contact-popup upgrade payment">
        <div class="popup-inquiry-form">
            <div id=AddBankAccountDetail class="modal fade" data-backdrop="static" role=dialog>
                <div class=modal-dialog>
                    <div class=modal-content>
                        <div class="modal-header black-close">
                            <button type=button class=close data-dismiss=modal>
                                <span class="contact-left-img popup-close nonebg"><img src="{{url('/')}}/front/images/popup-close-btn.png" alt=""></span>
                            </button>
                        </div>
                        <div class=modal-body>                           
                            <div class="payment-detail-tab-one">
                               <div class="icn-vactino"></div>
                                <form name="frm_add_account" action="{{ $module_url_path.'/store' }}" id="frm_add_account" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">  
                                        <input type="hidden" name="user_id" value="{{$user_details['user_id']}}" >

                                        <div class="form-group">
                                            <input id="add-bank-name-id" name="bank_name" type="text" data-rule-required="true" data-rule-minlength="3"  data-rule-maxlength="50"  />
                                            <label for="add-bank-name-id">Bank Name</label> 
                                            <span class='error help-block'>{{ $errors->first('bank_name') }}</span>    
                                        </div>

                                        <div class="form-group">
                                            <input id="add-bank-account-no-id" name="account_number" type="text" data-rule-required="true"  data-rule-digits="true" data-rule-minlength="9" data-rule-maxlength="18" />
                                            <label for="add-bank-account-no-id">Account Number</label> 
                                            <span class='error help-block'>{{ $errors->first('account_number') }}</span>
                                        </div>                                       
                                        
                                        <div class="form-group">
                                            <input id="add-ifsc-code-id" name="ifsc_code" type="text" data-rule-required="true" data-rule-minlength="6" data-rule-maxlength="15" />
                                            <label for="add-ifsc-code-id">IFSC Code </label> 
                                            <span class='error help-block'>{{ $errors->first('ifsc_code') }}</span>
                                        </div>
                                        <div class="form-group">
                                            <div class="select-style">
                                                <select id="add-account-type-id" name="account_type" data-rule-required="true">
                                                   <option value="">Select Account Type</option>
                                                    <option value="1" >Saving Account</option>
                                                    <option value="2" >Current Account</option>
                                                    <option value="3" >Recurring Deposit Account</option>
                                                    <option value="4" >DEMAT Account</option>
                                                    <option value="5" >NRI Account</option>
                                                </select>
                                                <span class='error help-block'>{{ $errors->first('account_type') }}</span>
                                            </div>
                                        </div>   
                                        <div class="check-box inline-checkboxs check-margi">
                                            <input id="filled-in-box2" class="filled-in" name="account_check" type="checkbox" />
                                            <label for="filled-in-box2">Make default account for bank account</label>
                                        </div> 
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="change-pass-btn">
                                            <a class="login-btn cancel" data-dismiss=modal href="javascript:void(0)">Cancel</a>
                                            <input type="submit" class="login-btn" name="btn_store_bank_details" value="save" id="btn_store_bank_details">
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class=clearfix></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Add Bank Account Detail popup end here-->  
 <script>
            $(document).ready(function () {
                $('input, textarea, select').each(function () 
                {
                    $(this).on('focus', function () {
                        $(this).parent('.form-group').addClass('active')
                    });
                    $('label').on('click', function () {
                        $(this).parent('.form-group').addClass('active')
                    });
                    $(this).on('blur', function () {
                        if ($(this).val().length == 0) {
                            $(this).parent('.form-group').removeClass('active')
                        }
                    });
                    if ($(this).val() != '') 
                    {
                        //alert($(this).val());
                        $(this).parent('.form-group').addClass('active')

                    }

                })
            });
        </script>
   
   
    <script>
 //<!--tab js script-->  
         $('#horizontalTab').easyResponsiveTabs({
           type: 'default',     
           width: 'auto', 
           fit: true, 
           closed: 'accordion',
           activate: function(event) { 
               var $tab = $(this);
               var $info = $('#tabInfo');
               var $name = $('span', $info);
               $name.text($tab.text());
               $info.show();
           }
         });

    function delete_record(id)
    {
      swal({
        title: "Are you sure",
        text: "Do you want to delete records?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
      },
      function(){
          location.href="{{url($module_url_path)}}/delete/"+id;
        });
    }

    function get_record(id)
    {
        $.ajax({
            type: "GET",
            url: "{{url('/')}}/bank_details/get_data/"+id,
            data: {id:id},
            dataType: "json",
            success: function(res) 
            {              
                $("#id1").val(res.id);
                $("#account_number1").val(res.account_number);
                $("#account_type1").val(res.account_type);
                $("#bank_name1").val(res.bank_name);
                $("#ifsc_code1").val(res.ifsc_code);
                $("#user_id1").val(res.user_id);
                
                $('#account_number1').parent('.form-group').addClass('active');
                $('#account_type1').parent('.form-group').addClass('active');
                $('#bank_name1').parent('.form-group').addClass('active');
                $('#ifsc_code1').parent('.form-group').addClass('active');

                if(res.selected == 0)
                {
                    $('#filled-in-box4').prop('checked', false);   
                }
                else
                {
                    $('#filled-in-box4').prop('checked', true); // Checks it 
                }
                

            }
        });


    }

</script>

<script type="text/javascript">
 /*scrollbar start*/
 (function($){
      $(window).on("load",function(){
           $.mCustomScrollbar.defaults.scrollButtons.enable=true; 
           $.mCustomScrollbar.defaults.axis="yx"; 
           $(".content-d").mCustomScrollbar({theme:"dark"});
      });
 })(jQuery);
</script>
  
 <script type="text/javascript">
        $(document).ready(function()
        {
            jQuery('#frm_add_account').validate(
            {
                ignore: [],
                errorClass: "error",
                errorElement : 'div',
            });                   
        });

        $(document).ready(function()
        {
            jQuery('#frm_update_account').validate(
            {
                ignore: [],
                errorClass: "error",
                errorElement : 'div',
            });                   
        });
        $('#frm_add_account').on('submit',function()
        {
              var form = $(this);  
              if(form.valid())
              {   
                showProcessingOverlay();
                return true;
              }
        });
         $('#frm_update_account').on('submit',function()
        {
              var form = $(this);  
              if(form.valid())
              {   
                showProcessingOverlay();
                return true;
              }
        });
       
    </script>
@endsection
