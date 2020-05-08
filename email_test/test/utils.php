<?php
function snippet($path, $vars = []) {
    extract($vars);
    include $path;
}
function loadTheme($path) {
    return json_decode(file_get_contents($path), true);
}
?>