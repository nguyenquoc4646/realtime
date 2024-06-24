<?php

namespace App\Http\Controllers;

use App\Events\ChatGroup;
use App\Events\ChatPrivateEvent;
use App\Events\StatusUserEvent;
use App\Models\ChatPrivateModel;
use App\Models\GroupchatDetailModel;
use App\Models\GroupchatModel;
use App\Models\MessageGroupModel;
use App\Models\MessagesPublicModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {


        $users = User::where('id', '<>', Auth::user()->id)->get();


        $group_my_chat = GroupchatModel::where('id_leader', '=', Auth::user()->id)
            ->select('groupchat.id as groupchatId', 'groupchat.name')
            ->get();

        $group_not_leader = GroupchatModel::leftJoin('groupchat_detail', 'groupchat.id', '=', 'groupchat_detail.groupchat_id')
            ->select('groupchat.id as groupchatId', 'groupchat.name')
            ->where('groupchat_detail.member_id', Auth::user()->id)->get();

        return view('home', [
            'users' => $users,
            'group_my_chat' => $group_my_chat,
            'group_not_leader' => $group_not_leader,
        ]);
    }

    public function chatGroup($idGroup)
    {
        $users = User::where('id', '<>', Auth::user()->id)->get();
        $groupChat = GroupchatModel::find($idGroup);
        $leader = User::find($groupChat->id_leader);
        $member_id = GroupchatDetailModel::where('groupchat_id', '=', $idGroup)
            ->pluck('member_id')->toArray();
        $member = User::whereIn('id', $member_id)->where('id', '<>', Auth::user()->id)->get();
        $messageGroup = MessageGroupModel::messageGroup($idGroup);

        $count = GroupchatModel::select('*')
            ->join('groupchat_detail', 'groupchat.id', '=', 'groupchat_detail.groupchat_id')
            ->count();





        return view('chat.chat-group', [
            'groupChat' => $groupChat,
            'leader' => $leader,
            'member' => $member,
            'users' => $users,
            'messageGroup' => $messageGroup,
            'count' => $count
        ]);
    }
    public function sendMessageGroup(Request $request)
    {
        $messageGroup = new MessageGroupModel;
        if (!empty($request->groupChatId) && !empty($request->message)) {
            $messageGroup->groupchat_id = $request->groupChatId;
            $messageGroup->message = $request->message;
            $messageGroup->user_send = Auth::user()->id;
            $messageGroup->save();
        }
        broadcast(new ChatGroup(GroupchatModel::find($request->groupChatId), $request->user(), $request->message));
        // truyền 3 tham số vào group_id,user,message
        return response()->json('Gửi tin nhắn vào nhóm thành công');
    }
    public function chat()
    {
        $getMessagePublic = MessagesPublicModel::leftJoin('users', 'users.id', '=', 'message_public.user_send')->select('users.id', 'users.image', 'message_public.messages', 'message_public.user_send', 'message_public.created_at')->get();
        $users = User::where('id', '<>', Auth::user()->id)->get();
        $count = User::all()->count();

        return view('chat.chat', [
            'users' => $users,
            'getMessagePublic' => $getMessagePublic,
            'count' => $count

        ]);
    }
    public function search(Request $request)
    {

        if (!empty($request->groupChatId)) {
            $member_id = GroupchatDetailModel::where('groupchat_id', '=', $request->groupChatId)
                ->pluck('member_id')->toArray();
            if (!empty($request->search_text)) {
                $result_filter = User::whereIn('id', $member_id)->where('id', '<>', Auth::user()->id)
                    ->where('name', 'like', '%' . $request->search_text . '%')
                    ->get();
            } else {
                $result_filter = User::whereIn('id', $member_id)->where('id', '<>', Auth::user()->id)->get();;
            }
        } else {
            if (!empty($request->search_text)) {
                $result_filter = User::where('name', 'like', '%' . $request->search_text . '%')
                    ->where('id', '<>', Auth::user()->id)
                    ->get();
            } else {
                $result_filter = User::where('id', '<>', Auth::user()->id)->get();
            }
        }

        return response()->json(['data' => $result_filter]);
    }


    public function sendMessage(Request $request)
    {

        $messagesPublic  = new MessagesPublicModel;
        if (!empty($request->message)) {
            $messagesPublic->user_send = Auth::user()->id;
            $messagesPublic->messages = $request->message;
            $messagesPublic->save();
        }
        broadcast(new StatusUserEvent($request->user(), $request->message));
        return json_encode([
            'success' => 'thành công',
        ]);
    }

    public function chatPrivate($idUser)
    {
        // Lấy danh sách người dùng khác (trừ người dùng hiện tại)
        $users = User::where('id', '<>', Auth::user()->id)->get();

        // Lấy thông tin người dùng được chỉ định
        $user = User::where('id', '=', $idUser)->first();

        // Lấy tin nhắn giữa người dùng hiện tại và người dùng được chỉ định
        $messagePrivate = ChatPrivateModel::select(
            'user_send.id as id_user_send',
            'user_send.image as image_user_send',
            'user_reciever.id as id_user_reciever',
            'user_reciever.image as image_user_reciever',
            'message_private.message',
            'message_private.created_at'
        )
            ->leftJoin('users as user_send', 'user_send.id', '=', 'message_private.user_send')
            ->leftJoin('users as user_reciever', 'user_reciever.id', '=', 'message_private.user_reciever')
            ->where(function ($query) use ($idUser) {
                $query->where('message_private.user_send', Auth::user()->id)
                    ->where('message_private.user_reciever', $idUser);
            })
            ->orWhere(function ($query) use ($idUser) {
                $query->where('message_private.user_send', $idUser)
                    ->where('message_private.user_reciever', Auth::user()->id);
            })
            ->orderBy('message_private.created_at', 'asc') // Đảm bảo tin nhắn được sắp xếp theo thời gian
            ->get();

        return view('chat.Chat-private', ['users' => $users, 'user' => $user, 'messagePrivate' => $messagePrivate]);
    }


    public function messagePrivate(Request $request)
    {
        $ChatPrivateModel = new ChatPrivateModel;
        if (!empty($request->idUserReciever) && !empty($request->message)) {
            $ChatPrivateModel->user_send = Auth::user()->id;
            $ChatPrivateModel->user_reciever = $request->idUserReciever;
            $ChatPrivateModel->message = $request->message;
            $ChatPrivateModel->save();
        }
        broadcast(new ChatPrivateEvent($request->user(), User::find($request->idUserReciever), $request->message));
        return response()->json('Thành công');
    }

    public function userInactive(Request $request)
    {
        // Retrieve the array of active user IDs from the request
        $activeUserIds = $request->activeUserIds;

        // Ensure the request contains an array of active user IDs
        if (!is_array($activeUserIds)) {
            return response()->json([
                'error' => 'Invalid data format',
            ], 400);
        }

        // Fetch users whose IDs are not in the array of active user IDs
        $inactiveUsers = User::whereNotIn('id', $activeUserIds)->get();

        return response()->json([
            'inactiveUsers' => $inactiveUsers,
            'success' => 'Thành công',
        ]);
    }

    public function createGroup(Request $request)
    {
        $groupChat = new GroupchatModel;
        if (!empty($request->id_leader)) {
            $groupChat->name = $request->name;
            $groupChat->id_leader = $request->id_leader;
            $groupChat->save();
        }

        $newGroupId = $groupChat->id;
        $groupChat = GroupchatModel::find($groupChat->id);
        $member_ids = [];
        foreach ($request->members as $member_id) {
            $groupChatDetail = new GroupchatDetailModel;
            $groupChatDetail->groupchat_id = $newGroupId;
            $groupChatDetail->member_id = $member_id;
            $groupChatDetail->save();
            // $member_ids[] = $member_id;
        }

        return response()->json([

            'message' => 'Tạo nhóm thành công',
        ]);
    }
}
