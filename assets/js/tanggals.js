$(document).ready(
         function() {
   		  $(function() {
               $("#tglterima").datepicker({
				  dateFormat:'yy-mm-dd',
                  showButtonPanel: true,
                  //minDate: new Date(),
                  showTime: true
               });
            });
   	   });