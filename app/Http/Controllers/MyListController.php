<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\myList;

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
            $tokenTask=str_random(10);
            $myList->task = $myList->task."{idTask:".$tokenTask.",whriteUp:".$request->input('whriteUp').",}";
            $myList->save();
            return $myList;
          }
        }

        // if ($request->has('creator') && $request->has('nameList')) {
        //   $myList = new myList;
        //   $myList->creator=$request->input('creator');
        //   $myList->nameList=$request->input('nameList');
        //   $myList->tokenList=str_random(16);
        //   if($myList->save()){
        //     return "Liste créée";
        //   } else {
        //     return "Probleme d'insertion de la Liste";
        //   };
        // }else{
        //   return "Saisir toutes les donneés";
        // }
      }


}
