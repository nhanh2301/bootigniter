/*!
 * BootIgniter v1.0.2-rc1 (https://github.com/feryardiant/bootigniter)
 * Copyright 2014 Fery Wardiyanto
 * Licensed under OSL (https://github.com/feryardiant/bootigniter/blob/master/license.md)
 */
$(document).ready(function () {
  'use strict';

  var Baka = {
    popup: function (url, title, w, h) {
      var left = (screen.width / 2) - (w / 2),
          top = (screen.height / 2) - (h / 2) - 20;

      window.open(url, title, 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=' + w + ',height=' + h + ',top=' + top + ',left=' + left);
      this.target = title;
    }
  }

  $('#toolbar-btn-cetak').on('click', function (e) {
    var url = $(this).attr('href'),
      title = $(this).attr('title');

    new Baka.popup(url, title, 800, 600);

    e.preventDefault();
  })

  $('.nav-sidebar li').each(function () {
    var li = $(this)

    if (li.hasClass('active') === true) {
      li.find('.collapse').addClass('in');
      li.find('.menu-toggle').removeClass('collapsed');
    }
  })

});
