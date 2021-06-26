jQuery(document).ready(function () {
   $('body').on('click', '#add-rout', function () {
       var last_li = document.querySelectorAll('#routs li:not([style])');
       var new_li = document.querySelector('#routs li[number="' + (parseInt(last_li[last_li.length - 1].getAttribute('number')) + 1) + '"]');
       if (new_li) {
           new_li.removeAttribute("style");
       } else {
           var routs = document.getElementById('routs');
           var last_li_for_copy = routs.querySelector('li:last-child');
           var last_number = parseInt(last_li_for_copy.getAttribute("number"));
           $('<li class="rout" number="' + (last_number + 1) + '">' + last_li_for_copy.innerHTML.replaceAll('name="Rout[' + last_number + ']', 'name="Rout[' + (last_number + 1) + ']')).insertAfter($(last_li_for_copy));
       }
   });
});