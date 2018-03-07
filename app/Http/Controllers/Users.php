<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Zob;
class users extends Controller
{

    public function index()
    {
	$articles = Zob::all()->take(20);
	return response()->json($articles);
     //
    }

    public function getUser($id){

       $article  = Zob::find($id);

       return response()->json($article);
   }

   public function deleteUser($id){
        $article  = Zob::find($id);
        $article->delete();

        return response()->json("succes");
    }

    public function createUser(Request $request){
	     $article = Zob::create($request->all());

	      return response()->json($article);
    }

    public function updateUser(Request $request,$id){
       $article  = Zob::find($id);
       $article->name = $request->input('name');
       $article->username = $request->input('username');
       $article->password = $request->input('password');
       $article->save();

       return response()->json($article);
   }

}
