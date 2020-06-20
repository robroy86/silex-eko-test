$( document ).ready(function() {
    $('.btn-more').click(function(){
        id = $(this).data('id');
        btn = $(this);
        $.ajax({
            type: "POST",
            url: '/admin/details',
            data: {'id': id},
            success: function( data ) {
                if ($('#admin-przystanek-list').length)
                    $('#bus-stop-errors').remove();
                btn.parent().removeClass('active');
                if (btn.next().length > 0) {
                    $('.bus-stop-details').replaceWith(data.html);
                } else {
                    btn.after(data.html);
                }
                btn.hide();
                $('#img'+id).carousel();
            },
            error: function() {
                if ($('#admin-przystanek-list').length == 0)
                    $('#admin-przystanek-list').prepend('<p id="bus-stop-errors" class="alert alert-danger">Wystąpił błąd, poinformuj administratora serwisu.</p>');
            },
            dataType: 'json'
        });
    });

    $('li.list-group-item').on('click', '.btn-hide', function(){
        id = $(this).data('id');
        $(this).parent().remove();
        $('.btn-more[data-id="'+id+'"]').show();
    });
});