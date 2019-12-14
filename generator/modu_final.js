
function send_data(ID,event){
    event.preventDefault();
        $.ajax({
            url: 'http://generator.getmodu.com/ajax/sendform.php', // url where to submit the request
            type : "POST", // type of action POST || GET
            dataType : 'json', // data type
            data : $("#"+ID).serialize() + "&email_to="+email+"&project_name="+project_name, // post data || get data
            success : function(result) {
                // Do not touch
                $("#"+ID).empty();
                $("#"+ID).prepend('<div class="row m-0 C" style="Background-color:White;">Thanks! Your message was successfully submitted.</div>');
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