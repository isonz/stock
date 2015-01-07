$(document).ready(function(){
	$("#hidename,#hidename1").toggle(
		function(){
			$("#hidename").removeAttr("class");
			$(".tdahidenname a").removeAttr("class");
		},
		function(){
			$("#hidename").attr("class","hidename");
			$(".tdahidenname a").attr("class","hidename");
		}
	);
}); 


