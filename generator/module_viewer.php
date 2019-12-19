<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Module Viewer</title>
    <script src="includes/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bexi.css" >
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 C shadow_box" style="background-color: white;padding-bottom: 20px;">
            <h2 id="%id%" class="bexi_title Black C" style="font-family: Arial, Helvetica, sans-serif;">Digital App</h2>
            <input type="text" name="subject" id="subject" value="977">
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 C shadow_box" style="background-color: white;padding-bottom: 20px;">
            <iframe src="http://generator.getmodu.com/view_module.php?id=977" frameborder="1"></iframe>
        </div>
    </div>
</div>
    
    <script>
$( document ).ready(function() {

    $("#subject").on('keyup', function (e) {
        if (e.keyCode === 13) {
            alert('ENTER');
        }
    });

});
    

    //$('#abc_frame').attr('src', url);
    </script>

</body>
</html>