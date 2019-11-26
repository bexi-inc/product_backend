var email="";

function send_data(ID,event){
    event.preventDefault();
        $.ajax({
            url: 'http://generator.getmodu.com/ajax/sendform.php', // url where to submit the request
            type : "POST", // type of action POST || GET
            dataType : 'json', // data type
            data : $("#"+ID).serialize() + "&e_to="+email, // post data || get data
            success : function(result) {
                // you can see the result from the console
                // tab of the developer tools
                $("#"+ID).empty();
                $("#"+ID).prepend('<div class="row m-0 C">Thanks! Your message was successfully submitted.</div>');
            },
            error: function(xhr, resp, text) {
                alert("Sorry, something went wrong =(");
            }
        });
}

$(document).ready(function(){
    $(".bexi_form").each(function(){
        $(this).submit(function(e){
          e.preventDefault();
          send_data($(this).attr("id"),e);
        });
      });
});