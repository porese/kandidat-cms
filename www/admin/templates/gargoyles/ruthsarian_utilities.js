/*******************************************************************************
*  ruthsarian_utilities.js : 2008.05.12
* -----------------------------------------------------------------------------
*  A group of useful JavaScript utilities that can aid in the development
*  of webpages. Credit and source of code is given before each set of
*  functions. 
*******************************************************************************/

/* event_attach() takes care of attaching event handlers (functions) to events. 
 * this simplifies the process of attaching multiple handlers to a single event
 *
 * NOTE: the onload stack is executed in a LIFO manner to mimic 
 *       IE's window.attachEvent function. However, Opera also has its own
 *       window.attachEvent function which executes the onload stack in a 
 *       FIFO manner. FIFO is better, but IE has a larger user base, so
 *       LIFO is the way we go.
 */
function event_attach( event , func )
{
	if ( window.attachEvent )
	{
		window.attachEvent( event , func );
	}
	else
	{
		if ( ( typeof( func ) ).toLowerCase() != 'function' )
		{
			return;
		}
		if ( ( typeof( document.event_handlers ) ).toLowerCase() == 'undefined' )
		{
			document.event_handlers = new Array();
		}
		if ( ( typeof( document.event_handlers[ event ] ) ).toLowerCase() == 'undefined' )
		{
			document.event_handlers[ event ] = new Array();
		}
		if ( ( typeof( eval( 'window.' + event ) ) ).toLowerCase() != 'function' )
		{
			eval( 'window.' + event + ' = function () { if ( ( typeof( document.event_handlers[ \'' + event + '\' ] ) ).toLowerCase() != \'undefined\' ) { for ( i = document.event_handlers[ \'' + event + '\' ].length - 1 ; i >= 0  ; i-- ) { document.event_handlers[ \'' + event + '\' ][ i ](); } } } ' );
		}
		document.event_handlers[ event ][ document.event_handlers[ event ].length ] = func;
	}
}

/* Browser Detect  v2.1.6
 * documentation: http://www.dithered.com/javascript/browser_detect/index.html
 * license: http://creativecommons.org/licenses/by/1.0/
 * code by Chris Nott (chris[at]dithered[dot]com)
 *
 * modified to include Dreamcast
 */
function browser_detect() 
{
	var ua			= navigator.userAgent.toLowerCase(); 
	this.isGecko		= (ua.indexOf('gecko') != -1 && ua.indexOf('safari') == -1);
	this.isAppleWebKit	= (ua.indexOf('applewebkit') != -1);
	this.isKonqueror	= (ua.indexOf('konqueror') != -1); 
	this.isSafari		= (ua.indexOf('safari') != - 1);
	this.isOmniweb		= (ua.indexOf('omniweb') != - 1);
	this.isDreamcast	= (ua.indexOf("dreamcast") != -1);
	this.isOpera		= (ua.indexOf('opera') != -1); 
	this.isIcab		= (ua.indexOf('icab') != -1); 
	this.isAol		= (ua.indexOf('aol') != -1); 
	this.isIE		= (ua.indexOf('msie') != -1 && !this.isOpera && (ua.indexOf('webtv') == -1)); 
	this.isMozilla		= (this.isGecko && ua.indexOf('gecko/') + 14 == ua.length);
	this.isFirebird		= (ua.indexOf('firebird/') != -1);
	this.isNS		= ((this.isGecko) ? (ua.indexOf('netscape') != -1) : ((ua.indexOf('mozilla') != -1) && !this.isOpera && !this.isSafari && (ua.indexOf('spoofer') == -1) && (ua.indexOf('compatible') == -1) && (ua.indexOf('webtv') == -1) && (ua.indexOf('hotjava') == -1)));
	this.isIECompatible	= ((ua.indexOf('msie') != -1) && !this.isIE);
	this.isNSCompatible	= ((ua.indexOf('mozilla') != -1) && !this.isNS && !this.isMozilla);
	this.geckoVersion	= ((this.isGecko) ? ua.substring((ua.lastIndexOf('gecko/') + 6), (ua.lastIndexOf('gecko/') + 14)) : -1);
	this.equivalentMozilla	= ((this.isGecko) ? parseFloat(ua.substring(ua.indexOf('rv:') + 3)) : -1);
	this.appleWebKitVersion	= ((this.isAppleWebKit) ? parseFloat(ua.substring(ua.indexOf('applewebkit/') + 12)) : -1);
	this.versionMinor	= parseFloat(navigator.appVersion); 
	if (this.isGecko && !this.isMozilla) {
		this.versionMinor = parseFloat(ua.substring(ua.indexOf('/', ua.indexOf('gecko/') + 6) + 1));
	}
	else if (this.isMozilla) {
		this.versionMinor = parseFloat(ua.substring(ua.indexOf('rv:') + 3));
	}
	else if (this.isIE && this.versionMinor >= 4) {
		this.versionMinor = parseFloat(ua.substring(ua.indexOf('msie ') + 5));
	}
	else if (this.isKonqueror) {
		this.versionMinor = parseFloat(ua.substring(ua.indexOf('konqueror/') + 10));
	}
	else if (this.isSafari) {
		this.versionMinor = parseFloat(ua.substring(ua.lastIndexOf('safari/') + 7));
	}
	else if (this.isOmniweb) {
		this.versionMinor = parseFloat(ua.substring(ua.lastIndexOf('omniweb/') + 8));
	}
	else if (this.isOpera) {
		this.versionMinor = parseFloat(ua.substring(ua.indexOf('opera') + 6));
	}
	else if (this.isIcab) {
		this.versionMinor = parseFloat(ua.substring(ua.indexOf('icab') + 5));
	}
	this.versionMajor	= parseInt(this.versionMinor); 
	this.isDOM1		= (document.getElementById);
	this.isDOM2Event	= (document.addEventListener && document.removeEventListener);
	this.mode		= document.compatMode ? document.compatMode : 'BackCompat';
	this.isWin		= (ua.indexOf('win') != -1);
	this.isWin32		= (this.isWin && (ua.indexOf('95') != -1 || ua.indexOf('98') != -1 || ua.indexOf('nt') != -1 || ua.indexOf('win32') != -1 || ua.indexOf('32bit') != -1 || ua.indexOf('xp') != -1));
	this.isMac		= (ua.indexOf('mac') != -1);
	this.isUnix		= (ua.indexOf('unix') != -1 || ua.indexOf('sunos') != -1 || ua.indexOf('bsd') != -1 || ua.indexOf('x11') != -1)
	this.isLinux		= (ua.indexOf('linux') != -1);
	this.isNS4x		= (this.isNS && this.versionMajor == 4);
	this.isNS40x		= (this.isNS4x && this.versionMinor < 4.5);
	this.isNS47x		= (this.isNS4x && this.versionMinor >= 4.7);
	this.isNS4up		= (this.isNS && this.versionMinor >= 4);
	this.isNS6x		= (this.isNS && this.versionMajor == 6);
	this.isNS6up		= (this.isNS && this.versionMajor >= 6);
	this.isNS7x		= (this.isNS && this.versionMajor == 7);
	this.isNS7up		= (this.isNS && this.versionMajor >= 7);
	this.isIE4x		= (this.isIE && this.versionMajor == 4);
	this.isIE4up		= (this.isIE && this.versionMajor >= 4);
	this.isIE5x		= (this.isIE && this.versionMajor == 5);
	this.isIE55		= (this.isIE && this.versionMinor == 5.5);
	this.isIE5up		= (this.isIE && this.versionMajor >= 5);
	this.isIE6x		= (this.isIE && this.versionMajor == 6);
	this.isIE6up		= (this.isIE && this.versionMajor >= 6);
	this.isIE7x		= (this.isIE && this.versionMajor == 7);
	this.isIE7up		= (this.isIE && this.versionMajor >= 7);
	this.isIE4xMac		= (this.isIE4x && this.isMac);
}

/* Opacity Displayer, Version 1.0 - http://old.alistapart.com/stories/pngopacity/
 * Copyright Michael Lovitt, 6/2002.
 */
function opacity( strId , strPath , intWidth , intHeight , strClass , strAlt )
{	
	if ( document.pngAlpha )
	{
		document.write( '<div style="height:'+intHeight+'px;width:'+intWidth+'px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''+strPath+'.png\', sizingMethod=\'scale\')" id="'+strId+'" class="'+strClass+'"></div>' );
	}
	else if ( document.pngNormal )
	{
		document.write( '<img src="'+strPath+'.png" width="'+intWidth+'" height="'+intHeight+'" name="'+strId+'" border="0" class="'+strClass+'" alt="'+strAlt+'" />' );
	}
	else if ( document.layers )
	{
		return( '<img src="'+strPath+'.gif" width="'+intWidth+'" height="'+intHeight+'" name="'+strId+'" border="0" class="'+strClass+'" alt="'+strAlt+'" />' );
	}
	else
	{
		document.write( '<img src="'+strPath+'.gif" width="'+intWidth+'" height="'+intHeight+'" name="'+strId+'" border="0" class="'+strClass+'" alt="'+strAlt+'" />' );
	}
	return( '' );
}
function opacity_init()
{
	var browser = new browser_detect();
	document.pngAlpha = false;
	document.pngNormal = false;
	document.strExt = ".gif";

	if ( ( browser.isIE55 || browser.isIE6up ) && !browser.isIE7up && browser.isWin32 )
	{
		document.pngAlpha = true;
		document.strExt = ".png";
	}
	else if ( 
			( browser.isGecko ) || 
			( browser.isIE5up && browser.isMac ) || 
			( browser.isOpera && browser.isWin && browser.versionMajor >= 6 ) || 
			( browser.isOpera && browser.isUnix && browser.versionMajor >= 6 ) || 
			( browser.isOpera && browser.isMac && browser.versionMajor >= 5 ) || 
			( browser.isOmniweb && browser.versionMinor >= 3.1 ) || 
			( browser.isIcab && browser.versionMinor >= 1.9 ) || 
			( browser.isWebtv ) || 
			( browser.isDreamcast ) ||
			( browser.isIE7up )
		)
	{
		document.pngNormal = true;
		document.strExt = ".png";
	}
}

/* handler for Netscape Navigator clients that screw up the display
 * of CSS pages when reloaded
 */
function NN_reloadPage( init )
{
	if ( init == true ) with ( navigator )
	{
		if ( ( appName == "Netscape" ) && ( parseInt ( appVersion ) == 4 ) )
		{
			document.NN_pgW = innerWidth;
			document.NN_pgH = innerHeight;
			event_attach ( 'onresize' , NN_reloadPage );
		}
	}
	else if ( innerWidth != document.NN_pgW || innerHeight != document.NN_pgH )
	{
		location.reload();
	}
}

/* Min Width v1.1.3 by PVII-www.projectseven.com
 * http://www.projectseven.com/tutorials/css/minwidth/index.htm
 *
 * modified to support both min and max widths, for readability 
 * and the ability to limit application to IE only so CSS 
 * min-width property may be used by compliant browsers. 
 *
 * NOTE: horizontal spacing (margins, padding, borders) set in
 *       % values may cause IE to crash when using this script.
 *
 * ALSO: padding, margins, and borders on parents of the element
 *       you specify may result in IE getting suck in an infinite
 *       loop. Please be sure to check your layout before you 
 *       publish it!
 */
function set_min_width( obj_name , min_width , ieOnly, noIE7 )
{
	if ( ( typeof( ieOnly ) ).toLowerCase() == 'undefined' ) { ieOnly = true; }
	if ( ( typeof( noIE7  ) ).toLowerCase() == 'undefined' ) { noIE7 = true;  }
	set_width_limits( obj_name, min_width, '', ieOnly, noIE7 );
}
function set_width_limits( obj_name , min_width , max_width, ieOnly, noIE7 )
{
	ua = navigator.userAgent.toLowerCase(); 
	if ( ( typeof( ieOnly ) ).toLowerCase() == 'undefined' ) { ieOnly = true; }
	if ( ( typeof( noIE7  ) ).toLowerCase() == 'undefined' ) { noIE7 = true;  }
	if ( ieOnly == false || ( document.getElementById && ua.indexOf( "msie" ) > -1 && !window.opera 
		&& ( noIE7 == false || parseFloat( ua.substring( ua.indexOf( "msie " ) + 5 )) < 7 )))
	{
		document.set_width_obj_name = obj_name;
		document.min_width_size = min_width;
		document.max_width_size = max_width;
		document.resizing = false;
		event_attach( 'onload' , control_width );
		event_attach( 'onresize' , control_width );
	}
}
function control_width()
{
	var cw , minw , maxw, gs, pl , pr , ml , mr , br , bl , ad , theDiv = document.set_width_obj_name;
	var g = document.getElementById( theDiv );
	minw = parseInt( document.min_width_size );
	maxw = parseInt( document.max_width_size );
	if ( g && document.body && document.body.clientWidth )
	{
		gs = g.currentStyle;
		cw = parseInt( document.body.clientWidth );
		pl = parseInt( gs.paddingLeft );
		pr = parseInt( gs.paddingRight );
		ml = parseInt( gs.marginLeft );
		mr = parseInt( gs.marginRight );
		bl = parseInt( gs.borderLeftWidth );
		br = parseInt( gs.borderRightWidth );
		ml = ml ? ml : 0;
		mr = mr ? mr : 0;
		pl = pl ? pl : 0;
		pr = pr ? pr : 0;
		bl = bl ? bl : 0;
		br = br ? br : 0;
		ad = pl + pr + ml + mr + bl + br;
		if ( cw <= minw )
		{
			minw -= ad;
			g.style.width = minw + "px";
		}
		else if ( cw > maxw )
		{
			maxw -= ad;
			g.style.width = maxw + "px";
		}
		else
		{
			g.style.width = "auto";
		}
	}
}

/* Cookie API  v1.0.1
 * documentation: http://www.dithered.com/javascript/cookies/index.html
 * license: http://creativecommons.org/licenses/by/1.0/
 * code (mostly) by Chris Nott (chris[at]dithered[dot]com)
 */
function setCookie( name, value, expires, path, domain, secure )
{
	 var curCookie = name + "=" + escape(value) +
		((expires) ? "; expires=" + expires.toGMTString() : "") +
		((path) ? "; path=" + path : "") +
		((domain) ? "; domain=" + domain : "") +
		((secure) ? "; secure" : "");
	document.cookie = curCookie;
}
function getCookie( name )
{
	var dc = document.cookie;
	var prefix = name + "=";
	var begin = dc.indexOf( "; " + prefix );
	if ( begin == -1 )
	{
		begin = dc.indexOf(prefix);
		if (begin != 0) return null;
	}
	else
	{
		begin += 2;
	}
	var end = document.cookie.indexOf( ";", begin );
	if ( end == -1 )
	{
		end = dc.length;
	}
	return unescape(dc.substring(begin + prefix.length, end));
}
function deleteCookie( name, path, domain )
{
	var value = getCookie( name );
	if ( value != null )
	{
		document.cookie = name + "=" + 
			((path) ? "; path=" + path : "") +
			((domain) ? "; domain=" + domain : "") +
			"; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
	return value;
}

/* font size functions operate on the body element's
 * style and defines sizes in percentages. because
 * the default font size is set to 0 in the array,
 * the first value in the font_sizes array should
 * _ALWAYS_ be 100.
 *
 *	var font_sizes = new Array( 100, 110, 120 );
 *	var current_font_size = 0;
 *	event_attach( 'onload' , loadFontSize );
 */
function loadFontSize()
{
	current_font_size = parseInt( '0' + getCookie ( "font_size" ) );
	setFontSize ( current_font_size );
}
function setFontSize( size )
{
	if( size >= 0 && size < font_sizes.length )
	{
		current_font_size = size;
	}
	else if( ++current_font_size >= font_sizes.length )
	{
		current_font_size = 0;
	}
	if ( document.body )
	{
		document.body.style.fontSize = font_sizes[ current_font_size ] + '%';
		setCookie( "font_size" , current_font_size );
	}
}

/* standard trim function to remove leading and trailing 
 * whitespace from a given string
 */
function trim( str )
{
   return str.replace(/^\s*|\s*$/g,"");
}

/* stylesheets should be defined in the HTML via a LINK tag
 * and rel attribute set to "alternate stylesheet". the title
 * attribute is then set in the format of "title : group"
 * this function will disable all but the stylesheet specified
 * by title in the group specified by group.
 *
 * Based on code by Paul Sowden
 * http://www.alistapart.com/articles/alternate/
 *        
 */
function setActiveStyleSheet( title , group )
{
	var i, a, b, g, t;
	if ( !title || !group )
	{
		return;
	}
	for ( i = 0; ( a = document.getElementsByTagName( "link" )[ i ] ); i++ ) 
	{
		if ( a.getAttribute( "rel" ).indexOf( "style" ) != -1 && a.getAttribute( "title" ) )
		{
			b = ( a.getAttribute( "title" ) ).split( ":" );
			g = trim( b[ b.length - 1 ] );
			if ( g.toLowerCase() == group.toLowerCase() )
			{
				a.disabled = true;
				t = trim( ( a.getAttribute( "title" ) ).substring( 0, a.getAttribute( "title" ).length - b[ b.length - 1 ].length - 1 ) );
				if( t.toLowerCase() == title.toLowerCase() )
				{
					a.disabled = false;
				}
				setCookie( "style_" + g.toLowerCase() , title );
			}
		}
	}
}
function getPreferredStylesheet ( group )
{
	return ( getCookie ( "style_" + group ) );
}

/* Son of Suckerfish Dropdowns w/Mac support and IFRAME matting
 * This attaches an event to each LI element so when the mouseover event triggers,
 * the element's class is altered to include (and remove on mouseout) an extra class.
 * We can then use that class, in conjunction with stylesheets, to trigger drop-down
 * menus that are (mostly) CSS-based.
 *
 * The second variable passed to sfHover (noMat), if set to true, will disable
 * the IFRAME matting used to hide form elements that peek through if the menu
 * appears over one. Use this option when there's no chance the menu will pop over
 * a form field as this will remove the lag/performance issues related to using
 * the IFRAME matting.
 *
 * Original:
 *	http://www.htmldog.com/articles/suckerfish/dropdowns/
 * Fixes to work with IE/Mac:
 *	http://carroll.org.uk/sandbox/suckerfish/bones2.html
 * IFRAME matting to handle hover over form elements: 
 *	http://homepage.mac.com/igstudio/design/ulsmenus/vertical-uls-iframe-2.html
 */
function sfHover ( objID, noMat )
{
	var browser = new browser_detect();
	if ( browser.isIE5up && !browser.isIE7up )
	{
		var sfEls = document.getElementById( objID ).getElementsByTagName( "LI" );
		for (var i=0; i<sfEls.length; i++)
		{
			if ( !noMat && !browser.isMac && ( browser.isIE55 || browser.isIE6x ))
			{
				sfEls[i].onmouseover = function()
				{
					this.className += ( this.className.length > 0 ? " " : "" ) + "sfhover";
					var ieUL = this.getElementsByTagName( "UL" )[0];
					if ( ieUL )
					{
						var ieMat = document.createElement( "IFRAME" );
						ieMat.style.width = ieUL.offsetWidth + "px";
						ieMat.style.height = ieUL.offsetHeight + "px";
						ieMat.style.filter = "progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)";
						ieUL.insertBefore( ieMat, ieUL.firstChild );
						ieMat.style.zIndex = "-1";
					}
				}
				sfEls[i].onmouseout = function()
				{
					this.className = this.className.replace( new RegExp( "( ?|^)sfhover\\b" ), "" );
					var ieUL = this.getElementsByTagName('ul')[0];
					if (ieUL)
					{
						ieUL.removeChild( ieUL.firstChild );
					}
				}
			}
			else
			{
				sfEls[i].onmouseover = function()
				{
					this.className += ( this.className.length > 0 ? " " : "" ) + "sfhover";
				}
				sfEls[i].onmouseout = function()
				{
					this.className = this.className.replace( new RegExp( "( ?|^)sfhover\\b" ), "" );
				}
			}
		}
	}
}

/* AjaxContainer is a mechanism to help support communications between
 * the browser and the server without requiring the user reload the
 * page. This is still a tad experimental, but should work.
 *
 */
function AjaxContainer ()
{
	var xmlHttp = null;
	var response_wait = 0;
	var response_handler = null;

	var getXmlHttpObject = function () {
		try {
			// Firefox, Opera 8.0+, Safari, IE7+
			xmlHttp = new XMLHttpRequest(); // xmlHttp is now a XMLHttpRequest.
		} catch (e) {
			// Internet Explorer
			try {
				xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		if ( xmlHttp != null ) {
			xmlHttp.onreadystatechange = function () {
				if ( xmlHttp.readyState == 4 ) {
					try {
						if ( xmlHttp.status == 200 ) {
							if (( typeof( response_handler )).toLowerCase() == 'function' ) {
								response_handler( xmlHttp );
							}
						}
					} catch (e) {
						content = "Error connecting to server.";
					}
				}
			}
		}
	}

	this.getByURL = function ( handler, url, method, form ) {
		if (( typeof( url )).toLowerCase() == 'undefined' ) { return ( null ); }		
		if (( typeof( handler )).toLowerCase() != 'function' ) { handler = null; }		
		if (( typeof( method )).toLowerCase() == 'undefined' ) { method = "get"; }
		if (( typeof( form )).toLowerCase() == 'undefined' ) { form = null;  }
		if ( xmlHttp != null )
		{
			response_wait = 0;
			response_handler = handler;
			xmlHttp.open( method, url, true );
			xmlHttp.send( form );
		}
	}

	this.getContent = function () {
		if ( xmlHttp != null ) {
			return ( xmlHttp.responseText );
		}
	}

	getXmlHttpObject();
}

/*
// ///////////////////////////
// isdefined v1.0
// 
// Check if a javascript variable has been defined.
// 
// Author : Jehiah Czebotar
// Website: http://www.jehiah.com
// Usage  : alert(isdefined('myvar'));
// ///////////////////////////
*/
function isDefined ( variable )
{
    return ( typeof( window[ variable ] ) == "undefined" ) ?  false : true;
}

/* pause()
 * modified form of code taken from:
 * http://www.sean.co.uk/a/webdesign/javascriptdelay.shtm
 */
function pause ( m )
{
	var date = new Date();
	var curDate = null;
	do 
	{
		curDate = new Date();
	}
	while (( curDate - date ) < m );
}
