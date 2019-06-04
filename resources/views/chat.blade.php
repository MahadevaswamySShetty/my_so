
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading text-center"><b>Group Message</b></div>
                <div class="panel-body" id="panel-body">
                    @foreach($chat as $chat)
                    <div class="row">
                        <div class="message {{ ($chat->user_id!=Auth::user()->id)?'not_owner':'owner'}}">
                            {{$chat->message}}<br/>
                            <b>{{$chat->created_at->diffForHumans()}}</b>
                           
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="panel-footer">
                    <div class="input-group" id="chat-form">
                        <input id="msg" type="text" class="form-control" placeholder="Type your message here..." />
                        <input type="hidden" id="csrf_token_input" value="{{csrf_token()}}"/>
                        <span class="input-group-btn">
                            <button class="btn btn-primary" onclick="button_send_msg()" id="btn-chat">
                                Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.slim.js"></script>
<script>
    var user = {{Auth::user()->id}};
    
    var socket = io.connect('http://localhost:8890');
    console.log(socket);
    socket.on('message', function (data) {
        data = jQuery.parseJSON(data);
        //console.log(data);
        if(data.user_id == user){
            $('#panel-body').append(
                    '<div class="row">'+
                    '<div class="message owner">'+
                    data.message+'<br/>'+'<b>now</b>'+
                    '</div>'+
                    '</div>');
             
        }else{
            $('#panel-body').append(
                    '<div class="row">'+
                    '<div class="message not_owner">'+
                    data.message+'<br/>'+'<b>now</b>'+
                    '</div>'+
                    '</div>');
             
        }

      });  
</script>
<script>
        
        function button_send_msg(){
            var msg = $('#msg').val();
            $('#msg').val('');//reset
            $.ajax({
                headers: { 'X-CSRF-Token' : $('#csrf_token_input').val() },
                type: "POST",
                url: '/group_chat_store',
                data: {
                    'message': msg,
                },
                success: function (data) {
                    console.log(data);
                    

                },
                error: function (e) {
                    console.log(e);
                }
            });
        }


    </script>
@endsection