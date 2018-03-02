<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Zob extends Model {


protected $collection = 'users';
public $timestamps = false;
protected $fillable = [
       'hello'
   ];
}
