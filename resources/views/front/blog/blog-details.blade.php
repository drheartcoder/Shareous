@extends('front.layout.master')                
@section('main_content')   
<link rel="stylesheet" href="{{url('/front/sceditor/themes/default.min.css')}}" />
<script src="{{url('/front/sceditor/sceditor.min.js')}}"></script>
    <div class="title-common">
        <h1>Newest Blog</h1>
    </div>
    
{{-- {{ dd($arr_blog['blog_category']['0']['category_name']) }} --}}

<div class="faq-main blog">
    <div class="container">
       <div class="blag-detail-back-main">
           <div class="contact-left-img detail"></div>
           <a href="{{ url('/blog') }}">Back</a>
       </div>
        <div class="row">
            <div class="col-sm-9 col-md-9 col-lg-9">
                <div class="blog-main-list details-margin small">
                    <div class="blog-img-box">
                        @if(isset($arr_blog['blog_image']) && !empty($arr_blog['blog_image']))  
                        <img src={{ $blog_image_public_img_path.$arr_blog['blog_image']}} alt="" />
                        @else
                        <img src="{{url('/front')}}/images/blog-1.png" alt="blog" />
                        @endif 

                        <div class="blog-txt-box">
                            <div class="blog-date">{{ date('j',strtotime($arr_blog['created_at'])) }}<span>{{ date('M',strtotime($arr_blog['created_at'])) }}</span></div>
                        </div>

                        <div class="view-blog"><i class="fa fa-eye"></i> {{ $arr_blog_view_count or '0' }} <span>|</span></div>
                        <div class="view-blog"><i class="fa fa-comment-o"></i> {{ $arr_blog_count or '0' }} <span>|</span></div>
                        <div class="view-blog by-admin">Post <span>By Admin</span></div><span>|</span>
                        <div class="view-blog"> <a href="{{url('/blog/'.$arr_blog['blog_category']['0']['slug'])}}">{{ $arr_blog['blog_category']['0']['category_name'] or '' }}</a> </div>
                    </div>
                    <div class="blog-title">
                        @if(isset($arr_blog['title']) && $arr_blog['title']!="")
                         {{ $arr_blog['title'] }}
                        @else
                        {{ $arr_blog['title'] or '' }}
                        @endif
                    </div>

                    <div class="blog-discrip">
                        @if(isset($arr_blog['description']) && $arr_blog['description']!="")
                            {!! html_entity_decode($arr_blog['description']) !!}
                        @else
                            {!! html_entity_decode($arr_blog['description']) or '' !!}
                        @endif
                    </div>
                </div>  
                @if(isset($arr_blog_comments) && sizeof($arr_blog_comments)>0)
                @foreach($arr_blog_comments as $comments)
                    <div class="rating-white-block marg-top">
                        <div class="review-profile-image">
                              @if(isset($comments['user_details']['0']['profile_image']) && !empty($comments['user_details']['0']['profile_image']))  
                              <img src={{ $profile_image_public_img_path.$comments['user_details']['0']['profile_image']}} alt="" />
                              @else
                              <img src="{{url('/front')}}/images/review-img1.png" alt="post" />
                              @endif                             
                        </div>
                        <div class="review-content-block">
                            <div class="review-send-head">
                                {{ $comments['title'] or '' }}
                            </div>
                            <div class="rating-review-stars">
                                <span class="start-rate-count-blue">2.1</span>
                                <span class="stars-block star-listing">
                                    <span>
                                        <i class="fa fa-star star-acti"></i> 
                                        <i class="fa fa-star star-acti"></i> 
                                        <i class="fa fa-star star-acti"></i> 
                                        <i class="fa fa-star"></i> 
                                        <i class="fa fa-star"></i>
                                    </span>
                                </span>
                                <div class="time-text"> {{ date('j F, Y',strtotime($comments['created_at'])) }} </div>
                            </div>
                            <div class="review-rating-message">
                                {!! html_entity_decode($comments['comment'])!!}
                            </div>
                        </div>
                    </div>
                @endforeach
                @else                     
                @endif

                @if(isset($user_details))
                <div class="rating-white-block marg-top text-box">
                    <div class="comments-title">Comments</div>           
                        <form name="review_form" id="review_form" method="POST" class="profile-page-form" action="add_review">
                        {{csrf_field()}}
                            <input type="hidden" name="user_id" value="{{$user_details['user_id']}}">
                            <input type="hidden" name="blog_id" value="{{$arr_blog['id']}}">
                            <div class="profile-info-mian rting">
                                <div class="form-group">
                                    <input type="text" name="title" data-rule-required="true" data-rule-maxlength="255">
                                    <label>Enter your Review Title</label>
                                    <span class="error" id="title" style="color:red">{{$errors->first('title')}}</span>
                                </div>
                                <div class="form-group">
                                    <textarea rows="2" name="comment" class="text-area" id="comment_sce" data-rule-maxlength="2000" data-rule-required="true" ></textarea>
                                    <label>Write a Comment</label>
                                    <span class="error" id="comment_sce" style="color:red">{{$errors->first('comment_sce')}}</span>
                                </div>
                            </div>                          
                            <div class="blog-button">
                                <div class="send-review-brn"> <button class="review-send" type="submit" >Send Review</button> </div>
                                <div class="send-review-brn"> 
                                    <a href="{{ URL::previous() }}" class="review-send blog-cancal-btn">Cancel</a> 
                                </div>
                            </div>

                        </form>
                </div>
                @else  
                <div class="rating-white-block marg-top text-box">
                    <div class="comments-title">Comments</div>           
                        <div class="profile-info-mian rting">
                            <div class="form-group">
                                <input type="text" name="title" data-rule-required="true" data-rule-maxlength="255">
                                <label>Enter your Review Title</label>
                                <span class="error" id="title" style="color:red">{{$errors->first('title')}}</span>
                            </div>
                            <div class="form-group">
                                <textarea rows="2" name="comment" id="comment_sce" class="text-area" data-rule-required="true" data-rule-maxlength="2000" data-rule-maxlength="500" ></textarea>
                                <label>Write a Comment</label>
                                <span class="error" id="comment" style="color:red">{{$errors->first('comment')}}</span>
                            </div>
                        </div>                          
                        <div class="blog-button">
                            <div class="send-review-brn"> <button class="review-send" onclick="checkLogin();" type="submit" >Send Review</button> </div>
                            <div class="send-review-brn"> <a href="{{ URL::previous() }}" class="review-send blog-cancal-btn">Cancel</a>  </div>
                        </div>                   
                </div>
                @endif
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3">
                <div class="search-bar">
                    <input placeholder="Search" name="search" id="search_category" onkeyup="javascript: return searchCategory(this,'search-category')" type="search" />
                    <div class="search-arrow"> <img src="{{url('/front')}}/images/seach-blog.png" alt="search-blog" /> </div>
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
                            @else 
                                
                            @endif
                        </ul>
                    </div>
                </div>
                <hr>               
                <div class="categories-title">Recent Posts</div>
                    @if(isset($arr_blog_recent) && sizeof($arr_blog_recent)>0)
                    @foreach($arr_blog_recent as $recent_blog)
                       <div class="recent-post-box">
                        <div class="post-img">
                            @if(isset($recent_blog['blog_image']) && !empty($recent_blog['blog_image']))  
                            <img src={{ $blog_image_public_img_path.$recent_blog['blog_image']}} alt="" />
                            @else
                            <img src="{{url('/front')}}/images/post2.png" alt="post" />
                            @endif 

                        </div>
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

<script type="text/javascript">
$(document).ready(function()
{
    jQuery('#review_form').validate(
    {
         ignore: [],
         errorElement: 'div',
         highlight: function(element) { },
         errorPlacement: function(error, element) 
         { 
            error.appendTo(element.parent());
         }
    });                   
});
</script>
<script>
  function checkLogin()
  {
    var is_user_login = '{{ validate_login('users') }}';  
    var url = '{{url('/login')}}';

    swal({
            title: "Oops!",
            text: "Please Login First!",
            type: "error"
        }, function() {
            window.location = url;
        });   
    }       
  function makeStatusMessageHtml(status, message)
  {
      str = '<div class="alert alert-'+status+'">'+
      '<a aria-label="close" data-dismiss="alert" class="close" href="#">'+'×</a>'+message+
      '</div>';
      return str;
  }
  </script>
<script>

   /* function showResult($this)
    {
        var category    = $("#search_category").val();       

        $.ajax({
            type: "GET",
            url: '/blog/details/search_category/',
            data: {category:category},
            dataType: "json",
            success: function(res) {
               
            }
        });

    }*/
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

<script src="{{url('/front/sceditor/formats/xhtml.js')}}"></script>
<script>
// Replace the textarea #example with SCEditor
var textarea = document.getElementById('comment_sce');
/*sceditor.instance(textarea).height(200);*/
sceditor.create(textarea, {
    format: 'xhtml',
    style: '{{url('/front/sceditor/themes/content/default.min.css')}}',
    emoticonsRoot: '{{url('/')}}/front/sceditor/'
});
sceditor.instance(textarea).height(300);
sceditor.instance(textarea).width('100%');
sceditor.instance.emoticons(disable);
</script>
@include('front.common.testimonial')
@endsection