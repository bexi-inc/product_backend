function send_data(){
        $.ajax({
            url: './ajax/send_form.php', // url where to submit the request
            type : "POST", // type of action POST || GET
            dataType : 'json', // data type
            data : $(this).closest(".bexi_form").serialize(), // post data || get data
            success : function(result) {
                // you can see the result from the console
                // tab of the developer tools
            },
            error: function(xhr, resp, text) {

            }
        });

    $(this).closest(".bexi_form").hide();
}