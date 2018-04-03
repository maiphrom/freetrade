var base_url = $('#base_url').attr('class');
function reset_time_out() {
	var chk_time_out = '';
	clearTimeout(chk_time_out);
	chk_time_out = setTimeout(function(){ location.href = base_url+'main_menu/logout'; }, 60 * 60 * 1000);
}

$( document ).ready(function() {
	reset_time_out();

	document.onclick = function(e){ reset_time_out(); };
	document.onmousemove = function(e){ reset_time_out(); };
	document.onkeydown = function(e){ reset_time_out(); };
	document.onscroll = function(e){ reset_time_out(); };
});	