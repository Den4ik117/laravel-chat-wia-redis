<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>

                <div class="panel-body">
                    @foreach($users as $user)
                        <div class="row">
                            <div class="col-md-6">
                                {{$user->name}} / {{ $user->email }}
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('conversations.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit">Add</button>
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
                    @foreach($conversations as $conversation)
                        <a href="{{route('conversations.show',$conversation->id)}}">
                            {{($conversation->user1()->first()->id==Auth::user()->id)?$conversation->user2()->first()->name:$conversation->user1()->first()->name}}
                        </a>
                        <hr/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
