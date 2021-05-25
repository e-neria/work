let NewActivity = {};
$(document).ready(function() {
    grid();
    NewActivity.formValidate();
    NewActivity.formSave();
    NewActivity.datePickerStart();
    NewActivity.selectTypeActivity();
});

function grid(){
    $(function () {
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
}

(function () {
    this.formValidate = function(){
        //Custom validation
        jQuery.validator.addMethod("validSelectedOption", function(value, element){
            if(value == "Select one option"){
                return false;
            }else{
                return true;
            }
        }, "Status for activity is required.");

        $("#formNewActivity").validate({
            errorElement: "strong",
            errorClass: "invalid",
            rules:{
                title:{
                    required: true
                },
                description: {
                    required: true
                },
                branch: {
                    required: true
                },
                status: {
                    required: true,
                    validSelectedOption: true
                },
                datePickerStart: {
                    required: true
                },
                type:{
                    required: true
                },
                activity: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: "Title activity is required."
                },
                description: {
                    required: "The description for activity is required."
                },
                branch: {
                    required: "The branch where are you working activity is required."
                },
                status: {
                    required: "Status activity is required."
                },
                datePickerStart: {
                    required: "Start date is required."
                },
                type: {
                    required: "Type activity is required."
                },
                activity: {
                    required: "The activity is required."
                }
            }
        });
    }

    this.formSave = function(){
        $("#btnSaveChanges").unbind('click').bind('click', function(event) {
            var isValid = $("#formNewActivity").valid();
            if(isValid){
                //TODO: Realizar petición ajax, para guardar la actividad
            }else{
                toastr.error('Please, complete the required fields for continue.')
            }
            NewActivity.formValidate();
        });
    }

    this.datePickerStart = function (){
        $('#datePickerStart').datepicker();
    }

    this.selectTypeActivity = function(){
        $("#typeActivity").change(function(){
            if($("#typeActivity").val() == 1){
                $("#activities").show();
                $("#activitiesSelect").attr('required','required');
                $("#activities > div.form-group > label").html('Activity:<span class="required">*</span>');
            }else{
                $("#activities").hide();
                $("#activitiesSelect").removeAttr('required');
                $("#activities > div.form-group > label").html('Activity:');
            }
        });
    }
}).apply(NewActivity);

