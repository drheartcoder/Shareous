
 @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible" id="success_message_div">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ Session::get('success') }}
    </div>
  @endif

  @if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible" id="error_message_div">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ Session::get('error') }}
    </div>
  @endif

<script type="text/javascript">
            
    $(document).ready(function()
    {
        setTimeout(function()
        {
            $('#success_message_div').fadeOut("slow", function () 
            {
                $('#success_message_div').remove();
            });
        }, 2000);

        setTimeout(function()
        {
            $('#error_message_div').fadeOut("slow", function () 
            {
                $('#error_message_div').remove();
            });
        }, 2000);
    });

</script>