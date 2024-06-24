<?php

use App\Models\GroupchatDetailModel;
use App\Models\GroupchatModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat',function($user){
    if($user != null){
        return [
            'id' => $user->id,
            'name' => $user->name,
            'image' => $user->image
        ];
    }else{
        return false;

    }
});

Broadcast::channel('chat.private.{idUserSend}.{idUserReciever}',function($user,$idUserSend,$idUserReciever){
    if($user != null){
        if($user->id == $idUserSend || $user->id == $idUserReciever){
            return true;
        }
    }
    return false;
});

Broadcast::channel('chat.group.{groupChatId}',function($user,$groupChatId){
    if($user){
        $groupChat = GroupchatModel::find($groupChatId);
        $member_id = GroupchatDetailModel::where('groupchat_id',$groupChatId)->pluck('member_id')->toArray();
        if($user->id == $groupChat->id_leader || in_array($user->id,$member_id)){
            return true;
        }
    }
    return false;
});
