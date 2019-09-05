   
    function ChangePalette(NewPallete)
    {
       // alert (paletteId);
        paletteId = NewPallete;
        var params = {
            "cmd" : "GetColorPallete",
            "id" : NewPallete
        }
        $.ajax({
            type: "GET",
            url: 'ajax.php',
            data: params,
            dataType: 'json',
            success: function(data){
                //alert(data.color1);
                $("#color1").css('background-color', data.color1);
                $("#color2").css('background-color', data.color2);
                $("#color3").css('background-color', data.color3);
                $("#color4").css('background-color', data.color4);
                $("#color5").css('background-color', data.color5);

                $('link[id^="mod_css_"]').remove();
                $('head').append('<? echo $js_css; ?>');
                //alert(data.color1);
            }
        });
    }


    function ChangeFont(NewFont)
    {
       // alert (paletteId);
        FontId = NewFont;
        
        var params = {
            "cmd" : "GetFontData",
            "id" : NewFont
        }
        $.ajax({
            type: "GET",
            url: 'ajax.php',
            data: params,
            dataType: 'json',
            success: function(data){
                //alert(data.color1);
                var cssfont = [data.import.slice(0, 6),' id="css_font_1" ', data.import.slice(6)].join('');
                //alert (cssfont);
                $('link[id^="css_font"]').remove();
                $('head').append(cssfont);
                $('#fontname').html(data.name + "");
                $('link[id^="mod_css_"]').remove();
                $('head').append('<? echo $js_css; ?>');
                //alert(data.color1);
            }
        });
    }


    function SearchImages()
    {
        var params = {
            "cmd" : "GetImagesByKeyDialog",
            "keyword" : $('#txtTagImage').val()
        }
        console.log("SearchImages");
        console.log(params);
        $.ajax({
            type: "GET",
            url: 'ajax.php',
            data: params,
            dataType: 'json',
            success: function(data){
                console.log(data);
               $('#divimages').html(data.html);
            }
        });
    }

    $(document).ready(function() {
        $('#txtColor').ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                $(el).val(hex);
                $(el).ColorPickerHide();
                //alert($("#" + $('#ModuleEdit').val()).html());
                $("#" + $('#ModuleEdit').val()).css('background-color', "#" + hex );
                $("#" + $('#ModuleEdit').val()).children().css('background-color', "#" + hex );
                $("#" + $('#ModuleEdit').val()).children().children().css('background-color', "#" + hex );
                $("#" + $('#ModuleEdit').val()).children().children().children().css('background-color', "#" + hex );
            },
            onBeforeShow: function () {
                $(this).ColorPickerSetColor(this.value);
            }
        });

        $('.bexi_module').click(function(e) {  
           //alert(1);
           var id = $(this).attr('id');
           var color = $( this ).css( "background-color" );
           //alert(RGBAToHexA(color));
           $('#ModuleEdit').val(id);
           $('#txtColor').val(RGBAToHexA(color));
           //alert ( $('#txtColor').val());
         // $( "#dialog" ).dialog('open');
        });

        /* $('img').dblclick(function(e) {  
             $( "#dlgimg" ).dialog('open');
         });*/


         $('.bexi_img').click(function(e) { 
           alert("imagen");
         });

         $('.bexi_text').click(function(e) { 
           alert("text");
         });

          $('.bexi_button').click(function(e) { 
           alert("button");
         });

           $('.bexi_link').click(function(e) { 
           alert("link");
         });

        $('.bexi_subtitle').click(function(e) { 
           alert("subtitle");
         });
        $('.bexi_title').click(function(e) { 
           alert("title");
         });
    });

    function RGBAToHexA(rgba) {
      let sep = rgba.indexOf(",") > -1 ? "," : " ";
      rgba = rgba.substr(5).split(")")[0].split(sep);
                    
      // Strip the slash if using space-separated syntax
      if (rgba.indexOf("/") > -1)
        rgba.splice(3,1);

      for (let R in rgba) {
        let r = rgba[R];
        if (r.indexOf("%") > -1) {
          let p = r.substr(0,r.length - 1) / 100;

          if (R < 3) {
            rgba[R] = Math.round(p * 255);
          } else {
            rgba[R] = p;
          }
        }
      }
      let r = (+rgba[0]).toString(16),
      g = (+rgba[1]).toString(16),
      b = (+rgba[2]).toString(16),
      a = Math.round(+rgba[3] * 255).toString(16);

      if (r.length == 1)
        r = "0" + r;
      if (g.length == 1)
        g = "0" + g;
      if (b.length == 1)
        b = "0" + b;
      if (a.length == 1)
        a = "0" + a;

      return "#" + r + g + b + a;
    }