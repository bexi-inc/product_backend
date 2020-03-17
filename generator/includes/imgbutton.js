$(document).ready(function() {

    // html2canvas($("#maindiv"), {
    // onrendered: function(canvas) {
    //     var link = document.createElement('a');
    //     link.download = 'my-image-name.jpeg';
    //     link.href = canvas.toDataURL("image/png");
    //     link.click();
    // },
    // allowTaint: true,
    // useCORS: true
    // });


    html2canvas(document.querySelector("#maindiv") ,{allowTaint: false, useCORS: true,backgroundColor:null}).then(canvas => {
        var extra_canvas = document.createElement("canvas");
        extra_canvas.setAttribute('width',300);
        extra_canvas.setAttribute('height',250);
        var ctx = extra_canvas.getContext('2d');
        ctx.drawImage(canvas,0,0,canvas.width, canvas.height,0,0,300,250);
        var dataURL = extra_canvas.toDataURL();
        var img = $(document.createElement('img'));
        img.attr('src', dataURL);
        // insert the thumbnail at the top of the page
        $('body').appendChild(img);

        var link = document.createElement('a');
        link.download = 'my-image-name.jpeg';
        link.href = dataURL;
        link.click();
    });


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