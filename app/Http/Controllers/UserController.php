<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Zob;
class UserController extends Controller
{
    private $salt;
    public function __construct()
    {
        $this->salt="userloginregister";
    }

    public function login(Request $request){
      if ($request->has('username') && $request->has('password')) {
        $user = User:: where("username", "=", $request->input('username'))
                      ->where("password", "=", sha1($this->salt.$request->input('password')))
                      ->first();
        if ($user) {
                    $token=str_random(60);
                    $user->api_token=$token;
                    $user->save();
                    return $user->api_token;
        } else {
          return "Le nom d'utilisateur ou le mot de passe est incorrect, la connexion a échoué!";
        }
      } else {
        return "Les informations de connexion sont incomplètes, veuillez entrer le nom d'utilisateur et le mot de passe!";
      }
    }

    public function info(){
      var_dump("hello");
      die;
      return Auth::auth();
    }

// VALIDE

    public function register(Request $request){
      if ($request->has('username') && $request->has('password') && $request->has('email')) {
        $user = new User;
        $user->username=$request->input('username');
        $user->password=sha1($this->salt.$request->input('password'));
        $user->email=$request->input('email');
        $user->codeUser=str_random(5);
        $user->api_token=str_random(10);
        if($user->save()){
          return "L'inscription de l'utilisateur a réussi!";
        } else {
          return " L'inscription de l'utilisateur a échoué";
        };
      }else{
        return "Saisir toutes les donneés";
      }
    }


    public function get_user(Request $request, $id)
       {
           $user = User::where('_id', $id)->get();
           if ($user) {
                 $res['success'] = true;
                 $res['message'] = $user;
                 return response($res);
           }else{
             $res['success'] = false;
             $res['message'] = 'Cannot find user!';
             return response($res);
           }
       }













}
