$(function() {
    $('#inscription').on('hidden.bs.modal', function (e) {
        $('#alert2').hide();
    });
    $("#inscription").on('show.bs.modal', function () {
        $('#alert2').hide();
    });
    $('.close').on('click', function(e){
        $('#alert2').hide();
    });
    $("#lien_connexion").on('click', function(e){
        $('#inscription').modal('hide');
    });
    $(".p-viewer-password").on('click', function() {
      var state = $('.fa').attr('class');
      if (state == 'fa fa-eye') {
        $("#pass").prop('type','text');
        $(".fa").attr("class","fa fa-eye-slash");
      } else {
        $("#pass").prop('type','password');
        $(".fa").attr("class","fa fa-eye");
      }
    });
    $(".p-viewer-password2").on('click', function() {
      var state = $('.fa').attr('class');
      if (state == 'fa fa-eye') {
        $("#pass2").prop('type','text');
        $(".fa").attr("class","fa fa-eye-slash");
      } else {
        $("#pass2").prop('type','password');
        $(".fa").attr("class","fa fa-eye");
      }
    });
    $('#signupForm').on('submit', function( event ) {
        event.preventDefault();
        $('.comments').empty();
        $('#alert2').hide();
        var postdata = $('#signupForm').serialize();
        $.ajax({
            url: "?c=login&t=registration",
            type: "POST",
            data: postdata,
            dataType: 'json',
            success: function(json) {
                if(json.isSuccess)
                {
                  $('#inscription').modal('hide');
                  $('#inscription').on('hidden.bs.modal', function (e) {
                    $('#connexion').modal('show');
                    $('#success').show();
                    $('.comments').html('Vous avez reçu un lien de confirmation !');

                    $('#connexion').on('hidden.bs.modal', function (e) {
                        $('#success').hide();
                    });
                    $("#connexion").on('show.bs.modal', function () {
                        $('#success').hide();
                    });
                    $('.close').on('click', function(e){
                        $('#success').hide();
                    });
                  });
                }
                else
                {
                    $('#alert2').show();
                    $('.comments').html(json.error);
                }
            },
            error: function( response ) {
                console.log(response);
            }
        });
    });
});
