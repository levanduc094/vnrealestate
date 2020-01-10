@extends('frontend.layouts.app')

@section('styles')
<link rel="stylesheet" href="{{asset('backend/plugins/bootstrap-select/css/bootstrap-select.css')}}">

@endsection

@section('content')

<section class="section">
    <div class="container">
        <div class="row">

            <div class="col s12 m3">
                <div class="agent-sidebar">
                    @include('user.sidebar')
                </div>
            </div>
            <form action="{{route('user.posts.update', $post->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="is_draft" value="0">
                <input type="hidden" name="title" value="{{$post->title}}">
                <input type="hidden" name="body" value="{{$post->body}}">
                <div class="col s12 m9">

                    <h4 class="agent-title">POST</h4>

                    <div class="body">
                        {{-- @if($post->is_draft)
                        <div class="form-group form-float">
                            <div class="form-line">
                                <p>Your post has been modified</p>
                                <label class="form-label">Notification</label>
                            </div>
                        </div>
                        @endif --}}
                        @if(isset($post->expire_date))
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{-- <input type="date" name="title" class="form-control" value="{{$post->expire_date}}">
                                --}}
                                <p><time datetime="{{$post->expire_date}}">{{$post->expire_date}}</time></p>
                                <label class="form-label">Expire date</label>
                            </div>
                        </div>
                        @endif
                        <div class="form-group form-float">
                            <div class="form-line">
                                {{-- <input type="text" name="title" class="form-control" value="{{$post->title}}"> --}}
                                <h4>{{$post->title}}</h4>
                                <label class="form-label">Title</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div name="body" id="tinymce">{!!$post->body!!}</div>
                            <label>Body</label>
                        </div>
                    </div>
                    <div class="m-t-15">
                        <a href="{{route('user.posts.index')}}" class="btn btn-danger btn-lg waves-effect">
                            <i class="material-icons left">arrow_back</i>
                            <span>BACK</span>
                        </a>
                        @if($post->is_draft)
                        <button type="submit" class="btn btn-indigo btn-lg waves-effect">
                            <i class="material-icons">save</i>
                            <span>APPROVE CHANGES</span>
                        </button>
                        @endif
                        @if(!$post->is_approved and ($post->expire_date == null
                        or
                        (time()-(60*60*24)) < strtotime($post->expire_date)))
                            <a href="{{route('user.posts.edit',$post->id)}}" class="btn btn-info btn-lg waves-effect">
                                <i class="material-icons">edit</i>
                                <span>EDIT</span>
                            </a>
                            @endif
                    </div>
                </div>
            </form>
        </div>

    </div>
    </div>
</section>

@endsection