@extends('backend.admin.layouts.app')

@section('title', 'Show comment')

@push('styles')


@endpush


@section('content')

<div class="block-header"></div>

<div class="row clearfix">

    <div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
        <div class="card">

            <div class="header bg-indigo">
                <h2>SHOW COMMENT</h2>
            </div>

            <div class="header">
                <h2>
                    User name
                    <br>
                </h2>
                {{$comment->user_name}}
            </div>

            <div class="header">
                <h2>
                    Body
                    <br>
                </h2>
                {{$comment->body}}
            </div>

            <div class="header">
                <h2>
                    Created at
                    <br>
                </h2>
                {{$comment->created_at}}
            </div>

        </div>
    </div>




</div>


@endsection


@push('scripts')

<script>



</script>


@endpush