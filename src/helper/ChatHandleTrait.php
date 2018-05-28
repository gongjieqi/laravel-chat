<?php
/**
 * Created by PhpStorm.
 * User: gongjieqi
 * Date: 2018/5/22
 * Time: 14:45
 */
namespace Gongjieqi\LaravelChat\Helper;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Str;

trait ChatHandleTrait
{
    public function bindUser($guard,$client_id)
    {
        $uuid = auth()->guard($guard)->user()->uuid;
        if(empty($uuid)){
            $uuid = Str::random(80);
            auth()->guard($guard)->user()->uuid = $uuid;
            auth()->guard($guard)->user()->save();
        }
        $is_bind = Gateway::getClientIdByUid($uuid);
        Gateway::bindUid($client_id,$uuid);

        if($guard == config('chat.user_guard')){
            $name = auth()->guard($guard)->user()->{config('chat.user_name_filed')};
        }else{
            $name = auth()->guard($guard)->user()->{config('chat.admin_name_filed')};
        }
        return ['guard'=>$guard,'uuid'=>$uuid,'name'=>$name,'is_bind'=>$is_bind];
    }

    public function getUserListByGroup($group,$online_users)
    {
        if($group == 'user'){
            $configModel =  config('chat.user');
            $name_filed = config('chat.user_name_filed');
        }else{
            $configModel =  config('chat.admin');
            $name_filed = config('chat.admin_name_filed');
        }

        $model = new $configModel;

        $users = $model::whereIn('uuid',$online_users)->get([$name_filed.' as name','uuid'])->toArray();

        foreach ($users as $key=>$user){
            $users[$key]['client_id'] = Gateway::getClientIdByUid($user['uuid']);
        }
        return $users;
    }
}