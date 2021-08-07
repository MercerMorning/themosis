<?php

namespace Theme\Models;


use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['message_id', 'path'];

}