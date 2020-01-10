$(function() {

  $('.close').on('click', function (e) {
    e.preventDefault();
    $('#alerte').removeClass('show');
    $('#alerte').hide();
});

  $('#contactForm').on('submit', function(event) {

        event.preventDefault();
        var postdata = $('#contactForm').serialize();

        //$('#alert').addClass("alert-danger");
        //$('#alert').removeClass("fade");
        $.ajax({
            url: "?c=homeContact&t=send",
            type: "POST",
            data: postdata,
            dataType: 'json',
            success: function(json) {
                if(json.isSuccess)
                {
                    $('#alerte').addClass("alert-success");
                    $('#alerte').removeClass("fade");
                    $('#alerte').css("background-color", "green");
                    $('#alerte').css("border-color", "green");
                    $('#alerte').show();
                    $('.comments').html(json.success);
                }
                else
                {
                    $('#alerte').addClass("alert-danger");
                    $('#alerte').removeClass("fade");
                    $('#alerte').css("background-color", "red");
                    $('#alerte').css("border-color", "red");
                    $('#alerte').show();
                    $('.comments').html(json.error);
                }
            },
            error: function( response ) {
                console.log(response);
            }
        });

  });
});
