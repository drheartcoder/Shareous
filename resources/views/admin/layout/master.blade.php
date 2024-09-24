<!-- HEader -->        
@include('admin.layout._header')    
        
<!-- BEGIN Sidebar -->
@include('admin.layout._sidebar')
<!-- END Sidebar -->

<!-- BEGIN Content -->
<div id="main-content">
    @yield('main_content')
</div>
    <!-- END Main Content -->

<!-- Footer -->        
@include('admin.layout._footer')    
                
              