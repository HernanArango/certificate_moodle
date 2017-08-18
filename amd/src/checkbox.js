define(['jquery'], function($) {
 
    return {
        init: function(id) {
            
            $('.checkoption').change(function(){
                var id = $(this).attr('name');

                alert(id);
                if ($(this).is(":checked"))
                {
                  alert("checked");
                }
                else{
                    
                }

                $.post( "assign_permission.php",{"userid": id}, function( data ) {
                  $( ".result" ).html( data );
                });
                
            });        

        }

    }


});
