$(document).ready(function() {
    var node = document.getElementById('maindiv');
    domtoimage.toPng(node)
    .then(function (blob) {
        window.saveAs(blob, 'my-node.png');
    })
    // .then(function (dataUrl) {
    //     var img = new Image();
    //     img.src = dataUrl;
    //     document.body.appendChild(img);
    // })
    .catch(function (error) {
        console.error('oops, something went wrong!', error);
    });
});