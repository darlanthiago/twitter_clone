setTimeout(alert => {
    var alert = document.getElementsByClassName('alert');
    $(alert).alert('close');
}, 3000);


$(function() {
    $('[data-toggle="tooltip"]').tooltip()
});