define(['jquery'], function($) {
 
    return {
        init: function() {
            
            $('.checkoption').change(function(){
               
                if ($(this).is(":checked"))
                {
                  alert("checked");
                }
                else{
                    
                }
                
            })        
           
        }
    }
   
});
