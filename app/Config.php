<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

    protected $table = 'config';
    protected $primaryKey = 'id';

    public static function getConfig($name)
    {
        return self::where('name', $name)->first();
    }

    public static function getValue($name)
    {
        $config = self::where('name', $name)->first();
        if($config) {
            return $config->value;
        } else {
            return null;
        }
    }

}