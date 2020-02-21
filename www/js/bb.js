/**
 * ББ-коды.
 *@license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */


var clientVer = parseInt(navigator.appVersion); // Get browser version
var ua = navigator.userAgent.toLowerCase();
var is_ie = (ua.indexOf('msie') != -1 && ua.indexOf('opera') == -1);
var is_safari = ua.indexOf('safari') != -1;
var is_gecko = (ua.indexOf('gecko') != -1 && !is_safari);
var is_win = ((ua.indexOf('win') != -1) || (ua.indexOf('16bit') != -1));
var baseHeight;

function edToolbar(obj,path)
{
	document.write("<ul id=\"bbcode\">");
	document.write("<li id=\"bt-bold\"><span><img onclick=\"bbcode('[b]','[/b]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Жирный\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-italic\"><span><img onclick=\"bbcode('[i]','[/i]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Наклонный\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-underline\"><span><img onclick=\"bbcode('[u]','[/u]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Подчеркнутый\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-strike\"><span><img onclick=\"bbcode('[s]','[/s]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Зачеркнутый\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-left\"><span><img onclick=\"bbcode('[left]','[/left]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Выравнивание по левому краю\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-center\"><span><img onclick=\"bbcode('[center]','[/center]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Выравнивание по центру\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-right\"><span><img onclick=\"bbcode('[right]','[/right]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Выравнивание по правому краю\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-hr\"><span><img onclick=\"bbcode('[hr]','','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Горизонтальный разделитель\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-list\"><span><img onclick=\"bbcode('[list=*][*]','[/*][/list]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Список\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-listnum\"><span><img onclick=\"bbcode('[list=1][*]','[/*][/list]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Список нумерованный\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-link\"><span><img onclick=\"tag('[url]','[/url]', tag_url('" + obj + "'),'" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Ссылка\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-email\"><span><img onclick=\"tag('[email]','[/email]', tag_email('" + obj + "'),'" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"E-mail\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-image\"><span><img onclick=\"tag('[img]','[/img]', tag_image('" + obj + "'),'" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Изображение\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-video\"><span><img onclick=\"tag('[video]','[/video]', tag_video('" + obj + "'),'" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Видео\" alt=\"\" /></span></li>");
	document.write("<li id=\"bt-code\"><span><img onclick=\"bbcode('[code]','[/code]','" + obj + "')\" src=\"" + path + "/images/b_bl.gif\" title=\"Код\" alt=\"\" /></span></li>");
	document.write("</ul>");
}
// Apply bbcodes. Code from phpBB
function bbcode(bbopen, bbclose, obj)
{
	theSelection = false;
	var textarea = document.getElementById(obj);
	textarea.focus();

	if ((clientVer >= 4) && is_ie && is_win)
	{
		theSelection = document.selection.createRange().text;
		if (theSelection)
		{
			// Add tags around selection
			document.selection.createRange().text = bbopen+theSelection+bbclose;
			textarea.focus();
			theSelection = '';
			return;
		}
	}
	else if (textarea.selectionEnd && (textarea.selectionEnd - textarea.selectionStart > 0))
	{
		mozWrap(textarea, bbopen, bbclose);
		textarea.focus();
		theSelection = '';
		return;
	}
	//The new position for the cursor after adding the bbcode
	var caret_pos = getCaretPosition(textarea).start;
	var new_pos = caret_pos+bbopen.length;
	// Open tag
	insert(bbopen+bbclose,false,null,obj);
	// Center the cursor when we don't have a selection
	if (!isNaN(textarea.selectionStart))
	{
		textarea.selectionStart = new_pos;
		textarea.selectionEnd = new_pos;
	}
	else if (document.selection)
	{
		var range = textarea.createTextRange();
		range.move("character", new_pos);
		range.select();
		storeCaret(textarea);
	}
	textarea.focus();
	return;
}
// Insert text at position. Code from phpBB
function insert(text, spaces, popup, obj)
{
	var textarea;

	if (!popup)
		textarea = document.getElementById(obj);
	else
		textarea = opener.document.getElementById(obj);
	if (spaces)
		text = ' '+text+' ';
	if (!isNaN(textarea.selectionStart))
	{
		var sel_start = textarea.selectionStart;
		var sel_end = textarea.selectionEnd;
		mozWrap(textarea, text, '')
		textarea.selectionStart = sel_start+text.length;
		textarea.selectionEnd = sel_end+text.length;
	}
	else if (textarea.createTextRange && textarea.caretPos)
	{
		if (baseHeight != textarea.caretPos.boundingHeight)
		{
			textarea.focus();
			storeCaret(textarea);
		}
		var caret_pos = textarea.caretPos;
		caret_pos.text = caret_pos.text.charAt(caret_pos.text.length - 1) == ' ' ? caret_pos.text+text+' ' : caret_pos.text+text;
	}
	else
		textarea.value = textarea.value+text;
	if (!popup)
		textarea.focus();
}
function mozWrap(txtarea, open, close)
{
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	var scrollTop = txtarea.scrollTop;

	if (selEnd == 1 || selEnd == 2)
		selEnd = selLength;

	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd)
	var s3 = (txtarea.value).substring(selEnd, selLength);

	txtarea.value = s1+open+s2+close+s3;
	txtarea.selectionStart = selEnd+open.length+close.length;
	txtarea.selectionEnd = txtarea.selectionStart;
	txtarea.focus();
	txtarea.scrollTop = scrollTop;
	return;
}
// Insert at Caret position.
function storeCaret(textEl)
{
	if (textEl.createTextRange)
		textEl.caretPos = document.selection.createRange().duplicate();
}
// Caret Position object.
function caretPosition()
{
	var start = null;
	var end = null;
}
// Get the caret position in an textarea.
function getCaretPosition(txtarea)
{
	var caretPos = new caretPosition();

	if(txtarea.selectionStart || txtarea.selectionStart == 0)
	{
		caretPos.start = txtarea.selectionStart;
		caretPos.end = txtarea.selectionEnd;
	}
	else if(document.selection)
	{
		var range = document.selection.createRange();
		var range_all = document.body.createTextRange();
		range_all.moveToElementText(txtarea);
		var sel_start;
		for (sel_start = 0; range_all.compareEndPoints('StartToStart', range) < 0; sel_start++)
			range_all.moveStart('character', 1);

		txtarea.sel_start = sel_start;
		caretPos.start = txtarea.sel_start;
		caretPos.end = txtarea.sel_start;
	}
	return caretPos;
}
function smile(code, popup, obj)
{
	return insert(code, true, popup, obj);
}
function smile_pop(desktopURL, alternateWidth, alternateHeight, noScrollbars)
{
	if ((alternateWidth && self.screen.availWidth * 0.8 < alternateWidth) || (alternateHeight && self.screen.availHeight * 0.8 < alternateHeight))
	{
		noScrollbars = false;
		alternateWidth = Math.min(alternateWidth, self.screen.availWidth * 0.8);
		alternateHeight = Math.min(alternateHeight, self.screen.availHeight * 0.8);
	}
	else
		noScrollbars = typeof(noScrollbars) != "undefined" && noScrollbars == true;

	window.open(desktopURL, 'requested_popup', 'toolbar=no,location=no,status=no,menubar=no,scrollbars='+(noScrollbars ? 'no' : 'yes')+',width='+(alternateWidth ? alternateWidth : 700)+',height='+(alternateHeight ? alternateHeight : 300)+',resizable=no');
	return false;
}
function visibility(id)
{
	var obj = document.getElementById(id);

	if (obj == null || typeof(obj) == "undefined")
		return;

	var current = obj.style.display;
	var change = {
		"none":{"display": "block"},
		"block":{"display": "none"}
	}
	obj.style.display = change[current]["display"];
	return;
}
function SelectedText(obj)
{
	var txt = '';
	var textarea = document.getElementById(obj);
	if (document.selection)
		txt = document.selection.createRange().text;
	else if (document.getSelection)
		txt = textarea.value.substring(textarea.selectionStart, textarea.selectionEnd);
	else if (window.getSelection)
		txt = window.getSelection().toString();
	else
		return txt;
	return txt;
}
function tag(bbopen, bbclose, tag, obj)
{
	var txt = SelectedText(obj);
	if (txt != '')
		bbcode(bbopen, bbclose, obj);
	else
		tag("","",txt,obj);
}
function tag_url(obj)
{
	var enterURL = prompt("Поместите ссылку веб-страницы", "http://");
	if (!enterURL)
	{
		alert("Ошибка! Нет ссылки");
		return false;
	}
	var enterTITLE = prompt("Введите название ссылки", "Название сайта");
	if (!enterTITLE || enterTITLE == "Название сайта")
		insert('[url='+enterURL+'][/url]',false,null,obj);
	else
		insert('[url='+enterURL+']'+enterTITLE+'[/url]',false,null,obj);
}
function tag_email(obj)
{
	var enter = prompt("Введите e-mail адрес", "");
	if (!enter)
	{
		alert("Нет E-mail'а");
		return false;
	}
	insert('[email]'+enter+'[/email]',false,null,obj);
}
function tag_image(obj)
{
	var image = prompt("Введите полный URL изображения", "http://");
	if (!image)
	{
		alert("Ошибка! Нет ссылки");
		return false;
	}
	var desc = prompt("Введите описание", "Описание");
	if (!desc || desc == "Описание")
		insert('[img]'+image+'[/img]',false,null,obj);
	else
		insert('[img='+desc+']'+image+'[/img]',false,null,obj);
}
function tag_video(obj)
{
	var enter = prompt("Поместите ссылку на видео", "http://");
	if (!enter)
	{
		alert("Ошибка! Нет ссылки");
		return;
	}
	insert('[video]'+enter+'[/video]',false,null,obj);
}
function tag_hide()
{
	var enter = prompt("Введите минимум сообщений для просмотра текста (0 — убрать от гостей)", "");
	if (!enter)
		bbcode('[hide]','[/hide]',obj);
	else
		bbcode('[hide='+enter+']','[/hide]',obj);
}
function add_handler(event, handler)
{
	if (document.addEventListener)
		document.addEventListener(event, handler, false);
	else if (document.attachEvent)
		document.attachEvent('on'+event, handler);
	else
		return false;

	return true;
}
function key_handler(e)
{
	e = e || window.event;
	var key = e.keyCode || e.which;

	if (e.ctrlKey && (is_gecko && key == 115 || !is_gecko && key == 83))
	{
		if (e.preventDefault)
			e.preventDefault();
		e.returnValue = false;
		document.post.preview.click()
		return false;
	}
	if (e.ctrlKey && (key == 13 || key == 10))
	{
		if (e.preventDefault)
			e.preventDefault();
		e.returnValue = false;
		document.post.submit.click()
		return false;
	}
}
var result = is_ie || is_safari ? add_handler("keydown", key_handler) : add_handler("keypress", key_handler);
if (result)
{
	setTimeout("document.forms.post.submit.title='Ctrl + Enter'", 500);
	setTimeout("document.forms.post.preview.title='Ctrl + S'", 500);
}

