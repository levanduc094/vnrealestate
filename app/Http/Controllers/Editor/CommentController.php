<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use App\Category;
use App\Tag;
use App\Comment;
use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Toastr;
use Auth;

class CommentController extends Controller
{

    public function index()
    {
        $comments = Comment::latest()->get();

        //return $comments;
        return view('admin.comments.index',compact('comments'));
    }


    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create',compact('categories','tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|unique:posts|max:255',
            // 'image'     => 'required|mimes:jpeg,jpg,png',
            'categories'=> 'required',
            'tags'      => 'required',
            'body'      => 'required'
        ]);

        $image = $request->file('image');
        $slug  = str_slug($request->title);

        if(isset($image)){
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('posts')){
                Storage::disk('public')->makeDirectory('posts');
            }
            $postimage = Image::make($image)->resize(1600, 980)->save();
            Storage::disk('public')->put('posts/'.$imagename, $postimage);

        }else{
            $imagename = 'default.png';
        }

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imagename;
        $post->body = $request->body;
        if(isset($request->status)){
            $post->status = true;
        }
        $post->is_approved = true;
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        Toastr::success('message', 'Post created successfully.');
        return redirect()->route('admin.posts.index');

    }


    public function show(Comment $comment)
    {
        $comment = Comment::find($comment->id);
        $user_name = User::find($comment->user_id)->name;
        $comment->user_name = $user_name;
        return view('admin.comments.show',compact('comment'));
    }


    public function edit(Comment $comment)
    {
        $comment = Comment::find($comment->id);
        return view('admin.comments.edit',compact('comment'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'approved' => 'required|max:255'
        ]);

        $comment = Comment::find($id);
        $comment->approved = $request->approved;
        $comment->save();

        Toastr::success('message', 'Comment updated successfully.');
        return redirect()->route('admin.comments.index');
    }


    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        // $comment->posts()->detach();
        Toastr::success('message', 'Comment deleted successfully.');
        return back();
    }
}
