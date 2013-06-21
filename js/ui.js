
$(document).ready(function()
{
    $.urlParam = function(name){
        var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }// url paramet snippet by nack athens

    var currentTODO; // todo currently edditing


    $( ".todoList" ).sortable({
    	axis		:'y',
    	container	:'window',
    	update		:function()
    	{
    		// The toArray method returns an array with the ids of the todos
            var arr = $(".todoList").sortable('toArray');

            // Striping the todo- prefix of the ids:

            arr = $.map(arr,function(val,key){
                return val.replace('todo-','');
            });

            // Saving with AJAX
            console.log($.get('ajax.php',{'type':'todo',action:'rearrange',positions:arr}));
    	}});


    function rebind()
    {

     $('.todo').on('click','a.discardChanges',function(){

            currentTODO.find('.text')
                        .text(currentTODO.data('origText'))
                        .end()
                        .removeData('origText');

            currentTODO.find('.desc')
                        .html(currentTODO.data('origDesc'))
                        .end()
                        .removeData('origDesc');


            return false;

     });


     $('.todo').on('click','a.saveChanges',function(){
        var text = currentTODO.find("input[type=text]").val();
        var desc = currentTODO.find('textarea[id="2"]').val();

        console.log(desc); 
        
        console.log(currentTODO); 

        var td = currentTODO;


        $.get("ajax.php",{'type':'todo','action':'edit','id':currentTODO.data('id'),'text':text,'desc':desc}, function(msg)
        {
                console.log(td); 

                $(td).removeData('origDesc').find(".desc").html(msg);

        });

        currentTODO.removeData('origText')
                    .find(".text")
                    .text(text);



        return false;

    });


    $('.text input').keypress(function(e){

        if(e.which == 13 ) {
            $(this).parent().find('a.saveChanges').trigger('click');
            return false;
        }
    });


    // When a double click occurs, just simulate a click on the edit button:
    $('.todo').on('dblclick',function(){
        $(this).find('a.edit').click();
    });

    $('.todo').on('click','a',function(){

        currentTODO = $(this).closest('.todo');
        currentTODO.data('id',currentTODO.attr('id').replace('todo-',''));

        return false;
    });

    $('.todo').on('click','a.delete', function(){

        console.log($.get("ajax.php",{'type':'todo',"action":"delete","id":currentTODO.data('id')},function(msg){
            currentTODO.fadeOut('fast');
        }));

    });

     $('.todo').on('click','a.edit',function(){

        var container = currentTODO.find('.text');
        var desc = currentTODO.find('.desc');

        if(!currentTODO.data('origText'))
        {
            // Saving the current value of the ToDo so we can
            // restore it later if the user discards the changes:

            currentTODO.data('origText',container.text());
            currentTODO.data('origDesc',desc.html());
        }
        else
        {
            // This will block the edit button if the edit box is already open:
            return false;
        }

        $('<input type="text">').val(container.text()).appendTo(container.empty());

         $.get("ajax.php",{'type':'todo','action':'get','id':currentTODO.data('id')}, function(msg)
        {
            $('<textarea type="text" class="in" id="2">').val(msg).appendTo(desc.empty());
        });


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


    $('#addButton').click(function(e){

        $.get("ajax.php",{'type':'todo','action':'new','text':'New Todo','list':$.urlParam('list_id'),'desc':""}, function(msg){


            //console.log(msg);
            var $el = $('.todoList').append(msg);

            rebind();
            $('.todoList li:last').trigger('dbclick');

        });

        

        return false;

    });


});

