$(function() {

    $(".p-viewer").on('click', function() {
      var state = $('.fa').attr('class');
      if (state == 'fa fa-eye') {
        $("#password").prop('type','text');
        $(".fa").attr("class","fa fa-eye-slash");
      } else {
        $("#password").prop('type','password');
        $(".fa").attr("class","fa fa-eye");
      }
    });
    $('#loginForm').on('submit', function( event ) {
        event.preventDefault();
        $('.comments').empty();
        $('#alert').hide();
        var postdata = $('#loginForm').serialize();
        $.ajax({
            url: "?c=login",
            type: "POST",
            data: postdata,
            dataType: 'json',
            success: function(json) {
                if(json.isSuccess)
                {
                  if(json.role === "admin") location.href = "?c=adminDashboard";
                  if(json.role === "user") location.href = "?c=blog&v=view1&page=1";
                }
                else
                {
                    $('#alert').show();
                    $('.comments').html(json.error);
                }
            },
            error: function( response ) {
                console.log(response);
            }
        });
    });
});
