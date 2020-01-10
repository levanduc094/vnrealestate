<?php

namespace App;


use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Post_tag extends Model
{
    //
    protected $table = 'post_tag';
    use Searchable;
}
