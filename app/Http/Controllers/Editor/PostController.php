<?php

namespace App\Http\Controllers\Editor;

use App\Post;
use App\User;
use App\Category;
use App\Tag;
use App\Comment;
use App\Message;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Toastr;
use Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::latest()->withCount('comments')->get();

        return view('editor.posts.index',compact('posts'));
    }

    public function postFromUsers()
    {
        $posts = Post::latest()->withCount('comments')->where('user_id', 4)->get();

        return view('editor.posts.index',compact('posts'));
    }


    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('editor.posts.create',compact('categories','tags'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|unique:posts|max:255',
            'image'     => 'mimes:jpeg,jpg,png',
            // 'categories'=> 'required',
            // 'tags'      => 'required',
            'body'      => 'required'
        ]);

        $image = $request->file('image');
        $slug  = str_slug($request->title);

        if(isset($image)){
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //dd(Storage::disk('public_html'));

            if(!Storage::disk('public')->exists('posts')){
                Storage::disk('public')->makeDirectory('posts');
            }
            $postimage = Image::make($image)->resize(1600, 980)->save($imagename);
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
        $meta_data = [];
        foreach ($request->tags as $tagId) {
            $temp = Tag::where('id', $tagId)->first()->name;
            array_push($meta_data, $temp);
        }
        $post->meta_data = join(' ', $meta_data);
        $post->save();

        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        Toastr::success('message', 'Post created successfully.');
        return redirect()->route('editor.posts.index');

    }


    public function show(Post $post)
    {
        $post = Post::withCount('comments')->find($post->id);

        return view('editor.posts.show',compact('post'));
    }


    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $post = Post::find($post->id);
        $selectedtag = $post->tags->pluck('id');
        return view('editor.posts.edit',compact('categories','tags','post','selectedtag'));
    }


    public function update(Request $request, $post)
    {
        $request->validate([
            'title'     => 'required|max:255',
            'image'     => 'mimes:jpeg,jpg,png',
            // 'categories'=> 'required',
            // 'tags'      => 'required',
            'body'      => 'required'
        ]);

        $image = $request->file('image');
        $slug  = str_slug($request->title);
        
        $post = Post::find($post->id);
        if(isset($image)){
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('posts')){
                Storage::disk('public')->makeDirectory('posts');
            }
            $postimage = Image::make($image)->resize(1600, 980)->save($imagename);
            Storage::disk('public')->put('posts/'.$imagename, $postimage);

        }else{
            $imagename = $post->image;
        }

        //$post->user_id = Auth::id();
        if(isset($request->status)){
            $post->status = true;
        }else{
            $post->status = false;
        }
        if(isset($request->is_approved)){
            $post->is_approved = true;
        }else{
            $post->is_approved = false;
        }
        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);
        $meta_data = [];
        if(isset($request->tags)){
            foreach ($request->tags as $tagId) {
                $temp = Tag::where('id', $tagId)->first()->name;
                array_push($meta_data, $temp);
            }
        }
        
        $post->meta_data = join(' ', $meta_data);
        
        $message = "Your post with title [". $request->title ."] has been modified";
        if($post->user_id != auth()->id()){
            if(($post->title != $request->title) or ($post->body != $request->body)){
                $post->is_draft = true;
            }else{
                $message = "Your post with title [". $request->title ."] will be expired on " . $request->expire_date ;
            }
            if(isset($request->is_approved)){
                $message = "Your post with title [". $request->title ."] has been approved" ;
            }
            if($request->status){
                $message = "Your post with title [". $request->title ."] has been published" ;
            }
            Message::create([
                'agent_id'  => $post->user_id,
                'user_id'   => Auth::id(),
                'name'      => "admin",
                'email'     => "admin@admin.com",
                'phone'     => "",
                'message'   => $message
            ]);
        }
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imagename;
        $post->expire_date = $request->expire_date;
        $post->body = $request->body;
        $post->save();
        Toastr::success('message', 'Post updated successfully.');
        return redirect()->route('editor.posts.index');
    }


    public function destroy(Post $post)
    {
        $post = Post::find($post->id);

        if(Storage::disk('public')->exists('posts/'.$post->image)){
            Storage::disk('public')->delete('posts/'.$post->image);
        }

        $post->delete();
        $post->categories()->detach();
        $post->tags()->detach();
        $post->comments()->delete();

        Toastr::success('message', 'Post deleted successfully.');
        return back();
    }
}
