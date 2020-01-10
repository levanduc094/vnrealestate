<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;

use App\Property;
use App\Message;
use App\Gallery;
use App\Comment;
use App\Rating;
use App\Tag;
use App\Post_tag;
use App\Post;
use App\User;

use Carbon\Carbon;
use Auth;
use DB;

class PagesController extends Controller
{
    public function properties()
    {
        $cities     = Property::select('city','city_slug')->distinct('city_slug')->get();
        $properties = Property::latest()->with('rating')->withCount('comments')->paginate(12);

        return view('pages.properties.property', compact('properties','cities'));
    }

    public function propertieshow($slug)
    {
        $property = Property::with('features','gallery','user','comments')
                            ->withCount('approvedComments')
                            ->where('slug', $slug)
                            ->first();
        $property->approved_comments = Comment::where('commentable_id', $property->id)->where('approved', 1)->get(); 
                           

        $rating = Rating::where('property_id',$property->id)->where('type','property')->avg('rating');                   

        $relatedproperty = Property::latest()
                    ->where('purpose', $property->purpose)
                    ->where('type', $property->type)
                    ->where('bedroom', $property->bedroom)
                    ->where('bathroom', $property->bathroom)
                    ->where('id', '!=' , $property->id)
                    ->take(5)->get();

        $videoembed = $this->convertYoutube($property->video, 560, 315);

        $cities = Property::select('city','city_slug')->distinct('city_slug')->get();

        return view('pages.properties.single', compact('property','rating','relatedproperty','videoembed','cities'));
    }


    // AGENT PAGE
    public function agents()
    {
        $agents = User::latest()->where('role_id', 2)->paginate(12);

        return view('pages.agents.index', compact('agents'));
    }

    public function agentshow($id)
    {
        $agent      = User::findOrFail($id);
        $properties = Property::latest()->where('agent_id', $id)->paginate(10);

        return view('pages.agents.single', compact('agent','properties'));
    }


    // BLOG PAGE
    public function blog()
    {
        $month = request('month');
        $year  = request('year');

        $posts = Post::latest()->withCount('comments')
                                ->when($month, function ($query, $month) {
                                    return $query->whereMonth('created_at', Carbon::parse($month)->month);
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->where('status',1)
                                ->paginate(10);

        return view('pages.blog.index', compact('posts'));
    }

    public function blogshow($slug)
    {
        $post = Post::withCount('approvedComments')->where('slug', $slug)->first(); 
        $post->approved_comments = Comment::where('commentable_id', $post->id)->where('approved', 1)->get(); 
        //$post->comments = $post->comments->where('approved', 1); 
        Post::where('id', '!=', $post->id)->searchable();
        // Post::where('id', $post->id)->unsearchable();
        Post::where('status', 0)->unsearchable();
        $related_news = [];
        if(!(!isset($post->meta_data) || trim($post->meta_data) === '')){
            $related_news = Post::search($post->meta_data)->where('status', 1)->take(3)->get();
            foreach ($related_news as $related_new) {
                $related_new->author = User::where('id', $related_new->user_id)->first()->name;
            }
            // $related_news = $related_news::where('id', '!=', $post->id)->get();
        }

        $post->related_news = $related_news;

        $blogkey = 'blog-' . $post->id;
        if(!Session::has($blogkey)){
            $post->increment('view_count');
            Session::put($blogkey,1);
        }
        //return $post;
        return view('pages.blog.single', compact('post'));
    }


    // BLOG COMMENT
    public function blogComments(Request $request, $id)
    {
        $request->validate([
            'body'  => 'required'
        ]);

        $post = Post::find($id);

        $post->comments()->create(
            [
                'user_id'   => Auth::id(),
                'body'      => $request->body,
                'parent'    => $request->parent,
                'parent_id' => $request->parent_id,
                'approved'  => 0
            ]
        );

        return back();
    }


    // BLOG CATEGORIES
    public function blogCategories()
    {
        $posts = Post::latest()->withCount(['comments','categories'])
                                ->whereHas('categories', function($query){
                                    $query->where('categories.slug', '=', request('slug'));
                                })
                                ->where('status',1)
                                ->paginate(10);

        return view('pages.blog.index', compact('posts'));
    }

    // BLOG TAGS
    public function blogTags()
    {
        $posts = Post::latest()->withCount('comments')
                                ->whereHas('tags', function($query){
                                    $query->where('tags.slug', '=', request('slug'));
                                })
                                ->where('status',1)
                                ->paginate(10);

        return view('pages.blog.index', compact('posts'));
    }

    // BLOG AUTHOR
    public function blogAuthor()
    {
        $posts = Post::latest()->withCount('comments')
                                ->whereHas('user', function($query){
                                    $query->where('username', '=', request('username'));
                                })
                                ->where('status',1)
                                ->paginate(10);

        return view('pages.blog.index', compact('posts'));
    }



    // MESSAGE TO AGENT (SINGLE AGENT PAGE)
    public function messageAgent(Request $request)
    {
        $request->validate([
            'agent_id'  => 'required',
            'name'      => 'required',
            'email'     => 'required',
            'phone'     => 'required',
            'message'   => 'required'
        ]);

        Message::create($request->all());

        if($request->ajax()){
            return response()->json(['message' => 'Message send successfully.']);
        }

    }

    // MESSAGE TO AUTHO 
    public function messageAuthor(Request $request)
    {
        $request->validate([
            'author_id'  => 'required',
            'user_id'  => 'required',
            'name'      => 'required',
            'email'     => 'required',
            'message'   => 'required',
            'post_title'   => 'required',
        ]);

        $message = '[' . $request->post_title . '] ' . $request->message;
        Message::create([
            'agent_id'  => $request->author_id,
            'user_id'   => Auth::user()->id,
            'name'      => Auth::user()->name,
            'email'     => Auth::user()->email,
            'phone'     => "",
            'message'   => $message
        ]);
        
        Toastr::success('message', 'Message send successfully.');
        if($request->ajax()){
            return response()->json(['message' => 'Message send successfully.']);
        }

    }

    
    // CONATCT PAGE
    public function contact()
    {
        return view('pages.contact');
    }

    public function messageContact(Request $request)
    {
        //dd($request);
        $request->validate([
            'name'      => 'required',
            'email'     => 'required',
            'phone'     => 'required',
            'message'   => 'required'
        ]);

        $message  = $request->message;
        $mailfrom = $request->email;
        
        Message::create([
            'agent_id'  => 1,
            'name'      => $request->name,
            'email'     => $mailfrom,
            'phone'     => $request->phone,
            'message'   => $message
        ]);
            
        $adminname  = User::find(1)->name;
        $mailto     = $request->mailto;
        $clientname  = $request->name;
        $phone  = $request->phone;


        Mail::to($mailto)->send(new Contact($message,$adminname,$mailfrom,$clientname,$phone));

        if($request->ajax()){
            return response()->json(['message' => 'Message send successfully.', 'data' => $mailfrom]);
        }

    }

    public function contactMail(Request $request)
    {
        return $request->all();
    }


    // GALLERY PAGE
    public function gallery()
    {
        $galleries = Gallery::latest()->paginate(12);

        return view('pages.gallery',compact('galleries'));
    }


    // PROPERTY COMMENT
    public function propertyComments(Request $request, $id)
    {
        $request->validate([
            'body'  => 'required'
        ]);

        $property = Property::find($id);

        $property->comments()->create(
            [
                'user_id'   => Auth::id(),
                'body'      => $request->body,
                'parent'    => $request->parent,
                'parent_id' => $request->parent_id,
                'approved'  => 0
            ]
        );

        return back();
    }


    // PROPERTY RATING
    public function propertyRating(Request $request)
    {
        $rating      = $request->input('rating');
        $property_id = $request->input('property_id');
        $user_id     = $request->input('user_id');
        $type        = 'property';

        $rating = Rating::updateOrCreate(
            ['user_id' => $user_id, 'property_id' => $property_id, 'type' => $type],
            ['rating' => $rating]
        );

        if($request->ajax()){
            return response()->json(['rating' => $rating]);
        }
    }


    // PROPERTY CITIES
    public function propertyCities()
    {
        $cities     = Property::select('city','city_slug')->distinct('city_slug')->get();
        $properties = Property::latest()->with('rating')->withCount('comments')
                        ->where('city_slug', request('cityslug'))
                        ->paginate(12);

        return view('pages.properties.property', compact('properties','cities'));
    }


    // YOUTUBE LINK TO EMBED CODE
    private function convertYoutube($youtubelink, $w = 250, $h = 140) {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "<iframe width=\"$w\" height=\"$h\" src=\"//www.youtube.com/embed/$2\" frameborder=\"0\" allowfullscreen></iframe>",
            $youtubelink
        );
    }
    
}
