 function rgb2hex(rgb){
     rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
     return (rgb && rgb.length === 4) ? "#" +
      ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}

  function bgchange(btid) {

  var vcolor = $("#" +btid).closest(".bexi_module").css("background-color").replace(/\s/g, "");
  var hexcolor =vcolor;
  alert(vcolor);
  if (vcolor != "transparent" && vcolor !="rgba(0,0,0,0)")
  {
      hexcolor = rgb2hex(vcolor);
  }
  $("#dialog-1").attr("Title", "Content Block Settings");
  $("#dialog-1").attr("data-id", "#" + btid);
  $("#dialog-1").html("<div>Background Color:<input type='text' id='colorpicker_1' class='form-control' data-control='hue' data-format='rgb' value='" + vcolor.toString() + "'></div>");
  $( "#dialog-1" ).dialog({
            resizable: false,
            height: "auto",
            width: 500,
            modal: true,
            buttons: {
              "Save": function() {
                $($(this).attr("data-id")).closest(".bexi_module").attr('style','position:relative; background-color:'+$("#colorpicker_1").minicolors("rgbaString")+'!important;');
                $( this ).dialog( "close" );
              },
              Cancel: function() {
                $( this ).dialog( "close" );
              }
            },
            open: function() {
              $('.ui-dialog-titlebar-close').find('.ui-icon').removeClass('ui-button-icon');
          }
    });

    $("#colorpicker_1").minicolors({
        control: $(this).attr('data-control') || 'hue',
        inline: $(this).attr('data-inline') === 'true',
        letterCase: 'lowercase',
        changeDelay: 200,
        opacity: true,
        format: 'rgb',
        theme: 'bootstrap',
        change: function(value, opacity) {
          if( !value ) return;
          if( opacity ) value += ', ' + opacity;
          if( typeof console === 'object' ) {
            console.log(value);
          }
        }
    });
  }

 $(document).ready(function() {
     $( ".bexi_title" ).wrap( "<div class='bexi_editor_title' style='width: 100%;'></div>" );

     $( ".bexi_subtitle" ).wrap( "<div class='bexi_editor_subtitle'  style='width: 100%;'></div>" );

       $( ".bexi_text" ).each(function( index ) {
           $(this).wrap( "<div class='bexi_editor_text' id='ed_" + $(this).attr('id') + "''></div>" );
       });

       $( ".bexi_link" ).wrap( "<div class='bexi_editor_link'></div>" );

       $( ".bexi_button" ).wrap( "<div class='bexi_editor_button' style='width: 100%;'></div>" );

       $( ".bexi_video" ).wrap( "<div class='bexi_editor_video' style='width: 100%;'></div>" );

       $(".bexi_img").addClass("fr-view fr-dib");
       $(".bexi_module").css("position", "relative");
       var num=1;
       $('.bexi_module').each(function() {
        //$(this).prepend('<div id="'+Math.floor((Math.random() * 10000) + 1)+'" class="bexi_module_bg transpa-bg" contenteditable="false" style="background-size: cover; position: absolute; top: 0; left: 0; width: inherit;height: 100%; z-index: 0;"></div>');
        $(this).prepend('<button class="btn" onClick="bgchange(this.id)" id="'+Math.floor((Math.random() * 10000) + 1)+'" style="z-index: 5;position: absolute; top: 15px; left: 15px;background-color: #f8f8f8;border: none;color: Black;padding: 7px 9px;font-size: 16px;cursor: pointer;"><i class="fas fa-fill-drip"></i></button>')
      });
       //$( ".bexi_icon" ).wrap( "<div class='bexi_editor_icon'></div>" );

        FroalaEditor.ICON_DEFAULT_TEMPLATE = "font_awesome_5";
        FroalaEditor.DefineIcon('icon_block', {FA5NAME: 'layer-group'});
        FroalaEditor.RegisterCommand('ContentBlock', {
          title: 'Content Block Settings',
          icon: 'icon_block',
          focus: false,
          undo: false,
          refreshAfterCallback: false,
          callback: function () {
            console.log(this);
            //alert(this.$box[0].id);
            //$("#" + this.$box[0].id).closest(".bexi_module").css("background-color","#000000")
           // alert($("#" + this.$box[0].id).closest(".bexi_module").html());

           var vcolor = $("#" + this.$box[0].id).closest(".bexi_module").css("background-color").replace(/\s/g, "");;
           console.log(vcolor);
           var hexcolor ="";
           if (vcolor != "transparent" && vcolor !="rgba(0,0,0,0)")
           {
               hexcolor = rgb2hex(vcolor);
           }
           $("#dialog-1").attr("Title", "Content Block Settings");
           $("#dialog-1").attr("data-id", "#" + this.$box[0].id);
           $("#dialog-1").html("<div>Background Color:<input type='text' id='colorpicker_1' class='form-control' data-control='hue' value='" + hexcolor + "'></div>");
           $( "#dialog-1" ).dialog({
                    resizable: false,
                    height: "auto",
                    width: 500,
                    modal: true,
                    buttons: {
                      "Save": function() {
                        //alert($(this).attr("data-id"));
                        //alert($("#colorpicker_1").minicolors("rgbString"));
                        //$($(this).attr("data-id")).closest(".bexi_module").css("background-color",$("#colorpicker_1").minicolors("rgbString"))
                        $($(this).attr("data-id")).closest(".bexi_module").attr('style', 'background-color:'+$("#colorpicker_1").minicolors("rgbString")+'!important');
                        $( this ).dialog( "close" );

                      },
                      Cancel: function() {
                        $( this ).dialog( "close" );
                      }
                    }
            });

            $("#colorpicker_1").minicolors({
                control: $(this).attr('data-control') || 'hue',
                inline: $(this).attr('data-inline') === 'true',
                letterCase: 'lowercase',
                opacity: false,
                change: function(hex, opacity) {
                  if(!hex) return;
                  if(opacity) hex += ', ' + opacity;
                  try {
                    console.log(hex);
                  } catch(e) {}
                  $(this).select();
                },
                theme: 'bootstrap'
            });
          }
        });

        var editortxt = new FroalaEditor('.bexi_editor_text',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarVisibleWithoutSelection: true,
          toolbarButtons : {
             'moreText': {
                'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
              },
              'moreParagraph': {
                'buttons': ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote']
              },
              'moreRich': {
                'buttons': ['insertLink', 'insertImage', 'insertVideo', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
              },
              'bexi_extra' : {
                  'buttons' : ['ContentBlock']
              }
          }
        });


        

        var editortitles = new FroalaEditor('.bexi_editor_title',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarVisibleWithoutSelection: true
        });

         var editorsubtitles = new FroalaEditor('.bexi_editor_subtitle',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarVisibleWithoutSelection: true
        });

         var editorlin = new FroalaEditor('.bexi_editor_link',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          linkEditButtons:['linkOpen', 'linkEdit', 'linkRemove','bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'clearFormatting']
        });

         var editorimg = new FroalaEditor('.bexi_img',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          imageManagerLoadURL: 'load_images.php',
          toolbarBottom : false,
          imageDefaultAlign: 'center',
          imageDefaultMargin: 0
        });

         var editorbtn = new FroalaEditor('.bexi_editor_button',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarBottom : false,
          toolbarVisibleWithoutSelection: true

        });

        var editorvid = new FroalaEditor('.bexi_editor_video', {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarBottom : false,
          videoResponsive: true,
          toolbarButtons: ['insertVideo'],
          videoAllowedProviders: ['youtube', 'vimeo'],
          videoInsertButtons: ['videoBack', '|', 'videoByURL']
        });

        var editorico = new FroalaEditor('.bexi_editor_icon', {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          iconsTemplate: 'font_awesome_5',
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarBottom : false,
          toolbarVisibleWithoutSelection: true,
          htmlAllowedEmptyTags: ['i','.fas','div'],
          htmlDoNotWrapTags: ['script', 'style', 'img','i'],
          toolbarButtons: ['fontAwesome'],
          toolbarButtons : {
            'moreText': {
               'buttons': ['fontSize', 'textColor', 'backgroundColor', 'clearFormatting']
             },
             'moreParagraph': {
               'buttons': ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify','lineHeight']
             },
             'moreRich': {
               'buttons': ['insertLink', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly']
             },
             'bexi_extra' : {
                 'buttons' : ['ContentBlock']
             },
         }
        });
/*
        var editormodule = new FroalaEditor('.bexi_module_bg',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          attribution: false,
          charCounterCount: false,
          initOnClick: false,
          toolbarBottom : false,
          toolbarVisibleWithoutSelection: true,
          htmlAllowedEmptyTags: ['div'],
          placeholderText: '',
          toolbarContainer: '#bexi_module_bg',
          quickInsertEnabled: false,
          toolbarButtons : {
             'bexi_extra' : {
                 'buttons' : ['ContentBlock']
             }
         }
        });
*/
});