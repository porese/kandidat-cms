var menuids=["mmenutree1"]
function buildsubmenus(){
  for (var i=0; i<menuids.length; i++){
	var ultags=document.getElementById(menuids[i]).getElementsByTagName("ul")
    for (var t=0; t<ultags.length; t++){
  		ultags[t].parentNode.getElementsByTagName("a")[0].className="subfolderstyle"
		if (ultags[t].parentNode.parentNode.id==menuids[i])
			ultags[t].style.left=ultags[t].parentNode.offsetWidth+"px"
		else
			ultags[t].style.left=ultags[t-1].getElementsByTagName("a")[0].offsetWidth+"px"
  		ultags[t].parentNode.onmouseover=function(){
  			this.getElementsByTagName("ul")[0].style.display="block"
  		}
	    ultags[t].parentNode.onmouseout=function(){
  		  	this.getElementsByTagName("ul")[0].style.display="none"
  		}
    }
	for (var t=ultags.length-1; t>-1; t--){
		ultags[t].style.visibility="visible"
		ultags[t].style.display="none"
	}
  }
}
if (window.addEventListener)
	window.addEventListener("load", buildsubmenus, false)
else if (window.attachEvent)
	window.attachEvent("onload", buildsubmenus)
