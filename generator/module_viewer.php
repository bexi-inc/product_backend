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

<div class="bexi_container">
    <div class="row justify-content-center py-5" style="position: relative;">
        <div class="col-md-6 C p-5 shadow_box" style="background-color: white;">
            <h2 id="%id%" class="bexi_title Black C">Digital App</h2>
            <p id="%id%" class="bexi_text Lead_text Gray C p-0">
                <form name="form" action="" method="get">
                    <input type="text" name="subject" id="subject" value="Car Loan">
                </form>
            </p>
            <p id="%id%" class="mt-4 C">
                <a class="btn btn-primary bexi_button" href="#" style="width: auto;">Download</a>
            </p>
        </div>
    </div>
</div>
    
    <script>

    $("#subject").on('keyup', function (e) {
        if (e.keyCode === 13) {
            alert('ENTER');
        }
    });

    $('#abc_frame').attr('src', url);
    </script>

</body>
</html>