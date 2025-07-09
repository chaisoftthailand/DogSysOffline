    <script type="text/javascript">
      $('#sl').click(function(){
      	$('#tl').loadingBtn();
      	$('#tb').loadingBtn({ text : "Signing In"});
      });
      
      $('#el').click(function(){
      	$('#tl').loadingBtnComplete();
      	$('#tb').loadingBtnComplete({ html : "Sign In"});
      });
      
      $('#StartDate').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#EndDate').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	

      $('#RECEIVEDATE9').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });      
      $('#xRECEIVEDATE9').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE9').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	

      $('#xRECEIVEDATE1').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE1').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	

      $('#xRECEIVEDATE2').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE2').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	
      
      $('#demoSelect').select2();
    </script>   