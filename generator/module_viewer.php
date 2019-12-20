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

<div>
    <div class="row justify-content-center">
        <div class="col-md-6 C shadow_box" style="background-color: white;padding-bottom: 20px;">
        <figure>
            <div>
                <svg id="icon-modu_beta" viewBox="0 0 60 55" width="100" height="50">
                    <title>modu_beta</title>
                    <path fill="#000" style="fill: var(--color1, #000)" d="M141.528 25.509c0-2.698 2.080-4.48 4.464-4.48s4.462 1.782 4.462 4.48c0 2.699-2.079 4.48-4.462 4.48-2.384 0-4.464-1.781-4.464-4.48z"></path>
                    <path fill="#000" style="fill: var(--color1, #000)" d="M44.17 13.311v16.423h-8.484v-16.094c0-3.613-1.971-5.365-4.543-5.365-2.736 0-4.816 1.752-4.816 5.365v16.094h-8.485v-16.094c0-3.613-1.97-5.365-4.543-5.365-2.68 0-4.816 1.752-4.816 5.365v16.094h-8.484v-28.467h8.484v3.503c1.532-2.354 4.16-4.051 7.936-4.051 3.339 0 6.24 1.424 8.047 4.489 1.751-2.573 4.816-4.489 9.304-4.489 6.021 0 10.4 3.833 10.4 12.592z"></path>
                    <path fill="#000" style="fill: var(--color1, #000)" d="M71.507 15.663c0-4.427-3.118-7.193-6.848-7.193s-6.849 2.767-6.849 7.193c0 4.428 3.119 7.195 6.849 7.195s6.848-2.768 6.848-7.195zM49.291 15.663c0-9.795 7.795-14.941 15.368-14.941s15.368 5.147 15.368 14.941c0 9.796-7.795 14.941-15.368 14.941s-15.368-5.146-15.368-14.941z"></path>
                    <path fill="#000" style="fill: var(--color1, #000)" d="M135.153 0.549v28.885h-8.644v-3.667c-1.617 2.445-4.461 4.222-8.588 4.222-5.856 0-10.819-3.611-10.819-12.442v-16.998h8.644v15.498c0 3.611 1.841 6.276 5.41 6.276 3.514 0 5.354-2.665 5.354-6.276v-15.498h8.644z"></path>
                    <path fill="#000" style="fill: var(--color1, #000)" d="M84.55 0.722v30.723c8.714 0 16.673-6.877 16.673-15.361s-7.959-15.361-16.673-15.361z"></path>
                    <path fill="#515b68" style="fill: var(--color2, #515b68)" d="M160.252 27.728c0-0.595-0.411-0.931-1.22-0.931h-1.848v1.861h1.848c0.832 0 1.22-0.347 1.22-0.931v0zM157.184 23.639v1.785h1.585c0.81 0 1.209-0.335 1.209-0.909 0-0.551-0.377-0.876-1.152-0.876h-1.642zM161.928 27.868c0 1.449-1.083 2.12-2.931 2.12h-3.444v-7.68h3.216c1.802 0 2.886 0.649 2.886 2.098 0 0.833-0.354 1.341-0.981 1.59v0.249c0.81 0.291 1.254 0.811 1.254 1.622v0z"></path>
                    <path fill="#515b68" style="fill: var(--color2, #515b68)" d="M164.662 23.682v1.709h3.336v1.374h-3.336v1.85h3.642v1.374h-5.1v-7.68h5.1v1.374z"></path>
                    <path fill="#515b68" style="fill: var(--color2, #515b68)" d="M174.678 23.639h-1.861v6.349h-1.379v-6.349h-1.861v-1.331h5.1z"></path>
                    <path fill="#515b68" style="fill: var(--color2, #515b68)" d="M179.571 27.133l-0.98-2.921h-0.277l-0.923 2.921h2.181zM180.020 28.464h-3.058l-0.484 1.525h-1.8l2.884-7.68h1.846l2.92 7.68h-1.8l-0.509-1.525z"></path>
                </svg>
            </div>
        </figure>
            <input type="text" name="subject" id="subject" value="977">
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 C shadow_box" style="background-color: white;padding-bottom: 20px;">
            <div style="--aspect-ratio: 16/9;">
                <iframe src="http://generator.getmodu.com/view_module.php?id=977" id="viewer" width="1600" height="600" frameborder="0"></iframe>
            </div>        
        </div>
    </div>
</div>
    
    <script>
$( document ).ready(function() {

    $("#subject").on('keyup', function (e) {
        if (e.keyCode === 13) {
            alert('ENTER');
            var id = $("#subject").val();
            var url = "http://generator.getmodu.com/view_module.php?id=" + id
            $('#viewer').attr('src', url);
        }
    });

});
    

    //$('#abc_frame').attr('src', url);
    </script>

</body>
</html>


<div class="App-navigationBar"><div class="NavigationBar"><div class="NavigationBar-container Container BoundsContainer Flex Flex-middle Flex-between"><div class="Flex Flex-middle"><div class="NavigationBar-logo"><a target="_blank" class="Logo"><div class="Logo-container Container"><svg class="Icon Icon-modu_beta Logo-icon"><use xlink:href="#icon-modu_beta"></use></svg></div></a></div> <div class="NavigationBar-projectTitle"><div class="Eyebrow"><div class="Eyebrow-container Container Flex Flex-center Flex-middle"><div class="ThreeDots Eyebrow-dots"><div class="ThreeDots-container Container Flex Flex-middle"><div class="ThreeDots-dot"></div> <div class="ThreeDots-dot"></div> <div class="ThreeDots-dot"></div></div></div> DASHBOARD</div></div></div></div> <div class="NavigationBar-navigation"></div> <div class="NavigationBar-actions"><div class="Container Flex Flex-middle"><!----> <div class="NavigationBar-action"><div class="UserMenu"><div class="UserMenu-container Container Flex Flex-middle Flex-center"><span class="AvatarDisplay" style="background-image: url(&quot;undefined&quot;);"><span class="AvatarDisplay-container">
      LS
    </span></span> <div class="UserMenu-trigger"><svg class="Icon Icon-arrow_down UserMenu-trigger-icon"><use xlink:href="#icon-arrow_down"></use></svg></div></div> <div class="v-portal" style="display: none;"></div></div></div></div></div></div></div></div>