<style>
    #wrap {display:block;bottom:0px;right:1px!important;right:18px;width:200px;line-height:30px;position:fixed;border:1px solid #fff;text-align:center;color:#fff; background:#000;}
</style>

<div id="wrap">我是不会动的，只有这个地方是属于我的，在你没有更改我的位置之前。</div>

<script>
    ws = new WebSocket({{ config('chat.socket') }});
    // 服务端主动推送消息时会触发这里的onmessage
    ws.onmessage = function(e){
        // json数据转换成js对象
        var data = eval("("+e.data+")");
        var type = data.type || '';
        switch(type){
            // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定
            case 'init':
                // 利用jquery发起ajax请求，将client_id发给后端进行uid绑定
                $.post('./bind.php', {client_id: data.client_id}, function(data){}, 'json');
                break;
            // 当mvc框架调用GatewayClient发消息时直接alert出来
            default :
                alert(e.data);
        }
    };
</script>