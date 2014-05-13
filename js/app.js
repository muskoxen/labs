var app = { 
	url: $('base').attr('href'),
	rpp: 20
}

$(function()
{

	$("a[rel=tooltip], button[rel=tooltip]").tooltip();

	$("a.menu-popover, button.menu-popover").mouseover(function(){
		$(this).popover('show');
	});
	$("a.menu-popover, button.menu-popover").mouseleave(function(){
		$(this).popover('hide');
	});

	/* Navegación */
	/*var url, elementos, $menu;
	url = window.location.toString().replace($('base').attr('href'), '');
	$menu	= $(".navbar-collapse ul").find('a[href="'+url+'"]:first');
	elementos = $menu.parents().closest('li').find('a:first');
	$.each(elementos, function(i, el){
		((elementos.length-1) === i) ? $('.breadcrumb').append('<li class="active">'+$(el).text()+'</li>') : $('.breadcrumb').append('<li><a>'+$(el).text()+'</a><span class="divider"></span></li>') ;
		$(el).parent().addClass('active');
	});*/
	
	$(document).ajaxStart(function(object)
	{
		if (object.target.activeElement.className != 'tmp ui-autocomplete-input ui-autocomplete-loading')  $("#cargando").fadeTo("fast", 1);
		$('body').css('cursor','progress');
	});
	$(document).ajaxStop(function(object)
	{ 
		if (object.target.activeElement.className != 'tmp ui-autocomplete-input ui-autocomplete-loading')  setTimeout(function(){ $("#cargando").fadeTo("fast", 0); },300);
		$('body').css('cursor','default');
	});
	$(document).ajaxError(function(object){
		
	});
	
});

$(document).ready(function(){
	$('.opcion').tooltip();

	$('#menu-seguridad li a').click(function(){
		var elemento = this;
		$('#opciones-seguridad li.dropdown').addClass('active');
		$('#menu-seguridad li').removeClass('active');
		$(elemento).parent().addClass('active');
		$.post(app.url + $(elemento).attr('data-link'), function(data){
			$('#contenedor-principal').html(data);
			$('div h3#titulo-seguridad').html('Control de ' + $(elemento).attr('data-titulo'));
			$('#opciones-seguridad li.dropdown').removeClass('open');
		});
		return false;
	});

});

$.extend({
	confirmar: function(msg, callback){

		$('#confirmacion .modal-body span').html(msg);
		var $dialogo = $("#confirmacion");
		$('#confirmacion button').off();
		$dialogo.modal('show');
		
		$('#confirmacion button[type="submit"]').click(function(){
			$dialogo.modal('hide');
			if (callback.aceptar && typeof(callback.aceptar) === 'function') {
				callback.aceptar();
			}
		}).focus();

		$('#confirmacion button[type="button"]').click(function(){
			$dialogo.modal('hide');
			if (callback.cancelar && typeof(callback.cancelar) === 'function') {
				callback.cancelar();
			} 
		});
		
		$('#confirmacion').off().on('shown', function(){ $('#confirmacion button[type=submit]').focus(); });
	},
	noticia: function(msg, type){
		$('#notificacion').notify({
			message: { text: msg  },
			type: type
		}).show();
	}
});


/* Inicialización en español para la extensión 'UI date picker' para jQuery. */
/* Traducido por Vester (xvester@gmail.com). */
jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});

/**
* bootstrap-notify.js v1.0
* --
* Copyright 2012 Goodybag, Inc.
* --
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

(function ($) {
var Notification = function (element, options) {
// Element collection
this.$element = $(element);
this.$note    = $('<div class="alert"></div>');
this.options  = $.extend(true, {}, $.fn.notify.defaults, options);

// Setup from options
if(this.options.transition)
if(this.options.transition == 'fade')
this.$note.addClass('in').addClass(this.options.transition);
else this.$note.addClass(this.options.transition);
else this.$note.addClass('fade').addClass('in');

if(this.options.type)
this.$note.addClass('alert-' + this.options.type);
else this.$note.addClass('alert-success');

if(!this.options.message && this.$element.data("message") !== '') // dom text
this.$note.html(this.$element.data("message"));
else 
if(typeof this.options.message === 'object')
if(this.options.message.html)
this.$note.html(this.options.message.html);
else if(this.options.message.text)
this.$note.text(this.options.message.text);
else 
this.$note.html(this.options.message);

if(this.options.closable)
var link = $('<a class="close pull-right">&times;</a>');
$(link).on('click', $.proxy(onClose, this));
this.$note.prepend(link);

return this;
};

onClose = function() {
this.options.onClose();
$(this.$note).remove();
this.options.onClosed();
};

Notification.prototype.show = function () {
if(this.options.fadeOut.enabled)
this.$note.delay(this.options.fadeOut.delay || 3000).fadeOut('slow', $.proxy(onClose, this));

this.$element.append(this.$note);
this.$note.alert();
};

Notification.prototype.hide = function () {
if(this.options.fadeOut.enabled)
this.$note.delay(this.options.fadeOut.delay || 3000).fadeOut('slow', $.proxy(onClose, this));
else onClose.call(this);
};

$.fn.notify = function (options) {
return new Notification(this, options);
};

$.fn.notify.defaults = {
type: 'success',
closable: true,
transition: 'fade',
fadeOut: {
enabled: true,
delay: 3000
},
message: null,
onClose: function () {},
onClosed: function () {}
}
})(window.jQuery);