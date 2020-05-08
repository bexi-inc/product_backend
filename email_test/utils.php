<?php
function snippet($path, $vars = []) {
    extract($vars);
    include $path;
}

function snippet2($code, $vars = []) {
    extract($vars);
    eval ($code);
}
function loadTheme($path) {
    return json_decode(file_get_contents($path), true);
}
?>