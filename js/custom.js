/* 

1. Add your custom JavaScript code below
2. Place the this code in your template:

  

*/
$('.modal-dialog').parent().on('show.bs.modal', function(e){ $(e.relatedTarget.attributes['data-target'].value).appendTo('body'); });
$('.pagination').addClass('pagination-rounded');