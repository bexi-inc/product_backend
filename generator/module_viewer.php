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
    </style>
</head>
<body style="background-color:#E7EAEA">

<div>
    <div class="row justify-content-center">
        <div class="col-4 C">
            <p>BACK</p>
        </div>
        <div class="col-4 C">
            <input type="text" name="subject" class="bexi_input" id="subject" value="977">
        </div>
        <div class="col-4 C">
            <p>NEXT</p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 C shadow_box" style="background-color: white;padding-bottom: 20px;">
            <div style="--aspect-ratio: 16/9;">
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
                    var url = "http://generator.getmodu.com/view_module.php?id=" + window.contents[0].id;
                    $('#viewer').attr('src', url);
                    $('#viewer').attr('current', 0);
                    console.log(window.contents);
                 }
            });
            $("#subject").on('keyup', function (e) {
                if (e.keyCode === 13) {
                    alert('ENTER');
                    var id = $("#subject").val();
                    var url = "http://generator.getmodu.com/view_module.php?id=" + id;
                    $('#viewer').attr('src', url);
                }
            });

        });
    </script>
</html>