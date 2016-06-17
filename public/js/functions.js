var table;
function attachHandler(form,refreshTable)
{
    form.submit(function (ev) {
        ev.preventDefault();
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function (data) {
                var form =  $(data).find('form.form-horizontal');
                var errors = $(data).find('ul.errors');
                if(errors.length!=0){
                    $('#modalForm').html(form.html());
                    $('#datepicker').datepicker({ dateFormat: 'dd.mm.yy' });
                }
                else{
                    $('#modal').modal('hide');
                    if(refreshTable){
                        table.destroy();
                        $('#table-body').html($(data).find('#table-body').html());
                        createNewTable();
                    }

                    $('.editButton').on('click',editHandler);
                    $('.btn-danger').on('click',deleteHandler);                    
                }
            }
        });
    });
}
function getNewForm(elem)
{
    elem.on('click',function(e){
        e.preventDefault();
        $('#modal').modal('hide');
        console.log('hide');
        $.ajax({
            url: elem.attr('href'),
            success: function(data){

                $('#modal').on('hidden.bs.modal',function(){
                    $('#modal').html(data);
                    if($('#new-client').length!=0)
                    {
                        getNewForm($('#new-client'));
                    }
                    if($('#new-project').length!=0)
                    {

                        getNewForm($('#new-project'));
                    }
                    $('#modal').modal('show');
                    $('body').removeAttr('style');
                    $('#modal').off('hidden.bs.modal');
                    attachHandler($('#modalForm'),false);
                    $('#modal').on('hidden.bs.modal',function(){
                        $('#modal').html('');
                    });
                });
            }
        });
        $('#modal').modal('hide');
    });
}
function attachDeleteHandler(form)
{
    form.submit(function (ev) {
        ev.preventDefault();
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function (data) {

                $('#modal').modal('hide');
                //$('.table').html($(data).find('.table').html());
                table.destroy();
                $('#table-body').html($(data).find('#table-body').html());
                $('.editButton').on('click', editHandler);
                $('.btn-danger').on('click', deleteHandler);


                createNewTable();

            }
        });
    });
}

function getAddForm(elem)
{

    $.ajax({
        url:elem.attr('href'),
        success:function(data){
            $('#modal').html(data);
            $('#modal').on('hide.bs.modal', function (e) {
                $('form')[0].reset();
            });
            $('#modal').on('show.bs.modal', function (e) {

                $( "#datepicker" ).datepicker({ dateFormat: 'dd.mm.yy' });

            });

            $('#modal').modal('show');
            $('body').removeAttr('style');
            attachHandler($('#modalForm'),true);
            if($('#new-client').length!=0)
            {
                getNewForm($('#new-client'));
            }
            if($('#new-project').length!=0)
            {

                getNewForm($('#new-project'));
            }

        }
    });
}
function getDeleteConfirmation(elem)
{
    $.ajax({
        url:elem.attr('href'),
        success:function(data){
            $('#modal').html(data);
            $('#modal').on('hide.bs.modal', function (e) {
                $('form')[0].reset();
            });
            $('#modal').on('show.bs.modal', function (e) {

                $( "#datepicker" ).datepicker({ dateFormat: 'dd.mm.yy' });

            });


            $('#modal').modal('show');
            $('body').removeAttr('style');
            attachDeleteHandler($('#delete'));
        }
    });
}
function formReset(form)
{
    form.find('[type=text]').val('');
    form.find('.errors').remove();
}


function editHandler(e)
{
    e.preventDefault();
    $('body').removeAttr('style');
    if($('#modal').children('#edit').length==0)
    {
        getAddForm($(this));

    }else
    {
        $('#modal').modal('show');

    }


}
function deleteHandler(e)
{
    e.preventDefault();
    $('body').removeAttr('style');
    if($('#modal').children('#delete').length==0)
    {
        getDeleteConfirmation($(this));
    }else
    {
        $('#modal').modal('show');
    }
    
}
function createNewTable()
{
    table = $('#table').DataTable({"order": []});
    $('#table_filter').remove();
    $('#table thead th').removeAttr('tabindex').removeClass('sorting');
    $('#table thead th'). unbind(); //disabling sorting

}



