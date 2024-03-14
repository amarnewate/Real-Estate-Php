$(document).ready(function(){
     $('#phone').on('input', function(){
         var phone = $(this).val();
         var country = $('#country').val();
         $.ajax({
             type: 'POST',
             url: 'validate_phone.php',
             data: { phone: phone, country: country },
             success: function(response){
                 $('#phone-error').html(response);
             }
         });
     });
 });
