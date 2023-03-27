<?php

namespace App\Http\Controllers;

use App\Events\CommentsSoftDelete;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        $comments = Comment::orderby("post_id", "asc")
            ->orderBy("parent_id", "asc")
            ->orderBy("id", "asc")
            ->paginate(20);
        return view("admin.comments.index", compact("comments"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        if ($user = Auth::user()) {
            $data = [
                "post_id" => $request->post_id,
                "user_id" => $user->id,
                "parent_id" => $request->parent_id,
                "body" => $request->body,
            ];
            Comment::create($data);
        }
        return back()->with("status", "Comment added successfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        //
        $comment = Comment::findOrFail($id);
        return view("admin.comments.show", compact("comment"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        //
        $comment = Comment::findorFail($id);
        return view("admin.comments.edit", compact("comment"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            "body" => "required",
            "post_id" => "required|integer|exists:posts,id",
            "user_id" => "required|integer|exists:users,id",
            "parent_id" => "nullable|integer|exists:comments,id",
        ]);
        //opzoeken van het comment via zijn id
        $comment = Comment::findOrFail($id);
        //update van het comment
        $comment->body = $request->body;
        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user_id;
        $comment->parent_id = $request->parent_id;
        //saven naar db
        $comment->save();
        //redirect
        return redirect()
            ->route("comments.index")
            ->with("status", "Comment updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        //
    }
}
