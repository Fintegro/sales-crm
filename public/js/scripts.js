$(function() {
    
    if($('#table').length!=0)
    {
        createNewTable();
    }
    $('#table').on( 'draw.dt', function () {
        $('.btn-danger').on('click', deleteHandler);
        $('.editButton').on('click', editHandler);
    } );
    $('.table-options a').on('click',function(e){
        e.preventDefault();
        if($('#modal').children('#modalForm').length==0){
            getAddForm($(this));
        }else{
            formReset($($('form')[0]));
            $('#modal').modal('show');
        }
        $('body').removeAttr('style');

    });
    $('.btn-danger').on('click', deleteHandler);    
    $('#modal').on('show.bs.modal',function(){
        $('body').removeAttr('style');
    });
    

});

