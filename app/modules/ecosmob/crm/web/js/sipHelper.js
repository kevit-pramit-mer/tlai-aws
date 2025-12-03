function checkWebSocketConnection() {
    if (UserAgent.transport.ws.readyState === WebSocket.OPEN) {
        console.log('WebSocket is connected.');
    } else {
        console.log('WebSocket is not connected.');
    }
}
function setCallStatus(status){
    callState = status;
}
function callHangupBtn() {
    makeButtonDisabled();
    cleanupMedia();
}
function updateRingingStatus() {

    var uuIdStatus = callID;
    $.ajax({
        async: false,
        data: 'uuIdCallStatus=' + uuIdStatus,
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/ringing-status-update",
        success: function (result) {
        }
    });
}
function updateAnswertime() {
    var uuIdStatus = callID;
    $.ajax({
        async: false,
        data: 'uuIdCallStatus=' + uuIdStatus,
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/ringing-status-update&anstime=true",
        success: function (result) {
        }
    });
}

function updateCallStatus() {
    var uuIdStatus = callID;
    var activeStateName = hanupCause;
    $.ajax({
        async: false,
        data: {uuIdStatus: uuIdStatus, activeStateName: activeStateName},
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/call-status-update",
        success: function (result) {
        }
    });
}
function updateAnswerStatus() {
    var uuIdStatus = callID;
    $.ajax({
        async: false,
        data: 'uuIdCallStatus=' + uuIdStatus,
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/answer-update",
        success: function (result) {

            timer.stop();
            timer.start({
                startValues: {countdown: false, seconds: parseInt(result)}
            });
        }
    });
}
function callcrm() {
    $.ajax({
        async: false,
        url: baseURL + "index.php?r=crm/crm/crm",
        success: function (result) {
            $('#form_elem').html(result);
        }
    });
}
function callscript() {

    var activeQueueName = currentQueueName;
    if(currentCall != null &&  currentCall.request.getHeader('X-campaign_id')){
        activeCampaignName = currentCall.request.getHeader('X-campaign_id');
    }else{
        activeCampaignName = '';
    }

    if (currentCall != null && currentCall.request.getHeader('X-cc_queue_id')) {
        activeQueueId = currentCall.request.getHeader('X-cc_queue_id');
    }else{
        activeQueueId = '';
    }
    currentQueueId = activeQueueId;

    $.ajax({
        async: false,
        data: {activeQueueId:activeQueueId,activeCampaignName:activeCampaignName,activeQueueName:activeQueueName},
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/script",
        success: function (result) {
            if(result !== null ){
                console.log("result===" + result);
                $('#Script').html(result);
            }
        }
    });
}
function submitPause() {
    $.ajax({
        async: false,
        //data: typeData,
        //type: 'POST',
        url: baseURL + "index.php?r=crm/crm/pause-effect",
        success: function (result) {
        }
    });
    $('.pause').hide();
    $('.resume').show();
}
function submitResume() {
    $.ajax({
        async: false,
        //data: typeData,
        //type: 'POST',
        url: baseURL + "index.php?r=crm/crm/resume-effect",
        success: function (result) {
        }
    });
    $('.pause').show();
    $('.resume').hide();
}
function updateHangUpStatuscustom() {
    $.ajax({
        async: false,
        url: baseURL + "index.php?r=crm/crm/hangup-updatecustom",
        success: function (result) {
        }
    });
}
function updateHangUpStatus() {
    setTimeout(function () {
        $.ajax({
            async: false,
            url: baseURL + "index.php?r=crm/crm/hangup-update",
            success: function (result) {
            }
        });
    }, 2000);
}
function clearTimer(){
    timer.stop();
    $(".call-timer").html("");
}
function cancelLead() {
    $.ajax({
        async: false,
        data: $('#lead-ajax-form').serializeArray(),
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/cancel-lead",
        success: function (result) {
            var final_data = JSON.parse(result);
            console.log(final_data);
            if(final_data.db_array) {
                $("#leadgroupmember-lg_first_name").val(final_data.db_array.lg_first_name);
                $('#leadgroupmember-lg_last_name').val(final_data.db_array.lg_last_name);
                $('#lg_contact_number').val(final_data.db_array.lg_contact_number);
                $('#leadgroupmember-lg_contact_number_2').val(final_data.db_array.lg_contact_number_2);
                $('#leadgroupmember-lg_email_id').val(final_data.db_array.lg_email_id);
                $('#leadgroupmember-lg_address').val(final_data.db_array.lg_address);
                $('#leadgroupmember-lg_alternate_number').val(final_data.db_array.lg_alternate_number);
                $('#leadgroupmember-lg_pin_code').val(final_data.db_array.lg_pin_code);
                $('#leadgroupmember-lg_permanent_address').val(final_data.db_array.lg_permanent_address);
                $('#leadcommentmapping-comment').val(final_data.db_array_comment.comment);
            }
        }
    });
}
function updateLead() {

    $.ajax({
        async: false,
        data: $('#lead-ajax-form').serializeArray(),
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/update-lead",
        success: function (result) {
            if (!window.localStorage.getItem('crmupdatedlead')) {
                window.localStorage.setItem('crmupdatedlead', result);
            }
            if (typeof (Storage) !== "undefined") {
                window.localStorage.setItem('crmupdatedlead', result);
                var jsonString = window.localStorage.getItem("crmupdatedlead");
                var final_data = JSON.parse(jsonString);
            } else {
                alert("Sorry, your browser does not support web storage...");
            }
            var final_data = JSON.parse(result);
            alert(final_data.message);

        }
    });
}
function makeButtonDisabled(){
    $('#hang-up-call').addClass('d-none');
    $('#mute-call').addClass('d-none');
    $('#unmute-call').addClass('d-none');
    $('#unhold-call').addClass('d-none');
    $('#hold-call').addClass('d-none');
    $('#transfer-call').addClass('d-none');

    $("#disabled-dial-pad").addClass('d-none');
    $("#dial-pad").removeClass('d-none');
}
function makeButtonEnable(){
    $('#hang-up-call').removeClass('d-none');
    $('#mute-call').removeClass('d-none');
    $('#unhold-call').removeClass('d-none');
    $('#transfer-call').removeClass('d-none');

    $("#disabled-dial-pad").removeClass('d-none');
    $("#dial-pad").addClass('d-none');
}
function muteCall(){
    if (currentCall){
        customSIPModule.muteCall(currentCall);
        $('#unmute-call').removeClass('d-none');
        $('#mute-call').addClass('d-none');
        // CALL MUTE
        if (typeof (Storage) !== "undefined") {
            window.localStorage.setItem('call_mute', true);
        }
    }

}
function unmuteCall(){

    if (currentCall){
        customSIPModule.unmuteCall(currentCall);
        $('#unmute-call').addClass('d-none');
        $('#mute-call').removeClass('d-none');
        if (typeof (Storage) !== "undefined") {
            window.localStorage.setItem('call_mute', false);
        }
    }
}
function holdCall(){
    if(currentCall){
        customSIPModule.holdCall(currentCall);

        // CALL HOLD
        if (typeof (Storage) !== "undefined") {
            window.localStorage.setItem('call_hold', true);
        }
        $("#unhold-call").addClass('d-none');
        $("#hold-call").removeClass('d-none');
    }
}
function unholdCall(){
    if(currentCall){

        customSIPModule.unholdCall(currentCall);

        // CALL HOLD
        if (typeof (Storage) !== "undefined") {
            window.localStorage.setItem('call_hold', true);
        }
        $('#unhold-call').removeClass('d-none');
        $("#hold-call").addClass('d-none');
    }
}
function transferCall() {

    var destinationNumber = $('#destination-number').val();
    if (destinationNumber) {
        //currentCall.transfer(destinationNumber);
        const transferTarget = SIP.UserAgent.makeURI(`sip:${destinationNumber}` + "@" + domainName);

        console.log("BTXtransfer::" + transferTarget)
        if (!transferTarget) {
            throw new Error("Failed to create transfer target URI.");
        }

        if(currentCall){
            currentCall.refer(transferTarget, {
                // Example of extra headers in REFER requestsip
                requestDelegate: {
                    onAccept() {

                    }
                }
            });
        }

    }
}
function cleanupMedia(){
    let mediaElement = audioContainer;
    // mediaElement.srcObject = remoteVideoElement.srcObject = localVideoElement.srcObject = null;
    const remoteVideoElement = document.getElementById('remoteVideo');
    if (typeof (remoteVideoElement) != 'undefined' && remoteVideoElement != null) {
        remoteVideoElement.srcObject = null;
    }
    const localVideoElement = document.getElementById('localVideo');
    if (typeof (localVideoElement) != 'undefined' && localVideoElement != null) {
        localVideoElement.srcObject = null;
    }
    //If it isn't "undefined" and it isn't "null", then it exists.
    mediaElement.srcObject = null;
    mediaElement.pause();
}
function activeCallScreen() {
    if (is_stop_playback == true) {
        return;
    }
    var activeCallerId = currentCall.request.getHeader('X-caller_id_number');
    var activeCallId = callID;
    if (currentCall.request.getHeader('X-destination_number')) {
        var activeDestiNumber = currentCall.request.getHeader('X-destination_number');
    }else if(currentCall.request.getHeader('X-did_number')){
        var activeDestiNumber = currentCall.request.getHeader('X-did_number');
    }else {
        var activeDestiNumber = currentCall.request.getHeader('X-remote_caller_id_number');
    }

    var activeStateName = callState;

    if (currentCall.request.getHeader('X-agent_id')) {
        var activeAgentName = currentCall.request.getHeader('X-agent_id');
    } else {
        var activeAgentName = agent_id;

    }
    if(currentCall.request.getHeader('X-campaign_id')){
        var activeCampaignName = currentCall.request.getHeader('X-campaign_id');
    }else{
        var activeCampaignName = '';
    }

    if (currentCall.request.getHeader('X-queue_join_time')) {
        queue_join_time = currentCall.request.getHeader('X-queue_join_time');
        console.log(" queue_join_time " + queue_join_time);
    }

    if (currentCall.request.getHeader('X-cc_queue')) {
        var activeQueueName = currentCall.request.getHeader('X-cc_queue');
    } else {
        var activeQueueName = '';
    }
    let whisperUuid = '';
    if (currentCall.request.getHeader('X-whisper_uuid')) {
        whisperUuid = currentCall.request.getHeader('X-whisper_uuid');
    }

    $.ajax({
        data: {
            activeCallerId: activeCallerId,
            activeCallId: activeCallId,
            activeDestiNumber: activeDestiNumber,
            activeStateName: activeStateName,
            activeAgentName: activeAgentName,
            activeCampaignName: activeCampaignName,
            activeQueueName: activeQueueName,
            whisperUuid:whisperUuid
        },
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/active-call",
        success: function (result) {
        }
    });
}
function deleteActiveCallStatus() {
    console.log("deleteActiveCallStatus() is_stop_playback =>>>> " + is_stop_playback);
    if (is_stop_playback == true) {
        is_stop_playback = false;
        return;
    }
    var uuId = callID;
    $.ajax({
        async: false,
        data: 'uuID=' + uuId,
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/active-call-delete",
        success: function (result) {
        }
    });
}
function updateCallEndTime() {
    var uuId = callID;
    $.ajax({
        async: false,
        data: 'callId=' + uuId,
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/update-call-end-time",
        success: function (result) {
        }
    });
}

function setupTimer(){
    $(".call-timer").show();
}
function callProgresiveType() {
    if (currentCall.request.getHeader('X-lead_id')) {
        var leadId = currentCall.request.getHeader('X-lead_id');
    } else if (currentCall.request.getHeader('X-remote_caller_id_number')) {
        var leadCallerIdNumber = currentCall.request.getHeader('X-remote_caller_id_number');
    }
    if (currentCall.request.getHeader('X-cc_queue_id')) {
        currentQueueId = currentCall.request.getHeader('X-cc_queue_id');
    }
    if (currentCall.request.getHeader('X-cc_queue')) {
        currentQueueName = currentCall.request.getHeader('X-cc_queue');
    }
    //callDirection = 'outgoing';
    $.ajax({
        type: 'POST',
        data: {leadCallerIdNumber: leadCallerIdNumber, lead_id: leadId, activeQueueId: currentQueueId, activeQueueName: currentQueueName },
        async: false,
        url: baseURL + "index.php?r=crm/crm/progresive-data",
        success: function (data)  {

            if (data.length) {

                if (!window.localStorage.getItem('crm')) {
                    window.localStorage.setItem('crm', data);
                }
                if (typeof (Storage) !== "undefined") {
                    window.localStorage.setItem('crm', data);
                    var jsonString = window.localStorage.getItem("crm");
                    var result = JSON.parse(jsonString);
                    window.localStorage.removeItem("crmupdatedlead");
                } else {
                    alert("Sorry, your browser does not support web storage...");
                }

                if (result.data_found) {
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
                    console.log('comment from if ====>', result.comment, $('#leadcommentmapping-comment'), $('#leadcommentmapping-comment').val());

                    $('#pk_id').val(result.pk_id);
                    $('#lg_id').val(result.lg_id);
                    $('#lg_id1').val(result.lg_id);
                    $('#submitbtn').show();
                    $('ul.tabs').tabs('select', 'CRM');

                } else {
                    $('#pk_id').val(result.pk_id);
                    $('#lg_id').val(result.lg_id);
                    $('#lg_id1').val(result.lg_id);
                    $('#leadcommentmapping-comment').val(result.comment);

                    $('#lg_contact_number').val(leadCallerIdNumber);
                    $('#submitbtn').show();
                }
            }
        }
    });

}
