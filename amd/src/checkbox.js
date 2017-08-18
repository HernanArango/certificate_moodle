define(['jquery'], function($) {
 
    return {
        init: function(id) {
            
            $('.checkoption').change(function(){
                var courseid = $(this).attr('courseid');
                var userid = $(this).attr('userid');

                
                if ($(this).is(":checked"))
                {
                  alert("checked");
                }
                else{
                    
                }

                $.post( "assign_permission.php",{"userid": userid, "courseid":courseid}, function( data ) {
                  $( ".result" ).html( data );
                });
                
            });        

        }

    }


});
