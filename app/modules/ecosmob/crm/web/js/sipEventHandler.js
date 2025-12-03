$(document).ready(function () {

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('../sw.js?v=' + Date.now()).then(function(registration) {
            console.warn('Service Worker registered = '+ registration);
            notificationReg = registration;
            // Wait until service worker becomes active
            return new Promise(function(resolve, reject) {
                if (registration.active) {
                    resolve(registration.active);
                } else {
                    registration.addEventListener('updatefound', function() {
                        if (registration.active) {
                            resolve(registration.active);
                        } else {
                            reject('Service Worker failed to become active');
                        }
                    });
                }
            });
        }).then(function(serviceWorker) {
            console.warn('Service Worker ready');
            // Now you can show notifications or perform other tasks
        }).catch(function(error) {
            console.error('Service Worker registration failed:', error);
        });
    } else {
        console.error('Service Worker is not supported by this browser');
    }

    $.ajax({
        async: true,
        data: '',
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/login-agent",
        success: function (result) {

        }
    });
    callscript();

    makeButtonDisabled();
    $("#disabled-dial-pad").removeClass('d-none');
    $("#dial-pad").addClass('d-none');

    $("#agent_iframe").each(function () {
        //Using closures to capture each one
        var iframe = $(this);
        iframe.on("load", function () { //Make sure it is fully loaded
            iframe.contents().click(function (event) {
                iframe.trigger("click");
            });
        });

        iframe.click(function () {
            if($('#dialPadModal:visible').length == 1)
            {
                $('#dialPadModal').toggle();
                $('body').toggleClass("dialer-open");
            }
        });
    });

    if (window.localStorage.getItem("crmupdatedlead")) {

        var jsonUpdatedLead = window.localStorage.getItem("crmupdatedlead");
        var updatedLead = JSON.parse(jsonUpdatedLead);

        $('#leadgroupmember-lg_first_name').val(updatedLead.crmlist.lg_first_name);
        $('#leadgroupmember-lg_last_name').val(updatedLead.crmlist.lg_last_name);
        $('#lg_contact_number').val(updatedLead.crmlist.lg_contact_number);
        $('#leadgroupmember-lg_contact_number_2').val(updatedLead.crmlist.lg_contact_number_2);
        $('#leadgroupmember-lg_email_id').val(updatedLead.crmlist.lg_email_id);
        $('#leadgroupmember-lg_address').val(updatedLead.crmlist.lg_address);
        $('#leadgroupmember-lg_alternate_number').val(updatedLead.crmlist.lg_alternate_number);
        $('#leadgroupmember-lg_pin_code').val(updatedLead.crmlist.lg_pin_code);
        $('#leadgroupmember-lg_permanent_address').val(updatedLead.crmlist.lg_permanent_address);
        $('#leadcommentmapping-comment').val(updatedLead.comment);
        $('#pk_id').val(updatedLead.pk_id);
        $('#lg_id').val(updatedLead.lg_id);
        $('#lg_id1').val(updatedLead.lg_id);

    } else if (window.localStorage.getItem("crm")) {

        var jsonString = window.localStorage.getItem("crm");
        var result = JSON.parse(jsonString);
        $('#leadgroupmember-lg_first_name').val(result.lg_first_name);
        $('#leadgroupmember-lg_last_name').val(result.lg_last_name);
        $('#lg_contact_number').val(result.lg_contact_number);
        $('#leadgroupmember-lg_contact_number_2').val(result.lg_contact_number_2);
        $('#leadgroupmember-lg_email_id').val(result.lg_email_id);
        $('#leadgroupmember-lg_address').val(result.lg_address);
        $('#leadgroupmember-lg_alternate_number').val(result.lg_alternate_number);
        $('#leadgroupmember-lg_pin_code').val(result.lg_pin_code);
        $('#leadgroupmember-lg_permanent_address').val(result.lg_permanent_address);
        $('#leadcommentmapping-comment').val(result.comment);
        $('#pk_id').val(result.pk_id);
        $('#lg_id').val(result.lg_id);
        $('#lg_id1').val(result.lg_id);
    }

    $('#agentdispositionmapping-disposition').on('change', function(){
        if($(this).val() != ''){
            $('#dis-sub').attr('disabled', false);
            $('#dis-sub').addClass('amber');
        }else{
            $('#dis-sub').attr('disabled', true);
            $('#dis-sub').removeClass('amber');
        }
    });
});
$(document).on("click", "#hang-up-call", function () {

    if(currentCall){
        switch (currentCall.state) {
            case 'Initial':
                if (callDirection === 'outgoing') {
                    currentCall.cancel();
                }
                if (callDirection === 'incoming') {
                    currentCall.reject({
                        statusCode: 486,
                        reasonPhrase: 'Busy Here'
                    });
                }
            case 'Establishing':
                if (callDirection === 'outgoing') {
                    currentCall.cancel();
                }
                if (callDirection === 'incoming') {
                    currentCall.reject({
                        statusCode: 486,
                        reasonPhrase: 'Busy Here'
                    });
                }
                break;
            case 'Established':
                hanupCause = "NORMAL_CLEARING";
                updateCallStatus();
                // An established session
                currentCall.bye()
                    .then(() => {
                        let tracks = audioContainer.srcObject.getTracks();
                        tracks.forEach(track => track.stop());
                        makeButtonDisabled();
                        deleteActiveCallStatus();

                    }).catch((error) => {
                    console.log("Error in Bye ", error);
                });
                break;
            case 'Terminating':
            case 'Terminated':
                break;
        }
        currentCall = '';
        if (callDirection === 'outgoing') {
            outgoingSession = '';
        }
        if (callDirection === 'incoming') {
            incomingSession = '';
        }
        $('#modal1').modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
        $('#modal1').modal('open');
        is_disp_submit = false; //20-1-2020
    }

});

$(document).on("click", "#acceptCall", function () {
    if (!currentCall) {
        $('#incomingCall').modal('close');
        return false;
    }
    currentCall.accept();
});
$(document).on("click", "#rejectCall", function () {
    if (!currentCall) {
        $('#incomingCall').modal('close');
        return false;
    }
    currentCall.reject();
    /*callHangupBtn();

    if(isPause == 1){
        $('#disable-pause').removeClass('d-none');
        $('#pause').addClass('d-none');
    }else{
        $('#disable-resume').removeClass('d-none');
        $('#resume').addClass('d-none');
    }

    $('#disable-dialnext').addClass('d-none');
    $('#dialNext').removeClass('d-none');

    $('#modal1').modal();
    $('#modal1').modal('open');
    is_disp_submit = false; //20-1-2020
    $('#incomingCall').modal('close');*/
});
$(".digit").on('click', function () {
    var num = ($(this).clone().children().remove().end().text());
    if (count < 17) {
        $("#output").val($('#output').val() + num.trim());
        count++
    }
});

$("#clear").click(function () {
   // $("#output :last").remove();
    //let value = $('#output').val();
    $('#output').val(
        function(index, value){
            return value.substr(0, value.length - 1);
        })
    count = $('#output').val().length;
});

$(document).on("click", "#call", function () {
    if (count) {
        is_m_call = true;
        makeCallDialPad();
        // $("#dialPadModal").modal('close');
        $('#dialPadModal').css("display", "none");
        $('body').removeClass("dialer-open");
        $("#disabled-dial-pad").removeClass('d-none');
        $("#dial-pad").addClass('d-none');

        $("#output").val('');
        count = 0;
    }
});
/*$("#output").on('keydown',function (evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if(charCode == 8 || charCode == 46){
        $('#output').val(
            function(index, value){
                return value.substr(0, value.length - 1);
            });
        count = $('#output').val().length;
    }
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43) {
        return false;
    }
    if (count < 17) {
        count = $('#output').val().length;
        return true;
    }

    return false;

});*/
$("body").on("click",function (e) {
    if(e.target.id != 'dial-pad') {
        if ($(e.target).closest('#dialPadModal').length == 0) {
            if ($('#dialPadModal:visible').length == 1) {
                $('#dialPadModal').toggle();
                $('body').toggleClass("dialer-open");
            }
        }
    }
});
$(document).on("submit", "#submit-disposition-form", function (e) {
    e.preventDefault();
    submitDisposition();
    if (campaignType == 'Blended' && campaignDialerType == 'AUTO') {
        //updateHangUpStatus();
    }
    document.getElementById("submit-disposition-form").reset();
    $('#modal1').modal('close');
});
$(document).on("click", ".crm", function () {
    callcrm();
});
$(document).on("click", ".script", function () {
    callscript();
});
$(document).on("click", ".pause", function (e) {
    if ($('.pause').attr('src') == urlDisablePause) {
        return false;
    }
    submitPause();
});
$(document).on("click", ".resume", function (e) {
    submitResume();
});
$(document).on("click", "#user_logout", function (e) {
    console.log("User Logout");
    console.log(" Logout is_call_active => " + is_call_active);
    console.log(" Logout is_stop_playback => " + is_stop_playback);
    if (is_call_active == false)// && is_stop_playback == true)
    {
        window.localStorage.removeItem("crmupdatedlead");
        window.localStorage.removeItem("crm");
        $.ajax({
            type: 'POST',
            url: baseURL + "index.php?r=auth/auth/logout",
            async: false,
            success: function (result) {
                window.location.href = baseURL + "index.php?r=auth/auth/logout";
            }
        });
    } else {
        console.log("Logout");
        $('#logoutModal').modal('open');
    }
});
$(document).on("submit", "#lead-ajax-form", function (e) {
    e.preventDefault();
    updateLead();
});
$(document).on("click", ".cancel", function (e) {
    e.preventDefault();
    cancelLead();
});
$(document).on("click", "#mute-call", function (e) {
    muteCall();
});
$(document).on("click", "#unmute-call", function (e) {
    unmuteCall();
});
$(document).on("click", "#hold-call", function (e) {
    unholdCall();
});
$(document).on("click", "#unhold-call", function (e) {
    holdCall();
});
$(document).on("click", "#transfer-call", function () {
    if(currentCall){
        $('#transferCallModal').modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
        $('#transferCallModal').modal('open');
    }
});
$(document).on("click", "#dialNext", function () {
    $('#dialNext').addClass('d-none');
    $('#disable-dialnext').removeClass('d-none');
    calldialNext();
});
$(document).on("click", "#transferCallBtn", function () {
    transferCall();
    $('#transferCallModal').modal('close');
    $('#destination-number').val('');
});
timer.addEventListener('secondsUpdated', function () {
    currentCallTime = timer.getTimeValues().toString();
    $('.call-timer').html(currentCallTime);
});

document.getElementById('output').addEventListener('input', function (e) {
    // Define the valid characters
    const validChars = /^[0-9+]*$/;

    // If the input value does not match the valid characters, remove the invalid characters
    if (!validChars.test(e.target.value)) {
        e.target.value = e.target.value.replace(/[^0-9+]/g, '');
    }else{
        count++;
    }
    if (count < 18) {
        count = $('#output').val().length;
    }else{
        $('#clear').trigger('click');
        return false;
    }
});
