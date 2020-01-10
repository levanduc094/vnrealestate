@extends('frontend.layouts.app')

@section('styles')

@endsection

@section('content')

<section class="section">
    <div class="container">
        <div class="row">

            <div class="col s12 m8">
                <div class="contact-content">
                    <h4 class="contact-title">{{ __('views.default.contactUs') }}</h4>

                    <form id="contact-us" action="{{ route('contact.message') }}" method="POST">
                        @csrf
                        <input type="hidden" name="mailto"
                            value="{{ $contactsettings[0]['email'] ?? 'levanduc094@gmail.com' }}">

                        @auth
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        @endauth

                        @auth
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <input id="name" name="name" type="text" class="validate" value="{{ auth()->user()->name }}"
                                readonly>
                            <label for="name">{{ __('default.name')}}</label>
                        </div>
                        @endauth
                        @guest
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <input id="name" name="name" type="text" class="validate">
                            <label for="name">{{ __('default.name')}}</label>
                        </div>
                        @endguest

                        @auth
                        <div class="input-field col s12">
                            <i class="material-icons prefix">mail</i>
                            <input id="email" name="email" type="email" class="validate"
                                value="{{ auth()->user()->email }}" readonly>
                            <label for="email">{{ __('default.email')}}</label>
                        </div>
                        @endauth
                        @guest
                        <div class="input-field col s12">
                            <i class="material-icons prefix">mail</i>
                            <input id="email" name="email" type="email" class="validate">
                            <label for="email">{{ __('default.email')}}</label>
                        </div>
                        @endguest

                        <div class="input-field col s12">
                            <i class="material-icons prefix">phone</i>
                            <input id="phone" name="phone" type="number" class="validate">
                            <label for="phone">{{ __('default.phone')}}</label>
                        </div>

                        <div class="input-field col s12">
                            <i class="material-icons prefix">mode_edit</i>
                            <textarea id="message" name="message" class="materialize-textarea"></textarea>
                            <label for="message">{{ __('default.message')}}</label>
                        </div>

                        <button id="msgsubmitbtn" class="btn waves-effect waves-light indigo darken-4 btn-large"
                            type="submit">
                            <span>{{ __('default.send')}}</span>
                            <i class="material-icons right">send</i>
                        </button>

                    </form>

                </div>
            </div> <!-- /.col -->

            <div class="col s12 m4">
                <div class="contact-sidebar">
                    <div class="m-t-30">
                        <i class="material-icons left">call</i>
                        <h6 class="uppercase">{{ __('message.callUsNow')}}</h6>
                        @if(isset($contactsettings[0]) && $contactsettings[0]['phone'])
                        <h6 class="bold m-l-40">{{ $contactsettings[0]['phone'] }}</h6>
                        @endif
                    </div>
                    <div class="m-t-30">
                        <i class="material-icons left">mail</i>
                        <h6 class="uppercase">{{ __('default.email')}}</h6>
                        @if(isset($contactsettings[0]) && $contactsettings[0]['email'])
                        <h6 class="bold m-l-40">{{ $contactsettings[0]['email'] }}</h6>
                        @endif
                    </div>
                    <div class="m-t-30">
                        <i class="material-icons left">map</i>
                        <h6 class="uppercase">{{ __('default.address')}}</h6>
                        @if(isset($contactsettings[0]) && $contactsettings[0]['address'])
                        <h6 class="bold m-l-40">{!! $contactsettings[0]['address'] !!}</h6>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $('textarea#message').characterCounter();

        $(function(){
            $(document).on('submit','#contact-us',function(e){
                e.preventDefault();

                var data = $(this).serialize();
                var url = "{{ route('contact.message') }}";
                var btn = $('#msgsubmitbtn');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    beforeSend: function() {
                        $(btn).addClass('disabled');
                        $(btn).empty().append('<span>LOADING...</span><i class="material-icons right">rotate_right</i>');
                    },
                    success: function(data) {
                        if (data.message) {
                            M.toast({html: data.message, classes:'green darken-4'});
                        }
                    },
                    error: function(xhr) {
                        M.toast({html: 'ERROR: Failed to send message!', classes: 'red darken-4'});

                    },
                    complete: function() {
                        $('form#contact-us')[0].reset();
                        $(btn).removeClass('disabled');
                        $(btn).empty().append('<span>SEND</span><i class="material-icons right">send</i>');
                    },
                    dataType: 'json'
                });

            })
        })

</script>
@endsection