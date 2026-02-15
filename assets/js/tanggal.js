$(document).ready(
         function() {
   		  $(function() {
               $("#tgl").datepicker({
				  dateFormat:'yy-mm-dd',
                  showButtonPanel: true,
                  //minDate: new Date(),
                  showTime: true
               });
            });
   	   });