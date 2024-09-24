@extends('front.layout.master')                
@section('main_content')

    <div class="clearfix"></div>
    <div class="title-common">
        <h1>FAQ</h1>
    </div>

    <div class="faq-main">
        <div class="container">
            <div class="change-pass-bady">
                <div class="frequently-asked-main">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="terms-dead">Frequently Asked Questions ?</div>
                        </div>    
                        @if(isset($arr_faq['data']) && sizeof($arr_faq['data']) > 0 )
                        <div class="col-md-5">
                        <form name="search_faq" action="{{url($module_url_path)}}/search" id="search_faq" method="get">  
                            <div class="search-bar">
                                <input placeholder="Search" name="search" id="search_category" onkeyup="javascript: return searchFaq(this,'faq-class')" type="search" value="{{old('search')}}" />
                                <div class="search-arrow search-submit-button">
                                    <button type="submit" class=""></button>
                                </div>
                            </div>
                        </form>                                        
                        </div>
                        @endif
                    </div>
                    <div id='faq_acc' class="faq-class">
                        <ul>
                            @if(isset($arr_faq['data']) && sizeof($arr_faq['data']) > 0 )
                            @foreach($arr_faq['data'] as $faq)
                            
                            <li class='has-sub' >
                                <a href='#'><span>{{ $faq['question'] }}</span></a>
                                <ul>
                                    <li>
                                        <div class="row">
                                            <div class="faq-text">{{ $faq['answer'] }}</div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                           
                            @endforeach
                            @else 
                                <div class="no-record-found"></div>
                            @endif
                        </ul>
                    </div>
                </div>    
                @if(isset($obj_pagination) && $obj_pagination!=null)            
                    @include('front.common.pagination',['obj_pagination' => $obj_pagination])
                @endif
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

   
@endsection