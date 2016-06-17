/**
 * Author: Anton Ashin
 */

jQuery(document).ready(function ($) {

    /**
     * Login form
     */
    if (!$('*').is('.login-form')) {
        $('body').css({
            background: '#1d1d1d'
        })
    }

    $(function () {
        var form = $(".login-form");

        form.css({
            opacity: 1,
            "-webkit-transform": "scale(1)",
            "transform": "scale(1)",
            "-webkit-transition": ".5s",
            "transition": ".5s"
        });
    });

    $('body').on('click', 'input', function () {
        //$('.errors').not($(this)).animate({opacity: '1'}, 150);
        $(this).parent().find('.errors').animate({opacity: '0'}, 150);
    });

    /*$(document).mouseup(function (e) {
        var form = $(".errors");
        if (!form.is(e.target) && form.has(e.target).length === 0) {
            $('.errors').animate({opacity: '1'}, 150);
        }
    });*/

    /**
     *  Disable :hover when scrolling
     *       .disable-hover,
     *            .disable-hover * {
     *                 pointer-events: none !important;
     *                      }
     */
    var body = document.body,
        timer;

    window.addEventListener('scroll', function () {
        clearTimeout(timer);
        if (!body.classList.contains('disable-hover')) {
            body.classList.add('disable-hover')
        }

        timer = setTimeout(function () {
            body.classList.remove('disable-hover')
        }, 500);
    }, false);


    /**
     * Load Tile content
     */
    $('body').on('click', '.load-tile-content', function(e){
        e.preventDefault();

        var linkHref = $(this).attr('href');

        $('.tile--general').hide();
        $('.load-tile-content').removeClass('is-active');
        $(this).addClass('is-active');
        $('.load-tile-content').not('.square-button').addClass('is-small');
        $('.tile--menu').addClass('is-left');
        $('.tile-area').addClass('ajax-is-load');

        $.ajax({
            url: linkHref,
            cache: true,
            success: function(html){
                $(".tile-content-wrap").html(html);
            },
            beforeSend: function() {
                $('#loading').show();
            },
            complete: function(){
                setTimeout(function() {
                    $('#loading').fadeOut(750);
                }, 200);
            },
            error: function(){
                var errorMessage = '<div class="error">Content not found</div>';
                $('.tile-content-wrap').append(errorMessage);
            }

        });
    });


    /**
     * Function for initializate modal dialog
     */
    function modalDialog(linkHref){
        var modalId = $('.dialog').attr('id');

        $.ajax({
            url: linkHref,
            cache: true,
            success: function(html){
                $("#dialog").html(html);
            },
            beforeSend: function() {
                $('#loading').show();
            },
            complete: function(){
                setTimeout(function() {
                    $('#loading').fadeOut(750);
                    var dialog = $('#' + modalId).data('dialog');
                    dialog.open();
                    $('.mask').fadeIn();

                    $('#' + modalId).find('input').each(function(){
                        if($(this).val().length > 1){
                            $(this).parent().find('.placeholder').hide();
                        }
                    })

                }, 200);
            },
            error: function(){
                var errorMessage = '<div class="error">Content not found</div>';
                $('#dialog').append(errorMessage);
            }

        });
    }


    /**
     * Dialog for remove string
     */
    $('body').on('click', '.removeButton', function(e){
        e.preventDefault();

        var linkHref = $(this).attr('href');
        modalDialog(linkHref);
    });


    /**
     * Dialog for change string
     */
    $('body').on('click', '.editButton', function(e){
        e.preventDefault();

        var linkHref = $(this).attr('href');
        modalDialog(linkHref);
    });

    /**
     * Dialog for add string
     */
    $('body').on('click', '.addButton', function(e){
        e.preventDefault();

        var linkHref = $(this).attr('href');
        modalDialog(linkHref);
    });


    /**
     * Close dialog
     */
    $('body').on('click', '.close-dialog', function(e){
        e.preventDefault();

        var modalId = $(this).closest('.dialog').attr('id');
        $('#' + modalId).css('visibility', 'hidden');
        $('.mask').fadeOut();
    });

    /**
     * Dialog for send form
     */
    $('body').on('click', '.sendButton', function(e){
        e.preventDefault();

        var form = $(this).closest('.dialog').find('form'),
            formId = form.attr('id'),
            formHref = form.attr('action'),
            formHrefAction = formHref.split('/')[2];

        var msg = $('#' + formId).serialize();

        $.ajax({
            url: formHref,
            type: 'POST',
            data: msg,
            success: function(data){
                var modalId = form.closest('.dialog').attr('id');
                var validation = data.match(/errors/g);

                if(validation != null){
                    $.Notify({
                        caption: 'Error',
                        content: 'validation false',
                        type: 'alert'
                    });

                    $('#dialog .container').remove();
                    $('#dialog').append(data);

                    $('#' + modalId).find('input').each(function(){
                        if($(this).val().length > 0){
                            $(this).parent().find('.placeholder').hide();
                        }
                    })

                }else {
                    $('#' + modalId).css('visibility', 'hidden');
                    $('.mask').fadeOut();

                    if(formHrefAction == 'edit'){
                        $.Notify({
                            caption: 'Change',
                            content: 'completed successfully',
                            type: 'Info'
                        });
                    }else if(formHrefAction == 'add'){
                        $.Notify({
                            caption: 'Add',
                            content: 'completed successfully',
                            type: 'success'
                        });
                    } else {
                        $.Notify({
                            caption: 'Removal',
                            content: 'completed successfully',
                            type: 'warning'
                        });
                    }

                    // refresh layout
                    var linkHref = $('.is-active').attr('href');
                    $.ajax({
                        url: linkHref,
                        cache: true,
                        success: function(html){
                            $(".tile-content-wrap").html(html);
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function(){
                            setTimeout(function() {
                                $('#loading').fadeOut(750);
                            }, 200);
                        },
                        error: function(){
                            var errorMessage = '<div class="error">Content not found</div>';
                            $('.tile-content-wrap').append(errorMessage);
                        }

                    });
                }
            },
            error: function(){
                $.Notify({
                    caption: 'Error',
                    content: '',
                    type: 'alert'
                });
            }

        });

    });

    /**
     * Filter form
     */
    $('body').on('click', '.filterButton', function(e){
        e.preventDefault();

        var form = $(this).closest('.filters').find('form'),
            formId = form.attr('id'),
            formHref = form.attr('action');

        var msg = $('#' + formId).serialize();

        //console.log(formHref);
        $.ajax({
            url: formHref,
            type: 'POST',
            data: msg,
            success: function(html){

                $.Notify({
                    caption: 'Success',
                    content: 'success',
                    type: 'success'
                });
                $(".tile-content-wrap").html(html);
            },
            error: function(){
                $.Notify({
                    caption: 'Error',
                    content: '',
                    type: 'alert'
                });
            }

        });

    });

    /**
     * Data table
     */
    $('#data_table').dataTable( {
        searching: true
    } );
});