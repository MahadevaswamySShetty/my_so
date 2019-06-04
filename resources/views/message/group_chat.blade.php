
@extends('layouts.app')
<style type="text/css">
    .avatar {
      vertical-align: middle;
      width: 25px;
      height: 25px;
      border-radius: 100%;
}
</style>
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
                            <img src="{{asset('storage/profile/'.$chat->users->file)}}" alt="Avatar" class="avatar">
                            <small><b>{{ $chat->users->name }}</b></small> <br/>
                            {{$chat->message}}<br/>
                            <small><b>{{$chat->created_at->diffForHumans()}}</b></small>
                           
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="panel-footer">
                    <form enctype="multipart/form-data">
                        <div class="input-group" id="chat-form">
                        <span class="input-group-btn btn btn-primary">
                             <input type="file" id="file" name="file">
                        </span>
                        <input id="msg" type="text" class="form-control" placeholder="Type your message here..." />
                        <input type="hidden" id="csrf_token_input" value="{{csrf_token()}}"/>
                        <span class="input-group-btn">
                            <button class="btn btn-primary" onclick="button_send_msg()" id="btn-chat">
                                Send</button>
                        </span>
                    </div>
                    </form>
                    
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
        if(data['data'].users.id == user){
            $('#panel-body').append(
                    '<div class="row">'+
                    '<div class="message owner">'+'<img src="{{asset('storage/profile/')}}/'+data['data'].users.file+'" alt="Avatar" class="avatar">'+data['data'].users.name+'<br/>'
                    +data['data'].message+'<br/>'+data['time']+
                    '</div>'+
                    '</div>');
             
        }else{
            $('#panel-body').append(
                    '<div class="row">'+
                    '<div class="message not_owner">'+'<img src="{{asset('storage/profile/')}}/'+data['data'].users.file+'" alt="Avatar" class="avatar">'+data['data'].users.name+'<br/>'
                    +data['data'].message+'<br/>'+data['time']+
                    '</div>'+
                    '</div>');
             
        }

      });  
</script>
<script>
    $(document).ready(function(){
            scrollToEnd();

            $(document).keypress(function(e) {
                if(e.which == 13) {
                    var msg = $('#msg').val();
                    $('#msg').val('');//reset
                    send_msg(msg);
                }
            });
        });

        function button_send_msg(){
            var msg = $('#msg').val();
            $('#msg').val('');//reset
            send_msg(msg);
        }
        
        function send_msg(msg){
            // var msg = $('#msg').val();
            // $('#msg').val('');//reset
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

         function scrollToEnd(){
            var d = $('#panel-body');
            d.scrollTop(d.prop("scrollHeight"));
        }
    </script>
@endsection