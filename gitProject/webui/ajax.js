//Determine what XmlHttpRequest object we will use.
function getXmlHttpRequestObject() {
	if (window.XMLHttpRequest) {
		return new XMLHttpRequest(); //Not IE
	} else if (window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP"); //IE
	} else {
		alert("NO XML support in Browser.  Please use a XML support Browser");
	}
}

var getRequest = getXmlHttpRequestObject();
var resultForm;

function getPage(page) {
	if (page == 0) {
		return;
	}

	if (getRequest.readyState == 4 || getRequest.readyState == 0) {
		getRequest.open("GET",page,true);
		getRequest.onreadystatechange = displayText;
		getRequest.send(null);
	}
}

function displayText() {
	if (getRequest.readyState == 4) {
		document.getElementById("main").innerHTML = getRequest.responseText;
	}
}

function getResults(page) {
	if (getRequest.readyState == 4 || getRequest.readyState == 0) {
		getRequest.open("GET",page,true);
		getRequest.onreadystatechange = displayResults;
		getRequest.send(null);
	}
}

function displayResults() {
	if (getRequest.readyState == 4) {
		document.getElementById("_results").innerHTML = getRequest.responseText;
	}
}

function getQueryXML(func,table,where) {
	if (getRequest.readyState == 4 || getRequest.readyState == 0) {
		getRequest.open("GET","queryxml.php?_ret=1&table="+table+"&"+where,true);
		getRequest.onreadystatechange = func;
		getRequest.send(null);
	}
}

function _openSearch(obj) {
        if (obj.value != "") {
                resultForm = document.access;
                getQueryXML(_getUserData,"user","uid="+obj.value+"&user=1&id=1&fields=uid");
        }
}

function _getUserData() {
        if (getRequest.readyState == 4) {
                var xmldoc = getRequest.responseXML.getElementsByTagName('user')[0];
                var _list = document.getElementById('_dynlist');

                _list.size = xmldoc.childNodes.length;

                if (_list.size == 1) {
			_list.size = 2;
		}

                for (var i=0; i < xmldoc.childNodes.length; i++) {
                        if (xmldoc.getElementsByTagName('uid')[i]) {
                                var _optnew = document.createElement('option');
                                _optnew.text = xmldoc.getElementsByTagName('uid')[i].firstChild.nodeValue;
                                _optnew.value = xmldoc.getElementsByTagName('uid')[i].firstChild.nodeValue;
                                if (isIE)
                                        _list.add(_optnew);
                                else
                                        _list.add(_optnew, null);
                        }
                }
                document.getElementById('_list').style.visibility = "visible";
        }
}

function _select(obj) {
        obj.value =  document.getElementById('_dynlist').value;
	_onblur();
}

function _onblur() {
	var _list = document.getElementById('_dynlist');
        for (var i=0; i < _list.length; i++) {
                _list.remove(i);
        }
        document.getElementById('_list').style.visibility = "hidden";
}

