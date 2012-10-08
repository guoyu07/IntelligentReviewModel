<?php
/* =============================================================
  Author(s): Gregory Krudysz
  Last Update: May-23-2012
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
    $("#ITS_search_box").live('keyup', function(event) {
		var key = $(this).val();
		var rtb = $(this).attr("rtb");
		var rid = $(this).attr("rid");
		var action;
        //$('div.ITS_search').html(key);	
        if(event.keyCode == 13){ action = 'submit'; } 
		else 				   { action = 'search'; }
        $.get('ajax/ITS_search.php', {
                ajax_args: action, 
                ajax_data: key+'~'+rtb+'~'+rid
            }, function(data) {
                $('div.ITS_search').html(data);							
        });
    });	    
    /*-------------------------------------------------------------------------*/
	//$("#ITS_search_box").submit(function() {
		//var key = $(this).val();
		//alert('sub');	
        /*
        $.get('ajax/ITS_search.php', {
                ajax_args: "search", 
                ajax_data: key
            }, function(data) {
                $('div.ITS_search').html(data);							
        });
        */
    //});   
    /*-------------------------------------------------------------------------*/	
    //function doChange() {alert('ch');}
    /*
                $(function() {
                // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
                $( "#dialog:ui-dialog" ).dialog( "destroy" );
                $( "#dialog-form:ui-dialog" ).dialog( "destroy" );
                var Qtitle = $( "#title" ),
                        Qimage = $( "#image" ),
                        Qquestion = $( "#question" ),
                        allFields = $( [] ).add( Qtitle ).add( Qimage ).add( Qquestion ),
                        tips = $( ".validateTips" );

                $( "#dialog-form" ).dialog( {		  
                        autoOpen: false,
                        height: 950,
                        width: 850,
                        modal: true,
                        buttons: {
                                "Create New Question": function() {
                                        var bValid = true;
                                        allFields.removeClass( "ui-state-error" );
                                        if ( true ) {
                                                $( "#users tbody" ).append( "<tr>" +
                                                        "<td>" + Qtitle.val() + "</td>" + 
                                                        "<td>" + Qimage.val() + "</td>" + 
                                                        "<td>" + Qquestion.val() + "</td>" +
                                                "</tr>" ); 
                                                $( this ).dialog( "close" );
                                        }
                                },
                                Cancel: function() {$( this ).dialog( "close" );}
                        },
                        close: function() {allFields.val( "" ).removeClass( "ui-state-error" );}
                });
        });*/
    /*----------------------*/
});
//===========================================//
</script>
