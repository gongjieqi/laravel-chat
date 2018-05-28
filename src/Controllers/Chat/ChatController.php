<?php
/**
 * Created by PhpStorm.
 * User: gongjieqi
 * Date: 2018/5/16
 * Time: 11:41
 */
namespace Gongjieqi\LaravelChat\Controllers\Chat;

use App\Http\Controllers\Controller;
use Gongjieqi\LaravelChat\Helper\ChatHandleTrait;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    use ChatHandleTrait;
    public function __construct()
    {
        Gateway::$registerAddress = config('chat.registerAddress');
        $this->middleware(['web']);
    }

    public function bind(Request $request)
    {
        if(auth(config('chat.user_guard'))->user()){
            $info = $this->bindUser(config('chat.user_guard'),$request->input('client_id'));

        }elseif(auth(config('chat.admin_guard'))->user()){
            $info = $this->bindUser(config('chat.admin_guard'),$request->input('client_id'));
        }else{
            $info['guard'] = null;
        }

        $group_name = '';
        if ($info['guard'] == config('chat.user_guard')){
           Gateway::joinGroup($request->input('client_id'), 'user');
           $group_name = 'Member';
        }elseif($info['guard'] == config('chat.admin_guard')){
           Gateway::joinGroup($request->input('client_id'), 'admin');
            $group_name = 'Admin';
        }else{
           //Gateway::joinGroup($request->input('client_id'), 'guest');
        }


        $group = Gateway::getAllGroupIdList();
        foreach ($group as $item){
           $current_group = Gateway::getUidListByGroup($item);
           $value = array_values($current_group);
           $return[$item] = $this->getUserListByGroup($item,$value);
        }

        if(count($info['is_bind']) <= 0){
            //完成绑定，通知所有在线用户
            Gateway::sendToUid(array_except(Gateway::getAllUidList(), Gateway::getUidByClientId($request->input('client_id'))),json_encode(array(
                'type'      => 'online',
                'uuid'=> Gateway::getUidByClientId($request->input('client_id')),
                'client_id' => $request->input('client_id'),
                'name'=> $info['name'],
                'group'=>$group_name
            )));
        }
        $return['own']['uuid'] = $info['uuid'];
        return $return;
    }

    public function sendMessage(Request $request)
    {
        if($request->input('client_id')){
            Gateway::sendToClient($request->input('client_id'),json_encode(array(
                'type'      => 'message',
                'from' => $request->input('from'),
                'message'=> $request->input('message'),
            )));
        }

        if($request->input('uuid')){
            Gateway::sendToUid($request->input('uuid'),json_encode(array(
                'type'      => 'message',
                'from' =>  $request->input('from'),
                'message'=> $request->input('message'),
            )));
        }

        return ['code'=>200,'message'=>'send message success!'];
    }
}