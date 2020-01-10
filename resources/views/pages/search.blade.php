@extends('frontend.layouts.app')

@section('styles')

@endsection

@section('content')

<section>
    <div class="container">
        <div class="row">

            {{-- <div class="col s12 m4 card">

                <h2 class="sidebar-title">search property</h2>

                <form class="sidebar-search" action="{{ route('search')}}" method="GET">

            <div class="searchbar">
                <div class="input-field col s12">
                    <input type="text" name="city" value="{{old('city')}}" id="autocomplete-input-sidebar"
                        class="autocomplete custominputbox" autocomplete="off">
                    <label for="autocomplete-input-sidebar">Enter City or State</label>
                </div>

                <div class="input-field col s12">
                    <select name="type" class="browser-default">
                        @if (old('type') == "")
                        <option value="" disabled selected>Choose Type</option>
                        @else
                        <option value="" disabled>Choose Type</option>
                        @endif
                        @if (old('type') == "apartment")
                        <option value="apartment" selected>Apartment</option>
                        @else
                        <option value="apartment">Apartment</option>
                        @endif
                        @if (old('type') == "house")
                        <option value="house" selected>House</option>
                        @else
                        <option value="house">House</option>
                        @endif
                    </select>
                </div>

                <div class="input-field col s12">
                    <select name="purpose" class="browser-default">
                        @if (old('purpose') == "")
                        <option value="" disabled selected>Choose Purpose</option>
                        @else
                        <option value="" disabled>Choose Purpose</option>
                        @endif
                        @if (old('purpose') == "rent")
                        <option value="rent" selected>Rent</option>
                        @else
                        <option value="rent">Rent</option>
                        @endif
                        @if (old('purpose') == "sale")
                        <option value="sale" selected>Sale</option>
                        @else
                        <option value="sale">Sale</option>
                        @endif
                    </select>
                </div>

                <div class="input-field col s12">
                    <select name="bedroom" class="browser-default">
                        @if (old('bedroom') == "")
                        <option value="" disabled selected>Choose Bedroom</option>
                        @else
                        <option value="" disabled>Choose Bedroom</option>
                        @endif
                        @foreach($bedroomdistinct as $bedroom)
                        @if (old('bedroom') == "{{$bedroom->bedroom}}")
                        <option value="{{$bedroom->bedroom}}" selected>{{$bedroom->bedroom}}</option>
                        @else
                        <option value="{{$bedroom->bedroom}}">{{$bedroom->bedroom}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>

                <div class="input-field col s12">
                    <select name="bathroom" class="browser-default">
                        @if (old('bathroom') == "")
                        <option value="" disabled selected>Choose Bathroom</option>
                        @else
                        <option value="" disabled>Choose Bathroom</option>
                        @endif
                        @foreach($bathroomdistinct as $bathroom)
                        @if (old('bathroom') == "{{$bathroom->bathroom}}")
                        <option value="{{$bathroom->bathroom}}" selected>{{$bathroom->bathroom}}</option>
                        @else
                        <option value="{{$bathroom->bathroom}}">{{$bathroom->bathroom}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>

                <div class="input-field col s12">
                    <input type="number" name="minprice" value="{{old('minprice')}}" id="minprice"
                        class="custominputbox">
                    <label for="minprice">Min Price</label>
                </div>

                <div class="input-field col s12">
                    <input type="number" name="maxprice" value="{{old('maxprice')}}" id="maxprice"
                        class="custominputbox">
                    <label for="maxprice">Max Price</label>
                </div>

                <div class="input-field col s12">
                    <input type="number" name="minarea" value="{{old('minarea')}}" id="minarea" class="custominputbox">
                    <label for="minarea">Floor Min Area</label>
                </div>

                <div class="input-field col s12">
                    <input type="number" name="maxarea" value="{{old('maxarea')}}" id="maxarea" class="custominputbox">
                    <label for="maxarea">Floor Max Area</label>
                </div>

                <div class="input-field col s12">
                    <div class="switch">
                        <label>
                            @if (old('featured') == "on")
                            <input type="checkbox" name="featured" checked />
                            @else
                            <input type="checkbox" name="featured" />
                            @endif
                            <span class="lever"></span>
                            Featured
                        </label>
                    </div>
                </div>
                <div class="input-field col s12">
                    <button class="btn btnsearch indigo" type="submit">
                        <i class="material-icons left">search</i>
                        <span>SEARCH</span>
                    </button>
                </div>
            </div>

            </form>

        </div> --}}

        <div class="col s12 m12">

            @foreach($properties as $property)
            <div class="card horizontal">
                <div>
                    <div class="card-content property-content">
                        @if(Storage::disk('public')->exists('property/'.$property->image) && $property->image)
                        <div class="card-image blog-content-image">
                            <img src="{{Storage::disk('public')->url('property/'.$property->image)}}"
                                alt="{{$property->title}}">
                        </div>
                        @endif
                        <span class="card-title search-title" title="{{$property->title}}">
                            <a href="{{ route('property.show',$property->slug) }}">{{ $property->title }}</a>
                        </span>

                        <div class="address">
                            <i class="small material-icons left">location_city</i>
                            <span>{{ ucfirst($property->city) }}</span>
                        </div>
                        <div class="address">
                            <i class="small material-icons left">place</i>
                            <span>{{ ucfirst($property->address) }}</span>
                        </div>

                        <h5>
                            {{-- &dollar;{{ $property->price }} --}}
                            {{ $property->price }}VNĐ
                            <br />
                            <small class="">{{ __('default.'.$property->type) }}
                                {{ __('default.for'.ucfirst($property->purpose)) }}</small>
                        </h5>

                    </div>
                    <div class="card-action property-action clearfix">
                        @if($property->bedroom > 0)
                        <span class="btn-flat">
                            <i class="material-icons">check_box</i>
                            {{__('views.default.bedroom')}}: <strong>{{ $property->bedroom}}</strong>
                        </span>
                        @endif
                        @if($property->bathroom > 0)
                        <span class="btn-flat">
                            <i class="material-icons">check_box</i>
                            {{__('views.default.bathroom')}}: <strong>{{ $property->bathroom}}</strong>
                        </span>
                        @endif
                        <span class="btn-flat">
                            <i class="material-icons">check_box</i>
                            {{__('views.default.area')}}: <strong>{{ $property->area}}</strong>
                            {{__('views.default.squareFeet')}}
                        </span>
                        <span class="btn-flat">
                            <i class="material-icons">comment</i>
                            {{ $property->comments_count}}
                        </span>

                        @if($property->featured == 1)
                        <span class="right featured-stars">
                            <i class="material-icons">stars</i>
                        </span>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach


            <div class="m-t-30 m-b-60 center">
                {{ 
                            $properties->appends([
                                'city'      => Request::get('city'),
                                'type'      => Request::get('type'),
                                'purpose'   => Request::get('purpose'),
                                'bedroom'   => Request::get('bedroom'),
                                'bathroom'  => Request::get('bathroom'),
                                'minprice'  => Request::get('minprice'),
                                'maxprice'  => Request::get('maxprice'),
                                'minarea'   => Request::get('minarea'),
                                'maxarea'   => Request::get('maxarea'),
                                'featured'  => Request::get('featured')
                            ])->links() 
                        }}
            </div>

        </div>

    </div>
    </div>
</section>

@endsection

@section('scripts')

@endsection