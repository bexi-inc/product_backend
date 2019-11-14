/********global variables********/
window.bexi_src="";
window.bexi_id="";

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

function clear_classes(classes){
  var classList = classes.split(/\s+/);
  var class_clear="";
  $.each(classList, function(index, item) {
      if (/^fa([a-z]?)$/.test(item)==false&&(/^fa-+/).test(item)==false) {
          class_clear+=item+" ";
      }
  });
  return class_clear;
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

function Manager_unsplash4(ID,numpag)
{
  var request=null;
  if(  $("#cont_unspl"+ID).masonry().length)
  {
    $("#cont_unspl"+ID).masonry('destroy');
  }
  $("#cont_unspl"+ID).empty();
  var keys=$('#inptext'+ID).val();
  if(keys!="")
  {
    request=$.ajax({
      url: "load_images.php",
      data: { key: keys, npag : numpag} ,
      datatype:"json",
      success: function(data){
      var jdata=JSON.parse(data);
      total=jdata.total;
      var $grid = $("#cont_unspl"+ID).masonry({
        // options
        itemSelector: '.grid-item',
        columnWidth: 200,
        gutter: 10
      });
      $.each(jdata.images, function(index, item) {
          var img = new Image();
          img.src = item.thumb;
          img.setAttribute("class", "image-list");
          img.setAttribute("alt", item.alt_description);
          img.onload=function(){
            $grid.masonry();
          };
          $(img).css("cursor","pointer");
          function add_img(){
            img.setAttribute("id",jdata.new_id);
            img.setAttribute("class", "bexi_img");
            img.src=item.url;
            $(img).unbind("click",add_img);
            $("#"+ID).append(img);
            $("#cont"+ID).dialog( "close" );
            $("#cont"+ID).remove();
          }
          $(img).click(add_img);
          var newdiv= $(document.createElement('div'));
          newdiv.attr("class", "grid-item");
          $(newdiv).append(img);
          $("#cont_unspl"+ID).append(newdiv);
          $grid.append(newdiv);
          $grid.masonry( 'appended',newdiv);
      });
      $("#cont"+ID).css("height","400px");
      $("#cont"+ID).closest(".ui-dialog").css("position","fixed");
      $("#cont"+ID).closest(".ui-dialog").css("top","80px");
      }
    });
  }
  return request;
}

function set_pagination4(ID,npag)
{
  var pag_cont =$('#cont_pag'+ID);
  pag_cont.pagination({
  
    // current page
    current: 1, 
  
    // the number of entires per page
    length: 10, 
  
    // pagination size
    size: 2,
  
    // Prev/Next text
    prev: "&lt;", 
    next: "&gt;", 
  
    // fired on each click
    ajax:function(options, refresh, $target){
     pag_cont.hide();
      var t = Manager_unsplash4(ID,options.current);
     t.done(function(data){
       var jdata=JSON.parse(data);
      refresh({
        total: jdata.total
      });
      pag_cont.show();
     })
    }
  });
}

function icon_manager(ID,numpag)
{
  var request=null;
  $("#cont_icon"+ID).empty();
  var keys=$('#inptext'+ID).val();
  if(keys!="")
  {
    request=$.ajax({
      url: "load_icons.php",
      data: { key: keys, npag : numpag} ,
      datatype:"json",
      success: function(data){
      var jdata=JSON.parse(data);
      total=jdata.total;
      $.each(jdata.icons, function(index, item) {
          var ico = $(document.createElement('i'));
          $(ico).attr("class",item.class);
          $(ico).css("cursor","pointer");
          $(ico).css("padding","7px 9px");
          $(ico).css("font-size","16px");
          $(ico).css("border-radius","5%");
          $(ico).css("margin","5px");
          $(ico).click(function(){
            var cl=clear_classes($("#"+ID).attr("class"));
            $("#"+ID).attr("class",cl+item.class);
            $("#diag_icon"+ID).dialog( "close" );
            $("#diag_icon"+ID).remove();
          });
          $("#cont_icon"+ID).append(ico);
      });
      $("#diag_icon"+ID).css("height","250px");
      $("#diag_icon"+ID).closest(".ui-dialog").css("position","fixed");
      $("#diag_icon"+ID).closest(".ui-dialog").css("top","80px");
      }
    });
  }
  return request;
}

function set_pagination3(ID,npag)
{
  var pag_cont =$('#cont_pag'+ID);
  pag_cont.pagination({
  
    // current page
    current: 1, 
  
    // the number of entires per page
    length: 26,
  
    // pagination size
    size: 2,
  
    // Prev/Next text
    prev: "&lt;", 
    next: "&gt;", 
  
    // fired on each click
    ajax:function(options, refresh, $target){
     pag_cont.hide();
      var t = icon_manager(ID,options.current);
     t.done(function(data){
       var jdata=JSON.parse(data);
      refresh({
        total: jdata.total
      });
      pag_cont.show();
     })
    }
  });
}

function Manager_unsplash2(ID,numpag)
{
  var request=null;
  if(  $("#cont_unspl"+ID).masonry().length)
  {
    $("#cont_unspl"+ID).masonry('destroy');
  }
  $("#cont_unspl"+ID).empty();
  var keys=$('#inptextsearch'+ID).val();
  if(keys!="")
  {
    request=$.ajax({
      url: "load_images.php",
      data: { key: keys, npag : numpag} ,
      datatype:"json",
      success: function(data){
      var jdata=JSON.parse(data);
      total=jdata.total;
      var $grid = $("#cont_unspl"+ID).masonry({
        // options
        itemSelector: '.grid-item',
        columnWidth: 200,
        gutter: 10
      });
      $.each(jdata.images, function(index, item) {
          var img = new Image();
          img.src = item.thumb;
          img.setAttribute("class", "image-list");
          img.setAttribute("alt", item.alt_description);
          img.onload=function(){
            $grid.masonry();
          };
          $(img).css("cursor","pointer");
          $(img).click(function(){
            var exist= false;
            if( $("#collapsetools" +ID).closest(".bexi_module").find(".transpa-bg").length)
            {
              var exist=true;
            }
            if(exist==false){
              $('#collapsetools'+ID).closest(".bexi_module").prepend('<div class="transpa-bg" style="background-size: cover; position: absolute; top: 0; left: 0; width: 100%;height: 100%; z-index: -1;"></div>');
            }
            $('#collapsetools'+ID).closest(".bexi_module").find(".transpa-bg").css("background-image","url("+item.url+")");
            $("#cont_unspl"+ID).empty();
            $("#cont_unspl"+ID).css("height","0px");
            $("#cont_pag"+ID).empty();
            $("#cont_pag"+ID).css("height","0px");
            $("#inptextsearch"+ID).val("");
            $("#inptext"+ID).val("");
            $( "#dialog-img"+ID).css("height","auto");
            $("#dialog-img"+ID).dialog( "close" );
          });
          var newdiv= $(document.createElement('div'));
          newdiv.attr("class", "grid-item");
          $(newdiv).append(img);
          $("#cont_unspl"+ID).append(newdiv);
          $grid.append(newdiv);
          $grid.masonry( 'appended',newdiv);
      });
      $( "#dialog-img"+ID).css("height","400px");
      $( "#dialog-img"+ID).closest(".ui-dialog").css("position","fixed");
      $( "#dialog-img"+ID).closest(".ui-dialog").css("top","80px");
      }
    });
  }
  return request;
}

function set_pagination2(ID,npag)
{
  var pag_cont =$('#cont_pag'+ID);
  pag_cont.pagination({
  
    // current page
    current: 1, 
  
    // the number of entires per page
    length: 10, 
  
    // pagination size
    size: 2,
  
    // Prev/Next text
    prev: "&lt;", 
    next: "&gt;", 
  
    // fired on each click
    ajax:function(options, refresh, $target){
     pag_cont.hide();
      var t = Manager_unsplash2(ID,options.current);
     t.done(function(data){
       var jdata=JSON.parse(data);
      refresh({
        total: jdata.total
      });
      pag_cont.show();
     })
    }
  });
}

function Manager_unsplash(ID,numpag)
{
  var request=null;
  if(  $("#cont_unspl"+ID).masonry().length)
  {
    $("#cont_unspl"+ID).masonry('destroy');
  }
  $("#cont_unspl"+ID).empty();
  var keys=$('#inptext'+ID).val();
  if(keys!="")
  {
    request=$.ajax({
      url: "load_images.php",
      data: { key: keys, npag : numpag} ,
      datatype:"json",
      success: function(data){
      var jdata=JSON.parse(data);
      total=jdata.total;
      var $grid = $("#cont_unspl"+ID).masonry({
        // options
        itemSelector: '.grid-item',
        columnWidth: 200,
        gutter: 10
      });
      $.each(jdata.images, function(index, item) {
          var img = new Image();
          img.src = item.thumb;
          img.setAttribute("class", "image-list");
          img.setAttribute("alt", item.alt_description);
          img.onload=function(){
            $grid.masonry();
          };
          $(img).css("cursor","pointer");
          $(img).click(function(){
            $("#"+ID).attr("src",item.url);
            $("#cont"+ID).dialog( "close" );
            $("#cont"+ID).remove();
          });
          var newdiv= $(document.createElement('div'));
          newdiv.attr("class", "grid-item");
          $(newdiv).append(img);
          $("#cont_unspl"+ID).append(newdiv);
          $grid.append(newdiv);
          $grid.masonry( 'appended',newdiv);
      });
      $("#cont"+ID).css("height","400px");
      $("#cont"+ID).closest(".ui-dialog").css("position","fixed");
      $("#cont"+ID).closest(".ui-dialog").css("top","80px");
      }
    });
  }
  return request;
}

function set_pagination(ID,npag)
{
  var pag_cont =$('#cont_pag'+ID);
  pag_cont.pagination({
  
    // current page
    current: 1, 
  
    // the number of entires per page
    length: 10, 
  
    // pagination size
    size: 2,
  
    // Prev/Next text
    prev: "&lt;", 
    next: "&gt;", 
  
    // fired on each click
    ajax:function(options, refresh, $target){
     pag_cont.hide();
      var t = Manager_unsplash(ID,options.current);
     t.done(function(data){
       var jdata=JSON.parse(data);
      refresh({
        total: jdata.total
      });
      pag_cont.show();
     })
    }
  });
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
          },
          close: function( event, ui ) {
            newDiv.remove();
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
              width: 750,
              modal: true,
              create: function() {
                $(this).find('#tabs-img').tabs();
               // remove the title of the dialog as we want to use the tab's one
               $(this).parent().children('.ui-dialog-titlebar').remove();
              },
              buttons: {
                Cancel: function() {
                  $("#cont_unspl"+(btid-10000)).empty();
                  $("#cont_unspl"+(btid-10000)).css("height","0px");
                  $("#cont_pag"+(btid-10000)).empty();
                  $("#cont_pag"+(btid-10000)).css("height","0px");
                  $("#inptextsearch"+(btid-10000)).val("");
                  $("#inptext"+(btid-10000)).val("");
                  $( "#dialog-img"+(btid-10000)).css("height","auto");
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

       $( ".bexi_link" ).wrap( "<div class='bexi_editor_link' style='width:100%;'></div>" );

       $( ".bexi_button" ).wrap( "<div class='bexi_editor_button' style='width: 100%;'></div>" );

       $( ".bexi_video" ).wrap( "<div class='bexi_editor_video' style='width: 100%;'></div>" );

       $(".bexi_img").addClass("fr-view fr-dib");
       $(".bexi_module").css("position", "relative");
       $("form").submit(function(e){
         e.preventDefault();
       })
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
                '<li><a data-tooltip="true" title="Upload" href="#tab-1"><i class="fas fa-cloud-upload-alt"></i></a></li>'+
                '<li><a data-tooltip="true" title="Link" href="#tab-2"><i class="fas fa-link"></i></a></li>'+
                '<li><a data-tooltip="true" title="Search" href="#tab-3"><i class="far fa-images"></i></a></li>'+
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
              '<div id="tab-3">'+
                '<div class="input-group mb-3">'+
                  '<input id="inptextsearch'+num+'" type="text" class="form-control" placeholder="Keyword,keyword,..."  aria-describedby="button-addon2">'+
                  '<div class="input-group-append">'+
                    '<button class="btn btn-outline-primary" type="button" onclick="set_pagination2(\''+num+'\','+1+');" id="button-addon2">Search</button>'+
                  '</div>'+
                '</div>'+
                '<div id="cont_unspl'+num+'">'+
                '</div>'+
                '<div id="cont_pag'+num+'" class="pagination">'+
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
        str+="margin-bottom:unset;"
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
          },
          close: function( event, ui ) {
            newDiv.remove();
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
          },
            close: function( event, ui ) {
              newDiv.remove();
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

      /************** ICON LINK ******************/
      FroalaEditor.ICON_DEFAULT_TEMPLATE = "font_awesome_5";
      FroalaEditor.DefineIcon('icon_block4', {FA5NAME: 'fas fa-link'});
      FroalaEditor.RegisterCommand('iconlink', {
        title: 'Insert Link',
        icon: 'icon_block4',
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
         newDiv.html('URL:<input id="inptext'+ID+'" type="text" placeholder="http://" tabindex="1" aria-required="true" dir="auto" class="" style="width:100%;">'+
          '<div class="custom-control custom-checkbox">'+
            '<input type="checkbox" class="custom-control-input" id="customCheck1">'+
            '<label class="custom-control-label" for="customCheck1">Open in new tab</label>'+
          '</div>'
         );
         $(newDiv).dialog({
             resizable: false,
             height: "auto",
             width: 500,
             modal: true,
             buttons: {
               "Save": function() {
                var parent=$("#"+ID).parent()[0].tagName;
                if(parent=="A")
                {
                 $("#"+ID).unwrap();
                }
                 var url=$("#inptext"+ID).val();
                 if(url!="")
                 {
                   if($("#customCheck1").prop('checked'))
                   {
                     $("#"+ID).wrap('<a href="'+url+'" target="_blank"></a>');
                   }else{
                    $("#"+ID).wrap('<a href="'+url+'"></a>');
                   }
                 }
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
             var parent=$("#"+ID).parent()[0].tagName;
             if(parent=="A")
             {
              $("#inptext"+ID).val($("#"+ID).parent().attr('href'));
              if($("#"+ID).parent().attr('target')=='_blank')
              {
                $("#customCheck1").attr('checked','true');
              }
             }
            },
            close: function( event, ui ) {
              newDiv.remove();
            }
         });
        }
      });

      /************** ICON REMOVE ******************/
      FroalaEditor.ICON_DEFAULT_TEMPLATE = "font_awesome_5";
      FroalaEditor.DefineIcon('icon_block6', {FA5NAME: 'fas fa-trash'});
      FroalaEditor.RegisterCommand('iconremove', {
        title: 'Remove',
        icon: 'icon_block6',
        focus: false,
        undo: false,
        refreshAfterCallback: false,
        callback: function () {
          var obj=this._original_html;
          var ID=$(obj).attr('id');
          $('#'+ID).closest(".bexi_editor_icon").remove();
        }
      });

      /************** Unsplash Manager ******************/
      FroalaEditor.ICON_DEFAULT_TEMPLATE = "font_awesome_5";
      FroalaEditor.DefineIcon('icon_block5', {FA5NAME: 'fas fa-images'});
      FroalaEditor.RegisterCommand('unsplash_manager', {
        title: 'Search image',
        icon: 'icon_block5',
        focus: false,
        undo: false,
        refreshAfterCallback: false,
        callback: function () {
          var ID=this.$el[0].getAttribute("id");
          var $img = this.image.get();
          if(ID==null)
          {
            ID=$img[0].getAttribute("id");
          }
          var newDiv = $(document.createElement('div'));
          newDiv.attr("Title", "Search Image");
          newDiv.attr("data-id", "#" + ID);
          newDiv.attr("id", "cont" + ID);
          newDiv.css("display", "block");
          newDiv.css("height", "auto");
          newDiv.css("width", "auto");
          newDiv.css("overflow", "auto");
          newDiv.html(
          '<div class="input-group mb-3">'+
          '<input id="inptext'+ID+'" type="text" class="form-control" placeholder="Keyword,keyword,..."  aria-describedby="button-addon2">'+
          '<div class="input-group-append">'+
            '<button class="btn btn-outline-primary" type="button" onclick="set_pagination(\''+ID+'\','+1+');" id="button-addon2">Search</button>'+
          '</div>'+
        '</div>'+
        '<div id="cont_unspl'+ID+'">'+
        '</div>'+
        '<div id="cont_pag'+ID+'" class="pagination">'+
        '</div>'
          );
          $(newDiv).dialog({
              resizable: false,
              height: "auto",
              width: 800,
              modal: true,
              buttons: {
                "Cancel": function() {
                  $( this ).dialog( "close" );
                  newDiv.remove();
                }
              },
              open: function() {
              $('.ui-dialog-titlebar-close').find('.ui-icon').removeClass('ui-button-icon');
            },
            close: function( event, ui ) {
              newDiv.remove();
            }
          });
        }
      });


      /************** Unsplash Insert Manager ******************/
      FroalaEditor.ICON_DEFAULT_TEMPLATE = "font_awesome_5";
      FroalaEditor.DefineIcon('icon_block5', {FA5NAME: 'fas fa-images'});
      FroalaEditor.RegisterCommand('unsplash_insert', {
        title: 'Search image',
        icon: 'icon_block5',
        focus: false,
        undo: false,
        refreshAfterCallback: false,
        callback: function () {
          var ID=this.$el[0].firstChild.getAttribute("id");
          var newDiv = $(document.createElement('div'));
          newDiv.attr("Title", "Search Image");
          newDiv.attr("data-id", "#" + ID);
          newDiv.attr("id", "cont" + ID);
          newDiv.css("display", "block");
          newDiv.css("height", "auto");
          newDiv.css("width", "auto");
          newDiv.css("overflow", "auto");
          newDiv.html(
          '<div class="input-group mb-3">'+
          '<input id="inptext'+ID+'" type="text" class="form-control" placeholder="Keyword,keyword,..."  aria-describedby="button-addon2">'+
          '<div class="input-group-append">'+
            '<button class="btn btn-outline-primary" type="button" onclick="set_pagination4(\''+ID+'\','+1+');" id="button-addon2">Search</button>'+
          '</div>'+
        '</div>'+
        '<div id="cont_unspl'+ID+'">'+
        '</div>'+
        '<div id="cont_pag'+ID+'" class="pagination">'+
        '</div>'
          );
          $(newDiv).dialog({
              resizable: false,
              height: "auto",
              width: 800,
              modal: true,
              buttons: {
                "Cancel": function() {
                  $( this ).dialog( "close" );
                  newDiv.remove();
                }
              },
              open: function() {
              $('.ui-dialog-titlebar-close').find('.ui-icon').removeClass('ui-button-icon');
            },
            close: function( event, ui ) {
              newDiv.remove();
            }
          });
        }
      });

       /************** ICON REEMPLACE ******************/
       FroalaEditor.ICON_DEFAULT_TEMPLATE = "font_awesome_5";
       FroalaEditor.DefineIcon('icon_block7', {FA5NAME: 'fas fa-exchange-alt'});
       FroalaEditor.RegisterCommand('iconexchange', {
         title: 'Icon Exchange',
         icon: 'icon_block7',
         focus: false,
         undo: false,
         refreshAfterCallback: false,
         callback: function () {
          var obj=this._original_html;
          var ID=$(obj).attr('id');
          var newDiv = $(document.createElement('div'));
          newDiv.attr("Title", "Icon Settings");
          newDiv.attr("data-id", "#" + ID);
          newDiv.attr("id","diag_icon"+ID);
          newDiv.css("display", "block");
          newDiv.css("height", "auto");
          newDiv.css("width", "auto");
          newDiv.css("overflow", "visible");
          newDiv.html(
            '<div class="input-group mb-3">'+
            '<input id="inptext'+ID+'" type="text" class="form-control" placeholder="Keyword,keyword,..."  aria-describedby="button-addon2">'+
            '<div class="input-group-append">'+
              '<button class="btn btn-outline-primary" type="button" onclick="set_pagination3(\''+ID+'\','+1+');" id="button-addon2">Search</button>'+
            '</div>'+
          '</div>'+
          '<div id="cont_icon'+ID+'">'+
          '</div>'+
          '<div id="cont_pag'+ID+'" class="pagination">'+
          '</div>'
            );
          $(newDiv).dialog({
              resizable: false,
              height: "auto",
              width: 400,
              modal: true,
              buttons: {
                "Cancel": function() {
                  $( this ).dialog( "close" );
                  newDiv.remove();
                }
              },
              open: function() {
              $('.ui-dialog-titlebar-close').find('.ui-icon').removeClass('ui-button-icon');
          },
            close: function( event, ui ) {
              newDiv.remove();
            }
          });
         }
       });


        var editortxt = new FroalaEditor('.bexi_editor_text',
        {
          key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
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
          },
          imageInsertButtons: ['imageBack', '|', 'imageUpload', 'imageByURL','unsplash_insert'],
          imageEditButtons:['imageUpload', 'imageByURL','unsplash_manager', 'imageAlign', 'imageCaption', 'imageRemove', '|', 'imageLink', 'linkOpen', 'linkEdit', 'linkRemove', '-', 'imageDisplay', 'imageStyle', 'imageAlt', 'imageSize'],
          // Set the image upload parameter.
          imageUploadParam: 'file',

          // Set the image upload URL.
          imageUploadURL: './ajax/uploadfile.php',

          // Additional upload params.
          imageUploadParams: {pid: $("#codeId").val(),uid:$("#userId").val(),tagid:null},

          // Set request type.
          imageUploadMethod: 'POST',

          // Set max image size to 5MB.
          imageMaxSize: 5 * 1024 * 1024,

          // Allow to upload PNG and JPG.
          imageAllowedTypes: ['jpeg', 'jpg', 'png'],
          events : {
            'blur': function () {
                auto_save();
            },
            'image.beforeUpload': function (images) {
              //save_img(null,images[0]);
              //console.log("1");
            },'image.inserted': function ($img, response) {
              // Image was inserted in the editor.
              var jresponse =JSON.parse(response);
              $img.attr("id",jresponse.id);
              $img.attr("src",jresponse.src);
              console.log(response);
            }
          }
        });

        var editortitles = new FroalaEditor('.bexi_editor_title',
        {
          key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarVisibleWithoutSelection: true,
          imageInsertButtons: ['imageBack', '|', 'imageUpload', 'imageByURL','unsplash_insert'],
          events : {
            'blur': function () {
                auto_save();
            },
            'image.beforeUpload': function (images) {
              // Image was inserted in the editor.
              console.log(images);
            }
          }
        });

         var editorsubtitles = new FroalaEditor('.bexi_editor_subtitle',
        {
          key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarVisibleWithoutSelection: true,
          imageInsertButtons: ['imageBack', '|', 'imageUpload', 'imageByURL','unsplash_insert'],
          events : {
            'blur': function () {
                auto_save();
            },
            'image.beforeUpload': function (images) {
              // Image was inserted in the editor.
              console.log(images);
            }
          }
        });

         var editorlin = new FroalaEditor('.bexi_editor_link',
        {
          key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          linkEditButtons:['linkOpen', 'linkEdit', 'linkRemove','bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'clearFormatting'],
          events : {
            'blur': function () {
                auto_save();
            },
            'image.beforeUpload': function (images) {
              // Image was inserted in the editor.
              console.log(images);
            }
          }
        });

         var editorimg = new FroalaEditor('.bexi_img',
        {
          key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          imageManagerLoadURL: 'load_images.php',
          toolbarBottom : false,
          imageDefaultAlign: 'center',
          imageDefaultMargin: 0,
          imageInsertButtons: ['imageBack', '|', 'imageUpload', 'imageByURL','unsplash_manager'],
          events : {
            'blur': function () {
                auto_save();
            },
            'image.beforeUpload': function (images) {
              // Image was inserted in the editor.
              console.log(images);
            }
          }
        });

         var editorbtn = new FroalaEditor('.bexi_editor_button',
        {
          key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarBottom : false,
          toolbarVisibleWithoutSelection: true,
          events : {
            'blur': function () {
                auto_save();
            }
          }

        });

        var editorvid = new FroalaEditor('.bexi_editor_video', {
          key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarBottom : false,
          videoResponsive: true,
          toolbarButtons: ['insertVideo'],
          videoAllowedProviders: ['youtube', 'vimeo'],
          videoInsertButtons: ['videoBack', '|', 'videoByURL'],
          events : {
            'blur': function () {
                auto_save();
            }
          }
        });

        var editorico = new FroalaEditor('.bexi_editor_icon', {
          key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
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
          pluginsEnabled: ['fontAwesome', 'fontFamily', 'fontSize'],
          toolbarButtons : {
             'moreMisc' : {
                 'buttons' : ['iconcolor','iconbgcolor','iconsize','iconlink','iconexchange','iconremove'],
                 'buttonsVisible': 6
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
          },
          'blur': function () {
            auto_save();
          }
        }
        });

});

function auto_save()
{
    var pid=$("#codeId").val();
    var uid=$("#userId").val();
    var c=$("#modu_main").html();
    var request=$.ajax({
      url: "./ajax/autosave.php",
      data: { userid: uid, projectid : pid,code:c} ,
      datatype:"json",
      method:"POST",
      success: function(data){
      }
    });
}

function save_img(TAGID,FILE){
  var pid=$("#codeId").val();
  var uid=$("#userId").val();
  var data = new FormData();
  data.append("file",FILE);
  data.append("userid",uid);
  data.append("projectid",pid);
  data.append("tagid",TAGID);
  var request=$.ajax({
    url: "./ajax/uploadfile.php",
    data: data,
    processData: false,
    contentType: false,
    method:"POST",
    success: function(response){
      var jresponse = JSON.parse(response);
      window.bexi_src=jresponse.src;
      console.log(window.bexi_src,"desde response");
      window.bexi_id=jresponse.id;
    }
  });
}

function load_imgs(){
  var imgs_array=[];
  var request=$.ajax({
    url: "./ajax/autosave.php",
    data: { userid: uid, projectid : pid},
    datatype:"json",
    method:"POST",
    success: function(data){
      var jdata=JSON.parse(data);
      $.each(jdata.images, function(index, item) {
        imgs_array.push(item);
      });
      var n=0;
      $(".bgimginput").each(function(){
        this.files[n]=imgs_array[n];
        n++;
      });
    }
  });
}