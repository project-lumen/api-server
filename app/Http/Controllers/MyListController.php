<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\myList;
use App\User;


//initialisation de la class emp pour l'ajout des task
class emp {
   public $idTask = "";
   public $titleTask  = "";
   public $check = "";
   public $flag = "";
   public $dateStart = "";
   public $dateEnd = "";
}

class MyListController extends Controller
{
      // retourne toutes les listes de l'utilisateur
      // middleware : true
      // param : api_token
      public function showListsUser(Request $request){
        $user = User::where("api_token", "=", $request->input('api_token'))->first();

        // var_dump($user->list);
        if(!empty($user->list)){
          $res['success']="valide";
          foreach ($user->list as  $index => $value) {
            $myList = myList::where('tokenList', "=", $value)->first();
            $res[$index] = ['name' => $myList->nameList, 'tokenList'=> $myList->tokenList];
          }
        }else{
          $res['success']=false;
          $res['message']="erreur";
        }
        return response($res);


      }


      //récupération de la la liste en fonction du tokenList choisi
      public function printList(Request $request){
         $myList = myList::where('tokenList', "=", $request->input('tokenList'))->first();
         return $myList;
      }

      //ajout d'une liste et d'une premiere task
      // param a envoyer: nameList / api_token
      public function addList(Request $request){
             if ($request->has('api_token') && $request->has('nameList') && $request->input('nameList') != null) {
               $user = User::where("api_token", "=", $request->input('api_token'))->first();
               $myList = new myList;
               $myList->creator=$user->_id;
               $myList->nameList=$request->input('nameList');
               $myList->tokenList=str_random(16);
               $myList->task =[];
               if($myList->save()){

                 $user = User::where("api_token", "=", $request->input('api_token'))->first();
                 $arrayName = $user->list ;
                 array_push($arrayName, $myList->tokenList);
                 $user->list = $arrayName;
                 $user->save();

                 $res['success'] = true;
                 $res['message'] = "Liste ajouté";
               } else {
                 $res['success'] = false;
                 $res['message'] = "Probleme insertion de liste";
               };
             }else{
               $res['success'] = false;
               $res['message'] = "Saisir toutes les données";
            }
            return response($res);
           }

      //Ajout d'une task grace à la class initialiser plus haut attention la task est un json
      public function addTask(Request $request){
        if ($request->has('tokenList')) {
          $myList = myList:: where("tokenList", "=", $request->input('tokenList'))
                        ->first();
          if ($myList) {
            $task = new emp();
            $task->idTask = str_random(10);
            $task->titleTask  = $request->input('titleTask');
            $task->check  = false;
            $task->flag  = $request->input('flag');
            $task->dateStart = $request->input('dateStart');
            $task->dateEnd = $request->input('dateEnd');


            $previusTask = $myList->task;
            array_push($previusTask, $task);

            $myList->task = $previusTask;
            $myList->save();

            $res['success']=true;
            $res['message']="Tache ajoutée";

          }else {
            $res['success']=false;
            $res['message']="erreur";

          }
        }
      return response($res);
      }

      public function checkTask(Request $request){
        if ($request->input('idTask') != null && $request->input('check') != null && $request->input('tokenList') != null){
        $myList = myList:: where("tokenList", "=", $request->input('tokenList'))
                      ->first();
        // a chaque itération ont add la task actuelle a la suite du tableau buffer
        //puis un insert le tableau buffer dans task
            $buffer=[];
              foreach ($myList->task as $key => $value) {
                if ($value["idTask"]==$request->input('idTask')) {
                  if ($request->input('check')=="false") {
                    $value["check"]=false;
                  }else {
                    $value["check"]=true;
                  }
                  array_push($buffer, $value);
                }else{
                  array_push($buffer, $value);
                }
              }
              $myList->task = $buffer;
              $myList->save();

              $res['success']=true;
              $res['message']="Check OK";
        }else{
          $res['success']=false;
          $res['message']="Il manque des informations";
        }
      return response($res);
      }



      public function modifTask(Request $request){
        if ($request->input('idTask') != null && $request->input('tokenList') != null){
        $myList = myList:: where("tokenList", "=", $request->input('tokenList'))
                      ->first();
        // a chaque itération ont add la task actuelle a la suite du tableau buffer
        //puis un insert le tableau buffer dans task
            $buffer=[];
              foreach ($myList->task as $key => $value) {
                if ($value["idTask"]==$request->input('idTask')) {
                  $value["titleTask"]=$request->input('titleTask');
                  $value["check"]=$request->input('check');
                  $value["flag"]=$request->input('flag');
                  $value["dateStart"]=$request->input('dateStart');
                  $value["dateEnd"]=$request->input('dateEnd');
                  array_push($buffer, $value);
                }else{
                  array_push($buffer, $value);
                }
              }
              $myList->task = $buffer;
              if ($myList->save()){
                $res['success']=true;
                $res['message']="modification OK";
              }else {
                $res['success']=false;
                $res['message']="erreur lors de l'insertion dans la db";
              }
        }else{
          $res['success']=false;
          $res['message']="Il manque des informations";
        }
      return response($res);
      }

      public function addUser(Request $request){
        $user = User::where("codeUser", "=", $request->input('codeUser'))->first();
        if ($request->input('codeUser')!= null && $user){
        $validation=0;
        $buffer = $user->list;
        foreach ($user->list as $key => $value) {
          if ($value == $request->input('tokenList') ) {
          $validation=1;
          }
        }
        if ($validation==0) {
          array_push($buffer, $request->input('tokenList'));
          $user->list = $buffer;
          $user->save();
          $res['success']=true;
          $res['message']="Liste ajouté";

        }else{
          $res['success']=false;
          $res['message']="Liste existante";
        }
      }else {
        $res['success']=false;
        $res['message']="code User invalide";
      }
      return response($res);
    }

    public function importantTask(Request $request){
      $user = user:: where("api_token", "=", $request->input('api_token'))->first();
      $buffer=[];
       foreach ($user->list as $key => $value) {
        $myList = myList:: where("tokenList", "=", $value)->first();
        foreach ($myList->task as $key => $value) {
          if ($value["flag"]=="true") {
            $value["tokenList"]=$myList->tokenList;
            array_push($buffer, $value);
          }else{}
        }
       }
    return($buffer);
    }

    public function todayTask(Request $request){
      $user = user:: where("api_token", "=", $request->input('api_token'))->first();
      $buffer=[];
      $today = date("Y-m-d");

       foreach ($user->list as $key => $value) {
        $myList = myList:: where("tokenList", "=", $value)->first();
        foreach ($myList->task as $key => $value) {
          $start = strtotime($value["dateStart"]);
          $end = strtotime($value["dateEnd"]);
          $today = strtotime("now");
            if ($today>=$start && $today<=$end) {
              $value["tokenList"]=$myList->tokenList;
              array_push($buffer, $value);
            }
          }
        }
    return($buffer);
    }

    public function soonTask(Request $request){
      $user = user:: where("api_token", "=", $request->input('api_token'))->first();
      $buffer=[];
      $today = date("Y-m-d");

       foreach ($user->list as $key => $value) {
        $myList = myList:: where("tokenList", "=", $value)->first();
        foreach ($myList->task as $key => $value) {
          $end = strtotime($value["dateEnd"]);
          $today = strtotime("+3 day");
            if ( $today>=$end && $end!=false) {
              $value["tokenList"]=$myList->tokenList;
              array_push($buffer, $value);
            }
          }
        }
    return($buffer);
    }

//BESOIN DE L'API_TOKEN ET DE LA TOKENLIST À TEST
    public function ifOwner(Request $request){
      $user = User::where("api_token", "=", $request->input('api_token'))->first();
          $myList = myList:: where("tokenList", "=", $request->input('tokenList'))->first();
          if($user->_id == $myList->creator ){
            $res['success']=true;
            $res['message']="createur de la liste";
          }else{
            $res['success']=false;
            $res['message']="n'est pas le createur";
          }
    return response($res);
   }


   public function deleteList(Request $request){
     $user = User::all();
     $buffer=[];
     foreach ($user->list as $key => $value){
          if ( $value == $request->input('tokenList')){
          }else{
            array_push($buffer,$value);
          }
      }
      $user->list = $buffer;
      $user->save();
   return response($res);
  }


//BESOIN DE L'API_TOKEN ET DE LA TOKENLIST
  public function leaveList(Request $request){
    $user = User::where("api_token", "=", $request->input('api_token'))->first();
    $buffer=[];

     foreach ($user->list as $key => $value){
          if ( $value == $request->input('tokenList')){
          }else{
            array_push($buffer,$value);
          }
      }
    $user->list = $buffer;
    if ($user->save()) {
      $res['success']=true;
      $res['message']="Update OK";
    }else {
      $res['success']=false;
      $res['message']="Update Pas Ok";
    }
  return response($res);

  }

//FONCTION POUBELLE QUI ME SERT DE COPIER COLLER
    public function testTask(Request $request){
      $user = User::where("api_token", "=", $request->input('api_token'))->first();
      foreach ($user["list"] as $key ) {

          $myList = myList:: where("tokenList", "=", $key)
                            ->first();
          var_dump($myList["task"]);

      }
      return ("prout");
    }
}

//retourne un tableau avec les taches qui commence aujourd'hui
// public function todayTask(Request $request){
//   $user = user:: where("api_token", "=", $request->input('api_token'))->first();
//   $buffer=[];
//   $today = date("Y-m-d");
//
//    foreach ($user->list as $key => $value) {
//     $myList = myList:: where("tokenList", "=", $value)->first();
//     foreach ($myList->task as $key => $value) {
//       if ($today == $value["dateStart"] ) {
//         array_push($buffer, $value);
//       }
//     }
//    }
// return($buffer);
// }
