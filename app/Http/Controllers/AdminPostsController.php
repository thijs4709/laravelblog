<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //

        $posts = Post::with(["categories", "user", "photo"])
            ->filter(request("search"), request("fields"))
            ->withTrashed()
            ->paginate(20)
            ->appends(["search", "fields" => request("fields")]);
        return view("admin.posts.index", [
            "posts" => $posts,
            "fillableFields" => Post::getFillableFields(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view("admin.posts.create", compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
        request()->validate(
            [
                "title" => ["required", "between:2,255"],
                "categories" => ["required", Rule::exists("categories", "id")],
                "body" => "required",
            ],
            [
                "title.required" => "Title is required",
                "title.between" => "Title between 2 and 255 characters",
                "body.required" => "Message is required",
                "categories.required" => "Please check minimum one category",
            ]
        );
        $post = new Post();
        $post->user_id = Auth::user()->id;
        $post->title = $request->title;
        //$post->slug = $post->slugify($post->title); je kan 1 van deze 2 kiezen
        //$post->slug = Str::slug($post->title);
        $post->slug = $post->slugify($post->title);
        $post->body = $request->body;

        if ($file = $request->file("photo_id")) {
            $path = request()
                ->file("photo_id")
                ->store("posts");
            $photo = Photo::create(["file" => $path]);
            $post->photo_id = $photo->id;
        }
        //        user_id, post_id,title en body zijn nu ingevuld. We saven naar posts.
        $post->save();
        /*aangeduide categoriëen overschrijven en eventuele vorige deleten of nieuwe toevoegen*/
        $post->categories()->sync($request->categories, false);
        return redirect()
            ->route("posts.index")
            ->with("status", "Post Updated");
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Post $post)
    {
        //
        //$post = Post::findOrFail($id);
        $slug = $post->slugify($post->title);
        return view("admin.posts.show", compact("post", "slug"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        //
        $post = Post::findorFail($id);
        $categories = Category::all();
        return view("admin.posts.edit", compact("categories", "post"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        //
        request()->validate(
            [
                "title" => ["required", "between:2,255"],
                "categories" => ["required", Rule::exists("categories", "id")],
                "body" => "required",
            ],
            [
                "title.required" => "Title is required",
                "title.between" => "Title between 2 and 255 characters",
                "body.required" => "Message is required",
                "categories.required" => "Please check minimum one category",
            ]
        );
        $post = Post::findOrFail($id);
        $input = $request->all();
        //$input['slug'] = Str::slug($input['title']);
        $input["slug"] = $post->slugify($input["title"]);

        if ($request->hasFile("photo_id")) {
            //file upload
            //ophalen photo uit database
            $oldPhoto = $post->photo;
            $path = request()
                ->file("photo_id")
                ->store("posts");
            //is er een photo aanwezig
            // dd($oldPhoto);
            if ($oldPhoto) {
                unlink(public_path($oldPhoto->file));
                //update in de database van mijn oude foto
                $oldPhoto->update(["file" => $path]);
                $input["photo_id"] = $oldPhoto->id;
            } else {
                $photo = Photo::create(["file" => $path]);
                $input["photo_id"] = $photo->id;
            }
        }
        //        user_id, post_id,title en body zijn nu ingevuld. We saven naar posts.
        $post->update($input);
        /*aangeduide categoriëen overschrijven en eventuele vorige deleten of nieuwe toevoegen*/
        $post->categories()->sync($request->categories, true);
        return redirect()
            ->route("posts.index")
            ->with("status", "Post Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        //
        Post::findOrFail($id)->delete();
        return redirect()
            ->route("posts.index")
            ->with("status", "Post Deleted");
    }
    public function indexByAuthor(User $author)
    {
        $posts = $author
            ->posts()
            ->with("photo", "categories", "user")
            ->paginate(20);
        return view("admin.posts.index", ["posts" => $posts]);
    }
    protected function postRestore($id)
    {
        Post::onlyTrashed()
            ->where("id", $id)
            ->restore();
        //return redirect('admin/users');
        //return redirect()->route('admin.users');
        $name = Post::findOrFail($id)->title;
        $id1 = Post::findOrFail($id)->id;

        return back()->with("status", " Post: $name restored! (id = $id1)");
    }
    /**frontend methods**/
    public function post($id)
    {
        $post = Post::findOrFail($id);
        $postsTickers = Post::latest("created_at")
            ->take(6)
            ->get();
        $categories = Category::all();
        return view("post", compact("post", "postsTickers", "categories"));
    }
}
