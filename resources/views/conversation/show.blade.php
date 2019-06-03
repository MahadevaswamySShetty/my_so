@extends('layouts.app')



@section('content')
    <div class="container">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-8">
                            {{($conversation->user1()->first()->id==Auth::user()->id)?$conversation->user2()->first()->name:$conversation->user1()->first()->name}}
                        </div>
                    </div>
                </div>
                <div class="panel-body" id="panel-body">
                    @foreach($conversation->messages as $message)
                        <div class="row">
                            <div class="message {{ ($message->user_id!=Auth::user()->id)?'not_owner':'owner'}}">
                                {{$message->text}}<br/>
                               
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="panel-footer">
                    <div class="">
                        <div class="input-group">
                            <input id="msg" type="text" class="form-control" placeholder="Type your message here..." />
                            <input type="hidden" id="csrf_token_input" value="{{csrf_token()}}"/>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" onclick="button_send_msg()" id="btn-chat">
                                    Send</button>
                            </span>
                        </div>
                        <!-- <div class="col-md-10">
                            <textarea id="msg" class="form-control" placeholder="Write your message"></textarea>
                            <input type="hidden" id="csrf_token_input" value="{{csrf_token()}}"/>
                            <br/>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" onclick="button_send_msg()">Send</button>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.slim.js"></script>
    <script>
        var socket = io.connect('http://127.0.0.1:8890');
        socket.emit('add user', {'client':{{Auth::user()->id}},'conversation':{{$conversation->id}}});

        socket.on('message', function (data) {
            $('#panel-body').append(
                    '<div class="row">'+
                    '<div class="message not_owner">'+
                    data.msg+'<br/>'+
                    '</div>'+
                    '</div>');

            scrollToEnd();

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
            $.ajax({
                headers: { 'X-CSRF-Token' : $('#csrf_token_input').val() },
                type: "POST",
                url: "{{route('message.store')}}",
                data: {
                    'text': msg,
                    'conversation_id':{{$conversation->id}},
                },
                success: function (data) {
                    if(data==true){

                        $('#panel-body').append(
                                '<div class="row">'+
                                '<div class="message owner">'+
                                msg+'<br/>'+
                                '</div>'+
                                '</div>');

                        scrollToEnd();
                    }
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
