importScripts("https://code.jquery.com/jquery-3.4.1.min.js");

function first_thumbnail(){
    do {
      var out="1";
      $(".hero").find('*').each(function() {
        if($(this).width()==0&&$(this).height()==0)
        {
          out="0";
        }
      });
      if(out==="1")
      {
        thumbnail();
        postMessage("done");
      }
      console.log("cicle");
    } while (out=="0");
}

first_thumbnail();