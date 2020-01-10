@extends('frontend.layouts.app')

@section('styles')
<link rel="stylesheet"
    href="{{ asset('backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}">

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

            <div class="col s12 m9">

                <h4 class="agent-title">POSTS</h4>
                <div class="block-header">
                    <a href="{{route('user.posts.create')}}" class="waves-effect waves-light btn right m-b-15 addbtn">
                        <i class="material-icons left">add</i>
                        <span>CREATE </span>
                    </a>
                </div>
                <div class="agent-content">
                    {{-- <table class="striped responsive-table"> --}}
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">

                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Title</th>
                                <th>Is Approved</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach( $posts as $key => $post )
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    <span title="{{$post->title}}">
                                        {{ str_limit($post->title,10) }}
                                    </span>
                                </td>
                                <td>
                                    @if($post->is_approved == true)
                                    <span>Approved</span>
                                    @else
                                    <span>Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->status == true)
                                    <span>Published</span>
                                    @else
                                    <span>Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{route('user.posts.show',$post->id)}}"
                                        class="btn btn-success btn-sm waves-effect">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    @if($post->status != 1 and $post->is_approved != 1 and ($post->expire_date == null
                                    or
                                    (time()-(60*60*24)) < strtotime($post->expire_date)))
                                        <a href="{{route('user.posts.edit',$post->id)}}"
                                            class="btn btn-info btn-sm waves-effect">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        {{-- <button type="button" class="btn btn-danger btn-sm waves-effect"
                                            onclick="deletePost({{$post->id}})">
                                        <i class="material-icons">delete</i>
                                        </button>
                                        <form action="{{route('admin.posts.destroy',$post->slug)}}" method="POST"
                                            id="del-post-{{$post->id}}" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form> --}}
                                        @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="center">
                    </div>
                </div>

            </div>


</section>

@endsection

@section('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    function deleteMessage(id){
            swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            buttons: true,
            dangerMode: true,
            buttons: ["Cancel", "Yes, delete it!"]
            }).then((value) => {
                if (value) {
                    document.getElementById('del-message-'+id).submit();
                    swal(
                    'Deleted!',
                    'Message has been deleted.',
                    'success',
                    {
                        buttons: false,
                        timer: 1000,
                    })
                }
            })
        }
</script>
@endsection