<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\myList;

class MyListController extends Controller
{

    public function index()
    {
	$articles = myList::all()->take(20);
	return response()->json($articles);
     //
    }



}
