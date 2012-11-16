$(document).ready(function() {
    /*-------------------------------------------------------------------------/	
	* Submits the concepts selected and will return a set of questions matching 
	* the condition	
   /*-------------------------------------------------------------------------*/
    $('#submitConcepts').live('click', function(event) {
   /*-------------------------------------------------------------------------*/		
        var tdArray = new Array();
        var addButton = "<input type='button' name='createModule' id='createModule' value='Submit questions'>";
        $('#errorConceptContainer').html("");

        $('#seldcon tr').each(function() {
            $(this).find('td').each(function() {
                if ($(this).text() != 'x')
                    tdArray.push($(this).text());
            });
        });
        var tbvalues = tdArray.join();
        //	alert(tbvalues);
        // Ajax call to display questions
        $.post("ajax/ITS_concepts.php", {
            //data to be sent in request
            choice: 'submitConcepts',
            tbvalues: tbvalues
        }, function(data) {
            //alert('aya data:'+ data +' !');
            if (data) {
                data += '<input type="button" id="createModule" name="createModule" value="Create or add to a Module"><br><br><br><br>';
                $("#ConcQuesContainer").html(data);
            } else
                $("#ConcQuesContainer").html("<br> No questions Available");
        });
    });
    /*-------------------------------------------------------------------------*
	 * for Students!
	 *-------------------------------------------------------------------------*/
    $('#getQuesForConcepts').live('click', function(event) {
	/*-------------------------------------------------------------------------*/		
        var tdArray = new Array();
        $('#errorConceptContainer').html("");
        $('#seldcon tr').each(function() {
            $(this).find('td').each(function() {
                if ($(this).text() != 'x')
                    tdArray.push($(this).text());
            });
        });
        var tbvalues = tdArray.join();
        // Ajax call to send questions to replace the question container
        // alert(tbvalues);
        
        // Save RESOURCE data
        var concept = $( "input[name=selectResource]" ).attr("concept");
		var text = $('#ITS_resource_text').attr("rid");
        var equation = $('#ITS_resource_equation').attr("rid");
        var image = $('#ITS_resource_image').attr("rid");
        var example = $('#ITS_resource_example').attr("rid");
        
        alert(concept+'~'+text+'~'+equation+'~'+image+'~'+example);
        $.get('ajax/ITS_resource.php', {
            ajax_args: "resourceDB",
            ajax_data: concept+'~'+text+'~'+equation+'~'+image+'~'+example
        }, function(data) {
                $("#contentContainer").html(data);
        });
        $.get('ajax/ITS_screen2.php', {
            ajax_args: "getQuestionsForConcepts",
            ajax_data: tbvalues
        }, function(data) {
		    var tbdivs = tdArray.join('</span><span class="CHOICE">');
		    tbdivs = '<span class="CHOICE">'+tbdivs+'</span>';
		    //alert(tbdivs);
            $('#coContainer').html(tbdivs);
            $('#coContainer').show();
            //	alert(data);
            if (data)
                $("#contentContainer").html(data);
            else
                $("#contentContainer").html("There was some error in the request");
        });
    });
    /*-------------------------------------------------------------------------*
	* Deletes a row in the selected concepts table
	*-------------------------------------------------------------------------*/
    $(".choice_del").live('click', function() {
        $('#errorConceptContainer').html("");
        $(this).parents('tr').remove();
    });
    /*-------------------------------------------------------------------------*
	* Prompts a user to select or input module name of the module to be 
	* created with selected questions
	*-------------------------------------------------------------------------*/
    $('#createModule').live("click", function() {
	/*-------------------------------------------------------------------------*/		
        // collecting selected concepts:
        var tdArray = new Array();
        $('#seldcon tr').each(function() {
            $(this).find('td').each(function() {
                if ($(this).text() != 'x')
                    tdArray.push($(this).text());
            });
        });
        var tbvaluesConcp = tdArray.join();
        var atLeastOneIsChecked = $('#chcktbl:checked').length > 0;
        if (!atLeastOneIsChecked) {
            alert("Please select atleast one question");
            return false;
        }
        $.post("ajax/ITS_concepts.php", {
            //data to be sent in request
            choice: "getModuleDDList",
            }, function(data) {
            if (data) {
                var str = '<input type="button" value="Submit" name="subModule" id="subModule"></input>';
                //alert('hi');
                $("#moduleNameDialog").val(data);
                var dialog = $(data).appendTo('#moduleNameDialog');
                dialog.attr("id", "ModuleNameDivDD");
                dialog.dialog({
                    title: "Select Module Name",
                    show: 'blind',
                    hide: 'slide',
                    resizable: true,
                    width: '50%',
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $('#subModule').css('border', '2px solid brown');
                        dialog.dialog("option", "close", "slide");
                    }
                });
                //alert($('#ModuleNameDivDD'));															
                $('#ModuleNameDivDD').append(str);
                alert($('#subModule'));
            } else
                $("#ConcQuesContainer").html("There was some error in the request");
        });
    });
    /*-------------------------------------------------------------------------		
	 	 Create a module with selected questions and entered module name
	-------------------------------------------------------------------------*/
    $('#subModule').live('click', function() {
	/*-------------------------------------------------------------------------*/		
        var moduleName = $('#moduleListDD').val();
        if (moduleName == 0)
            moduleName = $('input[name=moduleName]').val();
        if (moduleName == '') {
            $('#ModuleNameDivDD').append('<br>Please enter the module name');
            return false;
        }
        var tdArrayQ = new Array();
        $('#chcktbl:checked').each(function() {
            //alert("adding"+ $(this).val());
            tdArrayQ.push($(this).val());
        });
        var tbvaluesQ = tdArrayQ.join();
        //	alert("TB values: "+tbvaluesQ);
        var tdArray = new Array();
        $('#seldcon tr').each(function() {
            $(this).find('td').each(function() {
                if ($(this).text() != 'X')
                    tdArray.push($(this).text());
            });
        });
        var tbvaluesConcp = tdArray.join();

        $.post("ajax/ITS_concepts.php", {
            //data to be sent in request
            choice: 'createModule',
            moduleName: moduleName,
            tbvaluesQ: tbvaluesQ,
            tbvaluesConcp: tbvaluesConcp
        }, function(data) {
            if (data)
                $("#ConcQuesContainer").html(data);
            else
                $("#ConcQuesContainer").html("There was some error in the request");
        });
        $('#ModuleNameDivDD').remove();
    });
    /*-------------------------------------------------------------------------*		
	* Triggers when a module name is selected from the dropdown at module 
	* creation
	*-------------------------------------------------------------------------*/
    $('.moduleListDD').live('change', function() {
	/*-------------------------------------------------------------------------*/		
        if ($(this).val() == 0) {
			//alert('xx');
            var str = '';
            str = '<br> Module Name: <input type="text" MAXLENGTH="20" name="moduleName"></input>';
            $('#ModuleNameDivDD').append(str);
        }
    });
    /*-------------------------------------------------------------------------		
	 Checks or unchecks all table rows when the head is checked or unchecked
	-------------------------------------------------------------------------*/
    $('#chckHead').live("click", function() {
	/*-------------------------------------------------------------------------*/		
        if (this.checked == false) {
            $('.chcktbl:checked').attr('checked', false);
        } else {
            $('.chcktbl:not(:checked)').attr('checked', true);
        }
    });
    /*-------------------------------------------------------------------------		
	 Selects a concepts in the concept viewer
	-------------------------------------------------------------------------*/
    $(".selcon").live("click", function() {
/*-------------------------------------------------------------------------*/		
        $('#errorConceptContainer').html("");
        var field=this.id;
        var tr = '';
        if ($('#seldcon td:contains(' + this.id + ')').length) {
            $('#errorConceptContainer').html("Concept already selected.");
            return false;
        }
        tr = '<tr><td width="10%">' + this.id + '</td><td width="80%"><div id="resource_'+field+'"></td><td width="20px"class="choice_del">x</td></tr>';  
        $('#seldcon').append(tr);
        $('#SelectedConcContainer').css('display','block');
        
            $.get("ajax/ITS_concepts.php", {
                resource: this.id +'~255'
            }, function(data) {
                $('#resource_'+field).html(data);
            });
    });
    /*-------------------------------------------------------------------------*
	 * When called, the letter clicked on concept viewer is submitted and the 
	 * function returns matching concepts
	 * ------------------------------------------------------------------------*/
    $(".ITS_alph_index").live("click", function() {
/*-------------------------------------------------------------------------*/		
		var header = $(this).html();
		$('.ITS_alph_index').each(function(index) {
			//alert(index);
			//alert($(this).html());
			//$(this).children("a").attr('id','');
			
                if ($(this).html() == header){
                    $(this).attr('id','current');
                } 
                else{
                    $(this).attr('id','');
                }
        });
        $('#errorConceptContainer').html("");
        $.get("ajax/ITS_concepts.php", {
            letter: $(this).html()
        }, function(data) {
            //alert(data);
            if (data){ $("#conceptListContainer").html(data); }
            else { $("#conceptContainer").html("<br> No Concepts Available"); }
        });
    });
    /*-------------------------------------------------------------------------*
	 * In student mode, this function call returns with all matched questions
	 *  for practice
	 * -------------------------------------------------------------------------*/
    //$('#showConcepts').live('click',function(event){
    //$('#QuestionMode').live('change', function(event) {
	$('a[name=selectMode]').live('click', function(event) {	
/*-------------------------------------------------------------------------*/		
		//alert($(this).html());
        // click does not work with Chrome!!
        var s = $(this).html();
        //alert($(this).name); //.id = 'current'; //var s = $(this).val();
        if (s == "CONCEPT") {		
			$('#nav2 > li > a').html('ASSIGNMENT');
			$('#nav1 > li > a').html(s);
			$('#chContainer').hide();
			$('#navContainer').hide();
            $('#chapterListingDiv').hide();
            $('#changeConcept').show();

            $('#Question').attr('choice_mode', 'concept');
            $('#Practice').attr('choice_mode', 'concept');
            $('#Review').attr('choice_mode', 'concept');
           //alert(s);
            $.post("ajax/ITS_concepts.php", {
                choice: "getConcepts"
            }, function(data) {
                // TODO: to put in condition to check if data returned is null or no questions
                $('#contentContainer').html(data);
            });
        } else if (s == "ASSIGNMENT") {
			$('#nav2 > li > a').html('CONCEPT');
			$('#nav1 > li > a').html(s);
			$('#coContainer').hide('slow');
			$('#changeConcept').hide();
			$('#chContainer').show();
			$('#navContainer').show();
            $('#chapterListingDiv').show();
            $('#Question').attr('choice_mode', 'module');
            $('#Practice').attr('choice_mode', 'module');
            $('#Review').attr('choice_mode', 'module');
            //	alert('calling');
            $.get("ajax/ITS_screen2.php", {
                ajax_args: "changeMode",
                ajax_data: 'question'
            }, function(data) {
                $('#contentContainer').html(data);
            });
        }
    });
/*-------------------------------------------------------------------------*/
    $('#changeConcept').live('click', function(event) {
/*-------------------------------------------------------------------------*/		
        $('#coContainer').html('');
        $.post("ajax/ITS_concepts.php", {
            choice: "getConcepts"
        }, function(data) {
            // TODO: to put in condition to check if data returned is null or no questions
            $('#contentContainer').html(data);
        });
    });
/*-------------------------------------------------------------------------
	  Displays the questions in a tabular form for the selected module
/*-------------------------------------------------------------------------*/
    $('.modules').live('click', function(event) {
/*-------------------------------------------------------------------------*/		
        $('input[name=currentModule]').val(this.id);
//alert('xx');
        $.post("ajax/ITS_concepts.php", {
            choice: "getModuleQuestion",
            modulesQuestion: this.id
        }, function(data) {
            //alert(data);
            if (data)
                $("#ModuleQuestion").html(data);
            else
                $("#ModuleQuestion").html("<br> No Questions");
        });
        $("#DelQuestions").show();
    });
    /*-------------------------------------------------------------------------*
	 * Deletes selected questions from the selected module
	 * ------------------------------------------------------------------------*/
    $("#DelQuestions").live('click', function(event) {
/*-------------------------------------------------------------------------*/		
        var tdArrayQ = new Array();
        var ModuleName = $('input[name=currentModule]').val();
        $('#chcktbl:checked').each(function() {
            tdArrayQ.push($(this).val());
        });
        if (tdArrayQ.length == 0) {
            alert('No Questions selected');
            return false;
        }
        var tbvaluesQ = tdArrayQ.join();
        $.post("ajax/ITS_concepts.php", {
            choice: "deleteModuleQuestion",
            deleteQuestion: tbvaluesQ,
            ModuleName: ModuleName
        }, function(data) {
            if (data) {
                $.post("ajax/ITS_concepts.php", {
                    choice: "getModuleQuestion",
                    modulesQuestion: ModuleName
                }, function(data) {
                    if (data)
                        $("#ModuleQuestion").html(data);
                    else
                        $("#ModuleQuestion").html("<br> No Questions");
                });
            } else
                $("#ModuleQuestion").html("<br> No Questions");
        });
    });
/*-------------------------------------------------------------------------*/    
});
