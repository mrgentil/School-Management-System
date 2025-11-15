<script>

    function getLGA(state_id){
        var url = '<?php echo e(route('get_lga', [':id'])); ?>';
        url = url.replace(':id', state_id);
        var lga = $('#lga_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                //console.log(resp);
                lga.empty();
                $.each(resp, function (i, data) {
                    lga.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });

            }
        })
    }

    function syncOptionWithAcademicSection() {
        var optionSelect = $('#option_id');
        var sectionDisplay = $('#academic_section_display');
        var sectionHidden = $('#academic_section_value');
        var notice = $('#academic_section_lock_notice');

        if (!optionSelect.length || !sectionDisplay.length || !sectionHidden.length) {
            return;
        }

        function lockSectionToOption() {
            var selected = optionSelect.find('option:selected');
            var sectionId = selected.data('section-id');

            if (sectionId) {
                sectionHidden.val(sectionId);
                sectionDisplay.val(sectionId).trigger('change');
                sectionDisplay.prop('disabled', true);
                notice.removeClass('d-none');
            } else {
                sectionHidden.val(sectionDisplay.val());
                sectionDisplay.prop('disabled', false);
                notice.addClass('d-none');
            }
        }

        optionSelect.on('change', lockSectionToOption);
        lockSectionToOption();

        sectionDisplay.on('change', function(){
            if(!sectionDisplay.prop('disabled')){
                sectionHidden.val($(this).val());
            }
        });
    }

    $(function(){
        syncOptionWithAcademicSection();
    });

    function getClassSections(class_id, destination){
        var url = '<?php echo e(route('get_class_sections', [':id'])); ?>';
        url = url.replace(':id', class_id);
        var section = destination ? $(destination) : $('#section_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                //console.log(resp);
                section.empty();
                section.append($('<option>', {
                    value: '',
                    text: 'Choisir...'
                }));
                $.each(resp, function (i, data) {
                    section.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });

            }
        })
    }

    function getClassSubjects(class_id){
        var url = '<?php echo e(route('get_class_subjects', [':id'])); ?>';
        url = url.replace(':id', class_id);
        var section = $('#section_id');
        var subject = $('#subject_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                console.log(resp);
                section.empty();
                subject.empty();
                $.each(resp.sections, function (i, data) {
                    section.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
                $.each(resp.subjects, function (i, data) {
                    subject.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });

            }
        })
    }


    

    // Configuration Toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    <?php if(session('pop_error')): ?>
    toastr.error('<?php echo e(session('pop_error')); ?>', 'Erreur!');
    <?php endif; ?>

    <?php if(session('pop_warning')): ?>
    toastr.warning('<?php echo e(session('pop_warning')); ?>', 'Attention!');
    <?php endif; ?>

    <?php if(session('pop_success')): ?>
    toastr.success('<?php echo e(session('pop_success')); ?>', 'Succès!');
    <?php endif; ?>

    <?php if(session('flash_info')): ?>
    toastr.info('<?php echo e(session('flash_info')); ?>', 'Information');
    <?php endif; ?>

    <?php if(session('flash_success')): ?>
    toastr.success('<?php echo e(session('flash_success')); ?>', 'Succès!');
    <?php endif; ?>

    <?php if(session('flash_warning')): ?>
    toastr.warning('<?php echo e(session('flash_warning')); ?>', 'Attention!');
    <?php endif; ?>

    <?php if(session('flash_error') || session('flash_danger')): ?>
    toastr.error('<?php echo e(session('flash_error') ?: session('flash_danger')); ?>', 'Erreur!');
    <?php endif; ?>

    

    function pop(data){
        var type = data.type || 'error';
        var title = data.title || 'Notification';
        
        // Convertir les types pour Toastr
        if(type === 'danger') type = 'error';
        
        toastr[type](data.msg, title);
    }

    function flash(data){
        var type = data.type;
        var msg = data.msg;
        var title = data.title || '';
        
        // Convertir les types pour Toastr
        if(type === 'danger') type = 'error';
        
        // Afficher le toast
        toastr[type](msg, title);
    }

    function confirmDelete(id) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this item!",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then(function(willDelete){
            if (willDelete) {
             $('form#item-delete-'+id).submit();
            }
        });
    }

    function confirmReset(id) {
        swal({
            title: "Are you sure?",
            text: "This will reset this item to default state",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then(function(willDelete){
            if (willDelete) {
             $('form#item-reset-'+id).submit();
            }
        });
    }

    $('form#ajax-reg').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this), 'store');
        $('#ajax-reg-t-0').get(0).click();
    });

    $('form.ajax-pay').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this), 'store');

//        Retrieve IDS
        var form_id = $(this).attr('id');
        var td_amt = $('td#amt-'+form_id);
        var td_amt_paid = $('td#amt_paid-'+form_id);
        var td_bal = $('td#bal-'+form_id);
        var input = $('#val-'+form_id);

        // Get Values
        var amt = parseInt(td_amt.data('amount'));
        var amt_paid = parseInt(td_amt_paid.data('amount'));
        var amt_input = parseInt(input.val());

//        Update Values
        amt_paid = amt_paid + amt_input;
        var bal = amt - amt_paid;

        td_bal.text(''+bal);
        td_amt_paid.text(''+amt_paid).data('amount', ''+amt_paid);
        input.attr('max', bal);
        bal < 1 ? $('#'+form_id).fadeOut('slow').remove() : '';
    });

    $('form.ajax-store').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this), 'store');
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $('form.ajax-update').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this));
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $('.download-receipt').on('click', function(ev){
        ev.preventDefault();
        $.get($(this).attr('href'));
        toastr.info('Téléchargement en cours...', 'Information');
    });

    function reloadDiv(div){
        var url = window.location.href;
        url = url + ' '+ div;
        $(div).load( url );
    }

    function submitForm(form, formType){
        var btn = form.find('button[type=submit]');
        disableBtn(btn);
        var ajaxOptions = {
            url:form.attr('action'),
            type:'POST',
            cache:false,
            processData:false,
            dataType:'json',
            contentType:false,
            data:new FormData(form[0])
        };
        var req = $.ajax(ajaxOptions);
        req.done(function(resp){
            resp.ok && resp.msg
               ? flash({msg:resp.msg, type:'success'})
               : flash({msg:resp.msg, type:'danger'});
            hideAjaxAlert();
            enableBtn(btn);
            formType == 'store' ? clearForm(form) : '';
            scrollTo('body');
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){
                var errors = e.responseJSON.errors;
                displayAjaxErr(errors);
            }
           if(e.status == 500){
               displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])
           }
            if(e.status == 404){
               displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])
           }
            enableBtn(btn);
            return e.status;
        });
    }

    function disableBtn(btn){
        var btnText = btn.data('text') ? btn.data('text') : 'Submitting';
        btn.prop('disabled', true).html('<i class="icon-spinner mr-2 spinner"></i>' + btnText);
    }

    function enableBtn(btn){
        var btnText = btn.data('text') ? btn.data('text') : 'Submit Form';
        btn.prop('disabled', false).html(btnText + '<i class="icon-paperplane ml-2"></i>');
    }

    function displayAjaxErr(errors){
        $('#ajax-alert').show().html(' <div class="alert alert-danger border-0 alert-dismissible" id="ajax-msg"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>');
        $.each(errors, function(k, v){
            $('#ajax-msg').append('<span><i class="icon-arrow-right5"></i> '+ v +'</span><br/>');
        });
        scrollTo('body');
    }

    function scrollTo(el){
        $('html, body').animate({
            scrollTop:$(el).offset().top
        }, 2000);
    }

    function hideAjaxAlert(){
        $('#ajax-alert').hide();
    }

    function clearForm(form){
        form.find('.select, .select-search').val([]).select2({ placeholder: 'Select...'});
        form[0].reset();
    }



</script><?php /**PATH C:\laragon\www\eschool\resources\views/partials/js/custom_js.blade.php ENDPATH**/ ?>