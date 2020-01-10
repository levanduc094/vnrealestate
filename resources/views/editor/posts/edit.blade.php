@extends('backend.editor.layouts.app')

@section('title', 'Edit Post')

@push('styles')

<link rel="stylesheet" href="{{asset('backend/plugins/bootstrap-select/css/bootstrap-select.css')}}">

@endpush


@section('content')

<div class="block-header"></div>

<div class="row clearfix">
    <form action="{{route('editor.posts.update',$post->slug)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>EDIT POST</h2>
                </div>
                <div class="body">

                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="title" class="form-control" value="{{$post->title}}">
                            <label class="form-label">Post Title</label>
                        </div>
                    </div>

                    <div class="form-group">
                        @if($post->status)
                        @php
                        $checked = 'checked';
                        @endphp
                        @else
                        @php
                        $checked = '';
                        @endphp
                        @endif
                        <input type="checkbox" name="status" class="filled-in" value="{{$post->status}}" {{$checked}}
                            autocomplete="Off" />
                        <label for="status">Published</label>
                    </div>
                    <div class="form-group">
                        @if($post->is_approved)
                        @php
                        $checked = 'checked';
                        @endphp
                        @else
                        @php
                        $checked = '';
                        @endphp
                        @endif
                        <input type="checkbox" id="is_approved" name="is_approved" class="filled-in"
                            value="{{$post->is_approved}}" {{$checked}} autocomplete="Off" />
                        <label for="is_approved">Approved</label>
                    </div>
                    <div class="form-group">
                        <input type="date" name="expire_date" value="{{ $post->expire_date }}" />
                        <label for="expire_date">Expire date</label>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="">Body</label>
                        <textarea name="body" id="tinymce">{{$post->body}}</textarea>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>SELECT CATEGORY</h2>
                </div>
                <div class="body">

                    <div class="form-group form-float">
                        <div class="form-line {{$errors->has('categories') ? 'focused error' : ''}}">
                            <label for="categories">Select Category</label>
                            <select name="categories[]" class="form-control show-tick" id="categories" multiple
                                data-live-search="true">
                                @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-float">
                        <div class="form-line {{$errors->has('tags') ? 'focused error' : ''}}">
                            <label for="tags">Select Tag</label>
                            <select name="tags[]" class="form-control show-tick" id="tags" multiple
                                data-live-search="true">
                                @foreach($tags as $tag)
                                <option value="{{$tag->id}}">{{$tag->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="form-label">Featured Image</label>
                        <input type="file" name="image">
                    </div>


                    <a href="{{route('admin.posts.index')}}" class="btn btn-danger btn-lg m-t-15 waves-effect">
                        <i class="material-icons left">arrow_back</i>
                        <span>BACK</span>
                    </a>

                    <button type="submit" class="btn btn-indigo btn-lg m-t-15 waves-effect">
                        <i class="material-icons">save</i>
                        <span>UPDATE</span>
                    </button>

                </div>
            </div>
        </div>
    </form>
    <form class="author-message-box" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="author_id" value="{{ $post->user->id }}">
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        <input type="hidden" name="post_title" value="{{ $post->title }}">
        <input type="hidden" name="name" value="{{ $post->user->name }}">
        <input type="hidden" name="email" value="{{ $post->user->email }}">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>{{__('message.contactWithAuthor')}}</h2>
                </div>
                <div class="body">

                    <div class="clearfix">

                        <div>
                            <ul class="collection with-header m-t-0 p-l-0">
                                <li class="collection-item p-0">
                                    @if($post->user)
                                    <div class="card horizontal card-no-shadow">
                                        <div class="card-image p-l-10 agent-image">
                                            <img src="{{Storage::disk('public')->url('users/'.$post->user->image)}}"
                                                alt="{{ $post->user->username }}" class="imgresponsive">
                                        </div>
                                        <div class="card-stacked">
                                            <div class="p-l-10 p-r-10">
                                                <h5 class="m-t-b-0">{{ $post->user->name }}</h5>
                                                <h5 class="m-t-b-0">{{ $post->user->email }}</h5>
                                                <a href="{{ route('agents.show',$post->user->id) }}"
                                                    class="profile-link">{{__('default.profile')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </li>
                                <li class="collection agent-message">
                                    <div class="agent-message-box">
                                        <div class="box">
                                            <textarea name="message" placeholder="Your Messsage"></textarea>
                                        </div>
                                        <div class="box p-t-15">
                                            <button id="msgsubmitbtn" class="btn waves-effect waves-light w100 indigo"
                                                type="submit">
                                                {{__('default.send')}}
                                                <i class="material-icons left">send</i>
                                            </button>
                                        </div>
                                </li>
                            </ul>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

{{-- SELECTED CATEGORIES --}}
@php
$categories = [];
@endphp
@foreach($post->categories as $category)
@php
$categories[] = $category->id;
@endphp
@endforeach

@endsection


@push('scripts')

<script src="{{ asset('backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
<script>
    @php
            $selectedcategory = json_encode($categories);
            $selectedtags = json_encode($selectedtag);
        @endphp

        $('#categories').selectpicker();
        $('#categories').selectpicker('val',{{$selectedcategory}});

        $('#tags').selectpicker();
        $('#tags').selectpicker('val',{{$selectedtags}});
        
</script>

<script src="{{asset('backend/plugins/tinymce/tinymce.js')}}"></script>
<script>
    $(function () {
            tinymce.init({
                selector: "textarea#tinymce",
                theme: "modern",
                height: 300,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons',
                image_advtab: true
            });
            tinymce.suffix = ".min";
            tinyMCE.baseURL = '{{asset('backend/plugins/tinymce')}}';

            //MESSAGE
            $(document).on('submit','.author-message-box',function(e){
                e.preventDefault();

                var data = $(this).serialize();
                var url = "{{ route('blog.message') }}";
                var btn = $('#msgsubmitbtn');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    beforeSend: function() {
                        $(btn).addClass('disabled');
                        $(btn).empty().append('LOADING...<i class="material-icons left">rotate_right</i>');
                    },
                    success: function(data) {
                        if (data.message) {
                            M.toast({html: data.message, classes:'green darken-4'})
                        }
                    },
                    error: function(xhr) {
                        M.toast({html: xhr.statusText, classes: 'red darken-4'})
                    },
                    complete: function() {
                        $('form.author-message-box')[0].reset();
                        $(btn).removeClass('disabled');
                        $(btn).empty().append('SEND<i class="material-icons left">send</i>');
                    },
                    dataType: 'json'
                });

            })
        });

    // document.addEventListener('DOMContentLoaded', function() {
    //     var elems = document.querySelectorAll('.datepicker');
    //     var instances = M.Datepicker.init(elems, options);
    // });
</script>

@endpush