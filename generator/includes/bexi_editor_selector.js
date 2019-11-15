$(document).ready(function() {
  console.log("iniciando");
  $("#img_select_project").click(function(){
  		//console.log("Project Selected");
  		//console.log('SelectProject|' + $(this).attr("bexi-code"));

  		window.parent.postMessage('SelectProject|' + $(this).attr("bexi-code"), '*')

/*
  		var myEvent = new CustomEvent('bexi_modu', { detail: {cmd: 'SelectProject', code: $(this).attr("bexi-code")} });
  		window.parent.dispatchEvent(myEvent);
*/
  });
});
