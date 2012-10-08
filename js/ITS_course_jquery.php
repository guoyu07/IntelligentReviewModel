<?php
/* ============================================================= */
  $LAST_UPDATE = 'Aug-28-2012';
  /* Author(s): Gregory Krudysz
/* ============================================================= */
?>
<script type="text/javascript">
	$(function() {
      $(".ITS_select").change(function() { document.ece2025.submit(); });
			$("#select_class").buttonset();
    });
	/*-------------------------------------------------------------------------*/
  $(document).ready(function() { 
     $("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
	 /*-------------------------------------------------------------------------*/		
	 $("#sortProfile").change(function() { doChange(); }).attr("onchange", function() { doChange(); });
	 /*-------------------------------------------------------------------------*/	 
	 $("a.ITS_question_img").fancybox({
	      type: 'image',
		  closeClick: true,
		  aspectRatio: true,
		  padding: 5,
          helpers: {
	overlay : {
		closeClick : true,
		speedOut   : 300,
		showEarly  : false,
		css        : { 'background' : 'rgba(255, 255, 255, 0)'}
	},			  
              title : {
                  type : 'inside'
              }
          }
      });
	 /*-------------------------------------------------------------------------*/
	function doChange() {			
      var sid     = $("#sortProfile").attr("sid");
      var section = $("#sortProfile").attr("section");
      var status  = $("#sortProfile").attr("status");
      var ch      = $("#sortProfile").attr("ch");
      var orderby = $("#sortProfile option:selected").text();
			//alert(sid+'~'+orderby);
      $.get('ITS_admin_AJAX.php', { ajax_args: "orderProfile", ajax_data: sid+'~'+section+'~'+status+'~'+ch+'~'+orderby}, function(data) {
			  //alert(data);
				$("#userProfile").html(data); 
				$("#sortProfile").change(function() { doChange(); });
      });			
    }	
	 /*-------------------------------------------------------------------------*/
  });
</script>
