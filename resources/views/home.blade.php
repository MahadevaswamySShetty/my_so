@extends('layouts.app')
@section('content')

<style type="text/css">
    #messages{
        border: 1px solid black;
        height: 300px;
        margin-bottom: 8px;
        overflow: scroll;
        padding: 5px;
    }
</style>
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Chat Message Module</div>
                <div class="panel-body">
 
                <div class="row">
                    <div class="col-lg-8">
                      <div id="messages"></div>
                    </div>
                    <div class="col-lg-8" >
                            <form id="form_submit">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                <input type="hidden" name="user" value="{{ Auth::user()->name }}">
                                <textarea class="form-control msg"></textarea>
                                <br/>
                                <input type="submit" value="Send" id="submit" class="btn btn-success send-msg">
                            </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.slim.js"></script>

<script>
    var socket = io.connect('http://localhost:8890');
    console.log(socket);
    socket.on('message', function (data) {
        data = jQuery.parseJSON(data);
        console.log(data);
       $("#messages").append("<strong>"+data.user+":</strong><p>"+data.message+"</p>");
      });   
   $(document).ready(function(e){
     $("#form_submit").on('submit',function(e){      
        e.preventDefault();
        alert("hi");
        var token = $("input[name='_token']").val();
        var user = $("input[name='user']").val();
        var msg = $(".msg").val();
        if(msg != ''){
            $.ajax({
                type: "POST",
                url: '{{url("sendMessage")}}',
                dataType: "json",
                data: {'_token':token,'message':msg,'user':user},
                success:function(data){
                    console.log(data);
                    $(".msg").val('');
                }
            });
        }else{
            alert("Please Add Message.");
        }
    })
   }) 
    
</script>
@endsection