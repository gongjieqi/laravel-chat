# laravel-chat
这是一个简易版本，结合laravel的用户和workman实现前后台用户聊天的webIM

操作步骤：
1. 在config/app.php 中添加服务提供者：

    Gongjieqi\LaravelChat\Chat\ChatServiceProvider::class,
    
2. artisan vendor:publish
3. artisan migrate
4. 配置文件config/chat.php
   参数说明：
       
       30行以上的配置，参考workman手册（就是这么懒...）
       
       'user' => 'App\User', //前台用户Model
       
       'user_table' => 'users', //前台用户表
   
       'user_guard' => 'web',  //前台用户guard
   
       'user_name_filed' => 'name',  //前台用户用户名字段
       接下来的就是对应后台用户的响应配置了
       
5. 运行 php app/server/start.php start

6. 将chat/chat.blade.php include到对应的模板文件里去