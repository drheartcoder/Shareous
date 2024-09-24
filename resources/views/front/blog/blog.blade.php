@extends('front.layout.master')                
@section('main_content')

    <!--Header section end here-->
    <div class="clearfix"></div>
    <div class="title-common">
        <h1>Newest Blog</h1>
    </div>

<div class="faq-main blog">
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-9 col-lg-9">

                @if(isset($arr_blog['data']) && sizeof($arr_blog['data'])>0)
                @foreach($arr_blog['data'] as $blog)

                
               <div class="blog-main-list"> 
                    <div class="blog-img-box" style="cursor:pointer;" onClick="window.location.href='{{ $module_url_path.'/details/'.encrypt($blog['id']) }}'">
                          @if(isset($blog['blog_image']) && !empty($blog['blog_image']))  
                          <img src={{ $blog_image_public_img_path.$blog['blog_image']}} alt="" />
                          @else
                          <img src="{{url('/front')}}/images/slider-img-home.jpg" alt="blog" />
                          @endif 

                        <div class="blog-txt-box">
                            <div class="blog-date">{{ date('j',strtotime($blog['created_at'])) }}<span>{{ date('M',strtotime($blog['created_at'])) }}</span></div> 
                        </div>                       

                        <div class="view-blog"><i class="fa fa-eye"></i> {{ $blog['blog_view_count_count'] or '0' }} <span>|</span></div>
                        <div class="view-blog"><i class="fa fa-comment-o"></i>{{ $blog['blog_comment_count_count'] or '0' }}  <span>|</span></div>                       
                        <div class="view-blog by-admin">Post <span>By Admin</span></div><span>|</span>
                        <div class="view-blog link"> <a href="{{url('/blog/'.$blog['blog_category']['0']['slug'])}}">{{ $blog['blog_category']['0']['category_name'] or '' }}</a> </div>  

                    </div>
                    <div class="blog-title">
                         @if(isset($blog['title']) && $blog['title']!="")
                         <a href="{{ $module_url_path.'/details/'.encrypt($blog['id']) }}"> {{ $blog['title'] }} </a>
                        @else
                        {{ $blog['title'] or '' }}
                        @endif                    
                    </div>

                    <div class="blog-discrip">
                        @if(isset($blog['description']) && $blog['description']!="" && strlen($blog['description'])>200)
                         {!! html_entity_decode(str_limit($blog['description'],200)) !!} 
                         <a href="{{ $module_url_path.'/details/'.encrypt($blog['id']) }}">Read More</a>
                        @else
                        {!! html_entity_decode($blog['description']) or '' !!}
                        @endif

                    </div>
</div>

                @endforeach
                @else 
                  <!-- <div class="blog-main-list" style="margin-top: 65px;">
                    <div class="blog-img-box">
                    </div>
                    <div class="blog-title" style="padding-top: 28px;"> -->
                      <div class="list-vactions-details" style="margin-top: 20px;">
                      <div class="no-record-found"></div>
                    </div>
                    <!-- </div>
                  </div> -->
                @endif

                @if(isset($obj_pagination) && $obj_pagination!=null)            
                    @include('front.common.pagination',['obj_pagination' => $obj_pagination])
                @endif
            </div>


            <div class="col-sm-3 col-md-3 col-lg-3">
                <div class="search-bar">
                    <input placeholder="Search" name="search" id="search_category" onkeyup="javascript: return searchCategory(this,'search-category')" type="search" />
                    <button type="button" class="search-arrow"> <img src="{{url('/front')}}/images/seach-blog.png" alt="search-blog" /> </button>
                </div>

                <div class="categories-box">
                    <div class="categories-title">Categories</div>
                    <div class="categories-ul-box" id="search-category">
                        <ul>
                            @if(isset($arr_blog_category) && sizeof($arr_blog_category)>0)
                            @foreach($arr_blog_category as $category)
                            <div>
                            <li> <a href="{{url('/blog/'.$category['slug'])}}"> <i class="fa fa-circle-o" ></i> {{ $category['category_name'] }} <span>({{ $category['blog_count'] or '' }})</span></a></li>
                            </div>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <hr>

                <div class="categories-title">Recent Posts</div>

                @if(isset($arr_blog_recent) && sizeof($arr_blog_recent)>0)
                @foreach($arr_blog_recent as $recent_blog)
                    <div class="recent-post-box">
                       <a href="#" class="post-img">
                              @if(isset($blog['blog_image']) && !empty($blog['blog_image']))  
                              <img src={{ $blog_image_public_img_path.$recent_blog['blog_image']}} alt="" />
                              @else
                              <img src="{{url('/front')}}/images/post1.png" alt="post" />
                              @endif 

                        </a>
                        <div class="post-txt-bx">
                            <div class="post-name"><a href="{{ $module_url_path.'/details/'.encrypt($recent_blog['id']) }}">{{ $recent_blog['title'] }} </a></div>
                            <div class="post-date">{{ date('j F, Y',strtotime($recent_blog['created_at'])) }}</div>
                        </div>
                        <div class="clr"></div>
                    </div>
                @endforeach
                @else                     
                @endif
            </div>
        </div>
    </div>
</div>

@include('front.common.testimonial')
    <script>
        function searchCategory(reff,section_id)
        {

            var serach_key = $(reff).val();
            var search_section = $('#'+section_id);
                
               search_section.find('div').each(function(index, row)
               {
               
                 var allCells = $(row).find('li');
                 
                 if(allCells.length >0)
                 {
                   var found = false;
                   allCells.each(function(index, div)
                   {
                     var regExp = new RegExp(serach_key, 'i');
                     if(regExp.test($(div).text()))
                     {
                       found = true;
                       return false;
                     }
                   });
                   if(found == true)$(row).show();else $(row).hide();
                 }
               });
        }
    </script>

@endsection