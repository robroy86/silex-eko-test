$( document ).ready(function() {
    $('.btn-more').click(function(){
        id = $(this).data('id');
        alert(id);
    });
});