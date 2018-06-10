<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreparedContent extends Model
{

    protected $table = 'preparedcontent';
    protected $primaryKey = 'id';

    protected $subContents;

    public static function getFromTopId($topId = NULL) {
        return self::where([['status', '1'], ['topid', $topId]])->get();
    }

    public static function getRoles()
    {
        return self::where([['status', '1']])->get();
    }

    public static function get($id)
    {
        return self::where(function($query) {
            $query->where('status', '1');
            $query->orWhere('status', '0');
        })->where('id', $id)->first();
    }

    public static function getFromDisplayName($name)
    {
        return self::where(function($query) {
            $query->where('status', '1');
        })->where('display_name', $name)->first();
    }

    public static function hasShortCut($id, $letter, $number) {
        if(self::where([['letter', $letter], ['number', $number], ['id', '!=', $id], ['status', '1']])->first()) {
            return true;
        } else {
            return false;
        }
    }

    public static function disableSubContents($topId)
    {
        self::where([['topid', $topId], ['status', '1']])->update(['topid' => NULL]);
    }

}