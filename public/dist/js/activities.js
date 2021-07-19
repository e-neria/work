let Activity = {};
let additionalParams = {};
let listActivities = {};

$(document).ready(function() {
    Activity.formValidate();
    Activity.formSave();
    Activity.datePickerStart();
    Activity.selectTypeActivity();
    Activity.showSearch();
    Activity.hideSearch();
    Activity.search();
    Activity.searchEnterKey();
    Activity.clearActivity();
    Activity.isShow();
    //Activity.acutocompleteActivities();
    /*$('#gridActivities').bootstrapTable({
        scroll": true,
        scrollY: 200,
    });*/
});

function ajaxRequest(params){
    Activity.loadGrid(params);
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

        jQuery.validator.addMethod("validSelectionActivity", function(value, element){
            if($("#idActivity").val() == ""){
                return false;
            }else{
                return true;
            }
        }, "Select an activity.");

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
                datePickerEnd: {
                    required: true
                },
                typeActivity:{
                    required: true
                },
                listActivities: {
                    required: true,
                    validSelectionActivity: true
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
                datePickerEnd: {
                    required: "End date is required."
                },
                typeActivity: {
                    required: "Type activity is required."
                },
                listActivities: {
                    required: "The activity is required."
                }
            }
        });
    }

    this.formSave = function(){
        $("#btnSaveChanges").unbind('click').bind('click', function(event) {
            var isValid = $("#formNewActivity").valid();
            if(isValid){
                console.log('coming soon...');
                //TODO: Realizar petición ajax, para guardar la actividad
                $("#formNewActivity").submit();
            }else{
                toastr.error('Please, complete the required fields for continue.')
            }
            Activity.formValidate();
        });
    }

    this.datePickerStart = function (){
        $('#datePickerStart, #datePickerEnd').datepicker({
            showOn: "button",
            buttonImage: "/img/schedule.png",
            buttonImageOnly: true,
            buttonText: "Select date"
        });
    }

    this.selectTypeActivity = function(){
        $("#typeActivity").change(function(){
            if($("#typeActivity").val() == 1){
                $("#activities").show();
                $("#activitiesSelect").attr('required','required');
                $("#activities > div.form-group > label").html('Activity:<span class="required">*</span>');

                //Get Activities
                jQuery.ajax({
                    url: '/activities/getListActivities',
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function () {
                    },
                    complete: function (xhr, textStatus) {
                    },
                    success: function (data, textStatus, xhr) {
                        //console.log(data.body.data);
                        listActivities = data.body.data;
                        //console.log(listActivities);
                        Activity.acutocompleteActivities(listActivities);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                    }
                });



            }else{
                $("#activities, #clearActivity").hide();
                $("#activitiesSelect").removeAttr('required');
                $("#activities > div.form-group > label").html('Activity:');
            }
        });
    }

    this.loadGrid = function(params){
        console.log('ok in ajax request...');

        if(additionalParams.search != undefined){
            params.data.search = additionalParams.search;
        }

        var url = "/activities/getListActivitiesPaginated";
        $.get(url + '?' + $.param(params.data)).then(function (response){
            params.success(response);
        });
    }

    this.showSearch = function(){
        $(document).on('click', '.displaySearch', function(){
            $('.search').show();
            $('.displaySearch').hide();
        });
    }

    this.hideSearch = function(){
        $(document).on('click', '.btnClose', function(){
            $('#inputSearch').val('');
            additionalParams = {};
            $('.search').hide();
            $('.displaySearch').show();
            $('#gridActivities').bootstrapTable('refresh');
        });
    }

    this.search = function (){
        $(document).on('click', '.btnSearch', function(){
            additionalParams = {search: $('#inputSearch').val()};
            $('#gridActivities').bootstrapTable('refresh');
        });
    }

    this.searchEnterKey = function(){
        $('#inputSearch').keyup(function(e){
            if(e.keyCode == 13)
            {
                console.log('ok');
                $('.btnSearch').trigger('click')
            }
        });
    }

    this.acutocompleteActivities = function(data){
        console.log('ok.....');
        console.log(data);
        $("#listActivities").autocomplete({
            source: Object.values(data),
            focus: function( event, ui ) {
                $("#listActivities").val(ui.item.label);
                return false;
            },
            select: function (event, ui){
                console.log(ui);
                console.log(ui.item.label);
                $("#idActivity").val(ui.item.value);
                $("#listActivities").val(ui.item.label);
                $("#clearActivity").show();
                return false;
            }
        });
    }

    this.clearActivity = function(){
        $(document).on('click', '.clearActivity', function(){
            $("#idActivity").val("");
            $('#listActivities').val("");
            $("#clearActivity").hide();
        });
    }

    this.isShow = function(){
        if($('#formAction').val() == "show"){
            $('input[type="text"], textarea, select').attr('readonly', true).attr('disabled', true);
        }
        console.log($('#formAction').val());
        console.log("??");
    }
}).apply(Activity);

