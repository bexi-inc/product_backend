<?php
    /**
     * Content Loader to share variables and randomize
     * @param string $title - The h1 content
     * @param string $titleAlign - h1 alignment can be left, center or right
     * @param string $linkColor - The color to be used by the content links and buttons
     */
    // Shared variables between the content type
    $title = isset($title) ? $title : null;
    $titleAlign = isset($titleAlign) ? $titleAlign : "left";
    $titlesColor = isset($titlesColor) ? $titlesColor : "#000000";
    $linkColor = isset($linkColor) ? $linkColor : "#ff6364";
    $textColor = isset($textColor) ? $textColor : "#7f8fa4";
    // Available contents to randomize
    $contents = [
        'confirm.php',
        'download.php',
        'welcome.php'
    ];
    // $contentCurrent = $contents[array_rand($contents)];
    $buttonConfigDefault = [
        backgroundColor => $linkColor, radius => "25px", height => "57px", width=> "250px", fontWeight => "bold",
    ];
    $buttonConfig = isset($buttonConfig) ? array_merge($buttonConfigDefault, $buttonConfig) : $buttonConfigDefault;
    /**
     * @var string Determines what content load
     */
    $load = isset($load) ? $load : $contents[array_rand($contents)];
    include $load;
?>