$(document).ready(function() {
  console.log("iniciando");
  $("#img_select_project").click(function(){
  		console.log("Project Selected");
  		parent.SelectProject($(this).attr("bexi-code"));
  });
});
