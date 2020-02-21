elRTE.prototype.options.buttons.typograf = 'Typograf Oransky&Makarov (off-line)';
elRTE.prototype.options.buttons.typograf2 = 'Typograf Jare (off-line)';
elRTE.prototype.options.buttons.typograf3 = 'Typograf ArtLebedev';
elRTE.prototype.options.buttons.typograf4 = 'Typograf.ru';
elRTE.prototype.options.panels.mypanel = ['typograf','typograf2','typograf3','typograf4'];
elRTE.prototype.save = function() {
  inp = document.getElementById('action');
  if(inp){inp.value='onlysave';}
  this.beforeSave();
  this.editor.parents('form').submit();
}  

$(function() {
 elRTE.prototype.ui.prototype.buttons.typograf = function(rte, name) {
     var self=this;
     this.constructor.prototype.constructor.call(this, rte, name);
     
     this.addtxt = function(data){
         this.rte.history.add();
         this.rte.doc.body.innerHTML=data;
         this.rte.window.focus();
         this.rte.ui.update();
     }
 
     this.command = function() {
         var indata=this.rte.doc.body.innerHTML;
   		 $.post('elrte/typograf/typo_off1.php',{text:indata},function(data){
   			self.addtxt(data);
   		 });
     }
 
     this.update = function() {
         this.domElem.removeClass('disabled');
     }
 }

 elRTE.prototype.ui.prototype.buttons.typograf2 = function(rte, name) {
     this.constructor.prototype.constructor.call(this, rte, name);
     var self=this;
     
     this.addtxt = function(data){
         this.rte.history.add();
         this.rte.doc.body.innerHTML=data;
         this.rte.window.focus();
         this.rte.ui.update();
     }
 
     this.command = function() {
    	 var indata=this.rte.doc.body.innerHTML;
   		 $.post('elrte/typograf/typo_off2.php',{text:indata},function(data){
   			self.addtxt(data);
   		 });
     }
 
     this.update = function() {
         this.domElem.removeClass('disabled');
     }
 }

 elRTE.prototype.ui.prototype.buttons.typograf3 = function(rte, name) {
     this.constructor.prototype.constructor.call(this, rte, name);
     var self=this;
     
     this.addtxt = function(data){
         this.rte.history.add();
         this.rte.doc.body.innerHTML=data;
         this.rte.window.focus();
         this.rte.ui.update();
     }
 
     this.command = function() {
    	 var indata=this.rte.doc.body.innerHTML;
   		 $.post('elrte/typograf/typo_al.php',{text:indata},function(data){
   			self.addtxt(data);
   		 });
     }
 
     this.update = function() {
         this.domElem.removeClass('disabled');
     }
 }

 elRTE.prototype.ui.prototype.buttons.typograf4 = function(rte, name) {
     this.constructor.prototype.constructor.call(this, rte, name);
     var self=this;
     
     this.addtxt = function(data){
         this.rte.history.add();
         this.rte.doc.body.innerHTML=data;
         this.rte.window.focus();
         this.rte.ui.update();
     }
 
     this.command = function() {
    	 var indata=this.rte.doc.body.innerHTML;
   		 $.post('elrte/typograf/typo_ru.php',{text:indata},function(data){
   			self.addtxt(data);
   		 });
     }
 
     this.update = function() {
         this.domElem.removeClass('disabled');
     }
 }

    $("#pubdate").datepicker({
		showWeek: true,
		showButtonPanel: true
    });
});

$().ready(function() {
	var optsh = {
		cssClass : 'el-rte',
		lang     : 'ru',
		height   : 130,
        toolbars :  {
             mymaxi : ['save', 'copypaste', 'undoredo', 'elfinder', 'style', 'alignment', 'colors', 'format', 'indent', 'lists', 'links', 'elements', 'mypanel', 'media', 'tables', 'fullscreen']
        },		
        toolbar  : 'mymaxi',
		cssfiles : ['elrte/css/elrte-inner.css'],
		doctype  : '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		absoluteURLs : false,
		fmOpen : function(callback) {
			$('<div id="myfinder" />').elfinder({
				url : 'elrte/connectors/php/connector.php',
				lang : 'ru',
				cssClass : 'el-finder',
				dialog : { width : 900, modal : true, title : 'Файловый менеджер' },
				closeOnEditorCallback : true,
				editorCallback : callback
			})
		}
	}
	$('#editorh').elrte(optsh);
	$('#editorm').elrte(optsh);

	var opts = {
		cssClass : 'el-rte',
		lang     : 'ru',
		height   : 500,
        toolbars :  {
             mymaxi : ['save', 'copypaste', 'undoredo', 'elfinder', 'style', 'alignment', 'colors', 'format', 'indent', 'lists', 'links', 'elements', 'mypanel', 'media', 'tables', 'fullscreen']
        },		
        toolbar  : 'mymaxi',
		cssfiles : ['elrte/css/elrte-inner.css'],
		absoluteURLs : false,
		doctype  : '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		absoluteURLs : false,
		fmOpen : function(callback) {
			$('<div id="myfinder" />').elfinder({
				url : 'elrte/connectors/php/connector.php',
				lang : 'ru',
				cssClass : 'el-finder',
				dialog : { width : 900, modal : true, title : 'Файловый менеджер' },
				closeOnEditorCallback : true,
				editorCallback : callback
			})
		}

	}
	$('#editor').elrte(opts);
})
