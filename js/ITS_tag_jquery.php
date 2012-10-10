<?php
/* =============================================================
  Author(s): Gregory Krudysz
  Last Update: Nov-5-2012
/* ============================================================= */
?>
<script type="text/javascript">
    $(document).ready(function() { 
		/*-------------------------------------------------------------------------*/ 
        $('.tag_del').live('click', function() { 
			var tid   = $(this).attr('tid');
			var tname = $(this).attr('tname');
			var rid   = $(this).attr('rid');
			var rname = $(this).attr('rname');
			//alert(tid+'~'+tname+'~'+rid+'~'+rname);			

			$.get('ajax/ITS_tag.php', {
                ajax_args: "deleteTAG", 
                ajax_data: tid+'~'+tname+'~'+rid+'~'+rname
            }, function(data) {
				$('#ITS_question_container').append(data);	
                //$('div.taginfo').html(data);							
            });
            //var parentTag = $(this).parent().get(0).tagName;alert(parentTag);
			$(this).parent().parent().parent().parent().hide(800, function () {
				$(this).remove();
			});
		});   
		/*-------------------------------------------------------------------------*/ 
        $('.tag_add').live('click', function() { 
			var tid   = $(this).attr('tid');
			var tname = $(this).attr('tname');
			var rid   = $(this).attr('rid');
			var rname = $(this).attr('rname');
			//alert(tid+'=~'+tname+'~'+rid+'~'+rname);
			
			$.get('ajax/ITS_tag.php', {
                ajax_args: "addTAG", 
                ajax_data: tid+'~'+tname+'~'+rid+'~'+rname
            }, function(data) {
                $('#Q_tag_list').append(data);							
            });
            /* remove from list */
            $(this).parent().parent().parent().parent().hide(800, function () {
				$(this).remove();
			});
		});		           
        /*-------------------------------------------------------------------------*/ 
        $('.tagref').live('click', function() {  
			var tid   = $(this).attr('tid');
			var tname = $(this).html();
			$.get('ajax/ITS_tag.php', {
                ajax_args: "practiceMode", 
                ajax_data: tid+','+tname
            }, function(data) {
                $('div.taginfo').html(data);							
            });
		if ($("div.taginfo").is(":hidden")) {
			$("div.taginfo").slideDown("slow");
		} else {
			$("div.taginfo").hide();
		}
		}); 	
    /*-------------------------------------------------------------------------*/	
    $(".ITS_addTAG").live('click', function(event) {
        var obj_id = $(this).attr("ref");	
        var tag    = $(this).attr("tag");
        $("#TXA_"+obj_id+"_TARGET").insertAtCaret(tag);
    });      
    /*-------------------------------------------------------------------------*/
});
//===========================================//
</script>
