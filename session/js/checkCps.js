	var srchStr=window.location.search;

	load();

	function load() {
		if(srchStr == null || typeof srchStr == 'undefined') {
		} else {
			//if(srchStr) {
			var srchArray=getSearchAsArray(srchStr);
			if(srchArray["cps"]) {
				//store cps information
				document.cookie = "CPS_ID="+srchArray["cps"]+"; expire="+1000*6*5;
			} //else alert("error: no cps information");
		}
	}

	function getSearchAsArray(srchStr) {
		var results=new Array();
		var input=unescape(srchStr.substr(1));
		if(input) {
			var srchArray=input.split("&");
			var tempArray=new Array();
			for(var i=0;i<srchArray.length;i++) {
				tempArray=srchArray[i].split("=");
				results[tempArray[0]]=tempArray[1];
			}
		}
		return results;
	}
