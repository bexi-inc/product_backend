var email="adrian@bexi.io";

function send_data(ID){
        $.ajax({
            url: 'http://generator.getmodu.com/ajax/sendform.php', // url where to submit the request
            type : "POST", // type of action POST || GET
            dataType : 'json', // data type
            data : $("#"+ID).closest(".bexi_form").serialize() + "&e_to="+email, // post data || get data
            success : function(result) {
                // you can see the result from the console
                // tab of the developer tools
                var parent= $("#"+ID).closest(".bexi_form").attr("id");
                $("#"+ID).closest(".bexi_form").empty();
                $("#"+parent).prepend('<div class="row m-0">Thanks! Your message was successfully submitted.</div>');
            },
            error: function(xhr, resp, text) {
                alert("Sorry, something went wrong =(");
            }
        });
}