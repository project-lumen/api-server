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
        foreach ($user->list as  $index => $value) {
          $myList = myList::where('tokenList', "=", $value)->first();
          $res[$index] = ['name' => $myList->nameList, 'task'=> $myList->task];




          //   return $myList->creator;
                      // return $myList;

        }
        return response($res);


      }


      //récupération de la la liste en fonction du tokenList choisi
      public function printList(Request $request){
         $myList = myList::where('tokenList', "=", $request->input('tokenList'))->first();
         return $myList;
      }

      //ajout d'une liste et d'une premiere task
      // param a envoyer : creator / nameList / api_token
      public function addList(Request $request){
        if ($request->has('creator') && $request->has('nameList')) {
          $myList = new myList;
          $myList->creator=$request->input('creator');
          $myList->nameList=$request->input('nameList');
          $myList->tokenList=str_random(16);
            $task = new emp();
            $task->idTask = str_random(10);
            $task->titleTask  = "Ma premiere Tache";
            $task->check  = true;
            $task->flag  = 4;
            $task->dateStart = "12/12/2012";
            $task->dateEnd = "13/13/2013";
            $newTask= json_encode($task);
          $myList->task =$newTask;
          if($myList->save()){
            $user = User::where("api_token", "=", $request->input('api_token'))->first();

              $arrayName =$user->list ;
              array_push($arrayName, $myList->tokenList);
              var_dump($arrayName);


              $user->list= $arrayName;

            $user->save();
            return "Liste créée";
          } else {
            return "Probleme d'insertion de la Liste";
          };
        }else{
          return "Saisir toutes les donneés";
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

             //ont encode l'objet task en json
            $newTask= json_encode($task);

            //ont concaténe la nouvelle tache avec les anciennes
            $myList->task = $myList->task.','.$newTask;
            $myList->save();
            // pour récupérer les task en json il faut la mettre entre []
            // var_dump(json_decode('['.$myList->task.']'));
            return "Nouvelle tache ajoutée";
          }
        }
      }


}
