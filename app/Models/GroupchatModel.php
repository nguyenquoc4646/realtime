<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GroupchatModel extends Model
{
    use HasFactory;
    protected $table ='groupchat';

    public static function group_sefl_member(){
        return self::select('*')
        ->join('groupchat_detail','groupchat.id','=','groupchat_detail.groupchat_id')
        ->where('groupchat_detail.member_id','=',Auth::user()->id)
        ->get();
    }
}
