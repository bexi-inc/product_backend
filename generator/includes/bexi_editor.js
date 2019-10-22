 $(document).ready(function() {
 $( ".bexi_title" ).wrap( "<div class='bexi_editor_title' style='width: 100%;'></div>" );

        $( ".bexi_subtitle" ).wrap( "<div class='bexi_editor_subtitle'  style='width: 100%;'></div>" );

        $( ".bexi_text" ).wrap( "<div class='bexi_editor_text'></div>" );

         $( ".bexi_link" ).wrap( "<div class='bexi_editor_link'></div>" );

          $( ".bexi_button" ).wrap( "<div class='bexi_editor_button' style='width: 100%;'></div>" );

        
        var editortxt = new FroalaEditor('.bexi_editor_text',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true
        });

        var editortitles = new FroalaEditor('.bexi_editor_title',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true
        });

         var editorsubtitles = new FroalaEditor('.bexi_editor_subtitle',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true
        });

         var editortxt = new FroalaEditor('.bexi_editor_link',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true
        });

         var editorimg = new FroalaEditor('.bexi_img',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          imageManagerLoadURL: 'load_images.php',
          toolbarBottom : false

        });

         var editorbtn = new FroalaEditor('.bexi_editor_button',
        {
          key  :   "CTD5xE3F3E2B1A4A1wnhvfF1rH-7oA9A7B6E5C2H4E3J2A7B8==",
          toolbarInline: true,
          charCounterCount: false,
          initOnClick: true,
          toolbarBottom : false

        });
});