@extends('frontend.layouts.app')

@section('styles')

@endsection

@section('content')

<section class="section">
    <div class="container">
        <div class="row">

            <div class="col s12 m8">

                <div class="card">
                    <div class="card-image">
                        @if(Storage::disk('public')->exists('posts/'.$post->image))
                        <img src="{{Storage::disk('public')->url('posts/'.$post->image)}}" alt="{{$post->title}}">
                        @endif
                        <!-- <img src="{{Storage::disk('public')->url('/posts/'.$post->image)}}" alt="{{$post->title}}"> -->
                    </div>
                    <div class="card-content">
                        <span class="card-title" title="{{$post->title}}">{{ $post->title }}</span>
                        {!! $post->body !!}
                    </div>
                    <div class="card-action blog-action">
                        <a href="{{ route('blog.author',$post->user->username) }}" class="btn-flat">
                            <i class="material-icons">person</i>
                            <span>{{$post->user->name}}</span>
                        </a>
                        <a href="#" class="btn-flat disabled">
                            <i class="material-icons">watch_later</i>
                            <span>{{$post->created_at->diffForHumans()}}</span>
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
                            <i class="material-icons">visibility</i>
                            <span>{{$post->view_count}}</span>
                        </a>
                    </div>
                    <div class="card-action blog-action">
                        {{-- <span>{{ __('view.default.shareOn') }}</span> --}}
                        <a href="http://www.facebook.com/sharer.php?u={{ route('blog.show',$post->slug) }}"
                            target="_blank" class="btn-flat">
                            <span class="mdi mdi-facebook"></span>
                        </a>

                        <a href="http://www.twitter.com/share?url={{ route('blog.show',$post->slug) }}" target="_blank"
                            class="btn-flat">
                            <span class="mdi mdi-twitter"></span>
                        </a>

                        <a href="mailto:?Subject={{ $post->title }}&body={{__('message.iFoundThisPost')}} {{ route('blog.show',$post->slug) }}"
                            target="_blank" class="btn-flat">
                            <span class="mdi mdi-email"></span>
                        </a>

                    </div>

                </div>

                @if($post->related_news && count($post->related_news) > 1)
                <div class="card" id="comments">
                    <div class="p-15 grey lighten-4">
                        <h5 class="m-0">{{ __('default.relatedNews') }}</h5>
                    </div>
                    <div class="single-narebay p-15">

                        @foreach($post->related_news as $news)
                        @if($news->id != $post->id)
                        <div class="comment">
                            <div class="author-image">
                                <span
                                    style="background-image:url({{ Storage::disk('public')->url('users/'.$news->image) }});"></span>
                            </div>
                            <div class="content">
                                <div class="author-name">
                                    <a
                                        href="{{ route('blog.show',$news->slug) }}"><strong>{{ $news->title }}</strong></a>
                                    <span class="time">{{ $news->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="author-comment">
                                    {{ $news->author }}
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="card" id="comments">
                    <div class="p-15 grey lighten-4">
                        <h5 class="m-0">{{ $post->approved_comments_count }} {{ __('default.comments') }}</h5>
                    </div>
                    <div class="single-narebay p-15">
                        @foreach($post->approved_comments as $comment)
                        @if($comment->parent_id == null)
                        <div class="comment">
                            <div class="author-image">
                                <span
                                    style="background-image:url({{ Storage::disk('public')->url('users/'.$comment->users->image) }});"></span>
                            </div>
                            <div class="content">
                                <div class="author-name">
                                    <strong>{{ $comment->users->name }}</strong>
                                    <span class="time">{{ $comment->created_at->diffForHumans() }}</span>

                                    @auth
                                    <span class="right replay" data-commentid="{{ $comment->id }}">Reply</span>
                                    @endauth

                                </div>
                                <div class="author-comment">
                                    {{ $comment->body }}
                                </div>
                            </div>
                            <div id="comment-{{$comment->id}}"></div>
                        </div>
                        @endif

                        @if($comment->children->count() > 0)
                        @foreach($comment->children as $comment)
                        @if($comment->approved == 1)

                        <div class="comment children">
                            <div class="author-image">
                                <span
                                    style="background-image:url({{ Storage::disk('public')->url('users/'.$comment->users->image) }});"></span>
                            </div>
                            <div class="content">
                                <div class="author-name">
                                    <strong>{{ $comment->users->name }}</strong>
                                    <span class="time">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="author-comment">
                                    {{ $comment->body }}
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif

                        @endforeach

                        @auth
                        <div class="comment-box">
                            <h6>{{ __('message.leaveAComment') }}</h6>
                            <form action="{{ route('blog.comment',$post->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent" value="0">

                                <textarea name="body" class="box"></textarea>
                                <input type="submit" class="btn indigo" value="{{ __('default.comment') }}">
                            </form>
                        </div>
                        @endauth

                        @guest
                        <div class="comment-login">
                            <h6>{{ __('message.pleaseLoginToComment') }}</h6>
                            <a href="{{ route('login') }}" class="btn indigo">{{ __('default.login')}}</a>
                        </div>
                        @endguest

                    </div>
                </div>

            </div>

            <div class="col s12 m4">

                @include('pages.blog.sidebar')

            </div>

        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $(document).on('click','span.right.replay',function(e){
        e.preventDefault();
        
        var commentid = $(this).data('commentid');

        $('#comment-'+commentid).empty().append(
            `<div class="comment-box">
                <form action="{{ route('blog.comment',$post->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent" value="1">
                    <input type="hidden" name="parent_id" value="`+commentid+`">
                    
                    <textarea name="body" class="box" placeholder="Leave a comment"></textarea>
                    <input type="submit" class="btn indigo" value="Comment">
                </form>
            </div>`
        );
    });
</script>
@endsection