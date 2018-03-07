<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model;


class myList extends Model {

    protected $collection = 'list';
    public $timestamps = false;

    protected $fillable = [
        'task'
    ];
}
