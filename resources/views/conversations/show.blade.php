<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .panel-body{
            height: 50vh;
            overflow-y: scroll;
        }
        .message{
            padding: 10pt;
            border-radius: 5pt;
            margin: 5pt;
        }
        .owner{
            background-color: #ccd7e0;
            float: right;
        }
        .not_owner{
            background-color: #eaeff2;
            float:left;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="col-md-offset-2 col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        {{($conversation->user1()->first()->id==auth()->id())?$conversation->user2()->first()->name:$conversation->user1()->first()->name}}
                    </div>
                </div>
            </div>
            <div class="panel-body" id="panel-body">
                @foreach($conversation->messages as $message)
                    <div class="row">
                        <div class="message {{ ($message->user_id!=auth()->id())?'not_owner':'owner'}}">
                            {{$message->text}}<br/>
                            <b>{{$message->created_at->diffForHumans()}}</b>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="panel-footer">
                <textarea id="msg" class="form-control" placeholder="Write your message"></textarea>
                <input type="hidden" id="csrf_token_input" value="{{csrf_token()}}"/>
                <br/>
                <div class="row">
                    <div class="col-md-offset-4 col-md-4">
                        <button class="btn btn-primary btn-block" onclick="button_send_msg()">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--<script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>--}}
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="https://cdn.socket.io/4.5.4/socket.io.min.js" integrity="sha384-/KNQL8Nu5gCHLqwqfQjA689Hhoqgi2S84SNUxC3roTe4EhJ9AfLkp8QiQcU8AMzI" crossorigin="anonymous"></script>
<script>
    var socket = io.connect('http://127.0.0.1:8890', {
        // cors: {
        //     origin: "http://127.0.0.1:8000",
        //     methods: ["GET", "POST"]
        // }
    });
    socket.emit('add user', {'client':{{auth()->id()}},'conversation':{{$conversation->id}}});

    socket.on('message', function (data) {
        console.log(data);

        $('#panel-body').append(
            '<div class="row">'+
            '<div class="message not_owner">'+
            data.msg+'<br/>'+
            '<b>now</b>'+
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
            url: "{{route('messages.store')}}",
            data: {
                'text': msg,
                'conversation_id':{{$conversation->id}},
            },
            success: function (data) {
                // if(data==true){
                //
                //     $('#panel-body').append(
                //         '<div class="row">'+
                //         '<div class="message owner">'+
                //         msg+'<br/>'+
                //         '<b>ora</b>'+
                //         '</div>'+
                //         '</div>');
                //
                //     scrollToEnd();
                // }
                console.log('sended')
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
</body>
</html>
