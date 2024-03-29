let Activity = {};
let additionalParams = {};
let listActivities = {};

$(document).ready(function() {
    Activity.onLoad();
    Activity.formValidate();
    Activity.formSave();
});

function ajaxRequest(params){
    Activity.loadGrid(params);
}

(function () {
    this.onLoad = function(){
        //datePicker
        $('#datePickerStart, #datePickerEnd').datepicker({
            showOn: "button",
            buttonImage: "/img/schedule.png",
            buttonImageOnly: true,
            buttonText: "Select date"
        });

        //Select typeActivity
        $("#typeActivity").change(function(){
            if($("#typeActivity").val() == 1){
                $("#activities").show();
                $("#activitiesSelect").attr('required','required');
                $("#activities > div.form-group > label").html('Activity:<span class="required">*</span>');
                //Get Activities
                Activity.getActivities();
            }else{
                $("#activities, #clearActivity").hide();
                $("#activitiesSelect").removeAttr('required');
                $("#activities > div.form-group > label").html('Activity:');
            }
        });

        //showSearch
        $(document).on('click', '.displaySearch', function(){
            $('.search').show();
            $('.displaySearch').hide();
        });

        //hideSearh
        $(document).on('click', '.btnClose', function(){
            $('#inputSearch').val('');
            additionalParams = {};
            $('.search').hide();
            $('.displaySearch').show();
            $('#gridActivities').bootstrapTable('refresh');
        });

        //search
        $(document).on('click', '.btnSearch', function(){
            additionalParams = {search: $('#inputSearch').val()};
            $('#gridActivities').bootstrapTable('refresh');
        });

        //searchEnterKey
        $('#inputSearch').keyup(function(e){
            if(e.keyCode == 13)
            {
                $('.btnSearch').trigger('click')
            }
        });

        //clearActivity
        $(document).on('click', '.clearActivity', function(){
            Activity.getActivities();
            $("#idActivity").val("");
            $('#listActivities').val("");
            $("#clearActivity").hide();
        });

        //isShow
        if($('#formAction').val() == "show"){
            $('input[type="text"], textarea, select').attr('readonly', true).attr('disabled', true);
        }

        if($('#id').val() != ""){
            $('#observations').attr('readonly', true).attr('disabled', true);
        }

        //currentlyWorking
        $('.currentlyWorking').on('click', function(){
            var isCheck = $(this).is(':checked');
            if(isCheck)
                $('#divDatePickerEnd').hide();
            else{
                var now = new Date();
                var dateEnd = (String(parseInt(now.getMonth()) + 1)).padStart(2, '0') + "/" + String(now.getDate()).padStart(2, '0') + "/" + now.getFullYear();
                $('#datePickerEnd').val(dateEnd);    
                $('#divDatePickerEnd').show();
            }
        });

        var activityId = $('#id');
        var activityStatus = $('#status');
        if(activityId.val() != 0){
            if(activityStatus.val() == 1){
                $('.currentlyWorking').prop('checked', false);
                $('#divDatePickerEnd').show();
                $('#divCurrentlyWorking').hide();
            }
        }

        //addObservation
        $(document).on('click', '.addObservation', function(){
            //loadGridObservations
            if($('#id').val() != "" && $('#id').val() != undefined){
                var urlGridObservations = "/observations/index";
                $.ajax({
                    method: 'POST',
                    dataType: 'json',
                    url: urlGridObservations,
                    data: { activityId: $('#id').val()},
                    success: function(response) {
                        $('#gridObservations').bootstrapTable({
                            data: response.body.data
                        });
                    }
                });
            }
            $('.addObservations').modal('show');
        });

        $(document).on('click', '#btnSaveObservation', function(){
            //saveObservation
            var urlGridObservations = "/observations/add";
            $.ajax({
                method: 'POST',
                dataType: 'json',
                url: urlGridObservations,
                data: { 
                    activityId: $('#id').val(),
                    observation: $('#observation').val()
                },
                beforeSend:function(){
                    $('#bodyGridObservations').html('');
                    $('#bodyGridObservations').append('<tr><td colspan="2"><div class="alert alert-warning text-center generando hide"><i class="fa fa-spinner fa-spin fa-4x fa-fw"></i><h4>Guardando observación.</h4></div></td></tr>');
                },
                success: function(response) {
                    $('#observation').val('');
                    $('#gridObservations').bootstrapTable("destroy");
                    $('#gridObservations').bootstrapTable({
                        data: response.observations.body.data
                    });
                    toastr.success(response.data.body.message);
                }
            });
        });
    }

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

    this.loadGrid = function(params){
        if(additionalParams.search != undefined){
            params.data.search = additionalParams.search;
        }

        var url = "/activities/getListActivitiesPaginated";
        $.get(url + '?' + $.param(params.data)).then(function (response){
            params.success(response);
        });
    }

    this.formSave = function(){
        $("#btnSaveChanges").unbind('click').bind('click', function(event) {
            var isValid = $("#formNewActivity").valid();
            if(isValid){
                $("#formNewActivity").submit();
            }else{
                toastr.error('Please, complete the required fields for continue.')
            }
            Activity.formValidate();
        });
    }

    this.acutocompleteActivities = function(data){
        $("#listActivities").autocomplete({
            source: Object.values(data),
            focus: function( event, ui ) {
                $("#listActivities").val(ui.item.label);
                return false;
            },
            select: function (event, ui){
                $("#idActivity").val(ui.item.value);
                $("#listActivities").val(ui.item.label);
                $("#clearActivity").show();
                return false;
            }
        });
    }

    this.getActivities = function(){
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
                listActivities = data.body.data;
                Activity.acutocompleteActivities(listActivities);
            },
            error: function (xhr, textStatus, errorThrown) {
            }
        });
    }
}).apply(Activity);

