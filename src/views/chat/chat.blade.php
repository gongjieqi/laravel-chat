<style>
    #wrap {display:block;bottom:0px;right:1px!important;right:18px;width:200px;line-height:30px;position:fixed;text-align:center;color:#000; background:#fff;box-shadow: 0px 0px 20px #888888}

    .chat-list {display: none}

    .chat-list-head{
        height: 20px;
        border-bottom: 1px solid #ccbebe;
        display: none;
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
            cursor: pointer;
            padding: 0;
    }

    .chat-box{display: none}

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
        background: green;
    }

    .chat span{
        font-size: 14px;
    }
</style>

<div id="wrap">
    <div class="chat-list-head">
        <i class="fa fa-remove"></i>
    </div>

    <div class="chat-list">
        <ul class="chat-ul">
            <li class="chat">
                <i></i>
                <span>Gone</span>
            </li>
            <li class="chat">
                <i></i>
                <span>Gone</span>
            </li>
            <li class="chat">
                <i></i>
                <span>Gone</span>
            </li>
            <li class="chat">
                <i></i>
                <span>Gone</span>
            </li>
            <li class="chat">
                <i></i>
                <span>Gone</span>
            </li>
        </ul>
    </div>

    <div class="chat-bottom">
        <span class="fa fa-wechat"></span> <span>Chat</span>
    </div>

    <div class="chat-box">
        <div class="chat-box-head"></div>
        <div class="chat-box-body"></div>
        <div class="chat-box-buttom"></div>
    </div>
</div>

<script>
    ws = new WebSocket("ws://{{ config('chat.socket_ip').':'.config('chat.socket_port') }}");
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
                $.post('{{ route("chat-bind") }}', {client_id: data.client_id}, function(data){}, 'json');
                break;
            // 当mvc框架调用GatewayClient发消息时直接alert出来
            default :
                alert(e.data);
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
</script>