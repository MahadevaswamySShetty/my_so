@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>
                <div class="panel-body">
                    @foreach($user as $user)
                    <div class="row">
                        <div class="col-md-6">
                        {{$user->name}}
                        </div>
                        <div class="col-md-6">
                        <form action="{{route('conversation.store')}}" method="post">
                                {{ csrf_field()}}
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                <input type="submit" class="btn btn-primary" value="add"><br><br>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Conversations</div>
                <div class="panel-body">
                    @foreach($conversation as $conversation)
                        <a href="{{ route('conversation.show',$conversation->id)}}">
                            {{($conversation->user1()->first()->id==Auth::user()->id)?$conversation->user2()->first()->name:$conversation->user1()->first()->name}}
                        </a><hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
