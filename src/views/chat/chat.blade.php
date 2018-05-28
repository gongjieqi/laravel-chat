<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
<style>
    #wrap {display:block;bottom:0px;right:1px!important;right:18px;width:200px;line-height:30px;position:fixed;text-align:center;color:#000; background:#fff;box-shadow: 0px 0px 20px #888888}

    .chat-list {
        display: none;
        max-height: 400px;
        overflow: auto;
    }

    .chat-list-head{
        height: 20px;
        border-bottom: 1px solid #ccbebe;
        display: none;
        background: #cac8c8;
    }
    .chat-list-head i{
        cursor: pointer;
        float: right;
        display: block;
        height: 10px;
    }
    .chat-ul {padding: 0}
    .chat-bottom{cursor: pointer}

    .chat{  position: relative;
        height: 42px;
        padding: 5px 15px 5px 60px;
        font-size: 0;
        cursor: auto;
        padding: 0;
    }
    .chat:hover{
        background: gainsboro;
    }
    .chat i{
        position: absolute;
        left: 15px;
        top: 8px;
        width: 15px;
        height: 15px;
        border-radius: 100%;
        background: #22a7f0;
    }

    .chat span{
        font-size: 14px;
    }

    .group{
        cursor: pointer;
        background: #353d47;
        color: white;
    }

    .talk_con{
        width:600px;
        height:500px;
        margin:50px auto 0;
        background:#f9f9f9;
        display: none;
        box-shadow: 0px 0px 20px #888888;
    }

    .talk_title{
        width:600px;
        background: #353d47;
        color: white;
    }
    .talk_title i{
        cursor: pointer;
        float: right;
        display: block;
        height: 10px;
    }
    .talk_show{
        width:600px;
        height:420px;
        border-bottom:1px solid #666;
        background:#fff;
        margin:10px auto 0;
        overflow:auto;
    }
    .talk_input{
        width:600px;
        margin:10px auto 0;
    }
    .talk_word{
        width:420px;
        height:26px;
        padding:0px;
        float:left;
        margin-left:10px;
        outline:none;
        text-indent:10px;
    }
    .talk_sub{
        width:56px;
        height:30px;
        float:left;
        margin-left:10px;
    }
    .atalk{
        margin:10px;
    }
    .atalk span{
        display:inline-block;
        background:#0181cc;
        border-radius:10px;
        color:#fff;
        padding:5px 10px;
    }
    .btalk{
        margin:10px;
        text-align:right;
    }
    .btalk span{
        display:inline-block;
        background:#ef8201;
        border-radius:10px;
        color:#fff;
        padding:5px 10px;
    }
</style>

<div id="wrap">
    <div class="chat-list-head">
        <i class="fa fa-remove"></i>
    </div>

    <div class="chat-list">
    </div>

    <div class="chat-bottom">
        <span class="fa fa-wechat"></span> <span>Chat</span>
    </div>
</div>


<div class="talk_con">
    <div class="talk_title">
        <i class="fa fa-remove"></i>
        <h3></h3>
    </div>
    <div class="talk_show" id="words">
    </div>
    <div class="talk_input">
        <input type="text" class="talk_word" id="talkwords">
        <input type="button" value="发送" class="talk_sub" id="talksub" uuid="" name="">
    </div>
</div>

<script>
    ws = new WebSocket("ws://{{ config('chat.socket_from_client_ip').':'.config('chat.socket_port') }}");
    own_uuid = '';
    message_cache = {};
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    // 服务端主动推送消息时会触发这里的onmessage
    ws.onmessage = function(e){
        // json数据转换成js对象
        var data = eval("("+e.data+")");
        var type = data.type || '';
        switch(type){
            // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定
            case 'init':
                // 利用jquery发起ajax请求，将client_id发给后端进行uid绑定
                $.post('{{ route("chat-bind") }}', {client_id: data.client_id}, function(backdata){
                    if(backdata.hasOwnProperty('admin')){
                        $(".chat-list").append("<div class=\"group chat-admin-group\">Admin</div>");
                        $ulHtml = '<ul class="chat-ul list-group">';
                        for (var i = 0; i < backdata.admin.length ; i++){
                            $ulHtml+= '<li class="chat list-group-item" onclick="openChat(this);" id="'+data.client_id+'"> <i></i> <span id="'+backdata.admin[0].uuid+'">'+backdata.admin[0].name+'</span> <span class="badge"></span></li>';
                        }
                        $ulHtml += '</ul>';
                        $(".chat-list").append($ulHtml);
                    }

                    if(backdata.hasOwnProperty('user')){
                        $(".chat-list").append("<div class=\"group chat-user-group\">Member</div>");
                        $ulHtml = '<ul class="chat-ul list-group">';
                        for (var i = 0; i< backdata.user.length; i++){
                            $ulHtml += '<li class="chat list-group-item" onclick="openChat(this);" id="'+data.client_id+'"> <i></i> <span id="'+backdata.user[0].uuid+'">'+backdata.user[0].name+'</span> <span class="badge"></span></li>';
                        }
                        $ulHtml+='</ul>';
                        $(".chat-list").append($ulHtml);
                    }
                    if(backdata.hasOwnProperty('own')){
                        own_uuid = backdata.own.uuid;
                    }
                }, 'json');
                break;
            case 'message':
                var from = data.from;
                var message = data.message;
                if(!$('.talk_con').is(":hidden") && ($('.talk_sub').attr('uuid') == from)){
                    $('.talk_show').append('<div class="atalk"><span id="asay">'+message+'</span></div>');
                }else{
                    var unread = $('#'+from).next('span').html();
                    if(unread == ''){
                        $('#'+from).next('span').html(1)
                    }else{
                        $('#'+from).next('span').html(parseInt(unread)+1);
                    }
                    if (message_cache.hasOwnProperty(from)){
                        message_cache[from].push(message);
                    }else{
                        message_cache[from] = [message];
                    }
                }
                break;
//            case 'online':
//                var div_class = '';
//                if(data.group == 'Member'){
//                    div_class = 'chat-user-group';
//                }
//                if(data.group == 'Admin'){
//                    div_class = 'chat-admin-group';
//                }
//
//                if($(".group").hasClass(div_class)){
//                    $("."+div_class).next('ul').append('<li class="chat list-group-item" onclick="openChat(this);" id="'+data.client_id+'"> <i></i> <span id="'+data.uuid+'">'+data.name+'</span> <span class="badge"></span></li>');
//                }else{
//                    $(".chat-list").append("<div class=\"group "+div_class+"\">"+data.group+"</div>");
//                    $ulHtml = '<ul class="chat-ul list-group">';
//                    $ulHtml+= '<li class="chat list-group-item" onclick="openChat(this);" id="'+data.client_id+'"> <i></i> <span id="'+data.uuid+'">'+data.name+'</span> <span class="badge"></span></li>';
//                    $ulHtml += '</ul>';
//                    $(".chat-list").append($ulHtml);
//                }
//                break;
//            case 'offline':
//                $('#'+data.client_id).remove();
//                break;
            default :
                console.log(e.data);
        }
    };

    $('.chat-bottom').click(function(){
        $(this).hide();
        $('.chat-list').show();
        $('.chat-list-head').show();
    });

    $('.chat-list-head i').click(function(){
        $('.chat-bottom').show();
        $('.chat-list').hide();
        $('.chat-list-head').hide();
    });

    $('.talk_title i').click(function(){
        $('.talk_con').hide();
    });

    $('.talk_sub').click(function(){
        var uuid = $(this).attr('uuid');
        var name = $(this).attr('name');
        var message = $('#talkwords').val();
        if(message.length == 0){
            return false;
        }
        $.post('{{ route("chat-send") }}', {uuid: uuid,message:message,from:own_uuid}, function(backdata){
            if(backdata.code == 200){
                $('#talkwords').val('');
                $('.talk_show').append('<div class="btalk"><span id="bsay">'+message+'</span></div>');
            }
        });

    });
    function openChat(HtmlObj){
        var uuid = $(HtmlObj).children('span').attr('id');
        var name = $(HtmlObj).children('span').html();
        var old_uuid = $('.talk_sub').attr('uuid');
        if(uuid != old_uuid){
            $('.talk_show').html('');
        }
        $('.talk_title>h3').html(name);
        $('.talk_sub').attr('uuid',uuid);
        $('.talk_sub').attr('name',name);
        $('.talk_con').show();

        $('#'+uuid).next('span').html('');
        if(message_cache.hasOwnProperty(uuid)){
            for (var i=0;i<message_cache[uuid].length;i++){
                $('.talk_show').append('<div class="atalk"><span id="asay">'+message_cache[uuid][i]+'</span></div>');
            }
            message_cache[uuid] = [];
        }
    }
</script>