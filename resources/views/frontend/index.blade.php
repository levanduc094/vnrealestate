@extends('frontend.layouts.app')

@section('content')

<!-- SERVICE SECTION -->

<section class="section grey lighten-4 center">
    <div class="container">
        <div class="row">
            <h4 class="section-heading">{{ __('views.index.services') }}</h4>
        </div>
        <div class="row">
            @foreach($services as $service)
            <div class="col s12 m4">
                <div class="card-panel">
                    <i class="material-icons large indigo-text">{{ $service->icon }}</i>
                    <h5>{{ $service->title }}</h5>
                    <p>{{ $service->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- FEATURED SECTION -->

<section class="section">
    <div class="container">
        <div class="row">
            <h4 class="section-heading">{{ __('views.index.featuredProperties') }}</h4>
        </div>
        <div class="row">

            @foreach($properties as $property)
            <div class="col s12 m4">
                <div class="card">
                    <div class="card-image">
                        @if(Storage::disk('public')->exists('property/'.$property->image) && $property->image)
                        <span class="card-image-bg"
                            style="background-image:url({{Storage::disk('public')->url('property/'.$property->image)}});"></span>
                        @else
                        <span class="card-image-bg"><span>
                                @endif
                                @if($property->featured == 1)
                                <a class="btn-floating halfway-fab waves-effect waves-light indigo" title="Featured"><i
                                        class="small material-icons">star</i></a>
                                @endif
                    </div>
                    <div class="card-content property-content">
                        <a href="{{ route('property.show',$property->slug) }}">
                            <span class="card-title tooltipped" data-position="bottom"
                                data-tooltip="{{ $property->title }}">{{ str_limit( $property->title, 18 ) }}</span>
                        </a>

                        <div class="address">
                            <i class="small material-icons left">location_city</i>
                            <span>{{ ucfirst($property->city) }}</span>
                        </div>
                        <div class="address">
                            <i class="small material-icons left">place</i>
                            <span>{{ ucfirst($property->address) }}</span>
                        </div>
                        <div class="address">
                            <i class="small material-icons left">check_box</i>
                            <span>{{ __('default.'.$property->type) }}</span>
                        </div>
                        <div class="address">
                            <i class="small material-icons left">check_box</i>
                            <span>{{ __('default.for'.ucfirst($property->purpose)) }}</span>
                        </div>

                        <h5>
                            {{ $property->price }}VNĐ
                            <div class="right" id="propertyrating-{{$property->id}}"></div>
                        </h5>
                    </div>
                    <div class="card-action property-action">
                        @if($property->bedroom > 0)
                        <span class="btn-flat">
                            <i class="material-icons">check_box</i>
                            {{ __('views.default.bedroom') }}: <strong>{{ $property->bedroom}}</strong>
                        </span>
                        <br />
                        @endif
                        @if($property->bathroom > 0)
                        <span class="btn-flat">
                            <i class="material-icons">check_box</i>
                            {{ __('views.default.bathroom') }}: <strong>{{ $property->bathroom}}</strong>
                        </span>
                        <br />
                        @endif
                        <span class="btn-flat">
                            <i class="material-icons">check_box</i>
                            {{ __('views.default.area') }}: <strong>{{ $property->area}}</strong>
                            {{ __('views.default.squareFeet') }}
                        </span>
                        <br />
                        <span class="btn-flat">
                            <i class="material-icons">comment</i>
                            <strong>{{ $property->comments_count}}</strong>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>


<!-- TESTIMONIALS SECTION -->

<section class="section grey lighten-3 center">
    <div class="container">

        <h4 class="section-heading">{{ __('views.index.testimonials') }}</h4>

        <div class="carousel testimonials">

            @foreach($testimonials as $testimonial)
            <div class="carousel-item testimonial-item" href="#{{$testimonial->id}}!">
                <div class="card testimonial-card">
                    <span style="height:20px;display:block;"></span>
                    <div class="card-image testimonial-image">
                        <img src="{{Storage::disk('public')->url('testimonial/'.$testimonial->image)}}">
                    </div>
                    <div class="card-content">
                        <span class="card-title">{{$testimonial->name}}</span>
                        <p>
                            {{$testimonial->testimonial}}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

    </div>

</section>


<!-- BLOG SECTION -->

<section class="section center">
    <div class="row">
        <h4 class="section-heading">{{ __('views.index.recentNews') }}</h4>
    </div>
    <div class="container">
        <div class="row">

            @foreach($posts as $post)
            <div class="col s12 m4">
                <div class="card">
                    <div class="card-image">
                        @if(Storage::disk('public')->exists('posts/'.$post->image) && $post->image)
                        <span class="card-image-bg"
                            style="background-image:url({{Storage::disk('public')->url('posts/'.$post->image)}});"></span>
                        @endif
                    </div>
                    <div class="card-content">
                        <span class="card-title tooltipped" data-position="bottom" data-tooltip="{{$post->title}}">
                            <a href="{{ route('blog.show',$post->slug) }}">{{ str_limit($post->title,18) }}</a>
                        </span>
                        {!! str_limit($post->body,120) !!}
                    </div>
                    <div class="card-action blog-action">
                        <a href="{{ route('blog.author',$post->user->username) }}" class="btn-flat">
                            <i class="material-icons">person</i>
                            <span>{{$post->user->name}}</span>
                        </a>
                        @foreach($post->categories as $key => $category)
                        <a href="{{ route('blog.categories',$category->slug) }}" class="btn-flat">
                            <i class="material-icons">folder</i>
                            <span>{{$category->name}}</span>
                        </a>
                        @endforeach
                        @foreach($post->tags as $key => $tag)
                        <a href="{{ route('blog.tags',$tag->slug) }}" class="btn-flat">
                            <i class="material-icons">label</i>
                            <span>{{$tag->name}}</span>
                        </a>
                        @endforeach
                        <a href="#" class="btn-flat disabled">
                            <i class="material-icons">watch_later</i>
                            <span>{{$post->created_at->diffForHumans()}}</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>

<!-- newsletter -->
<section class="section grey lighten-3 center news-letter">
    <div class="row">
        <h4 class="section-heading">{{ __('views.index.joinNewsLetter') }}</h4>
    </div>
    <div class="container">
        <div class="row">

            <form method="post" action="{{url('newsletter')}}">
                @csrf
                <div class="input-field inline col s10">
                    <input id="email_inline" type="email" class="validate" name="email" />
                    <label for="email_inline">Email</label>
                    <!-- <span class="helper-text" data-error="wrong" data-success="right">Helper text</span> -->
                </div>
                <div class="input-field col s2">

                    <button class="btn waves-effect waves-light" type="submit"
                        name="action">{{ __('views.index.joinNewsLetter.submit') }}
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </form>

        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $(function(){
        var js_properties = <?php echo json_encode($properties);?>;
        js_properties.forEach(element => {
            if(element.rating){
                var elmt = element.rating;
                var sum = 0;
                for( var i = 0; i < elmt.length; i++ ){
                    sum += parseFloat( elmt[i].rating ); 
                }
                var avg = sum/elmt.length;
                if(isNaN(avg) == false){
                    $("#propertyrating-"+element.id).rateYo({
                        rating: avg,
                        starWidth: "20px",
                        readOnly: true
                    });
                }else{
                    $("#propertyrating-"+element.id).rateYo({
                        rating: 0,
                        starWidth: "20px",
                        readOnly: true
                    });
                }
            }
        });
    })
</script>
@endsection