<?php

namespace App\Http\Controllers;

use App\Events\UsersSoftDelete;
use App\Http\Requests\UsersRequest;
use App\Models\Photo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        $users = User::with(["roles", "photo"])
            ->orderByDesc("id")
            ->withTrashed()
            ->paginate(10);
        //        $users = DB::table('users')->get(); //eerste stap voor hieronder om alles van users weet te geven
        //        $users = DB::table('users') //dit gebruik je als je moeilijke querries moet schrijven (doet het zelfde als  $users = DB::table('users')->get();)
        //            ->select('users.id as user_id', 'photo_id', 'users.name as user_name', 'users.email', DB::raw('GROUP_CONCAT(roles.name) as role_names'), 'is_active', 'users.created_at as user_created_at', 'users.updated_at as user_updated_at')
        //            ->leftJoin('user_role', 'users.id', '=', 'user_role.user_id')
        //            ->leftJoin('roles', 'user_role.role_id', '=', 'roles.id')
        //            ->groupBy('users.id', 'photo_id', 'user_name', 'email', 'is_active', 'user_created_at', 'user_updated_at')
        //            ->orderBy('users.id')
        //            ->get();
        //        $users = $users->map(function ($user){
        //           $user->role_names = explode(',', $user->role_names);
        //           return $user;
        //        });
        //$users = User::all();
        //$users = User::where('email','diego.oconnell@example.com')->orderByDesc('id')->take(10)->get();

        return view("admin.users.index", ["users" => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        $roles = Role::pluck("name", "id")->all();
        return view("admin.users.create", compact("roles"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function index2()
    {
        //
        $users = User::with("photo", "roles")
            ->orderByDesc("id")
            ->withTrashed()
            ->paginate(10);
        return view("admin.users.index2", ["users" => $users]);
    }

    public function store(UsersRequest $request)
    {
        //
        //        User::create([
        //           'name'=>$request['name'],
        //           'email'=>$request['email'],
        //           'password'=>Hash::make($request['password']),
        //            'role_id'=>$request['role_id'],
        //            'is_active'=>$request['is_active'],
        //        ]);
        $user = new user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active = $request->is_active;
        $user->password = Hash::make($request->password);
        if ($file = $request->file("photo_id")) {
            $path = request()
                ->file("photo_id")
                ->store("users");
            //$name = time().$file->getClientOriginalName();
            //$file = $file->move('img',$name);
            $photo = Photo::create(["file" => $path]);
            $user->photo_id = $photo->id;
        }
        $user->save();
        /*wegschrijven van meerder rollen in de tussentabel*/
        $user->roles()->sync($request->roles, false);

        return redirect("admin/users")->with([
            "alert" => [
                "model" => "user",
                "type" => "success",
                "message" => "Added",
            ],
        ]);
        //return redirect()->route('users.index'); //->route() is voor als je de alias wilt gebruiken
        //return back()->withInput();
        //return back(); //dit is zonder input
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        //$user = User::find($id); //find zal altijd worden uitgevoerd. gevaar: de id moet bestaan.
        //        if(!user){
        ////            throw new ModelNotFoundException();
        ////        }
        $user = User::findOrFail($id);
        $roles = Role::pluck("name", "id")->all();
        return view("admin.users.edit", compact("user", "roles"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        //validation IN de controller
        request()->validate([
            "name" => ["required", "max:255", "min:3"],
            "email" => ["required", "email"],
            "roles" => ["required", Rule::exists("roles", "id")],
            "is_active" => ["required"],
        ]);

        //dd($request);
        $user = User::findOrFail($id);
        if (trim($request->password) == "") {
            $input = $request->except("password");
        } else {
            $input = $request->all();
            $input["password"] = Hash::make($request["password"]);
        }

        //oude foto verwijderen
        //we kijken eerst of er een foto bestaat
        if ($request->hasFile("photo_id")) {
            //file upload
            //ophalen photo uit database
            $oldPhoto = Photo::find($user->photo_id);
            $path = request()
                ->file("photo_id")
                ->store("users");
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
        $user->update($input);

        $user->roles()->sync($request->roles, true);
        $name = $user->name;
        $id1 = $user->id;
        return redirect("/admin/users")->with(
            "status",
            " User $name updated! (id= $id1"
        ); //status is een naam die je zelf kiest
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
        $name = User::findOrFail($id)->name;
        $id1 = User::findOrFail($id)->id;

        $user = User::findOrFail($id);
        //overdracht naar het event
        UsersSoftDelete::dispatch($user);
        //delete van users
        $user->delete();
        //return redirect('/admin/users')
        return redirect()
            ->route("users.index")
            ->with("status", " User $name deleted! (id = $id1)");
    }
    protected function userRestore($id)
    {
        User::onlyTrashed()
            ->where("id", $id)
            ->restore();
        $user = User::withTrashed()
            ->where("id", $id)
            ->first();
        $user
            ->posts()
            ->onlyTrashed()
            ->restore();
        //return redirect('admin/users');
        //return redirect()->route('admin.users');
        $name = User::findOrFail($id)->name;
        $id1 = User::findOrFail($id)->id;

        return back()->with("status", " User $name restored! (id = $id1)");
    }
}
