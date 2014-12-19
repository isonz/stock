$(document).ready(function(){
	clickBgMask();
	catelogRoots();
}); 

function uzAutoRunCheck(obj){
	if(obj.attr('checked')){
		window.location.href = "?auto=1"
	}else{
		window.location.href = "/uz"
	}
}

function uzAutoRun(){
	$("#selectall").attr('checked','checked');
	selectAll($("#selectall"));
	uzAdd();
}

function selectAll(checkbox) {
	$('#uzform input[type=checkbox]').attr('checked', $(checkbox).attr('checked'));
}

var flag = 0;
function uzAdd(){
	$("#_tb_token_id").val($("#_tb_token_id1").val());
	$("#bgMask").show();
	$("#bgTransparent").show();
	flag = 0;
	$.ajax({      
		type: "POST",
		dataType: "json",
		url: "/uz",     
		data: $("#uzform").serialize(),
		success: function(data){
			if(data.error<1){
				postUz(data.url, data.itemId, data.href);
			}else{
				$("#bgTransparent").html('Something Error, Mybe not input data !');
			}
		}  
	});
	return false;
}

var url_numb =0;
var current_url_numb = 0;
function postUz(url, itemId, href){
	url_numb = url.length;
	for(var i=0; i<url_numb; i++){
		(function(i){
			setTimeout(function(){
				if(0==flag){
					uzMoney(i, url[i], itemId[i], href[i]);
				}else{
					setTimeout("locareload()",5000);
				}
		    }, 2000 * i)
		})(i);
	}
}

function uzMoney(i, url, itemId, href){
	$.ajax({
        type: 'GET',
        dataType: 'jsonp',
        jsonpCallback: 'jsonp',
        data: {},
        url: href,
        error: function (XMLHttpRequest, textStatus, errorThrown) {$("#bgTransparent").prepend('Get Money请求失败！'); flag++;},
        success: function (json) {
            $("#bgTransparent").prepend(i+':Get Money 请求成功，等待返回数据.<br />');
            if('undefined'!=typeof(json.result.content.commission)){
				var money = json.result.content.commission;
				if(uzMinMoney <= money){
					uzAjaxAdd(i, url, itemId,'money:'+money+' UZ添加');
				}
                var sts = upStatus(itemId, money);
                if(sts<1) flag++;
            }else{
				flag++;
            	current_url_numb++;
            	if(current_url_numb >= url_numb) setTimeout("locareload()",5000);
			}
        }
   });
}

function uzAjaxAdd(i, url, itemId, msg){
	$.ajax({
    	type: 'GET',
        dataType: 'jsonp',
        jsonpCallback: 'jsonp',
        data: {},
        url: url,
        error: function (XMLHttpRequest, textStatus, errorThrown) {$("#bgTransparent").prepend(msg+'请求失败！'); flag++;},
        success: function (json) {
        	$("#bgTransparent").prepend(i+': '+msg+'请求成功，等待返回数据.<br />');
            if('undefined'!=json.status && 1==json.status){
            	var sts = upStatus(itemId, -1);
                if(sts<1) flag++;
            }else{
				$("#bgTransparent").prepend('UZ返回错误结果，可能推荐理由含有违禁词.'); flag++;
			}
            current_url_numb++;
            if(current_url_numb >= url_numb) setTimeout("locareload()",5000);
        }
   });
}

function locareload(){
	if($("#autorun").attr('checked')){
		window.location.reload();
	}
}

function upStatus(itemId, money){
	$.ajax({      
		type: "POST",
		dataType: "json",
		url: "/uz",     
		data: {itemId:itemId,uzMoney:money,type:'update'},
		success: function(data){
			$("#bgTransparent").prepend(data.msg);
			return data.error;
		}
	});
}

function clickBgMask(){
	$("#bgMask").click(function(){
		$(this).hide();
		$("#bgTransparent").html('');
		$("#bgTransparent").hide();
		$("#catelog_root").hide();
		$("#catelog_child").hide();
	});
}


function addTitle(obj){
	var title = obj.find('option:selected').text();
	obj.next("input").val(title);
}
/*  *************************** 多级联动 *********************************** */
var root_catelog;
var root_catelog_val;
function catelogRoots(){
	$("a.input-bg.catelog_roots").click(function(){
		var zb = $(this).offset();
		root_catelog = $(this);
		$("#bgMask").css("opacity","0.0");
		$("#bgMask").show();
		$("#catelog_root").show();
		$("#catelog_root").css({left:zb.left+"px",top:zb.top+24+"px"});
	});
	$("#catelog_root a").click(function(){
		root_catelog_val = $(this).text();
		$("a.input-bg.catelog_roots").text(root_catelog_val);
		$("#bgMask").hide();
		$("#catelog_root").hide();
		$("#catelog_child").html('');
		var fid = $(this).attr("data");
		$.getJSON("/add?fid="+fid, function(data){
			for(var i=0; i<data.length; i++){
  				$("#catelog_child").prepend('<a class="a" href="javascript:;" data="'+data[i].id+'">'+data[i].title+'</a>');
			}
			$("#catelog_child").append('<br clear="all">');
			var zb = root_catelog.parent().next().children("a").offset();
			$("#catelog_child").css({left:zb.left+"px",top:zb.top+24+"px"});
			$("#catelog_child").show();
			$("#bgMask").css("opacity","0.0");
			$("#bgMask").show();
			tagClick(root_catelog.parent().next().children("a"));
		});
	});
	$("a.input-bg.catelog_tag").click(function(){
		var zb = $(this).offset();
		$("#bgMask").css("opacity","0.0");
		$("#bgMask").show();
		$("#catelog_child").show();
		$("#catelog_child").css({left:zb.left+"px",top:zb.top+24+"px"});
		tagClick($(this));
	});
	
	/*************************单级******************************/
	$("a.input-bg.main_catelog_roots").click(function(){
		var zb = $(this).offset();
		root_catelog = $(this);
		$("#bgMask").css("opacity","0.0");
		$("#bgMask").show();
		$("#main_catelog_root").show();
		$("#main_catelog_root").css({left:zb.left+"px",top:zb.top+24+"px"});
	});
	$("#main_catelog_root a").click(function(){
		$("a.input-bg.main_catelog_roots").text($(this).text());
		$("#bgMask").hide();
		$("#catelog_root").hide();
		var fid = $(this).attr("data");
		$("input.catelogs").val(fid);
		$("#bgMask").hide();
		$("#main_catelog_root").hide();
	});
	/****************************************/
}

var search_url = 'http://s.taobao.com/search?js=1&stats_click=search_radio_all%3A1&q=';
function tagClick(obj)
{
	$("#catelog_child a").unbind("click");
	$("#catelog_child a").click(function(){		
		var val = root_catelog_val+'-'+$(this).text();
		var i = 0;
		$(".input-title").each(function() {
    		if($(this).val() == val) i++;
		});
		if(0==i){
			obj.text($(this).text());
			$("#bgMask").hide();
			$("#catelog_child").hide();
			obj.next().val($(this).attr("data"));
			obj.parent().next().children("input").val(val);
			var url = search_url+encodeURIComponent($(this).text());
			obj.parent().next().next().children("input").val(url);
		}
	});
}
/*  *************************** END 多级联动 *********************************** */

function checkCatelogDiffTitle(obj){
	var val = $.trim(obj.val());
		var i = 0;
	$(".input-title").each(function() {
		if($(this).val() == val) i++;
	});
	if(1<i){
		obj.val('');
	}else{
		obj.val(val);
	}
}

function checkCatelogAdd(){
	var catelog = $("input.catelogs").val();
	var title = $("input.input-title").val();
	if(''==catelog || ''==title){
		alert('存在未填写的信息！');
		return false;
	}
	return true;
}

function clearURL(){
	$(".input-url").val('');
}

function showtime(){
    var t = $("#dtime").text();
    t = t - 1;
    $("#dtime").text(t);
}


