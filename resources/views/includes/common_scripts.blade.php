<!-- jQuery  -->
<script src="{{ URL::asset('assets/js/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/popper.min.js')}}"></script><!-- Popper for Bootstrap -->
<script src="{{ URL::asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/modernizr.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/jquery.slimscroll.js')}}"></script>
<script src="{{ URL::asset('assets/js/waves.js')}}"></script>
<script src="{{ URL::asset('assets/js/jquery.nicescroll.js')}}"></script>
<script src="{{ URL::asset('assets/js/jquery.scrollTo.min.js')}}"></script>

<!-- App js -->
<script src="{{ URL::asset('assets/js/app.js')}}"></script>


<!-- Plugins js : form elements-->
<script src="{{ URL::asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js')}}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js')}}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js')}}" type="text/javascript"></script>

<!-- Plugins Init js : form elements -->
<script src="{{ URL::asset('assets/pages/form-advanced.js')}}"></script>

<!-- notify alert -->
<script src="{{ URL::asset('assets/js/jquery.notify.min.js')}}"></script>

<!-- sweet alert -->
<script src="{{ URL::asset('assets/plugins/sweet-alert2/sweetalert2.min.js')}}"></script>

<!-- light box -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js" integrity="sha256-jGAkJO3hvqIDc4nIY1sfh/FPbV+UK+1N+xJJg6zzr7A=" crossorigin="anonymous"></script>


<script language="JavaScript" type="text/javascript">
    $('.gtZero').on('input',function () {
        this.value = this.value < 0 ? 0 : this.value;
    })

    $('.monthPicker').datepicker({
        autoclose: true,
        minViewMode: 1,
        format: 'mm/dd/yyyy'
    }).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('.to').datepicker('setStartDate', startDate);
    });

    function initializeDate() {
        jQuery('.datepicker-autoclose').datepicker({
            autoclose: true,
            todayHighlight: true
        });

    }

    function clearAll() {
        $('input').not(':checkbox').not('.noClear').val('');
        $('textarea').not('.noClear').val('');
        $(":checkbox").not('.noClear').attr('checked', false).trigger('change');
        $(":radio").not('.noClear').attr('checked', false).trigger('change');
        $('select').not('.noClear').val('').trigger('change');
    }


    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            onShown: function() {
                $('.modal').hide();
            },
            onHidden: function() {
                $('.modal').show();
            },
        });
    });
</script>




