@extends('admin.layout.master') 
@section('main_content')
<!-- BEGIN Page Title -->
<div class="page-title">
 <div>
 </div>
</div>
<!-- END Page Title -->

<!-- BEGIN Breadcrumb -->
<div id="breadcrumbs">
  <ul class="breadcrumb">
    <li>
      <i class="fa fa-home"></i>
      <a href="{{ url($admin_panel_slug.'/dashboard') }}">Dashboard</a>
    </li>
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa fa-cubes"></i>
      <a href="{{ url($module_url_path) }}">{{ $module_title or ''}}</a>
    </span> 
    <span class="divider">
      <i class="fa fa-angle-right"></i>
      <i class="fa {{$page_icon}}"></i>
    </span>
    <li class="active">{{ $page_title or ''}}</li>
  </ul>
</div>

<div class="row">
 <div class="col-md-12">
  <div class="box">
   <div class="box-title">
    <h3>
     <i class="fa {{$page_icon}}"></i>
     {{ isset($page_title)?$page_title:"" }}
   </h3>
   <div class="box-tool">
     <a data-action="collapse" href="#"></a>
     <a data-action="close" href="#"></a>
   </div>
 </div>
 <div class="box-content">
  @include('admin.layout._operation_status') 
  <div class="row">
    <div class="col-sm-12">
      <form name="validation-form" id="validate_form" method="POST" class="form-horizontal" action="{{url($module_url_path)}}/store" enctype="multipart/form-data"  files ="true">
        {{csrf_field()}}
        <div class="form-group col-lg-11">
           <label class="col-sm-2 col-lg-3 control-label" for="page_title" style="text-align: right;">Property Type Name <i class="red">*</i></label>
           <div class="col-sm-5 col-lg-5 controls">
           <select class="form-control" id="propertytype_id" name="propertytype_id" data-rule-required="true">
              <option value="">Select Property Type</option>
              @if($parent_propertytype)
                @foreach($parent_propertytype as $propertytype)
                  <option value="{{$propertytype['id']}}">{{$propertytype['name']}}</option>
                @endforeach
              @endif
           </select>
                
            <span class='error help-block'>{{ $errors->first('propertytype_id') }}</span>
          </div>
        </div>

          <div class="form-group col-lg-11">
           <label class="col-sm-2 col-lg-3 control-label" for="page_title" style="text-align: right;">Aminity Name <i class="red">*</i></label>
           <div class="col-sm-5 col-lg-5 controls">
            <input type="text" name="amenity_name[]" class="form-control" value="{{old('amenity_name')}}" data-rule-required="true" data-rule-maxlength="255"  placeholder="Aminity Name">
            <span class='error help-block'>{{ $errors->first('amenity_name') }}</span>
          </div>
        </div>

      <div class="form-group col-lg-11">
       <div class="col-sm-6 col-sm-offset-2 col-lg-6 col-lg-offset-3">
        <input type="submit" value="Save" class="btn btn btn-primary btn-custom">
        <a href="{{url($module_url_path)}}" type="button" class="btn btn-cancel">Cancel</a>
      </div>
    </div>
 <input type="hidden" name="add_index" id="add_index" value="0">
</form>      
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
    $('#validate_form').validate();
});

function add_field()
{
  var add_limit         = '5';
  var add_index         = $("#add_index").val();
  var new_add_index     = parseInt(add_index) + 1; 
  if(new_add_index<add_limit)
  {
    $('#button_id').prev().append("<div class='col-xs-15 col-sm-15 col-lg-15 controls'>"+"<input type='text' name='amenity_name[]' class='form-control' placeholder='Aminity Name'>"+"<input type='button' class='btn btn-primary' onclick='remove_field(this)' value='-'></div>");
    $("#add_index").val(new_add_index); 
   }
   else
   {
      showAlert("You can not add more than"+' '+add_limit + " amenities"  ,"error");
      return false;
   }

   // $('#button_id').prev().append("<div class='col-sm-15 col-lg-15 controls'><input type='text' name='name[]' class='form-control' placeholder='Aminity Name'><input type='button' class='btn btn-primary' onclick='remove_field(this)' value='-'></div>");
   
}
function remove_field(eve)
{
  $(eve).parent().remove();
}

</script>

@stop
