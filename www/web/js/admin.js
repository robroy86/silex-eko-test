$( document ).ready(function() {
    $('.btn-more').click(function(){
        id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: '/reviewe/reviewed',
            data: {'id': id},
            success: function( data ) {
                console.log(data);
                alert('ready');
            },
            dataType: 'json'
        });
        //$(this)
    });
});