let username = extensionNumber;
let password = extensionPassword;
var transportOptions = {
    server: 'ws://' + wsHostname + ':' + wssPort,
};
const uri = SIP.UserAgent.makeURI(`sip:${username}` + "@" + sipDomain);

var config = {
    uri: uri,
    authorizationPassword: password,
    authorizationUsername: username,
    transportOptions
};
let audioContainer = document.getElementById("audio-container");
let outgoingSession;
var currentCall;
var UserAgent = new SIP.UserAgent(config);
customSIPModule.connectWs(config)
    .then((userAgent) => {
        console.log("UserAgent started:", userAgent);
        registerUser(userAgent);
    })
    .catch((error) => {
        console.error("Error starting UserAgent:", error);
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
}
function makeBargeignCallDisable(uuid) {
    var caller_id_name = extensionName;
    var caller_id_number = extensionNumber;
    // if(currentCall){
    //     currentCall.bye();
    // }
    handleCallHangup();
}

function makeBargeignCall(uuid) {
    var caller_id_name = extensionName;
    var caller_id_number = extensionNumber;
    handleCallHangup();
    // if(currentCall){
    //     currentCall.bye();
    // }
    const extraHeaders = [
        'X-caller_id_name:' + caller_id_name,
        'X-caller_id_number:' + caller_id_number,
        'X-barge_uuid:' + uuid
    ];
    const dedEnc = false; // Whether to use encryption (adjust as needed)
    let callParams = {
        video: false,
        extraHeaders: extraHeaders,
        target: SIP.UserAgent.makeURI(`sip:*93` + '@' + sipDomain)
    }
    callDirection = 'outgoing';
    customSIPModule.initiateCall(UserAgent, callParams, outgoingSessionCallback, outgoingDelegateCallback)
        .then((inviter) => {
            currentCall = inviter;
            outgoingSession = inviter;
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
function handleBye(byeRequest) {

}
function outgoingSessionCallback(newState){
    switch (newState) {
        case "Establishing":
            break;
        case "Established":
            setupRemoteMedia(outgoingSession);
            break;
        case "Terminated":
            break;
        default:
            break;
    }
}
function outgoingSessionCallbackWhisper(newState){
    switch (newState) {
        case "Establishing":
            break;
        case "Established":
            setupRemoteMedia(outgoingSession);
            break;
        case "Terminated":
            break;
        default:
            break;
    }
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
function setupRemoteMediaForSender(session){
    let peerConnection = session.sessionDescriptionHandler.peerConnection;
    let remoteStream = new MediaStream();
    peerConnection.getSenders().forEach((sender) => {
        if(sender.track){
            remoteStream.addTrack(sender.track);
            audioContainer.srcObject = remoteStream;
        }
    });
}
function outgoingDelegateCallback(eventState, response){

}
function makeHangupCall(uuid) {
    var caller_id_name = extensionName;
    var caller_id_number = extensionNumber;

    const extraHeaders = [
        'X-caller_id_name:' + caller_id_name,
        'X-caller_id_number:' + caller_id_number,
        'X-barge_uuid:' + uuid
    ];

    let callParams = {
        video: false,
        extraHeaders: extraHeaders,
        target: SIP.UserAgent.makeURI(`sip:*94` + '@' + sipDomain)
    }
    callDirection = 'outgoing';
    customSIPModule.initiateCall(UserAgent, callParams, outgoingSessionCallback, outgoingDelegateCallback)
        .then((inviter) => {
            currentCall = inviter;
            outgoingSession = inviter;
        })
        .catch((error) => {
            console.error("Failed to sent Invite", error);
        });
}

function makeListenCallDisable(uuid)
{
    var caller_id_name = extensionName;
    var caller_id_number = extensionNumber;
    handleCallHangup();
    // if(currentCall){
    //     currentCall.bye();
    // }
}
function handleCallHangup(){
    if(currentCall){
        switch (currentCall.state) {
            case 'Establishing':
                if (callDirection === 'outgoing') {
                    currentCall.cancel();
                }
                break;
            case 'Established':
                currentCall.bye();
                break;
            case 'Terminating':
            case 'Terminated':
                break;
        }
        currentCall = '';
    }
}

function makeListenCall(uuid) {
    var caller_id_name = extensionName;
    var caller_id_number = extensionNumber;
    handleCallHangup();
    // if(currentCall){
    //     currentCall.bye();
    // }
    const extraHeaders = [
        'X-caller_id_name:' + caller_id_name,
        'X-caller_id_number:' + caller_id_number,
        'X-barge_uuid:' + uuid
    ];

    let callParams = {
        video: false,
        extraHeaders: extraHeaders,
        target: SIP.UserAgent.makeURI(`sip:*95` + '@' + sipDomain)
    }
    callDirection = 'outgoing';
    customSIPModule.initiateCall(UserAgent, callParams, outgoingSessionCallback, outgoingDelegateCallback)
        .then((inviter) => {
            currentCall = inviter;
            outgoingSession = inviter;
        })
        .catch((error) => {
            console.error("Failed to sent Invite", error);
        });
}

function makeWhisperCallDisable(uuid) {
    var caller_id_name = extensionName;
    var caller_id_number = extensionNumber;
    handleCallHangup();
    // if(currentCall){
    //     currentCall.bye();
    // }
}

function makeWhisperCall(uuid) {
    var caller_id_name = extensionName;
    var caller_id_number = extensionNumber;
    // if(currentCall){
    //     currentCall.bye();
    // }
    handleCallHangup();

    const extraHeaders = [
        'X-caller_id_name:' + caller_id_name,
        'X-caller_id_number:' + caller_id_number,
        'X-barge_uuid:' + uuid
    ];

    let callParams = {
        video: false,
        extraHeaders: extraHeaders,
        target: SIP.UserAgent.makeURI(`sip:*96` + '@' + sipDomain)
    }
    callDirection = 'outgoing';
    customSIPModule.initiateCall(UserAgent, callParams, outgoingSessionCallbackWhisper, outgoingDelegateCallback)
        .then((inviter) => {
            currentCall = inviter;
            outgoingSession = inviter;
        })
        .catch((error) => {
            console.error("Failed to sent Invite", error);
        });
}

$(document).on("click", ".livecalls", function () {

    var act_id = $(this).attr('active_id');
    var calltype = $(this).attr('id');
    var uuid = $(this).attr('uuid');
    console.log('ALL UUID=== : '+uuid);

    if (!localStorage.getItem('isCallReady')) {
        var myarray = [];
    }else{
        var myarray = JSON.parse(localStorage.getItem('isCallReady'));
    }

    if($(this).hasClass('text-white')){ // call is continue

        $(this).removeClass('text-white');
        $(this).addClass('color-orange');

        if (myarray.length) {
            var newmyarray = [];
            $.each( myarray, function( key, value ) {
                if(value != act_id){
                    newmyarray.push(value);
                }
            });
            localStorage.setItem('isCallReady', JSON.stringify(newmyarray));
        }
        // Hangup Call (For Phone Cut )
        if(calltype == 'live-whisper-call'){
            $(this).parent().find('.whisper').attr('disabled', false); //'.whisper'
            console.log("CLICKED ON DISABLE ICON => makeWhisperCallDisable ============ ");
            makeWhisperCallDisable(uuid);
        }
        if(calltype == 'live-listen-call'){
            $(this).parent().find('.listen').attr('disabled', false); //'.listen'
            makeListenCallDisable(uuid);
        }
        if(calltype == 'live-barge-call'){
            $(this).parent().find('.bargein').attr('disabled', false); //'.bargein'
            makeBargeignCallDisable(uuid);
        }
        if(calltype == 'live-hangup-call'){
            $(this).parent().find('.hangup').attr('disabled', false); //'.hangup'
        }

    }else {
        $(this).removeClass('color-orange').addClass('text-white');
        myarray.push($(this).attr('active_id'));

        localStorage.setItem('isCallReady', JSON.stringify(myarray));

        if(calltype == 'live-whisper-call'){
            $(this).parent().find('.whisper').attr('disabled', true); //'.whisper'
            makeWhisperCall(uuid);
        }
        if(calltype == 'live-listen-call'){
            $(this).parent().find('.listen').attr('disabled', true); //'.listen'
            makeListenCall(uuid);
        }
        if(calltype == 'live-barge-call'){
            $(this).parent().find('.bargein').attr('disabled', true); //'.bargein'
            makeBargeignCall(uuid);
        }
        if(calltype == 'live-hangup-call'){
            $(this).parent().find('.hangup').attr('disabled', true); //'.hangup'
            makeHangupCall(uuid);
        }
    }
});
$( document ).ajaxComplete(function() {
    if (!localStorage.getItem('isCallReady')) {
        var myarray = [];
    }else{
        var myarray = JSON.parse(localStorage.getItem('isCallReady'));
    }

    if (myarray.length) {

        $.each( myarray, function( key, value ) {
            $('[active_id="'+value+'"]').addClass('material-icons text-white');
        });
    }
});

function handleTabClose() {
    //window.removeEventListener('beforeunload', handleTabClose);
    customSIPModule.unregisterUA();
        $.ajax({
            async: true,
            type: 'POST',
            url: baseURL + "index.php?r=supervisor/supervisor/remove-sip",
            success: function (result) {
            }
        });

}

// Add event listener for tab close event
window.addEventListener('beforeunload', handleTabClose);
