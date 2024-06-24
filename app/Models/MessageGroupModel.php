<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageGroupModel extends Model
{
    use HasFactory;
    protected $table = 'message_group';

    public static function messageGroup($idGroup){
        return self::select('message_group.user_send','users.image','message_group.message','message_group.created_at')
        ->join('users','message_group.user_send','=','users.id')
        ->where('message_group.groupchat_id','=',$idGroup)
        ->limit(40)
        ->get();
    }
}
