@extends('backend.editor.layouts.app')

@section('title', 'Edit Comment')

@push('styles')

<link rel="stylesheet" href="{{asset('backend/plugins/bootstrap-select/css/bootstrap-select.css')}}">

@endpush


@section('content')

<div class="block-header"></div>

<div class="row clearfix">
    <form action="{{route('admin.comments.update',$comment->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>EDIT COMMENT</h2>
                </div>
                <div class="body">
                    <form action="{{route('admin.comments.update',$comment->id)}}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="body" class="form-control" value="{{$comment->user_name}}">
                                <label class="form-label">User name</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="body" class="form-control" value="{{$comment->body}}">
                                <label class="form-label">Body</label>
                            </div>
                        </div> -->
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" name="approved" class="form-control" value="{{$comment->approved}}">
                                <label class="form-label">Approved</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-indigo btn-lg m-t-15 waves-effect">
                            <i class="material-icons">update</i>
                            <span>Update</span>
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


@push('scripts')

<script src="{{ asset('backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>


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
        });
</script>

@endpush