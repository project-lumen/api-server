<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\myList;



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
      public function printList(Request $request){
         $myList = myList::where('tokenList', "=", $request->input('tokenList'))->first();
         return $myList;
      }

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
            return "Liste créée";
          } else {
            return "Probleme d'insertion de la Liste";
          };
        }else{
          return "Saisir toutes les donneés";
        }
      }

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

            $newTask= json_encode($task);

            $myList->task = $myList->task.','.$newTask;
            $myList->save();
            // var_dump(json_decode('['.$myList->task.']'));
            return "Nouvelle tache ajoutée";
          }
        }
      }


}
