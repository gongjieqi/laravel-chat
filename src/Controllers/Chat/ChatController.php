<?php
/**
 * Created by PhpStorm.
 * User: gongjieqi
 * Date: 2018/5/16
 * Time: 11:41
 */
namespace Gongjieqi\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use GatewayWorker\Lib\Gateway;
class ChatController extends Controller
{
    public function __construct()
    {
        Gateway::$registerAddress = config('chat.registerAddress');

        $this->middleware('auth');
    }

    public function bind(Request $request)
    {
       $uuid = auth('auth')->user()->uuid;

       Gateway::bindUid($request->input('client_id'),$uuid);

       if ($request->input('auth') == 'auth'){
           Gateway::joinGroup($request->input('client_id'), 'user');
       }else{
           Gateway::joinGroup($request->input('client_id'), 'admin');
       }
    }

    public function sendMessage(Request $request)
    {
        Gateway::sendToClient($request->input('client_id'),$request->input('message'));
    }
}