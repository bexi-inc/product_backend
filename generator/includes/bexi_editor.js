 function rgb2hex(rgb){
     rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
     return (rgb && rgb.length === 4) ? "#" +
      ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}

function validate_url(url){
  var result=/(http(s?):)([/|.|\w|\s|-])*\.(?:jpg|gif|png|jpeg)/.test(url);
  return result;
}

function bgchangeurl(ID){
  var url=$("#inptext"+ID).val();
  if(url!=""){
    if(validate_url(url)==true)
    {
      var exist= false;
      if( $("#collapsetools" +ID).closest(".bexi_module").find(".transpa-bg").length)
      {
        var exist=true;
      }
      if(exist==false){
        $('#collapsetools'+ID).closest(".bexi_module").prepend('<div class="transpa-bg" style="background-size: cover; position: absolute; top: 0; left: 0; width: 100%;height: 100%; z-index: -1;"></div>');
      }
      $('#collapsetools'+ID).closest(".bexi_module").find(".transpa-bg").css("background-image","url("+url+")");
      $("#inptext"+ID).val("");
      $( "#dialog-img"+(ID).toString()).dialog("close");
    }else{
      alert("Url invalid!");
    }
  }
}

function bgchange(btid) {
  var vcolor = $("#" +btid).closest(".bexi_module").css("background-color").replace(/\s/g, "");
  if (vcolor =="rgba(0,0,0,0)")
  {
    vcolor ="rgba(0,0,0,1)";
  }
  var newDiv = $(document.createElement('div'));
  newDiv.attr("Title", "Content Block Settings");
  newDiv.attr("data-id", "#" + btid);
  newDiv.css("display", "block");
  newDiv.css("height", "auto");
  newDiv.css("width", "auto");
  newDiv.css("overflow", "visible");
  newDiv.html("Color:<input type='text' id='colorpicker_"+btid+"' class='form-control' data-control='hue' data-format='rgb' value='" + vcolor + "'>");
  $(newDiv).dialog({
            resizable: false,
            height: "auto",
            width: 500,
            modal: true,
            buttons: {
              "Save": function() {
                $($(this).attr("data-id")).closest(".bexi_module").attr('style','position:relative; background-color:'+$("#colorpicker_"+btid).minicolors("rgbaString")+'!important;');
                $( this ).dialog( "close" );
                newDiv.remove();
              },
              Cancel: function() {
                $( this ).dialog( "close" );
                newDiv.remove();
              }
            },
            open: function() {
              $('.ui-dialog-titlebar-close').find('.ui-icon').removeClass('ui-button-icon');
          }
    });
    $("#colorpicker_"+btid).minicolors({
      control: $(this).attr('data-control') || 'hue',
      inline: $(this).attr('data-inline') === 'true',
      letterCase: 'lowercase',
      changeDelay: 200,
      format:'rgb',
      opacity: true,
      theme: 'bootstrap',
      change: function(value, opacity) {
      }
    });
  }

  function bgimgchange(btid) {
    $( "#dialog-img"+(btid-10000).toString()).dialog({
              resizable: false,
              height: "auto",
              width: 500,
              modal: true,
              create: function() {
                $(this).find('#tabs-img').tabs();
               // remove the title of the dialog as we want to use the tab's one
               $(this).parent().children('.ui-dialog-titlebar').remove();
              },
              buttons: {
                Cancel: function() {
                  $("#inptext"+(btid-10000)).val("");
                  $( this ).dialog("close");
                }
              }
      });
    }

    function previewImg(ID) {
      var exist= false;
      if( $("#collapsetools" +ID).closest(".bexi_module").find(".transpa-bg").length)
      {
        var exist=true;
      }
      if(exist==false){
        $('#collapsetools'+ID).closest(".bexi_module").prepend('<div class="transpa-bg" style="background-size: cover; position: absolute; top: 0; left: 0; width: 100%;height: 100%; z-index: -1;"></div>');
      }
      if($('#inpimg'+ID).prop('files')[0])
      {
        var reader= new FileReader();
        reader.onload=function(){
          $('#collapsetools'+ID).closest(".bexi_module").find(".transpa-bg").css("background-image","url("+reader.result+")");
        };
        reader.readAsDataURL($('#inpimg'+ID).prop('files')[0]);
      }
      $( "#dialog-img"+(ID).toString() ).dialog("close");
    }

  /**************   Change trigger for tooltip **************/

  $(function () {
    $('[data-tooltip="true"]').tooltip()
  })

  /**************  Close the toolbar when click outside **************/
  $(document).click(function(e) {
    if (!$(e.target).is('.bartool')) {
        $('.collapse').collapse('hide');
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
       $(".bexi_module").css("position", "relative");
       $('.bexi_module').each(function() {
        var num=Math.floor((Math.random() * 10000) + 1);
        $(this).prepend(
          '<button class="toolbtn" data-toggle="collapse" data-tooltip="true" data-placement="top" title="Content Block Settings" data-target="#collapsetools'+num+'" style="z-index: 5;position: absolute; top: 15px; left: 15px;background-color: White;border: none;color: Black;padding: 7px 9px;font-size: 16px;cursor: pointer;border-radius: 5%;"><i class="fas fa-layer-group toolbtn"></i></button>'+
          '<div class="collapse bartool" id="collapsetools'+num+'" style="z-index: 6;position: absolute; top: 53px; left: 15px;background-color: White;padding:10px;">'+
            '<button class="toolbtn" data-tooltip="true" data-placement="bottom" title="Background Color" onClick="bgchange(this.id)" id="'+num+'" style="background-color: White;border: none;color: Black;padding: 7px 9px;font-size: 16px;cursor: pointer;border-radius: 5%;"><i class="fas fa-fill-drip toolbtn"></i></button>'+
            '<button class="toolbtn" data-tooltip="true" data-placement="bottom" title="Background Image" onClick="bgimgchange(this.id)" id="'+(num+10000)+'" style="background-color: White;border: none;color: Black;padding: 7px 9px;font-size: 16px;cursor: pointer;border-radius: 5%;"><i class="far fa-images toolbtn"></i></button>'+
          '</div>'+
          '<div id="dialog-img'+num+'" class="ui-helper-hidden">'+
            '<div id="tabs-img">'+
              '<ul>'+
                '<li><a href="#tab-1"><i class="fas fa-cloud-upload-alt"></i></a></li>'+
                '<li><a href="#tab-2"><i class="fas fa-link"></i></a></li>'+
              '</ul>'+
              '<div id="tab-1">'+
                '<div id="'+num+'" class="col-lg-12 dropzone">'+
                  '<label  for="inpimg'+num+'" Class="C" style="height:100%;width:100%;cursor: pointer;">Drop Your Image Here<Br>(Or Click)</label>'+
                  '<input class="bgimginput" id="inpimg'+num+'" accept="image/*" onchange="previewImg('+num+')" style="display:none;" type="file">'+
                '</div>'+
              '</div>'+
              '<div id="tab-2">'+
                '<div class="fr-input-line R" data-children-count="1">'+
                  '<input id="inptext'+num+'" type="text" placeholder="http://" tabindex="1" aria-required="true" dir="auto" class="" style="width:100%;">'+
                  '<button class="align-self-end btn btn-outline-primary mt-3" type="button" onclick="bgchangeurl('+num+')">Change</button>'+
                '</div>'+
              '</div>'+
            '</div>'+
          '</div>'
        );

      });
      // Prevent default drag behaviors
      $(".dropzone").each(function(){
        this.addEventListener('dragover', function(e) {
          e.preventDefault();
          e.stopPropagation();
        });

        this.addEventListener('dragleave', function(e) {
          e.preventDefault();
          e.stopPropagation();
        });

        this.addEventListener('dragenter', function(e) {
          e.preventDefault();
          e.stopPropagation();
        });

        this.addEventListener('drop', function(e) {
          e.preventDefault();
          e.stopPropagation();
          var fileInput = document.getElementById("inpimg"+this.id.toString());
          fileInput.files = e.dataTransfer.files;
          previewImg(this.id);
        });
      });

      $('.bexi_icon').each(function() {
        var str=$(this).attr('style');
        if(str.search("width")==-1){
          str+="width:auto;";
        }
        $(this).wrap( '<p class="bexi_editor_icon" style="'+str+'"></p>' );
      });

        /************** ICON COLOR ******************/
       FroalaEditor.ICON_DEFAULT_TEMPLATE = "font_awesome_5";
       FroalaEditor.DefineIcon('icon_block', {FA5NAME: 'fas fa-tint'});
       FroalaEditor.RegisterCommand('iconcolor', {
         title: 'Icon Color',
         icon: 'icon_block',
         focus: false,
         undo: false,
         refreshAfterCallback: false,
         callback: function () {
          var obj=this._original_html;
          var ID=$(obj).attr('id');
          var color=$("#"+ID).css('color');
          var newDiv = $(document.createElement('div'));
          newDiv.attr("Title", "Icon Settings");
          newDiv.attr("data-id", "#" + ID);
          newDiv.css("display", "block");
          newDiv.css("height", "auto");
          newDiv.css("width", "auto");
          newDiv.css("overflow", "visible");
          newDiv.html("Color:<input type='text' id='colorpicker_"+ID+"' class='form-control' data-control='hue' value='" + color + "'>");
          $(newDiv).dialog({
              resizable: false,
              height: "auto",
              width: 500,
              modal: true,
              buttons: {
                "Save": function() {
                  $("#"+ID).css("color",$("#colorpicker_"+ID).minicolors("rgbString"));
                  $( this ).dialog( "close" );
                  newDiv.remove();
                },
                "Cancel": function() {
                  $( this ).dialog( "close" );
                  newDiv.remove();
                }
              },
              open: function() {
              $('.ui-dialog-titlebar-close').find('.ui-icon').removeClass('ui-button-icon');
          }
          });
          $("#colorpicker_"+ID).minicolors({
            control: $(this).attr('data-control') || 'hue',
            inline: $(this).attr('data-inline') === 'true',
            letterCase: 'lowercase',
            format: 'rgb',
            opacity: false,
            change: function(hex, opacity) {
            },
            theme: 'bootstrap'
          });
         }
       });

       /************** ICON BGCOLOR ******************/
       FroalaEditor.ICON_DEFAULT_TEMPLATE = "font_awesome_5";
       FroalaEditor.DefineIcon('icon_block2', {FA5NAME: 'fas fa-paint-brush'});
       FroalaEditor.RegisterCommand('iconbgcolor', {
         title: 'Icon Background Color',
         icon: 'icon_block2',
         focus: false,
         undo: false,
         refreshAfterCallback: false,
         callback: function () {
          var obj=this._original_html;
          var ID=$(obj).attr('id');
          var color=$("#"+ID).css('background-color');
          var newDiv = $(document.createElement('div'));
          newDiv.attr("Title", "Icon Settings");
          newDiv.attr("data-id", "#" + ID);
          newDiv.css("display", "block");
          newDiv.css("height", "auto");
          newDiv.css("width", "auto");
          newDiv.css("overflow", "visible");
          newDiv.html("Background Color:<input type='text' id='colorpicker_"+ID+"' class='form-control' data-control='hue' value='" + color + "'>");
          $(newDiv).dialog({
              resizable: false,
              height: "auto",
              width: 500,
              modal: true,
              buttons: {
                "Save": function() {
                  $("#"+ID).css("background-color",$("#colorpicker_"+ID).minicolors("rgbaString"));
                  $( this ).dialog( "close" );
                  newDiv.remove();
                },
                "Cancel": function() {
                  $( this ).dialog( "close" );
                  newDiv.remove();
                }
              },
              open: function() {
              $('.ui-dialog-titlebar-close').find('.ui-icon').removeClass('ui-button-icon');
          }
          });
          $("#colorpicker_"+ID).minicolors({
            control: $(this).attr('data-control') || 'hue',
            inline: $(this).attr('data-inline') === 'true',
            letterCase: 'lowercase',
            format: 'rgb',
            opacity: true,
            change: function(hex, opacity) {
            },
            theme: 'bootstrap'
          });
         }
       });
       /************** ICON SIZE ******************/
       FroalaEditor.ICON_DEFAULT_TEMPLATE = "font_awesome_5";
       FroalaEditor.DefineIcon('icon_block3', {FA5NAME: 'fas fa-text-height'});
       FroalaEditor.RegisterCommand('iconsize', {
          title: 'Icon Size',
          icon: 'icon_block3',
          type: 'dropdown',
          focus: false,
          undo: false,
          refreshAfterCallback: true,
          options: {
            '8': '8',
            '9': '9',
            '10': '10',
            '11': '11',
            '12': '12',
            '14': '14',
            '18': '18',
            '24': '24',
            '30': '30',
            '36': '36',
            '48': '48',
            '60': '60',
            '72': '72',
            '96': '96',
          },
          callback: function (cmd, val) {
            var obj=this._original_html;
            var ID=$(obj).attr('id');
            $("#"+ID).css("font-size",val+'px');
            console.log (val);
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
          imageUpload: false,
          fileUpload: false,
          placeholderText: '',
          quickInsertEnabled: false,
          toolbarVisibleWithoutSelection: true,
          htmlAllowedEmptyTags: ['i','.fas','div'],
          htmlDoNotWrapTags: ['script', 'style', 'img','i'],
          toolbarButtons : {
             'bexi_extra' : {
                 'buttons' : ['iconcolor','iconbgcolor','iconsize']
             }
         },
         events : {
          'keypress': function (keypressEvent) {
            keypressEvent.preventDefault();
            return false;
          },
          'paste.after': function () {
            return false;
          },
          'paste.before': function (original_event) {
            original_event.preventDefault();
            return false;
          }
        }
        });

});