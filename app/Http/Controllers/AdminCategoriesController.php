<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        $categories = Category::orderByDesc("id")
            ->withTrashed()
            ->paginate(5);
        return view("admin.categories.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        return view("admin.categories.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        //
        request()->validate(
            [
                "name" => ["required", "between:2,255"],
            ],
            [
                "name.required" => "Name is required",
                "title.between" => "Name between 2 and 255 characters",
            ]
        );
        Category::create($request->all());
        return redirect("admin/categories");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $category = Category::findOrFail($id);
        return view("admin.categories.edit", compact("category"));
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
                "name" => ["required", "between:2,255"],
            ],
            [
                "name.required" => "Name is required",
                "title.between" => "Name between 2 and 255 characters",
            ]
        );
        $category = Category::findOrFail($id);
        $category->name = $request->name;

        $category->save();

        return redirect()
            ->route("categories.index")
            ->with("status", "Category updated successfully!");
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
        Category::findOrFail($id)->delete();
        return redirect()
            ->route("categories.index")
            ->with("status", "Category Deleted");
    }
    protected function categoryRestore($id)
    {
        Category::onlyTrashed()
            ->where("id", $id)
            ->restore();
        //return redirect('admin/users');
        //return redirect()->route('admin.users');
        $name = Category::findOrFail($id)->name;
        return back()->with("status", " Post: $name restored!");
    }
    public function category(Category $category)
    {
        $sliderPosts = $category
            ->posts()
            ->with(["photo", "categories"])
            ->latest("created_at")
            ->take(5)
            ->get();
        $posts = $category
            ->posts()
            ->with(["photo", "categories"])
            ->latest("created_at")
            ->paginate(9);
        return view("category", compact("category", "sliderPosts", "posts"));
    }
}
