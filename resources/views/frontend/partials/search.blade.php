<!-- SEARCH SECTION -->

<section class="indigo darken-2 white-text center">
    <div class="container">
        <div class="row m-b-0">
            <div class="col s12">

                <form action="{{ route('search')}} " method="GET">

                    <div class="searchbar">
                        <div class="input-field col s12 m3">
                            <input type="text" name="city" id="autocomplete-input" class="autocomplete custominputbox"
                                autocomplete="off">
                            <label for="autocomplete-input">{{ __('views.search.enterCityState') }}</label>
                        </div>

                        <div class="input-field col s12 m2">
                            <select name="type" class="browser-default">
                                <option value="" disabled selected>{{ __('views.search.chooseType') }}</option>
                                <option value="apartment">{{ __('views.search.chooseType.apartment') }}</option>
                                <option value="house">{{ __('views.search.chooseType.house') }}</option>
                                <option value="land">{{ __('views.search.chooseType.land') }}</option>
                            </select>
                        </div>

                        <div class="input-field col s12 m2">
                            <select name="purpose" class="browser-default">
                                <option value="" disabled selected>{{ __('views.search.purpose') }}</option>
                                <option value="rent">{{ __('views.search.purpose.rent') }}</option>
                                <option value="sale">{{ __('views.search.purpose.sale') }}</option>
                            </select>
                        </div>

                        <div class="input-field col s12 m2">
                            <select name="bedroom" class="browser-default">
                                <option value="" disabled selected>{{ __('views.search.bedroom') }}</option>
                                @if(isset($bedroomdistinct))
                                @foreach($bedroomdistinct as $bedroom)
                                @if($bedroom->bedroom > 0)
                                <option value="{{$bedroom->bedroom}}">{{$bedroom->bedroom}}</option>
                                @endif
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="input-field col s12 m2">
                            <input type="text" name="maxprice" id="maxprice" class="custominputbox">
                            <label for="maxprice">{{ __('views.search.maxPrice') }}</label>
                        </div>

                        <div class="input-field col s12 m1">
                            <button class="btn btnsearch waves-effect waves-light w100" type="submit">
                                <i class="material-icons">search</i>
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>