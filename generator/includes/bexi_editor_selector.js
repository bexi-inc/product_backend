$(document).ready(function() {
  console.log("iniciando");
  $("#img_select_project").click(function(){
  		console.log("Project Selected");

  		var myEvent = new CustomEvent('bexi_modu', { detail: {cmd: 'SelectProject', code: $(this).attr("bexi-code")} });
  		window.parent.dispatchEvent(myEvent);

  });
});
