<!-- HEader -->        
@include('support.layout._header')    
        
<!-- BEGIN Sidebar -->
@include('support.layout._sidebar')
<!-- END Sidebar -->

<!-- BEGIN Content -->
<div id="main-content">
    @yield('main_content')
</div>
    <!-- END Main Content -->

<!-- Footer -->        
@include('support.layout._footer')    
                
              