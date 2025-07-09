/*
 * Facebox (for jQuery)
 * version: 1.2 (05/05/2008)
 * @requires jQuery v1.2 or later
 *
 * Examples at http://famspam.com/facebox/
 *
 * Licensed under the MIT:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2007, 2008 Chris Wanstrath [ chris@ozmm.org ]
 *
 * Usage:
 *  
 *  jQuery(document).ready(function() {
 *    jQuery('a[rel*=facebox]').facebox() 
 *  })
 *
 *  <a href="#terms" rel="facebox">Terms</a>
 *    Loads the #terms div in the box
 *
 *  <a href="terms.html" rel="facebox">Terms</a>
 *    Loads the terms.html page in the box
 *
 *  <a href="terms.png" rel="facebox">Terms</a>
 *    Loads the terms.png image in the box
 *
 *
 *  You can also use it programmatically:
 * 
 *    jQuery.facebox('some html')
 *
 *  The above will open a facebox with "some html" as the content.
 *    
 *    jQuery.facebox(function($) { 
 *      $.get('blah.html', function(data) { $.facebox(data) })
 *    })
 *
 *  The above will show a loading screen before the passed function is called,
 *  allowing for a better ajaxy experience.
 *
 *  The facebox function can also display an ajax page or image:
 *  
 *    jQuery.facebox({ ajax: 'remote.html' })
 *    jQuery.facebox({ image: 'dude.jpg' })
 *
 *  Want to close the facebox?  Trigger the 'close.facebox' document event:
 *
 *    jQuery(document).trigger('close.facebox')
 *
 *  Facebox also has a bunch of other hooks:
 *
 *    loading.facebox
 *    beforeReveal.facebox
 *    reveal.facebox (aliased as 'afterReveal.facebox')
 *    init.facebox
 *
 *  Simply bind a function to any of these hooks:
 *
 *   $(document).bind('reveal.facebox', function() { ...stuff to do after the facebox and contents are revealed... })
 *
 */
(function($) {
  $.facebox = function(data, klass) {

    $.facebox.loading()

    if (data.ajax) fillFaceboxFromAjax(data.ajax)
    else if (data.image) fillFaceboxFromImage(data.image)
    else if (data.div) fillFaceboxFromHref(data.div)
    else if ($.isFunction(data)) data.call($)
    else $.facebox.reveal(data, klass)
  }

  /*
   * Public, $.facebox methods
   */

  $.extend($.facebox, {
    settings: {
	  title				:false,	
	  message: 'Message not specified.',
	  ajaxErrorMessage: '<strong>Error 404</strong><br>The requested file could not be found.',
	  width: 370,
	  height: 'auto',	  
	  submitValue: false,
	  submitFunction: false,
	  submitFocus: false,
	  cancelValue: 'Close',
	  cancelFunction: false,	  
      opacity      : 0,
      overlay      : true,
      loadingImage : 'facebox/images/loading.gif',
      closeImage   : 'facebox/images/closelabel.gif',
      imageTypes   : [ 'png', 'jpg', 'jpeg', 'gif' ],
      faceboxHtml  : '\
    <div id="facebox" style="display:none;"> \
      <div class="popup"> \
        <table> \
          <tbody> \
            <tr> \
              <td class="tl"/><td class="b"/><td class="tr"/> \
            </tr> \
            <tr> \
              <td class="b"/> \
              <td class="body"> \
                <div class="content"> \
                </div> \
                <div class="footer"> \
                </div> \
              </td> \
              <td class="b"/> \
            </tr> \
            <tr> \
              <td class="bl"/><td class="b"/><td class="br"/> \
            </tr> \
          </tbody> \
        </table> \
      </div> \
    </div>'
    },

    loading: function() {
      init()
      if ($('#facebox .loading').length == 1) return true
      showOverlay()

      $('#facebox .content').empty()
      $('#facebox .body').children().hide().end().
        append('<div class="loading"><img src="'+$.facebox.settings.loadingImage+'"/></div>')

      $('#facebox').css({
        top:	getPageScroll()[1] + (getPageHeight() / 10),
        left:	385.5,
      }).show()		

      $(document).bind('keydown.facebox', function(e) {
        if (e.keyCode == 27) $.facebox.close()
        return true
      })
      $(document).trigger('loading.facebox')  
    },

    reveal: function(data, klass) {
      $(document).trigger('beforeReveal.facebox')
      if (klass) $('#facebox .content').addClass(klass)
      $('#facebox .content').append(data)
      $('#facebox .loading').remove()
      $('#facebox .body').children().fadeIn('normal')
      $('#facebox').css('left', $(window).width() / 2 - ($('#facebox table').width() / 2))
      $(document).trigger('reveal.facebox').trigger('afterReveal.facebox')
    },
    close: function() {
      $(document).trigger('close.facebox')
      return false
    }
  })

  /*
   * Public, $.fn methods
   */

  var settings_arr=[];
  $.fn.facebox = function(settings) {
//	settings_arr[oneTime]={};
//	$.extend(settings_arr[oneTime],settings);	  
//    init(settings)
	

//	alert($.facebox.settings.submitValue);
    function clickHandler() {
	//	 alert($.facebox.settings.submitValue);
		$.facebox.settings.inited=false;
	//	alert(settings.title);
		init(settings)
//		alert(settings.title);
	//	alert($.facebox.settings.submitValue);
      $.facebox.loading(true)
		
      // support for rel="facebox.inline_popup" syntax, to add a class
      // also supports deprecated "facebox[.inline_popup]" syntax
      var klass = this.rel.match(/facebox\[?\.(\w+)\]?/)
      if (klass) klass = klass[1]

      fillFaceboxFromHref(this.href, klass)
      return false
    }
    return this.click(clickHandler)
  }

  /*
   * Private methods
   */

  // called one time to setup facebox on this page
  var oneTime=0;
  function init(settings) {
	// alert(settings_arr[0]);
	// alert($.facebox.settings.submitValue);
//	 if(oneTime<=1){
    if ($.facebox.settings.inited) return true
    else $.facebox.settings.inited = true

    $(document).trigger('init.facebox')
    makeCompatible()

    var imageTypes = $.facebox.settings.imageTypes.join('|')
    $.facebox.settings.imageTypesRegexp = new RegExp('\.' + imageTypes + '$', 'i')

	if(settings.submitValue===undefined)(settings.submitValue=false)
	if(settings.submitFunction===undefined)(settings.submitFunction=false)
	if(settings.submitFocus===undefined)(settings.submitFocus=false)
	if(settings.cancelFunction===undefined)(settings.cancelFunction=false)

	//alert(settings.submitValue);
//	 alert(settings.title);
    if (settings) $.extend($.facebox.settings,settings)
	if($('#facebox').length==0){
    	$('body').append($.facebox.settings.faceboxHtml)
	}
//	alert($.facebox.settings.title+" --- "+settings.title);
    var preload = [ new Image(), new Image() ]
    preload[0].src = $.facebox.settings.closeImage
    preload[1].src = $.facebox.settings.loadingImage

    $('#facebox').find('.b:first, .bl, .br, .tl, .tr').each(function() {
      preload.push(new Image())
      preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
    })

//	$.facebox.settings.width+=($.facebox.settings.width+10);
//	$("#facebox").width($.facebox.settings.width);
//	alert($.facebox.settings.submitValue);
	$('#facebox .footer').html("<input type=\"button\" value=\""+$.facebox.settings.cancelValue+"\"/>");
	if($.facebox.settings.cancelFunction){
		$("#facebox .footer input[value=\""+$.facebox.settings.cancelValue+"\"]").click($.facebox.settings.cancelFunction);
	}else{
		$("#facebox .footer input[value=\""+$.facebox.settings.cancelValue+"\"]").click($.facebox.close);
	}	
	
	if($.facebox.settings.submitValue){
		$('#facebox .footer').prepend("<input class=\"faceboxSubmit\" type=\"button\" value=\""+$.facebox.settings.submitValue+"\"/>");
		if($.facebox.settings.submitFunction){
			$("#facebox .footer input[value=\""+$.facebox.settings.submitValue+"\"].faceboxSubmit").click($.facebox.settings.submitFunction);
		}else{
			$("#facebox .footer input[value=\""+$.facebox.settings.submitValue+"\"].faceboxSubmit").click($.facebox.close);
		}				
	}

    $('#facebox .close_image').attr('src', $.facebox.settings.closeImage)
	if(settings.title!==undefined){
		if($('#facebox .faceboxTitle').length == 0){ 
			$('#facebox .body').prepend("	<h2 class=\"faceboxTitle\">"+$.facebox.settings.title+"</h2>");
		}else{
			$('#facebox .faceboxTitle').html($.facebox.settings.title);
		}
	}else{
		$('#facebox .faceboxTitle').remove();
	}
//	alert(settings.width);
	if(settings.width!==undefined){
		$('#facebox .content').width(settings.width);
	}else{
		$('#facebox .content').width("");
	}
	// $.facebox.close
//	 oneTime++;
//	 }
  }
  
  // getPageScroll() by quirksmode.com
  function getPageScroll() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
      yScroll = self.pageYOffset;
      xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
      yScroll = document.documentElement.scrollTop;
      xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
      yScroll = document.body.scrollTop;
      xScroll = document.body.scrollLeft;	
    }
    return new Array(xScroll,yScroll) 
  }

  // Adapted from getPageSize() by quirksmode.com
  function getPageHeight() {
    var windowHeight
    if (self.innerHeight) {	// all except Explorer
      windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
      windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
      windowHeight = document.body.clientHeight;
    }	
    return windowHeight
  }

  // Backwards compatibility
  function makeCompatible() {
    var $s = $.facebox.settings

    $s.loadingImage = $s.loading_image || $s.loadingImage
    $s.closeImage = $s.close_image || $s.closeImage
    $s.imageTypes = $s.image_types || $s.imageTypes
    $s.faceboxHtml = $s.facebox_html || $s.faceboxHtml
  }

  // Figures out what you want to display and displays it
  // formats are:
  //     div: #id
  //   image: blah.extension
  //    ajax: anything else

  function fillFaceboxFromHref(href, klass) {

    // user set
    if (href.match(/#message/)) {
      var url    = window.location.href.split('#')[0]
      var target = href.replace(url,'')
	  $.facebox.reveal($.facebox.settings.message, klass)
	  	  
    // div
	}else if (href.match(/#/)) {
      var url    = window.location.href.split('#')[0]
      var target = href.replace(url,'')
      $.facebox.reveal($(target).clone().show(), klass)

    // image
    } else if (href.match($.facebox.settings.imageTypesRegexp)) {
      fillFaceboxFromImage(href, klass)
    // ajax
    } else {
      fillFaceboxFromAjax(href, klass)
    }
	
  }

  function fillFaceboxFromImage(href, klass) {
    var image = new Image()
    image.onload = function() {
      $.facebox.reveal('<div class="image"><img src="' + image.src + '" /></div>', klass)
    }
    image.src = href
  }

  function fillFaceboxFromAjax(href, klass) {
    $.ajax({
		url:href,
		cache:false,
		async:false,
		success:function(data){
			$.facebox.reveal(data, klass)
		},
		error:function(){
			$.facebox.reveal($.facebox.settings.ajaxErrorMessage, klass)
		}
	});
  }

  function skipOverlay() {
    return $.facebox.settings.overlay == false || $.facebox.settings.opacity === null 
  }

  function showOverlay() {
    if (skipOverlay()) return

    if ($('facebox_overlay').length == 0) 
      $("body").append('<div id="facebox_overlay" class="facebox_hide"></div>')
	
    $('#facebox_overlay').hide().addClass("facebox_overlayBG")
      .css('opacity', $.facebox.settings.opacity)
      .click(function() { $(document).trigger('close.facebox') })
      .fadeIn(200,function(){
		setTimeout(function(){
		  if($.facebox.settings.submitFocus){
				$(".faceboxSubmit")[0].focus();
		  }
		},1000)  
	  })
    return false
  }

  function hideOverlay() {
    if (skipOverlay()) return

    $('#facebox_overlay').fadeOut(200, function(){
      $("#facebox_overlay").removeClass("facebox_overlayBG")
      $("#facebox_overlay").addClass("facebox_hide") 
      $("#facebox_overlay").remove()
    })
    
    return false
  }

  /*
   * Bindings
   */

  $(document).bind('close.facebox', function() {
    $(document).unbind('keydown.facebox')
    $('#facebox').fadeOut(function() {
      $('#facebox .content').removeClass().addClass('content')
      hideOverlay()
      $('#facebox .loading').remove()	  
    })
  })

})(jQuery);
