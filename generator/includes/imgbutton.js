$(document).ready(function() {
    var node = document.getElementById('maindiv');
    domtoimage.toPng(node)
    .then(function (dataUrl) {
        var link = document.createElement('a');
        link.download = 'my-image-name.jpeg';
        link.href = dataUrl;
        link.click();
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