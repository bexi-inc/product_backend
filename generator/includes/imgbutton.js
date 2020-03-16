$(document).ready(function() {

    html2canvas($("#maindiv"), {
    onrendered: function(canvas) {
        var link = document.createElement('a');
        link.download = 'my-image-name.jpeg';
        link.href = canvas.toDataURL("image/png");
        link.click();
    },
    allowTaint: true
    });

    
    //html2canvas(document.querySelector("#maindiv")).then(canvas => {
        //document.body.appendChild(canvas)
        // var link = document.createElement('a');
        // link.download = 'my-image-name.jpeg';
        // link.href = canvas.toDataURL("image/png");
        // link.click();
    //});
    
    
    // var node = document.getElementById('maindiv');
    // domtoimage.toPng(node)
    // .then(function (dataUrl) {
    //     var link = document.createElement('a');
    //     link.download = 'my-image-name.jpeg';
    //     link.href = dataUrl;
    //     link.click();
    // })
    // .catch(function (error) {
    //     console.error('oops, something went wrong!', error);
    // });
});