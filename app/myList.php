<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class myList extends Model
{

    protected $collection = 'list';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator', 'nameList', 'tokenList',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'tokenList',
    ];
}
