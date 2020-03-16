$(document).ready(function() {
    var node = document.getElementById('maindiv');
    domtoimage.toPng(node)
    .then(function (dataUrl) {
        var img = new Image();
        img.src = dataUrl;
        document.body.appendChild(img);
    })
});