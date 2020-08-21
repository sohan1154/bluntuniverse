//Show tooltip on hover for select box
$(document).on('change', 'select', function(){
    $(this).attr('title', $(this).find("option:selected").text());
});

function resetFormFilters(){
    $('#recordSearch')[0].reset();
    loadSearchTable('#recordSearch', '#listRecords', 1, function() {
        // table reloaded
    });
}

// Clear form all data
$(document).on('click', "button.clear-all-btn", function() {   
    console.log('cleared');
    $(this).closest('form')[0].reset();
});

/**
 * ajax function for status-change 
 */
function changeStatus(RecordID) {

    // start loader 
    $('#RecordID_' + RecordID).loading({
        message: input_loading_max
    });

    alertify.confirm(
            'Status Change',
            'Are you sure? you want to change this record status.',
            function () {
                // success 

                let url = $('#RecordID_' + RecordID + ' span.changeStatus').attr('data-href');
                console.log('url:', url);

                let data = {id : RecordID}

                $.ajax({
                    url: url,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                        console.log('response:', response);

                        if (response.status == 'success') {
                            alertify.success(response.message);

                            // change text & stop loader 
                            $('#RecordID_' + RecordID + ' span.changeStatus').html(response.text);
                            $('#RecordID_' + RecordID).loading('stop');
                        } else if (response.status == 'error') {
                            $('#RecordID_' + RecordID).loading('stop');
                            alertify.error(response.message);
                        } else {
                            $('#RecordID_' + RecordID).loading('stop');
                            alertify.error('Error in post, Please try again.');
                        }
                    }
                })
            },
            function () {
                // cancel 
                $('#RecordID_' + RecordID).loading('stop');
            }
    );
}

/**
 * ajax function for verify record
 */
function verifyRecord(RecordID) {

    // start loader 
    $('#RecordID_' + RecordID).loading({
        message: input_loading_max
    });

    //var tableModel = $('#listingTable').attr('table-model');
    //console.log('tableModel:', tableModel)

    alertify.confirm(
            'Verification Confirmation', // confirmbox title
            'Are you sure you want to verify this record?', // message
            function () {
                // success 
                
                let url = $('#RecordID_' + RecordID + ' a.verifyRecord').attr('data-href');
                console.log('url:', url)

                let data = {id : RecordID}
                
                $.ajax({
                    url: url,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                        //console.log(response);
                        if (response.status == 'success') {
                            alertify.success(response.message);

                            // stop loader & hide row 
                            $('#RecordID_' + RecordID).loading('stop');
                            $('#RecordID_' + RecordID + ' a.verifyRecord').addClass('hide');
                        } else if (response.status == 'error') {
                            $('#RecordID_' + RecordID).loading('stop');
                            alertify.error(response.message);
                        } else {
                            $('#RecordID_' + RecordID).loading('stop');
                            alertify.error('Error in post, Please try again.');
                        }
                    }
                });
            },            
            function () {
                // cancel 
                $('#RecordID_' + RecordID).loading('stop');
            }
    ).set({transition:'fade', defaultFocus: 'cancel'});
}

/**
 * ajax function for delete record
 */
function deleteRecord(RecordID) {

    // start loader 
    $('#RecordID_' + RecordID).loading({
        message: input_loading_max
    });

    //var tableModel = $('#listingTable').attr('table-model');
    //console.log('tableModel:', tableModel)

    alertify.confirm(
            'Delete Confirmation', // confirmbox title
            'Are you sure you want to delete this record?', // message
            function () {
                // success 
                
                //var url = BASEURL + '/admin/delete-record/' + tableModel + '/' + RecordID;
                let url = $('#RecordID_' + RecordID + ' a.deleteRecord').attr('data-href');
                console.log('url:', url)

                let data = {id : RecordID}
                
                $.ajax({
                    url: url,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                        //console.log(response);
                        if (response.status == 'success') {
                            alertify.success(response.message);

                            // stop loader & hide row 
                            $('#RecordID_' + RecordID).loading('stop');
                            $('#RecordID_' + RecordID).addClass('hide');
                        } else if (response.status == 'error') {
                            $('#RecordID_' + RecordID).loading('stop');
                            alertify.error(response.message);
                        } else {
                            $('#RecordID_' + RecordID).loading('stop');
                            alertify.error('Error in post, Please try again.');
                        }
                    }
                });
            },            
            function () {
                // cancel 
                $('#RecordID_' + RecordID).loading('stop');
            }
    ).set({transition:'fade', defaultFocus: 'cancel'});
}

// Common alertify prompt box
function alertifyPrompt(title, message) {
       
    alertify.alert(
            title, 
            message,
            function(){ 
                // success                
                console.log('success');     
            }
        ).set({transition:'fade'});
}

function datetimepicker()
{
    $('.datePicker').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent:false,
        showClear:true,
        keepInvalid:true,
        ignoreReadonly:true,
        allowInputToggle:true,
        minDate: moment()
    });
}

// pick time only
function timePicker()
{
    $('.timePicker').datetimepicker({
        format: 'HH:mm',
        ignoreReadonly:true,
        stepping: 5,
        useCurrent: false,
        disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: 8 })], [moment({ h: 18, m: 1 }), moment({ h: 24 })]],
    });
}

function dateyearpicker()
{
    $('.dateyearpicker').datetimepicker({
        format: 'YYYY',
        viewMode: 'years',
        maxDate: moment(),
        showClear:true,
        keepInvalid:true,
        useCurrent: false,
        ignoreReadonly:true,
    });
}

// pick hours time only
function hourPicker()
{
    $('.hourPicker').datetimepicker({
        format: 'HH:mm',
        ignoreReadonly:true,
        useCurrent:false,
        keepInvalid:true,
    });
}

/**
 * password confirm-password field matched
 */
$(function() {

    if(document.getElementsByName("password").length > 0) {
        var password = document.getElementById("password")
        , confirm_password = document.getElementById("confirm_password");

        function validatePassword(){
            if(password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }
        
        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    }
})

/**
 * auto hide flash message 
 */
$(function(){
    $("#flash-message-success").fadeTo(5000, 2000).slideUp(2000, function(){
        $("#flash-message-success").slideUp(2000);
    });
    $("#flash-message-error").fadeTo(20000, 2000).slideUp(2000, function(){
        $("#flash-message-error").slideUp(2000);
    });
    $("#flash-message-warning").fadeTo(20000, 2000).slideUp(2000, function(){
        $("#flash-message-warning").slideUp(2000);
    });
})

$(function () {
    $('.start_datetimepicker').datetimepicker();
    $('.end_datetimepicker').datetimepicker({
        useCurrent: false
    });
    $(".start_datetimepicker").on("dp.change", function (e) {
        $('.end_datetimepicker').data("DateTimePicker").minDate(e.date);
    });
    $(".end_datetimepicker").on("dp.change", function (e) {
        $('.start_datetimepicker').data("DateTimePicker").maxDate(e.date);
    });
});

function copyClipboard(RecordID) {

    let copyText = $('#RecordID_' + RecordID + ' span.image-path').attr('data-image-path');

    let tempInput = document.createElement("input");
    tempInput.value = copyText;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    $('#RecordID_' + RecordID + ' a.copied').text('Copied');

    // /* Get the text field */
    // // let copyText = $('#RecordID_' + RecordID + ' span.image-path').attr('data-image-path');
    // let copyText = document.getElementById("image-path-"+RecordID);
  
    // /* Select the text field */
    // copyText.select();
    // copyText.setSelectionRange(0, 99999); /*For mobile devices*/
  
    // /* Copy the text inside the text field */
    // document.execCommand("copy");

    // console.log('copyClipboard')
  
    // /* change button label Copied */
    // $('#RecordID_' + RecordID + ' a.copied').text('Copied');
}

$(".title-for-slug").keyup(function(){

    let text = $(this).val();

    let slug = text.toLowerCase().replace(/ /g,'-').replace('/\s/g','-');

    $("#slug").val(slug);    
});
