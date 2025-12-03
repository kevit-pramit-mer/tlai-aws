var timer = new Timer();
var current_uuid = '';
var currentCallTime;
var is_call_active = false;
var is_stop_playback = false;
var is_m_call = false;
var is_disp_submit = true;
var queue_join_time = "";
var date = new Date();

var incomingSession;
var outgoingSession;
let inviter;
let inviterOptions;
let currentCall;
let audioContainer = document.getElementById("audio-container");

let ringFilePath = callRingFile;
const ringtone = new Audio(ringFilePath);
ringtone.preload = "none";
ringtone.autoplay = false;
ringtone.loop = true;

let callState;
let hanupCause = '';
let callDirection;

let username = extensionNumber;
let password = extensionPassword;
var transportOptions = {
    server: wssURL + ':' + wssPort,
};
let callID;
var currentCampaignId;
var currentQueueId;
var currentQueueName;
var notificationReg;
let activeQueueId = '';
let activeCampaignName= '';
let callUniqueId = '';

const uri = SIP.UserAgent.makeURI(`sip:${username}` + "@" + domainName);

var config = {
    uri: uri,
    authorizationPassword: password,
    authorizationUsername: username,
    delegate: {
        onInvite: (invitation) => {
            handleIncomingCall(invitation);
        }
    },
    displayName:extensionName,
    contactName:extensionNumber,
    transportOptions
};
var UserAgent = new SIP.UserAgent(config);
window.navigator.serviceWorker.addEventListener('message', (event) => {

    if(event.data.action == 'acceptCall'){
        $('#acceptCall').trigger('click');
    }
    if(event.data.action == 'rejectCall'){
        $('#rejectCall').trigger('click');
    }

});
customSIPModule.connectWs(config)
    .then((userAgent) => {
        console.log("UserAgent started:", userAgent);
        $("#disabled-dial-pad").addClass('d-none');
        $("#dial-pad").removeClass('d-none');
        registerUser(userAgent);
    })
    .catch((error) => {
        console.error("Error starting UserAgent:", error);
        $("#disabled-dial-pad").removeClass('d-none');
        $("#dial-pad").addClass('d-none');
    });

function registerUser(userAgent) {
    customSIPModule.registerUA(userAgent, registrationCallback)
        .then((userAgent) => {
            console.log("UserAgent registered:", userAgent);
            UserAgent = userAgent;
        })
        .catch((error) => {
            console.error("UserAgent registration failed:", error);
        });
}

function registrationCallback(newState) {
    console.log("Registration State:", newState);
    if(newState == 'Registered'){
        $("#disabled-dial-pad").addClass('d-none');
        $("#dial-pad").removeClass('d-none');
    }else{
        $("#disabled-dial-pad").removeClass('d-none');
        $("#dial-pad").addClass('d-none');
    }
}
function playRingToneForIncomingCall(state){
    if (state === 'Initial') {
        console.log(ringtone);
        // Check if the audio is paused or has not started playing
        if (ringtone.paused || ringtone.ended) {
            // Play the audio
            const playPromise = ringtone.play();
            if (playPromise !== undefined) {
                playPromise.catch(error => {
                    // Autoplay was prevented
                    console.error('Autoplay prevented:', error);
                    // Show a message or take other appropriate action
                });
            }
        }
    } else {
        ringtone.pause();
        ringtone.currentTime = 0;
    }
}
function showNotification(title, body) {
    // Check if the browser supports notifications
    if (!("Notification" in window)) {
        console.error("This browser does not support desktop notification");
        return;
    }

    // Check if permission is already granted
    if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        // const notificationData = {
        //     type: 'incomingCall',
        //     callerName: data.callerName,
        //     callData: data.callData // Adjust as needed
        // };
        notificationReg.showNotification("Incoming Call", {
            body: body,
            data: {
                currentCallId: `${currentCall}`
            },
            actions: [
                { action: "accept", title: "Accept" },
                { action: "reject", title: "Reject" }
            ]
        });
        // Handle notification action click

        //  var notification = new Notification(title, { body: body });
    } else if (Notification.permission !== "denied") {
        // Otherwise, we need to ask the user for permission
        Notification.requestPermission().then(function (permission) {
            // If the user accepts, let's create a notification
            if (permission === "granted") {
                var notification = new Notification(title, { body: body });
            }
        });
    }
}
function handleIncomingCall(invitation){

    callDirection = 'incoming';
    currentCall = invitation;

     let incomingNumber = currentCall.request.getHeader('X-caller_id_number');
    showNotification("Incoming Call", "You have an incoming call from "+ incomingNumber +" ! ");

    currentCall.delegate = {
        onBye: (byeRequest) => {
            handleBye(byeRequest);
        },
        onCancel: (byeRequest) => {
            handleBye(byeRequest);
        },
    };
    callID = invitation.request.getHeader('X-aleg_uuid');
    callUniqueId = callID;
    //callID = invitation.request.callId;
    if(invitation.state == 'Initial'){
        is_call_active = true;
        setCallStatus('Temporary Unavailable');
        hanupCause = 'Temporary Unavailable';
        handleRingingState();
        playRingToneForIncomingCall(invitation.state);
    }
    incomingSession = invitation;

    incomingSession.stateChange.addListener((newState) => {
        playRingToneForIncomingCall(invitation.state);
        switch (newState) {
            case "Initial":
                setCallStatus('Temporary Unavailable');
                break;
            case "Establishing":
                setCallStatus('NORMAL_CLEARING');
                console.warn("Incoming Establishing");
                break;
            case "Established":
                hanupCause = 'NORMAL_CLEARING';
                setCallStatus('NORMAL_CLEARING');
                handleAnsweringState();
                console.warn('Incoming Established');
                handleActiveState();
                setupRemoteMedia(incomingSession);
                break;
            case "Terminating":
                console.warn("Incoming Terminating");
                break;
            case "Terminated":
                setCallStatus('NORMAL_CLEARING');
                console.warn("Incoming Terminated");
                handleHangupState();
                break;
            default:
                break;
        }
    });
}
function setupRemoteMedia(session){
    let peerConnection = session.sessionDescriptionHandler.peerConnection;
    let remoteStream = new MediaStream();
    peerConnection.getReceivers().forEach((receiver) => {
        if(receiver.track){
            remoteStream.addTrack(receiver.track);
            audioContainer.srcObject = remoteStream;
            //audioContainer.play();
        }
    });
}
function closeIncomingCallPopup(){
    if($('#incomingCall').hasClass('open')){
        $('#incomingCall').modal('close');
    }
}
function handleActiveState() {
    callscript();
    is_call_active = true;
    closeIncomingCallPopup();
    console.log(currentCall.remoteIdentity.uri.toString());
    console.log(currentCall._dialog.dialogState);

    $('#connected-popup').removeClass('d-none');
    $('#connected-popup').find('.extention_number').html(currentCall.remoteIdentity._displayName);
    $("#hang-up-call.active").removeClass('d-none');
    $("#hang-up-call.disabled").addClass('d-none');

    $('#connected-popup').removeClass('d-none');

    if (callDirection == 'incoming') {
        $('#connected-popup').find('.extention_number').html(currentCall.request.getHeader('X-caller_id_number'));
    }
    if (callDirection == 'outgoing') {
        $('#connected-popup').find('.extention_number').html(currentCall.request.getHeader('X-destination_number'));

    }
    unholdCall();
    makeButtonEnable();
    updateCallStatus();
    updateAnswerStatus();

    //updateAnswerTime();
    setupTimer();
    if ((campaignType == 'Inbound') || (campaignType == 'Blended' && campaignDialerType == 'AUTO')) {
        //stopPlayback(currentCall.request.getHeader('X-aleg_uuid'));
    }
    // $('.dialnext').attr('src', urlDisableDialnext);
    $('#dialNext').addClass('d-none');
    $('#disable-dialnext').removeClass('d-none');
    if (currentCall.request.getHeader('X-agent_id')) {
        var activeAgentName = currentCall.request.getHeader('X-agent_id');
    } else {
        var activeAgentName = currentCall.request.getHeader('X-agent_id');
    }
    if (currentCall.request.getHeader('X-queue_join_time')) {
        queue_join_time = currentCall.request.getHeader('X-queue_join_time');
    }
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
function handleHangupState() {
    $('#connected-popup').addClass('d-none');
    $('#crmmodal').modal('close');

    $('#dialNext').removeClass('d-none');
    $('#disable-dialnext').addClass('d-none');

    if(isPause == 1){
        $('#disable-pause').addClass('d-none');
        $('#pause').removeClass('d-none');
    }else{
        $('#disable-resume').addClass('d-none');
        $('#resume').removeClass('d-none');
    }

    is_call_active = false;
    if(callDirection == 'incoming'){
        closeIncomingCallPopup();
    }
    $('#modal1').modal({
        dismissible: false, // Modal can be dismissed by clicking outside of the modal
    });
    $('#dis-sub').attr('disabled', true);
    $('#dis-sub').removeClass('amber');
    $('#modal1').modal('open');
    updateHangUpStatus();
    makeButtonDisabled();
    clearTimer();
    cleanupMedia();
    // updateCallStatus();
    deleteActiveCallStatus();
    updateCallEndTime();
}
function openDispositionModal(){
    $('#modal1').modal({
        dismissible: false, // Modal can be dismissed by clicking outside of the modal
    });

    $('#modal1').modal('open');
}
function closeDispositionModal(){
    $('#modal1').modal('close');
}
function handleAnsweringState(){
    updateAnswertime();
    updateAnswerStatus();
}

function outgoingSessionCallback(newState) {
    switch (newState) {
        case "Establishing":
            setCallStatus('ORIGINATOR_CANCEL');
            console.warn("outgoing Establishing");
            break;
        case "Established":
            setCallStatus('NORMAL_CLEARING');
            handleAnsweringState();
            console.warn('outgoing Established');
            // let peerConnection = outgoingSession.sessionDescriptionHandler.peerConnection;
            // let remoteStream = new MediaStream();
            // peerConnection.getSenders().forEach((sender) => {
            //     if(sender.track){
            //         remoteStream.addTrack(sender.track);
            //         audioContainer.srcObject = remoteStream;
            //     }
            // });
            hanupCause = 'NORMAL_CLEARING';
            handleActiveState();
            setupRemoteMedia(outgoingSession);
            break;
        case "Terminated":
            setCallStatus('NORMAL_CLEARING');
            console.warn("outgoing Terminated");
            handleHangupState();
            break;
        default:
            break;
    }
}
function handleTryingState(){

    $('#hang-up-call').removeClass('d-none');
    if(isPause == 1){
        $('#disable-pause').removeClass('d-none');
        $('#pause').addClass('d-none');
    }else{
        $('#disable-resume').removeClass('d-none');
        $('#resume').addClass('d-none');
    }

}
function handleRingingState(){
    is_call_active = true;
    if (typeof (Storage) !== "undefined") {
        window.localStorage.setItem('call_mute', true);
        window.localStorage.setItem('call_hold', true);
    }

    if(isPause == 1){
        $('#disable-pause').removeClass('d-none');
        $('#pause').addClass('d-none');
    }else{
        $('#disable-resume').removeClass('d-none');
        $('#resume').addClass('d-none');
    }
    // $('#transfer-call').attr('src', urlTransfercall);
    // $('.dialnext').attr('src', urlDisableDialnext);


    if (currentCall.request.getHeader('X-lead_id')) {
        callProgresiveType();
    } else if (currentCall.request.getHeader('X-remote_caller_id_number')) {
        callProgresiveType();
    }

    if (campaignType == 'Inbound') {
        if (currentCall.request.getHeader('X-auto_answer') == 'yes') {
            currentCall.accept();
        } else if (currentCall.request.getHeader('X-auto_answer') == 'no') {
            openIncomingCallPopup();
        } else if (!currentCall.request.getHeader('X-auto_answer')) {
            currentCall.accept();
        }
    } else if (campaignType == 'Outbound' && campaignDialerType == 'PREVIEW') {
        openIncomingCallPopup();
    } else if (campaignType == 'Outbound' && campaignDialerType == 'PROGRESSIVE') {
        currentCall.accept();
    } else if (campaignType == 'Blended' && campaignDialerType == 'AUTO') {
        openIncomingCallPopup();
    }
    current_uuid = callID;
    if ((campaignType == 'Inbound' && currentCall.request.getHeader('X-cc_queue')) || (campaignType == 'Blended' && campaignDialerType == 'AUTO')) {
        campaignCdr(extensionNumber);
        updateCallStatus();
        activeCallScreen();
        showDisposition();
    }

}
function openIncomingCallPopup(){
    $('#incomingCall').modal({
        dismissible: false, // Modal can be dismissed by clicking outside of the modal
    });
    $('#incomingCall').modal('open');
    if (currentCall.request.getHeader('X-caller_id_number')) {
        $('#caller_id_number').val(currentCall.request.getHeader('X-caller_id_number'));
    }
}
function outgoingDelegateCallback(eventState, response) {
    if (eventState == 'trying') {
        console.info(eventState);
        handleTryingState();
    } else if (eventState == 'ringing') {
        console.info(eventState);
        setupRemoteMedia(outgoingSession);
    } else if (eventState == 'accepted') {
        updateAnswertime();
        updateAnswerStatus();
    } else if (eventState == 'rejected') {
        if(isPause == 1){
            $('#disable-pause').addClass('d-none');
            $('#pause').removeClass('d-none');
        }else{
            $('#disable-resume').addClass('d-none');
            $('#resume').removeClass('d-none');
        }
        console.info(eventState);
        console.info(response.message.reasonPhrase);
        hanupCause = response.message.reasonPhrase;
        callState = response.message.reasonPhrase;
        updateCallStatus();
    } else if (eventState == 'redirected') {
        console.info(eventState);
    }
}
function stopPlaybackOutgoingSessionCallback(newState) {
    console.log(newState);
}
function stopPlaybackOutgoingDelegateCallback(eventState, response) {
    console.log(eventState);
}

function makeCallDialPad() {
    is_call_active = true;
    $('.break-in-out').addClass('d-none');
    var lg_contact_number = $("#output").val();
    var lg_id = $('#lg_id').val();
    var caller_id_name = extensionName;
    var caller_id_number = extensionNumber;
    var outgoingBandwidth = 64; // Specify your desired bandwidth
    var incomingBandwidth = 64; // Specify your desired bandwidth
    const extraHeaders = [
        'X-agent_id:' + agent_id,
        'X-lead_id:' + lg_id,
        'X-campaign_id: ' + campaign_id,
        'X-caller_id_name:' + caller_id_name,
        'X-caller_id_number:' + caller_id_number,
        'X-destination_number:' + lg_contact_number
    ];
    const dedEnc = false; // Whether to use encryption (adjust as needed)

    let constraints = {
        audio: true,
        video: false,
    }
    let callParams = {
        constraints: constraints,
        extraHeaders: extraHeaders,
        ringParams: {
            ringfile: "http://localhost/uctenant_master/web/theme/sound/bell_ring2.mp3",
            audioFileContainer: audioContainer
        },
        target: SIP.UserAgent.makeURI(`sip:${lg_contact_number}` + '@' + domainName)
    }
    callDirection = 'outgoing';
    customSIPModule.initiateCall(UserAgent, callParams, outgoingSessionCallback, outgoingDelegateCallback)
        .then((inviter) => {
            currentCall = inviter;
            outgoingSession = inviter;
            callID = outgoingSession.request.callId;
            callUniqueId = callID;
            campaignCdr(extensionNumber);
            currentCall.delegate = {
                onBye: (byeRequest) => {
                    handleBye(byeRequest);
                }
            };
        })
        .catch((error) => {
            console.error("Failed to sent Invite", error);
        });

}

var count = 0;

function handleBye(byeRequest) {
    is_call_active = false;
    let resHeader = byeRequest.request.headers['Reason'];
    const inputString = resHeader[0]['raw'];
    const regex = /text="([^"]+)"/;
    const match = inputString.match(regex);
    if (match) {
        const extractedText = match[1];
        hanupCause = extractedText;
        updateCallStatus();
    } else {
        updateCallStatus();
        console.log("Text not found");
    }
}

function campaignCdr(extensionNumber) {

    var activeCallerId = currentCall.request.getHeader('X-caller_id_number');
    var activeCallId = callID;
    if (currentCall.request.getHeader('X-did_number')) {
        var activeDestiNumber = currentCall.request.getHeader('X-did_number');
    } else if (currentCall.request.getHeader('X-destination_number')) {
        var activeDestiNumber = currentCall.request.getHeader('X-destination_number');
    } else if (currentCall.request.getHeader('X-remote_caller_id_number')) {
        var activeDestiNumber = currentCall.request.getHeader('X-remote_caller_id_number');
    }
    /*var activeDestiNumber = lg_contact_number;*/

    var activeStateName = callState;
    if (currentCall.request.getHeader('X-agent_id') && currentCall.request.getHeader('X-campaign_id')) {
        var activeAgentName = currentCall.request.getHeader('X-agent_id');
        var activeCampaignName = currentCall.request.getHeader('X-campaign_id');
        currentCampaignId = currentCall.request.getHeader('X-campaign_id');
    } else {
        var activeAgentName = agent_id;
        var activeCampaignName = campaign_id;
    }

    if (currentCall.request.getHeader('X-queue_join_time')) {
        queue_join_time = currentCall.request.getHeader('X-queue_join_time');
    }
    //var temp_queue_name = '';
    if (currentCall.request.getHeader('X-cc_queue')) {
        var temp_queue_name = currentCall.request.getHeader('X-cc_queue');
        currentQueueName = temp_queue_name;
    } else {
        var temp_queue_name = '';
    }
    if (currentCall.request.getHeader('X-cc_queue_id')) {
        currentQueueId = currentCall.request.getHeader('X-cc_queue_id');
    }
    // Start Recording file
    if (currentCall.request.getHeader('X-recording_file')) {
        var recording_file = currentCall.request.getHeader('X-recording_file');
    }

    else {
        var recording_file = '';
    }
    // End  Recording file
    let activeLeadGrpMemberId =  $('#lg_id').val();
    $.ajax({
        data: {
            activeLeadGrpMemberId: activeLeadGrpMemberId,
            activeCallerId: activeCallerId,
            extensionNumber: extensionNumber,
            activeCallId: activeCallId,
            activeDestiNumber: activeDestiNumber,
            activeStateName: activeStateName,
            activeAgentName: activeAgentName,
            activeCampaignName: activeCampaignName,
            activeQueueName: temp_queue_name,
            activeRecording_file: recording_file,
            activeQueueJoinTime: queue_join_time,
        },
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/campaign-cdr",
        success: function (result) {
            if (result) {
                updateRingingStatus();
            }
        }
    });
}

function submitDisposition() {
    is_disp_submit = true;
    updateHangUpStatuscustom();

    if (callID === "undefined")
        var uuId = current_uuid;
    else
        var uuId = callID;

    var cause = callState;

    var data = $('#submit-disposition-form').serializeArray();
    data.push({'name': 'callId', 'value': uuId}, {'name': 'cause', 'value': cause},
        {'name': 'activeCampaignName', 'value': currentCampaignId},
        {'name': 'activeQueueId', 'value': currentQueueId},
        {'name': 'activeQueueName', 'value': currentQueueName}
    );

    currentCall = null;
    $.ajax({
        async: false,
        data: data,
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/submit-disposition",
        success: function (result) {
            $('#dis-sub').attr('disabled', true);
            $('#dis-sub').removeClass('amber');
            console.log(result);
            if (is_m_call == true) {
                is_m_call = false;
                is_call_active = false;
                window.location.reload();
            }
            //window.location.reload();
        }
    });
}

function stopPlayback(aleg_uuid) {
    if (window.localStorage.getItem('call_hold') && window.localStorage.getItem('call_mute')) {
        if (window.localStorage.getItem("call_hold") == "false" || window.localStorage.getItem("call_mute") == "false") {
            return;
        }
    }
    var caller_id_name = cmp_caller_name;
    var caller_id_number = extensionNumber;
    const extraHeaders = [
        'X-caller_id_name:' + caller_id_number,
        'X-caller_id_number:' + caller_id_name,
        'X-aleg_uuid:' + aleg_uuid
    ];


    is_stop_playback = true;

    let constraints = {
        audio: true,
        video: false,
    }
    let callParams = {
        constraints: constraints,
        extraHeaders: extraHeaders,
        target: SIP.UserAgent.makeURI(`sip:*99` + '@' + domainName)
    }
    customSIPModule.initiateCall(UserAgent, callParams, stopPlaybackOutgoingSessionCallback, stopPlaybackOutgoingDelegateCallback)
        .then((inviter) => {

        })
        .catch((error) => {
            console.error("Failed to sent Invite for playback", error);
        });

}

function makeCall() {
    $('.break-in-out').addClass('d-none');
    is_m_call = true;
    is_call_active = true;
// Extract values from your HTML elements or variables
    var lg_contact_number = $('#lg_contact_number').val();
    var lg_id = $('#lg_id').val();
    var caller_id_name = cmp_caller_name;
    var caller_id_number = extensionNumber;
    var recording_file = '/usr/local/freeswitch/recordings/' + extensionNumber + '_' + lg_contact_number + '.wav';

    if (!lg_contact_number) {
        lg_contact_number = 0;
    }
    const outgoingBandwidth = 'default';
    const incomingBandwidth = 'default';

// Set user variables
    const extraHeaders = [
        'X-agent_id:' + agent_id,
        'X-lead_id:' + lg_id,
        'X-campaign_id: ' + currentCampaignId,
        'X-caller_id_name:' + caller_id_number,
        'X-caller_id_number:' + caller_id_name,
        'X-destination_number:' + lg_contact_number,
        'X-cmp_caller_id:' + cmp_caller_id,
        'X-recording_file:' + recording_file,
        'X-campaign_call: true'
    ];
    let constraints = {
        audio: true,
        video: false,
    }
    let callParams = {
        constraints: constraints,
        extraHeaders: extraHeaders,
        ringParams: {
            ringfile: "http://localhost/uctenant_master/web/theme/sound/bell_ring2.mp3",
            audioFileContainer: audioContainer
        },
        target: SIP.UserAgent.makeURI(`sip:${lg_contact_number}` + '@' + domainName)
    }

    customSIPModule.initiateCall(UserAgent, callParams, outgoingSessionCallback, outgoingDelegateCallback)
        .then((inviter) => {
            currentCall = inviter;
            outgoingSession = inviter;
            callID = outgoingSession.request.callId;
            callUniqueId = callID;
            currentCall.delegate = {
                onBye: (byeRequest) => {
                    handleBye(byeRequest);
                }
            };
            activeCallScreen();
            campaignCdr(extensionNumber);
            showDisposition();
        })
        .catch((error) => {
            console.error("Failed to sent Invite", error);
        });

}

function calldialNext() {
    callDirection = 'outgoing';
    $.ajax({
        async: false,
        url: baseURL + "index.php?r=crm/crm/dial-next-data",
        success: function (data) {
            if (data.length) {
                $('#dialNext').addClass('d-none');
                $('#disable-dialnext').removeClass('d-none');
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
                console.log('result', result);
                //var result = JSON.parse(data);
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
                $('#submitbtn').show();
                $('ul.tabs').tabs('select', 'CRM');
                currentCampaignId = result.cmp_id;
                $('.crm-submit').show();
                makeCall();

            } else {
                //$('#lead-group-member-form').html(no_more_lead_in_hopper);
                $('#leadgroupmember-lg_first_name').val('');
                $('#leadgroupmember-lg_last_name').val('');
                $('#lg_contact_number').val('');
                $('#leadgroupmember-lg_contact_number_2').val('');
                $('#leadgroupmember-lg_email_id').val('');
                $('#leadgroupmember-lg_address').val('');
                $('#leadgroupmember-lg_alternate_number').val('');
                $('#leadgroupmember-lg_pin_code').val('');
                $('#leadgroupmember-lg_permanent_address').val('');
                $('#leadcommentmapping-comment').val('');
                $('#pk_id').val('');
                $('#lg_id').val('');
                $('#lg_id1').val('');
                $('#no-lead-body').html(no_more_lead_in_hopper);
                $('.crm-submit').hide();
                $('#noleadmodal').modal({
                    dismissible: false, // Modal can be dismissed by clicking outside of the modal
                });
                $('#noleadmodal').modal('open');
            }
        }
    });
}
function showDisposition(){

    if(currentCall){
        if(currentCall.request.getHeader('X-campaign_id')){
            currentCampaignId = currentCall.request.getHeader('X-campaign_id');
        }
        if (currentCall.request.getHeader('X-cc_queue_id')) {
            currentQueueId = currentCall.request.getHeader('X-cc_queue_id');
        }
        if (currentCall.request.getHeader('X-cc_queue')) {
            currentQueueName = currentCall.request.getHeader('X-cc_queue');
        }
    }
    $.ajax({
        // async: false,
        data: {activeQueueId:currentQueueId ,activeCampaignName:currentCampaignId, activeQueueName:currentQueueName},
        type: 'POST',
        url: baseURL + "index.php?r=crm/crm/dispostion-list-type",
        success: function (result) {

            var resultjson = JSON.parse(result);
            console.log("result option " , resultjson);
            var option = "<option>Select Disposition</option>";
            if(resultjson.data!='' && resultjson.msg==''){

                Object.keys(resultjson['data']).forEach(function(k){

                    option += "<option value='"+k+"'>"+resultjson['data'][k]+"</option>";

                });
                $("#agentdispositionmapping-disposition").html(option);
//              $("#agentdispositionmapping-disposition").material_select();
            }else{
                $('#agentdispositionmapping-disposition').parent().html(resultjson.msg);
            }
        }
    });
}

function handleTabClose() {
    window.removeEventListener('beforeunload', handleTabClose);
    // if(callUniqueId){
    //     $.ajax({
    //         async: false,
    //         data: 'callId=' + uuId,
    //         type: 'POST',
    //         url: baseURL + "index.php?r=crm/crm/update-disposition-and-logout",
    //         success: function (result) {
    //         }
    //     });
    // }else{
    //     $.ajax({
    //         async: true,
    //         data: '',
    //         type: 'POST',
    //         url: baseURL + "index.php?r=crm/crm/logout-agent",
    //         success: function (result) {
    //         }
    //     });
    // }
    customSIPModule.unregisterUA();
    if(currentCall) {
        switch (currentCall.state) {
            case 'Initial':
            case 'Establishing':
                if (callDirection === 'outgoing') {
                    currentCall.cancel();
                }
                if (callDirection === 'incoming') {
                    currentCall.reject(603);
                }
                break;
            case 'Established':
                hanupCause = "NORMAL_CLEARING";
                currentCall.bye()
                    .then(() => {

                    }).catch((error) => {
                });
                break;
            case 'Terminating':
            case 'Terminated':
                break;
        }
        $.ajax({
            async: true,
            data: 'callId=' + callUniqueId,
            type: 'POST',
            url: baseURL + "index.php?r=crm/crm/update-disposition-and-logout",
            success: function (result) {
            }
        });
    }else{
        $.ajax({
            async: true,
            type: 'POST',
            url: baseURL + "index.php?r=crm/crm/logout-agent",
            success: function (result) {
            }
        });
    }
}

// Add event listener for tab close event
window.addEventListener('beforeunload', handleTabClose);

function callAutoDisposition(uuId, activeStateName){
    // $.ajax({
    //     async: true,
    //     data: 'callId=' + uuId,
    //     type: 'POST',
    //     url: baseURL + "index.php?r=crm/crm/update-call-end-time",
    //     success: function (result) {
    //     }
    // });
    //
    // $.ajax({
    //     async: true,
    //     data: {uuIdStatus: uuId, activeStateName: activeStateName},
    //     type: 'POST',
    //     url: baseURL + "index.php?r=crm/crm/call-status-update",
    //     success: function (result) {
    //     }
    // });

    // $.ajax({
    //     async: false,
    //     data: 'callId=' + uuId,
    //     type: 'POST',
    //     url: baseURL + "index.php?r=crm/crm/update-disposition-and-logout",
    //     success: function (result) {
    //     }
    // });
}
