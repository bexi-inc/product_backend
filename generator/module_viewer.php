<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Module Viewer</title>
    <script src="includes/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bexi.css" >
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <style>
        
        .iframe-container {
            min-height: calc(100vh - 100px);
        }
        
        .iframe-container iframe {
        border: 0;
        left: 0;
        top: 0;
        width: 100%;
        min-height: calc(100vh - 100px);
        }
        
        /* 4x3 Aspect Ratio */
        .iframe-container-4x3 {
        padding-top: 75%;
        }

        .buttons{
            position: relative;
            display: table;
            padding: 20px 12px;
            border: 0;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1;
            letter-spacing: 2px;
            color: #fff;
            background-color: #FF6364;
            margin: auto;
            display: block;
            top: 15%;
            width: 150px;
            font-weight: 700;
        }

        .buttons:hover{
            color: #fff;
            background-color: #221F93;
        }

        .buttons2{
            position: relative;
            display: table;
            padding: 20px 12px;
            border: 0;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1;
            letter-spacing: 2px;
            color: #fff;
            background-color: #FF6364;
            margin: auto;
            display: block;
            top: 15%;
        }
    </style>
</head>
<body style="background-color:#E7EAEA">

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-1 C">
            <button class="buttons2 first mr-0"><i class="fas fa-angle-double-left"></i></button>
        </div>
        <div class="col-3 C">
            <button class="buttons back ml-0"><i class="fas fa-chevron-left"></i> BACK</button>
        </div>
        <div class="col-4 C">
            <input type="text" name="subject" class="bexi_input C" id="subject" >
        </div>
        <div class="col-3 C">
            <button class="buttons next mr-0">NEXT <i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="col-1 C ">
            <button class="buttons2 last ml-0"><i class="fas fa-angle-double-right"></i></button>
        </div>
    </div>
    <div class="row m-0 justify-content-center">
        <div class="col-md-12 C shadow_box" style="background-color: white;">
            <div class="iframe-container">
                <iframe src="" id="viewer" width="1600" height="600" frameborder="0"></iframe>
            </div>  
        </div>
    </div>
</div>

</body>

    <script>
        window.contents;
        function SortByName(a, b){
            var aID = a.id;
            var bID = b.id; 
            return (aID-bID);
        };
        $( document ).ready(function() {
            $.ajax({
                url: 'http://generator.getmodu.com/api/v1/api.php',
                data: {"cmd" : "scan"},
                dataType: "json",
                type: 'POST',
                 complete:function(data){
                    
                    window.contents = data.responseJSON.contents;
                    window.contents.sort(SortByName);
                    var url = "http://generator.getmodu.com/aux_viewer.php?id=" + window.contents[0].id;
                    $('#viewer').attr('src', url);
                    $('#viewer').attr('current', 0);
                    $("#subject").val(window.contents[0].id);
                    console.log(window.contents);
                 }
            });
            $("#subject").on('keyup', function (e) {
                if (e.keyCode === 13) {
                    var id = $("#subject").val();
                    var exist = 0;
                    var pos;
                    for (let index = 0; index < window.contents.length; index++) {
                        if (window.contents[index].id == id) {
                            exist = 1;
                            pos = index;
                            index = window.contents.length;
                        }
                    }
                    if (exist == 1) {
                        var url = "http://generator.getmodu.com/aux_viewer.php?id=" + id;
                        $('#viewer').attr('src', url);
                        $('#viewer').attr('current', pos);
                    }
                    else{
                        alert("The content block that you're trying to access was deleted from the Data Base");
                        $("#subject").val(window.contents[current].id);
                    }
                    
                }
            });
            $(".next").click(function () {
                var n = window.contents.length;
                var current = parseInt($('#viewer').attr('current')) + 1;
                console.log(n,'n');
                console.log(current);
                if (current < n){
                    var url = "http://generator.getmodu.com/aux_viewer.php?id=" + window.contents[current].id;
                    $('#viewer').attr('src', url);
                    $('#viewer').attr('current', current);
                    $("#subject").val(window.contents[current].id);
                    console.log('IF NEXT');
                }
                
            });
            $(".last").click(function () {
                var n = window.contents.length;
                var n = n - 1;
                console.log(n,'n');
                    var url = "http://generator.getmodu.com/aux_viewer.php?id=" + window.contents[n].id;
                    $('#viewer').attr('src', url);
                    $('#viewer').attr('current', n);
                    $("#subject").val(window.contents[n].id);
                
            });
            $(".back").click(function () {
                var n = window.contents.length;
                var current = parseInt($('#viewer').attr('current')) - 1;
                console.log(current);
                if (current >= 0){
                    var url = "http://generator.getmodu.com/aux_viewer.php?id=" + window.contents[current].id;
                    $('#viewer').attr('src', url);
                    $('#viewer').attr('current', current);
                    $("#subject").val(window.contents[current].id);
                    console.log('IF BACK');
                }
            });
            $(".first").click(function () {
                var url = "http://generator.getmodu.com/aux_viewer.php?id=" + window.contents[0].id;
                $('#viewer').attr('src', url);
                $('#viewer').attr('current', 0);
                $("#subject").val(window.contents[0].id);
                
            });
            
             
        });
    </script>
</html>