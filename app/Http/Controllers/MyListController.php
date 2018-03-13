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
            //   return $myList->creator;
                        // return $myList;
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
               $myList->creator=$user->pseudo;
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
                 
                 return response($res);
               } else {
                 $res['success'] = false;
                 $res['message'] = "Probleme insertion de liste";
                 return response($res);
               };
             }else{
               $res['success'] = false;
               $res['message'] = "Saisir toutes les données";
               return response($res);
             }
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
            $task->check  = true;
            $task->flag  = 4;
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
              return response($res);
        }else{
          $res['success']=false;
          $res['message']="Il manque des informations";
          return response($res);
        }
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
              return response($res);
        }else{
          $res['success']=false;
          $res['message']="Il manque des informations";
          return response($res);
        }
      }

    public function testTask(Request $request){
      $user = User::where("api_token", "=", $request->input('api_token'))->first();
      foreach ($user["list"] as $key ) {

          $myList = myList:: where("tokenList", "=", $key)
                            ->first();
          var_dump($myList["task"]);

      }
      return ("bite");
  }



}
