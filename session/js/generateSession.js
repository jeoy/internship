authenticationUrl="http://localhost/session/page";

//function:replaceNullOrUndifined
function replaceNullOrUndifined(data) {
	if (data == null || typeof data == 'undefined') 
		return "";
	return data;
}

//function:Trim
function Trim(str) {
	str=replaceNullOrUndifined(str);
	var iStart = 0;
	var iEnd = 0;
	var iLen = str.length;
	for(iStart = 0 ; iStart < iLen && str.charAt(iStart) == " "; iStart++);
	for(iEnd = iLen;  iEnd > 1 && str.charAt(iEnd -1 ) == "  ";  iEnd--);
	if(iStart > iEnd )  return  "";  
	return  str.substring(iStart,iEnd);
}

//function:TrimQuotationMarks
function TrimQuotationMarks(str) {
	str=replaceNullOrUndifined(str);
	var iStart = 0;
	var iEnd = str.length;
	if(str.charAt(iEnd -1)=='"') iEnd--;
	if(str.charAt(0)=='"') iStart++;
	if(iStart > iEnd )  return  "";  
	return  str.substring(iStart,iEnd);
}


function getCookie(objName) {
	var cookies = document.cookie.split(";");
	var iLen = cookies.length;
	if (iLen <= 0)
		return null;
	var keyName = Trim(objName);
	for ( var i = 0; i < iLen; i++) {
		if (typeof cookies[i] == 'undefined' || cookies[i] == "")
			continue;
		var keyValues = cookies[i].split("=");
		var key = Trim(keyValues[0]);
		if (key == keyName) {
			return decodeURIComponent(TrimQuotationMarks(Trim(keyValues[1]))).replace(/%20/g," ");
		}
	}
	return null;
}

function replaceNullOrUndifined(data) {
	if (data == null || typeof data == 'undefined') 
		return "";
	return data;
}

function setCookieFromJSONP(cookieName,cookieValue,timeout) {
	document.cookie = cookieName+"="+cookieValue+"; expire="+timeout;
	//var cookie = $.cookie(cookieName, cookieValue, { expires: 1000 * 60 * 5 });
	/*
	if($.cookie(cookieName)) {
		alert("cookie已存在");
		//getViewData(pageType, id);
	} else {
		alert("cookie不存在");
		// 1分钟过期
		//var cookie = $.cookie('the_cookie'+id, 'Gonn', { expires: 1000 * 60 * 5 });
		//$.cookie('the_cookie'+id, 'Gonn');
		//var cookie = $.cookie('the_cookie'+id);
		//alert(cookie);
		//insert_page(pageType, id);
	}
	*/
}

//
function WriteResult(dataObj) {
	//alert(dataObj.Session_ID);
	
	SessionObj.Session_ID = replaceNullOrUndifined(dataObj.Session_ID);
	SessionObj.Token_ID = replaceNullOrUndifined(dataObj.Token_ID);
	SessionObj.User_ID = replaceNullOrUndifined(dataObj.User_ID);
	SessionObj.Token_Date = replaceNullOrUndifined(dataObj.Token_Date);
	
	time_out = 1000 * 60 * 5;
	//获取当前时间
	date=new Date();
	expireDays=10;
	//将date设置为10天以后的时间
	date.setTime(date.getTime()+expireDays*24*3600*1000);
	timeout = date.toGMTString();
	//将cookie设置为10天后过期
	
	setCookieFromJSONP('Session_ID',SessionObj.Session_ID,timeout);
	setCookieFromJSONP('Token_ID',SessionObj.Token_ID,timeout);
	setCookieFromJSONP('User_ID',SessionObj.User_ID,timeout);
	setCookieFromJSONP('Token_Date',SessionObj.Token_Date,timeout);
	
	/*
	var data = dataObj.result[0];
	if (dataObj.result){
		alert(1);
		SessionObj.Session_ID = replaceNullOrUndifined(data.COOKIE_SESSION_ID);
		SessionObj.Token_ID = replaceNullOrUndifined(data.COOKIE_TOKEN_ID);
		SessionObj.User_ID = replaceNullOrUndifined(data.COOKIE_USER_ID);
		SessionObj.Token_Date = replaceNullOrUndifined(data.COOKIE_TOKEN_DATE);
		SessionObj.S_Token_ID = replaceNullOrUndifined(data.COOKIE_S_TOKEN_ID);
		SessionObj.User_IP = replaceNullOrUndifined(data.COOKIE_USER_IP);
		SessionObj.User_City = replaceNullOrUndifined(data.COOKIE_USER_CITY);
		SessionObj.User_Name = replaceNullOrUndifined(data.COOKIE_USER_NAME);
		SessionObj.NickName = replaceNullOrUndifined(data.COOKIE_NICKNAME);
		SessionObj.User_Level_ID = replaceNullOrUndifined(data.COOKIE_USER_LEVEL_ID);
		//判断一下引用此js的原文件的url地址，若该地址不在.wangjiu.com域名下。
		if (document.domain.indexOf(domainName) == -1) {
			addCookie("COOKIE_SESSION_ID", SessionObj.Session_ID ,data.COOKIE_TIMEOUT);
			addCookie("COOKIE_TOKEN_ID", SessionObj.Token_ID ,data.COOKIE_TIMEOUT);
			addCookie("COOKIE_S_TOKEN_ID", SessionObj.S_Token_ID ,data.COOKIE_TIMEOUT,true);
			addCookie("COOKIE_TOKEN_DATE", SessionObj.Token_Date ,data.COOKIE_TIMEOUT);
			addCookie("COOKIE_USER_IP", SessionObj.User_IP ,data.COOKIE_TIMEOUT);
			addCookie("COOKIE_USER_CITY", SessionObj.User_City ,data.COOKIE_TIMEOUT);
			addCookie("COOKIE_USER_ID", SessionObj.User_ID ,data.COOKIE_TIMEOUT);
			addCookie("COOKIE_USER_NAME", SessionObj.User_Name ,data.COOKIE_TIMEOUT);
			addCookie("COOKIE_NICKNAME", SessionObj.NickName ,data.COOKIE_TIMEOUT);
			addCookie("COOKIE_USER_LEVEL_ID", SessionObj.User_Level_ID ,data.COOKIE_TIMEOUT);
		}

	}
	*/
	SessionObj.IsLinkSuccess = true;
}

var SessionObj = new Object();
SessionObj.Session_ID = getCookie("Session_ID");
SessionObj.Token_ID = getCookie("Token_ID");
SessionObj.Token_Date = getCookie("Token_Date");
SessionObj.User_ID = getCookie("USER_ID");
SessionObj.IsLinkSuccess = true;
/*
SessionObj.User_Name = getCookie("COOKIE_USER_NAME");
SessionObj.NickName = getCookie("COOKIE_NICKNAME");
SessionObj.User_Level_ID = getCookie("COOKIE_USER_LEVEL_ID");
SessionObj.Token_Date = getCookie("COOKIE_TOKEN_DATE");
SessionObj.User_IP = getCookie("COOKIE_USER_IP");
SessionObj.User_City = getCookie("COOKIE_USER_CITY");
SessionObj.linkData="";
*/


if (SessionObj.Session_ID == null || SessionObj.Session_ID == ""
		|| SessionObj.Token_ID  == null || SessionObj.Token_ID  == ""
		|| SessionObj.Token_Date == null || SessionObj.Token_Date == ""
/*		|| SessionObj.User_IP == null || SessionObj.User_IP == ""
		|| SessionObj.User_Level_ID == null || SessionObj.User_Level_ID == ""
		|| bNeedExtend*/
		) {
	SessionObj.IsLinkSuccess = false;

	//JSONP
	//generateSessionJSONP(authenticationUrl+"/api/list/generateSession.jsonp?IS_PHONE=0&a="+ Math.random());
	//generateSessionJSONP(authenticationUrl+"/generateSessionJSONP.php?a="+ Math.random());
	generateSessionJSONP(authenticationUrl+"/generateSessionJSONP.php?");
}


//jsonp 方式
function generateSessionJSONP(url) {
	var JSONP=document.createElement("script");  
    JSONP.type="text/javascript";  
    JSONP.src=url+"callback=WriteResult";  
    document.getElementsByTagName("body")[0].appendChild(JSONP);
    //document.getElementsByTagName("title")[0].innerHTML="sa";
    
  //ajax
	var xmlhttp;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET",JSONP.src,true);
	xmlhttp.send();
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			//JSONP.innerHTML=xmlhttp.responseText;
		}
	};
}
//通用jsonp 方式
function getJSONP(url,callback) {
	var JSONP=document.createElement("script");
    JSONP.type="text/javascript";
    JSONP.src=url+"&callback="+callback;
    document.getElementsByTagName("head")[0].appendChild(JSONP);
}

/*
if (SessionObj.Session_ID == null || SessionObj.Session_ID == ""
		|| SessionObj.Token_ID  == null || SessionObj.Token_ID  == ""
		|| SessionObj.Token_Date == null || SessionObj.Token_Date == ""
		//|| SessionObj.User_IP == null || SessionObj.User_IP == ""
		//|| SessionObj.User_Level_ID == null || SessionObj.User_Level_ID == ""
		//|| bNeedExtend
		) {
	SessionObj.IsLinkSuccess = false;
	
	//JSONP
	//generateSessionJSONP(authenticationUrl+"/api/list/generateSession.jsonp?IS_PHONE=0&a="+ Math.random());
	//generateSessionJSONP(authenticationUrl+"/generateSessionJSONP.php?a="+ Math.random());
	$.ajax({
		url:"http://localhost/session/page/generateSessionJSONP.php",//url:"http://crossdomain.com/services.php",
	    dataType:'jsonp',
	    data:'',
	    jsonp:'callback',
	    success:function(result) {
	    	//alert("success");
	    	for(var i in result) {
	    		//alert(i+result[i]);
	    		if($.cookie(i)) {
					//alert("cookie已存在");
				} else {
					//alert("cookie不存在");
				}
	    		setCookieFromJSONP(i,result[i]);
	    	}
	    },
	    timeout:3000,
	    error:function() {
	    	alert('error!');
	    }
	});
}
 */