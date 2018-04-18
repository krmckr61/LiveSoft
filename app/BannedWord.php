<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannedWord extends Model
{
    protected $table = 'bannedword';
    protected $primaryKey = 'id';
}