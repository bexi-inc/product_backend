 function rgb2hex(rgb){
     rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
     return (rgb && rgb.length === 4) ? "#" +
      ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}

$.extend({
  replaceTag: function (currentElem, newTagObj, keepProps) {
      var $currentElem = $(currentElem);
      var i, $newTag = $(newTagObj).clone();
      if (keepProps) {//{{{
          newTag = $newTag[0];
          newTag.className = currentElem.className;
          $.extend(newTag.classList, currentElem.classList);
          $.extend(newTag.attributes, currentElem.attributes);
      }//}}}
      $currentElem.wrapAll($newTag);
      $currentElem.contents().unwrap();
      // return node; (Error spotted by Frank van Luijn)
      return this; // Suggested by ColeLawrence
  }
});

$.fn.extend({
  replaceTag: function (newTagObj, keepProps) {
      // "return" suggested by ColeLawrence
      return this.each(function() {
          jQuery.replaceTag(this, newTagObj, keepProps);
      });
  }
});


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

       $(".bexi_icon").replaceTag('<span>', true);
       $( ".bexi_icon" ).wrap( "<div class='bexi_editor_icon'></div>" );

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
           $("#dialog-1").html("<div>Backgroun Color:<input type='text' id='colorpicker_1' class='form-control' data-control='hue' value='" + hexcolor + "'></div>");
           $( "#dialog-1" ).dialog({
                    resizable: false,
                    height: "auto",
                    width: 500,
                    modal: true,
                    buttons: {
                      "Save": function() {
                        //alert($(this).attr("data-id"));
                        //alert($("#colorpicker_1").minicolors("rgbString"));
                        $($(this).attr("data-id")).closest(".bexi_module").css("background-color",$("#colorpicker_1").minicolors("rgbString"))
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
         },
          faButtons: ["fontAwesomeBack", "|"]
        });
});