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

});
