@enqueueCSS('news-page', getThemeAssetUrl('libs/styles/news.css'), 'master-page')
@enqueueJS('grid-a-licious', getThemeAssetUrl('libs/jquery.grid-a-licious.min.js'), JS_LOCATION_HEAD, 'jquery')
@php
    /** @var \Modules\ModHairWorld\Entities\PostTypes\News $post */
    $og_img = getNoThumbnailUrl();
    $og_width = 500;
    $og_height = 500;
    if($post->cover){
        //$og_img = $post->cover->getThumbnailUrl('large');
        $og_img = route('frontend.facebook_thumb', ['upload'=>$post->cover_id, 'file_name'=>'thumb_'.time().'.jpg']);
        $og_width = 1200;
        $og_height = 628;
    }

	//$og_img_ = preg_replace("/^https:/i", "http:", $og_img);
	$og_img_ = $og_img;
	
	$info = str_limit(strip_tags($post->description), 300);
	$info = preg_replace( "/\r|\n/", " ", $info );

   $keywords = trim($post->meta_keywords);
   $keywords_string = '';
    if($keywords){
        $keywords = preg_split('/\r\n|[\r\n]/', $keywords);
        foreach ($keywords as $index=>$keyword){
            if($index != 0){
                $keywords_string .= ', ';
            }
            $keywords_string .= $keyword;
        }
    }
@endphp
@extends(getThemeViewName('master'))
@section('current_page_title')
    {!! $post->title !!}
@endsection
@push('page_meta')
    <meta name="description" content="{{ $info }}"/>
    <meta property="og:title" content="{{ $post->title }}"/>
    <meta property="og:image" content="{{ $og_img_ }}"/>
    <meta property="og:description" content="{{ $info }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:image:secure_url" content="{{ $og_img }}" />
    <meta property="og:image:width" content="{{$og_width}}" />
    <meta property="og:image:height" content="{{$og_height}}" />
    @if($keywords_string)
        <meta name="keywords" content="{{$keywords_string}}" />
    @endif
@endpush
@section('page_content')
    <div class="news-page-header">
        <div class="container">
            <div class="title">{!! $post->title !!}</div>
            <div class="date">{!! $post->published_at->format('d/m/Y') !!}</div>
        </div>
    </div>
    <div class="news-page-content-2">
        <div class="container">
            <div class="main-img" style="background-image: url('{!! $post->cover?$post->cover->getUrl():getNoThumbnailUrl() !!}')">
            </div>
            <div class="main-content">
                <div class="wrapper">
                    <div class="content">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
            <div class="news-step-wrapper">
                <div class="news-steps">
                    <div class="item item-p">
                    </div>
                    @if($content_blocks)
                        @foreach($content_blocks as $k=>$item)
                            <div class="item">
                                <div class="item-wrapper">
                                    <div class="img">
                                        <img src="{!! $item['cover'] !!}">
                                    </div>
                                    <div class="content">
                                        <div class="content-wrapper">
                                            <div class="number">0{!! sprintf('%01d', $k+1); !!}</div>
                                            <div class="title">{!! $item['title'] !!}</div>
                                            <div class="text">{!! $item['content'] !!}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if($post->button_title)
                    <div class="item">
                        <div class="book-button">
                            <a href="{!! $post->button_link !!}">{!! $post->button_title !!}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="page-sharer">
        <div class="wrapper">
            <div class="container">
                <span class="text">Hãy chia sẽ bài viết này tới bạn bè của bạn</span>
                <div class="items d-inline-block">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={!! $post->getUrl() !!}" target="_blank" class="d-inline-block item" style="background-color: #00ace8">
                        <i class="fa fa-facebook"></i>
                        <span class="name">Facebook</span>
                    </a>
                    <a href="https://plus.google.com/share?url={!! $post->getUrl() !!}" target="_blank" class="d-inline-block item" style="background-color: #e57945">
                        <i class="fa fa-google-plus"></i>
                        <span class="name">Google</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-bread-cumber">
        <div class="container">
            <a href="#">Trang chủ</a> / <a href="#">Xu hướng tóc</a>
        </div>
    </div>
@endsection
@push('page_footer_js')
    <script type="text/javascript">
        $(function () {
            $(".news-steps").gridalicious({width: 500,gutter: 100});
        })
    </script>
@endpush