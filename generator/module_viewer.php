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
    <style>
        [style*="--aspect-ratio"] > :first-child {
        width: 100%;
        }
        [style*="--aspect-ratio"] > img {  
        height: auto;
        } 
        @supports (--custom:property) {
        [style*="--aspect-ratio"] {
            position: relative;
        }
        [style*="--aspect-ratio"]::before {
            content: "";
            display: block;
            padding-bottom: calc(100% / (var(--aspect-ratio)));
        }  
        [style*="--aspect-ratio"] > :first-child {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
        }  
        }
        .iframe-container {
        overflow: hidden;
        padding-top: 56.25%;
        position: relative;
        }
        
        .iframe-container iframe {
        border: 0;
        height: 100%;
        left: 0;
        position: absolute;
        top: 0;
        width: 100%;
        }
        
        /* 4x3 Aspect Ratio */
        .iframe-container-4x3 {
        padding-top: 75%;
        }
        iframe {
        -moz-transform: scale(0.25, 0.25); 
        -webkit-transform: scale(0.25, 0.25); 
        -o-transform: scale(0.25, 0.25);
        -ms-transform: scale(0.25, 0.25);
        transform: scale(0.25, 0.25); 
        -moz-transform-origin: top left;
        -webkit-transform-origin: top left;
        -o-transform-origin: top left;
        -ms-transform-origin: top left;
        transform-origin: top left;
        }
        #viewer{
            -moz-transform: scale(0.25, 0.25); 
            -moz-transform-origin: top left;
        }
    </style>
</head>
<body style="background-color:#E7EAEA">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-4 C">
            <p class="back" >BACK</p>
        </div>
        <div class="col-4 C">
            <input type="text" name="subject" class="bexi_input" id="subject" value="977">
        </div>
        <div class="col-4 C">
            <p class="next" >NEXT</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-11 C shadow_box" style="background-color: white;padding: 20px;">
            <!--<div style="--aspect-ratio: 16/9;">
                <iframe src="" id="viewer" width="1600" height="600" frameborder="0"></iframe>
            </div>-->
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
        }
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
                        alert('NON EXISTENT');
                        $("#subject").val($('#viewer').attr('current'));
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
             
        });
    </script>
</html>