<?php
    require "utils.php";
    $themes = [
        "themes/theme-1.json",
        "themes/theme-2.json",
        "themes/theme-3.json",
    ];
    $theme = loadTheme($themes[array_rand($themes)]);
    $brandColors = [
        "#FF00BA",
        "#3FC3D5",
        "#2B87A0"
    ];
    snippet("head.php", [title => "Test Email", backgroundColor => $theme["backgroundColor"]]);
    snippet("header.php", [align => "left", logoSrc => "http://uploads.getmodu.com/emails/modu-beta-logo.png", logoAlt => "Modu Logo"]);
    snippet("content/loader.php", [linkColor => $theme["link"]["color"], subdomain => "test", projectName => "Test Project Name",  buttonConfig => $theme["button"]]);
    snippet("footer.php", [brandColors => $brandColors]);
?>