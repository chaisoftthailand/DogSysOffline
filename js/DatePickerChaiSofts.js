
      $('#sl').click(function(){
      	$('#tl').loadingBtn();
      	$('#tb').loadingBtn({ text : "Signing In"});
      });
      
      $('#el').click(function(){
      	$('#tl').loadingBtnComplete();
      	$('#tb').loadingBtnComplete({ html : "Sign In"});
      });
      
      $('#StartDate').datepicker({      	format: "yyyy-mm-dd",language: 'th', thaiyear: true,      	autoclose: true,      	todayHighlight: true      });
	$('#EndDate').datepicker({      	format: "yyyy-mm-dd",language: 'th', thaiyear: true,      	autoclose: true,      	todayHighlight: true      });	

      $('#xRECEIVEDATE9').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE9').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
      $('#zRECEIVEDATE9').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });      		

      $('#xRECEIVEDATE1').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE1').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	
	$('#zRECEIVEDATE1').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });		

      $('#xRECEIVEDATE2').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE2').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	
	$('#zRECEIVEDATE2').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });		
      
      $('#xRECEIVEDATE3').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE3').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	
	$('#zRECEIVEDATE3').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });		

      $('#xRECEIVEDATE4').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE4').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	
	$('#zRECEIVEDATE4').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });		

      $('#xRECEIVEDATE5').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE5').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	
	$('#zRECEIVEDATE5').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	

      $('#xRECEIVEDATE6').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });
	$('#yRECEIVEDATE6').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });	
	$('#zRECEIVEDATE6').datepicker({      	format: "yyyy-mm-dd",      	autoclose: true,      	todayHighlight: true      });		

      $('#demoSelect').select2();