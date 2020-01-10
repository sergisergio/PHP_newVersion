// $(window).on('load', function () {
//     $('#alert').hide();
//     $('#alert2').hide();
//     $('#success').hide();
//     $('#success2').hide();
// });

// $(function() {
//     $('#alert').hide();
//     $('#alert2').hide();
//     $('#success').hide();
//     $('#success2').hide();
//     $('.close').on('click', function(e){
//         $('#success').hide();
//     });
//     $('#addCategory').on('submit', function( event ) {
//         event.preventDefault();
//         $('.comments').empty();
//         $('#alert').hide();
//         var postdata = $('#addCategory').serialize();
//         $.ajax({
//             url: "?c=adminCategory&t=addCategory",
//             type: "POST",
//             data: postdata,
//             dataType: 'json',
//             success: function(json) {
//                 if(json.isSuccess)
//                 {
//                     $('#success').show();
//                     $('.commentsSuccess').html(json.success);
//                 }
//                 else
//                 {
//                     $('#alert').show();
//                     $('.commentsAlert').html(json.error);
//                 }
//             },
//             error: function( response ) {
//                 console.log(response);
//             }
//         });
//     });

//     $('#deleteCategory').on('submit', function( event ) {
//         event.preventDefault();
//         $('.commentsSuccess2').empty();
//         $('.commentsAlert2').empty();
//         $('#alert2').hide();
//         var postdata = $('#deleteCategory').serialize();
//         $.ajax({
//             url: "?c=adminCategory&t=deleteCategory",
//             type: "POST",
//             data: postdata,
//             dataType: 'json',
//             success: function(json) {
//                 if(json.isSuccess)
//                 {
//                     $('#success2').show();
//                     $('.commentsSuccess2').html(json.success);
//                 }
//                 else
//                 {
//                     $('#alert2').show();
//                     $('.commentsAlert2').html(json.error);
//                 }
//             },
//             error: function( response ) {
//                 console.log(response);
//             }
//         });
//     });
// });
