function confirm_action(ref,evt,msg){var msg=msg||false;evt.preventDefault();swal({title:"Are you sure ?",text:msg,type:"warning",showCancelButton:true,confirmButtonColor:"#DD6B55",confirmButtonText:"Yes",cancelButtonText:"No",closeOnConfirm:false,closeOnCancel:true},function(isConfirm){if(isConfirm==true){window.location=$(ref).attr('href');}});}function check_multi_action(frm_id,action){var len=$('input[name="checked_record[]"]:checked').length;var flag=1;var frm_ref=$("#"+frm_id);if(len<=0){swal("Oops..","Please select the record to perform this Action.");return false;}if(action=='delete'){var confirmation_msg="Do you really want to delete selected record(s) ?";}else if(action=='deactivate'){var confirmation_msg="Do you really want to deactivate selected record(s) ?";}else if(action=='activate'){var confirmation_msg="Do you really want to activate selected record(s) ?";}swal({title:"Are you sure ?",text:confirmation_msg,type:"warning",showCancelButton:true,confirmButtonColor:"#DD6B55",confirmButtonText:"Yes",cancelButtonText:"No",closeOnConfirm:true,closeOnCancel:true},function(isConfirm){if(isConfirm){$('input[name="multi_action"]').val(action);$(frm_ref)[0].submit();}else{return false;}});}function showAlert(msg,type,confirm_btn_txt){confirm_btn_txt=confirm_btn_txt||'Ok';swal({title:"",text:msg,type:type,confirmButtonText:confirm_btn_txt});return false;}