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
      }
      console.log("cicle");
    } while (out=="0");
}

first_thumbnail();