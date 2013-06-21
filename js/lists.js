$(document).ready(function()
{

	
	var currentTODO;

	$("#lists .but").click(function(){

		console.log($.get('ajax.php',{'type':'list','action':'add',text:"newlist"},function(msg){
            
			$("#lists ol").append('<li id="'+ msg+'"class="list"><a href="" class="text">'+ "newlist" + '</a>'+ '<div class="actions"><a href="" class="edit">Edit</a>'
							+'<a href="" class="public">Publish</a>'
							+'<a href="" class="delete">Delete</a>'
						+'</div> </li>');
			rebind();

        }));

	});



	function rebind()
    {	

	     $('.list').on('click','a.discardChanges',function(){

	            currentTODO.find('.text')
	                        .text(currentTODO.data('origText'))
	                        .end()
	                        .removeData('origText');

	            currentTODO = 0;

	            return false;

	     });


	     $('.list').on('click','a.saveChanges',function(){
	        var text = currentTODO.find("input[type=text]").val();

	        

	        console.log($.get("ajax.php",{'type':'list','action':'edit','id':currentTODO.data('id'),'text':text}));

	        currentTODO.removeData('origText')
	                    .find(".text")
	                    .text(text);

	        currentTODO = 0;

	        return false;

	    });


	    $('.text input').keypress(function(e){

	        if(e.which == 13) {
	            $(this).parent().find('a.saveChanges').trigger('click');
	            return false;
	        }
	    });


	    // When a double click occurs, just simulate a click on the edit button:
	    $('.list').on('dblclick',function(){
	        $(this).find('a.edit').click();
	    });

	    $('.list').on('click','a',function(){

	       	if($(this).parent().attr('class') == "actions" || $(this).parent().attr("id") == currentTODO.attr("id"))
	        {
	       		currentTODO = $(this).closest('.list');
	        	currentTODO.data('id',currentTODO.attr('id'));
	        	return false;
	        }

	    });

	    $('.list').on('click','a.delete', function(){

	        console.log($.get("ajax.php",{'type':'list','action':"delete","id":currentTODO.data('id')},function(msg){
	            currentTODO.fadeOut('fast');
	        }));

	    });


	    $('.list').on('click','a.public',function(){


	    	$( "#publish" ).dialog({
				      resizable: false,
				      height:140,
				      modal: true,
				      buttons: {
				      "Yes!": function() {
				           console.log($.get("ajax.php",{'type':'list','action':"publish","id":currentTODO.attr('id'),"public":1}));
				          $( this ).dialog( "close" );


	        				currentTODO = 0;
				       },
				       "NO!": function() {
				          console.log($.get("ajax.php",{'type':'list','action':"publish","id":currentTODO.attr('id'),"public":0}));
				          $( this ).dialog( "close" );


	        			currentTODO = 0;
				        }
				     }});

	        return false;

	    });


	    $('.list').on('click','a.edit',function(){

	        var container = currentTODO.find('.text');

	        if(!currentTODO.data('origText'))
	        {
	            // Saving the current value of the ToDo so we can
	            // restore it later if the user discards the changes:

	            currentTODO.data('origText',container.text());
	        }
	        else
	        {
	            // This will block the edit button if the edit box is already open:
	            return false;
	        }

	        $('<input type="text">').val(container.text()).appendTo(container.empty());

	        // Appending the save and cancel links:
	        container.append(
	            '<div class="editTodo">'+
	                '<a href="" class="saveChanges">Save</a> or <a href="" class="discardChanges">Cancel</a>'+
	            '</div>'
	        );

	        rebind();

	   });
    }


    rebind();

});