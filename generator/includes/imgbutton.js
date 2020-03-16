$(document).ready(function() {
    var element = $("#maindiv"); // global variable
    var getCanvas; // global variable
    
    html2canvas(element, {
    onrendered: function (canvas) {
        document.body.appendChild(canvas);
        //$("#previewImage").append(canvas);
        getCanvas = canvas;
        }
    });
});