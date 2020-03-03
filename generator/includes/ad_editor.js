$(document).ready(function() {
    $( ".bexi_title" ).wrap( "<div class='bexi_editor_title' style='width: 100%;'></div>" );

    $( ".bexi_subtitle" ).wrap( "<div class='bexi_editor_subtitle'  style='width: 100%;'></div>" );

    $( ".bexi_text" ).each(function( index ) {
        $(this).wrap( "<div class='bexi_editor_text' id='ed_" + $(this).attr('id') + "''></div>" );
    });

    $( ".bexi_button" ).wrap( "<div class='bexi_editor_button' style='width: 100%;'></div>" );

    $('.bexi_icon').wrap( '<p class="bexi_editor_icon" ></p>');

    initialize_editors_text();
});


function auto_save(){

};


function styles_ptags(){
    $("#modu_main").find("p").each(function(){
      if($(this).attr("class")===undefined)
      {
        $(this).css("padding","0px");
        $(this).css("margin","0px");
      }else{
        if($(this).attr("class").search("bexi_editor")!==-1){
          $(this).css("padding","0px");
          $(this).css("margin","0px");
        }
      }
    });
  }

function initialize_editors_text(){

    var editortitles = new FroalaEditor('.bexi_editor_title',
    {
      //key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
      iconsTemplate: 'font_awesome_5',
      key : FroalaKey,
      fileUpload: false,
      placeholderText: '',
      quickInsertEnabled: false,
      toolbarInline: true,
      charCounterCount: false,
      toolbarVisibleWithoutSelection: true,
      emoticonsUseImage: false,
      imageStyles: {
        'fr-rounded': 'Rounded',
        'fr-bordered': 'Bordered'
      },
      toolbarButtons : {
        'moreText': {
            'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor','clearFormatting']
          },
          'moreParagraph': {
            'buttons': ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'lineHeight', 'outdent', 'indent', 'quote']
          },
          'moreRich': {
            'buttons': ['fontAwesome','emoticons', 'specialCharacters'],
            'buttonsVisible': 4
          }
      },
      fontFamilySelection: true,
      fontFamilyDefaultSelection: 'Font',
      imageUploadParam: 'file',

      // Set the image upload URL.
      imageUploadURL: './ajax/uploadfile.php',

      // Additional upload params.
      imageUploadParams: {devid: $("#devId").val(),userid:$("#userId").val(),projectid:$("#codeId").val(),tagid:""},

      // Set request type.
      imageUploadMethod: 'POST',

      // Set max image size to 5MB.
      imageMaxSize: 3 * 1024 * 1024,

      // Allow to upload PNG and JPG.
      imageAllowedTypes: ['jpeg', 'jpg', 'png'],
      events : {
            'blur': function () {
              auto_save();
          },
          'image.beforeUpload': function (images) {
            this.opts.imageUploadParams.tagid=window.bexi_tagid;
          },
          'image.inserted': function ($img, response) {
            // Image was inserted in the editor.
            var jresponse =JSON.parse(response);
            $img.attr("id",jresponse.id);
            $img.attr("src",jresponse.src+"?timestamp=" + new Date().getTime());
            $img.addClass("fr-view");
            window.bexi_tagid=jresponse.id;
            auto_save();
          },
          'image.replaced': function ($img, response) {
            // Image was replaced in the editor.
            var jresponse =JSON.parse(response);
            $img.attr("id",jresponse.id);
            $img.attr("src",jresponse.src+"?timestamp=" + new Date().getTime());
            $img.addClass("fr-view");
            window.bexi_tagid=jresponse.id;
            auto_save();
          },
          'click': function (clickEvent) {
            // Do something here.
            // this is the editor instance.
            if(clickEvent.currentTarget.tagName=="IMG")
            {
              window.bexi_tagid=$(clickEvent.currentTarget).attr("id").replace(/\s/g, '');
            }
            else{
              window.bexi_tagid=null;
            }
          },
          'initialized': function () {
            styles_ptags();
          },
          'image.resizeEnd': function ($img) {
            auto_save();
          },
          'image.beforeRemove': function ($img) {
            auto_save();
          }
      }
    });

    var editorsubtitles = new FroalaEditor('.bexi_editor_subtitle',
    {
      //key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
      iconsTemplate: 'font_awesome_5',
      key : FroalaKey,
      fileUpload: false,
      placeholderText: '',
      quickInsertEnabled: false,
      toolbarInline: true,
      charCounterCount: false,
      toolbarVisibleWithoutSelection: true,
      emoticonsUseImage: false,
      imageStyles: {
        'fr-rounded': 'Rounded',
        'fr-bordered': 'Bordered'
      },
      toolbarButtons : {
        'moreText': {
            'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor','clearFormatting']
          },
          'moreParagraph': {
            'buttons': ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'lineHeight', 'outdent', 'indent', 'quote']
          },
          'moreRich': {
            'buttons': ['fontAwesome','emoticons', 'specialCharacters'],
            'buttonsVisible': 4
          }
      },
      fontFamilySelection: true,
      fontFamilyDefaultSelection: 'Font',
      imageUploadParam: 'file',

      // Set the image upload URL.
      imageUploadURL: './ajax/uploadfile.php',
  
      // Additional upload params.
      imageUploadParams: {devid: $("#devId").val(),userid:$("#userId").val(),projectid:$("#codeId").val(),tagid:""},
  
      // Set request type.
      imageUploadMethod: 'POST',

      // Set max image size to 5MB.
      imageMaxSize: 3 * 1024 * 1024,

      // Allow to upload PNG and JPG.
      imageAllowedTypes: ['jpeg', 'jpg', 'png'],
      events : {
            'blur': function () {
              auto_save();
          },
          'image.beforeUpload': function (images) {
            this.opts.imageUploadParams.tagid=window.bexi_tagid;
          },
          'image.inserted': function ($img, response) {
            // Image was inserted in the editor.
            var jresponse =JSON.parse(response);
            $img.attr("id",jresponse.id);
            $img.attr("src",jresponse.src+"?timestamp=" + new Date().getTime());
            $img.addClass("fr-view");
            window.bexi_tagid=jresponse.id;
            auto_save();
          },
          'image.replaced': function ($img, response) {
            // Image was replaced in the editor.
            var jresponse =JSON.parse(response);
            $img.attr("id",jresponse.id);
            $img.attr("src",jresponse.src+"?timestamp=" + new Date().getTime());
            $img.addClass("fr-view");
            window.bexi_tagid=jresponse.id;
            auto_save();
          },
          'click': function (clickEvent) {
            // Do something here.
            // this is the editor instance.
            if(clickEvent.currentTarget.tagName=="IMG")
            {
              window.bexi_tagid=$(clickEvent.currentTarget).attr("id").replace(/\s/g, '');
            }
            else{
              window.bexi_tagid=null;
            }
          },
          'initialized': function () {
            styles_ptags();
          },
          'image.resizeEnd': function ($img) {
            auto_save();
          },
          'image.beforeRemove': function ($img) {
            auto_save();
          }
      }
    });



    var editorimg = new FroalaEditor('.bexi_img',
    {
      //key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
      iconsTemplate: 'font_awesome_5',
      key : FroalaKey,
      fileUpload: false,
      placeholderText: '',
      quickInsertEnabled: false,
      toolbarInline: true,
      charCounterCount: false,
      toolbarBottom : false,
      imageDefaultAlign: 'center',
      imageDefaultMargin: 0,
      emoticonsUseImage: false,
      imageStyles: {
        'fr-rounded': 'Rounded',
        'fr-bordered': 'Bordered'
      },
      imageEditButtons: ['imageReplace', 'imageAlign', 'imageRemove', '|','-','imageSize'],
      imageInsertButtons: ['imageBack', '|', 'imageUpload', 'imageByURL','unsplash_manager'],
      // Set max image size to 5MB.
      imageMaxSize: 3 * 1024 * 1024,

      // Allow to upload PNG and JPG.
      imageAllowedTypes: ['jpeg', 'jpg', 'png'],
    events : {
      'blur': function () {
          auto_save();
      },
      'image.beforeUpload': function (images) {
        if(images[0].size<=(3 * 1024 * 1024))
        {
          var res=save_img(window.bexi_tagid,images[0]);
          res.done(function(data){
            var jresponse =JSON.parse(data);
            if(window.bexi_tagid!=null)
            {
              $("#"+window.bexi_tagid).attr("id",jresponse.id);
              $("#"+window.bexi_tagid).attr("src",jresponse.src+"?timestamp=" + new Date().getTime());
              window.bexi_tagid=jresponse.id;
              $("#"+window.bexi_tagid).removeAttr("bexi_au_link");
              $("#"+window.bexi_tagid).removeAttr("bexi_img_au");
            }else{
              $("img").each(function(){
                var pos=$(this).attr("src").search("blob:http://generator.getmodu.com/");
                if(pos!=-1){
                  $(this).attr("id",jresponse.id);
                  $(this).attr("src",jresponse.src+"?timestamp=" + new Date().getTime());
                  $(this).removeAttr("bexi_au_link");
                  $(this).removeAttr("bexi_img_au");
                  window.bexi_tagid=jresponse.id;
                }
              });
            }
            auto_save();
          });
        }else{
          var newDiv = $(document.createElement('div'));
          newDiv.html('Image too large(Max 3MB)');
          $(newDiv).dialog({
              resizable: false,
              height: "auto",
              width: 400,
              modal: true,
              buttons: {
                "Ok": function() {
                  $(this).dialog( "close" );
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
          return false;
        }
      },
      'image.uploaded': function (response) {
      },
      'image.inserted': function ($img, response) {
          // Image was inserted in the editor.
      },
      'image.replaced': function ($img, response) {
        // Image was replaced in the editor.
      },
      'image.loaded': function ($img) {
      },
      'click': function (clickEvent) {
        // Do something here.
        // this is the editor instance.
        if(clickEvent.currentTarget.tagName=="IMG")
        {
          window.bexi_tagid=$(clickEvent.currentTarget).attr("id").replace(/\s/g, '');
        }
        else{
          window.bexi_tagid=null;
        }
      },
      'initialized': function () {
        styles_ptags();
      },
      'image.resizeEnd': function ($img) {
        auto_save();
      },
      'image.beforeRemove': function ($img) {
        $("#"+$img.attr("id")).remove();
        $(".fr-active").remove();
        auto_save();
        return false;
      },
      'contentChanged': function () {
        auto_save();
      }
    }
    });

    var editorbtn = new FroalaEditor('.bexi_editor_button',
    {
      //key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
      iconsTemplate: 'font_awesome_5',
      key : FroalaKey,
      fileUpload: false,
      placeholderText: '',
      quickInsertEnabled: false,
      toolbarInline: true,
      charCounterCount: false,
      toolbarBottom : false,
      emoticonsUseImage: false,
      toolbarVisibleWithoutSelection: true,
      toolbarButtons:{},
      events : {
        'blur': function () {
            auto_save();
        },
        'initialized': function () {
          styles_ptags();
        },
        'contentChanged': function () {
          auto_save();
        }
      }
    });


    var editorico = new FroalaEditor('.bexi_editor_icon', {
      //key  :   "yDC5hG4I4C10A6A4A3gF-10xjroewE4gjkH-8D1B3D3E2E6C1F1B4D4D3==",
      key : FroalaKey,
      iconsTemplate: 'font_awesome_5',
      toolbarInline: true,
      charCounterCount: false,
      toolbarBottom : false,
      imageUpload: false,
      fileUpload: false,
      placeholderText: '',
      quickInsertEnabled: false,
      emoticonsUseImage: false,
      multiLine: false,
      enter: FroalaEditor.ENTER_BR,
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
      },
      'initialized': function () {
        styles_ptags();
      },
      'contentChanged': function () {
        auto_save();
      }
    }
    });
}