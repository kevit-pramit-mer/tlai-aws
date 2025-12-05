// Cache buster: 2025-12-04-19:15 - Fixed SIP domain to use tenant1.teleaon.ai
var timer = new Timer();
var digitCount = 0;
var count = 0;

let username = extensionNumber;
let password = extensionPassword;

var transportOptions = {
    server: 'ws://' + wsHostname + ':' + wssPort,
};
let audioContainer = document.getElementById("audio-container");
let localVideo = document.getElementById("localVideo");
let remoteVideo = document.getElementById("remoteVideo");
let ringFilePath = callRingFile;
const ringtone = new Audio(ringFilePath);
ringtone.preload = "none";
ringtone.autoplay = false;
ringtone.loop = true;
let isRequestIncludeVideo = false;

let currentCall;
let outgoingSession;
let callID;
let callDirection;
let destination_number;
let notificationReg;
const uri = SIP.UserAgent.makeURI(`sip:${username}` + "@" + sipDomain);
console.log("mainSIP.js - Created URI:", uri.toString(), "using sipDomain:", sipDomain);
//let hasVideo = false;
let audioCall = true;
let speedDial = [];
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
// window.onload = () => {
//     const myInput = document.getElementById('output');
//     myInput.onpaste = e => e.preventDefault();
// }
customSIPModule.connectWs(config)
    .then((userAgent) => {
        console.log("UserAgent started:", userAgent);
        registerUser(userAgent);
    })
    .catch((error) => {
        $('#disable-call-dialer').attr('disabled','true');
        $('#disable-call-dialer').removeClass('d-none');
        $('#enable-call-dialer').addClass('d-none');
        console.error("Error starting UserAgent:", error);
    });


function incomingDelegateCallback(event, request){
    if(event == 'cancel'){
        handleBye(request);
    }
    if(event == 'bye'){
        handleBye(request);
    }
}
// if ('serviceWorker' in navigator) {
//     navigator.serviceWorker.register('../modules/ecosmob/extension/web/js/sw.js').then(function(registration) {
//         console.warn('Service Worker registered = '+ registration);
//         return navigator.serviceWorker.ready;
//     }).then(function(registration) {
//         console.warn('Service Worker ready');
//         // Now you can show notifications or perform other tasks
//     }).catch(function(error) {
//         console.error('Service Worker registration failed:', error);
//     });
// } else {
//     console.error('Service Worker is not supported by this browser');
// }
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
function registerUser(userAgent) {
    customSIPModule.registerUA(userAgent, registrationCallback)
        .then((userAgent) => {
            console.log("UserAgent registered:", userAgent);
            UserAgent = userAgent;
            //customSIPModule.subscribeToPresence(userAgent, 'sip:8005@ucdemo2.ecosmob.net');
        })
        .catch((error) => {
            $('#disable-call-dialer').attr('disabled','true');
            $('#disable-call-dialer').removeClass('d-none');
            $('#enable-call-dialer').addClass('d-none');
            console.error("UserAgent registration failed:", error);
        });
}
window.navigator.serviceWorker.addEventListener('message', (event) => {

    if(event.data.action == 'acceptCall'){
        $('#acceptCall').trigger('click');
    }
    if(event.data.action == 'rejectCall'){
        $('#rejectCall').trigger('click');
    }

});
function registrationCallback(newState) {
    console.log("Registration State:", newState);
    if(newState == 'Unregistered'){
        $('#disable-call-dialer').attr('disabled','true');
        $('#disable-call-dialer').removeClass('d-none');
        $('#enable-call-dialer').addClass('d-none');
    }else{
        $('#disable-call-dialer').addClass('d-none');
        $('#enable-call-dialer').removeClass('d-none');
    }
}

function initiateAudioCall(destination_number = ''){
    audioCall = true;
    disableCallerIcon();
    if(!destination_number){
         destination_number = $('#output').val();
    }
    $('.caller-details').text(destination_number);
    const extraHeaders = [
        'X-caller_id_number:' + extensionNumber,
        'X-destination_number:' + destination_number
    ];
    let constraints = {
        audio: true,
        video: false,
    }
    let callParams = {
        constraints: constraints,
        extraHeaders: extraHeaders,
        ringParams: {

        },
        target: SIP.UserAgent.makeURI(`sip:${destination_number}` + '@' + sipDomain)
    }
    callDirection = 'outgoing'
    customSIPModule.initiateCall(UserAgent, callParams, outgoingSessionCallback, outgoingDelegateCallback)
        .then((inviter) => {
            currentCall = inviter;
            outgoingSession = inviter;
            callID = outgoingSession.request.callId;
            extensionCdr(extensionNumber, destination_number, audioCall);
        })
        .catch((error) => {
            console.error("Failed to sent Invite", error);
        });

}


function initiateVideoCall(destination_number = ''){
    audioCall = false;
    disableCallerIcon();
    if(!destination_number){
        destination_number = $('#output').val();
    }
    $('.caller-details').text(destination_number);
    const extraHeaders = [
        'X-caller_id_number:' + extensionNumber,
        'X-destination_number:' + destination_number
    ];
    let constraints = {
        audio: true,
        video: true,
    }
    let callParams = {
        constraints: constraints,
        extraHeaders: extraHeaders,
        ringParams: {

        },
        target: SIP.UserAgent.makeURI(`sip:${destination_number}` + '@' + sipDomain)
    }
    callDirection = 'outgoing'
    customSIPModule.initiateCall(UserAgent, callParams, outgoingSessionVideoCallback, outgoingDelegateVideoCallback)
        .then((inviter) => {
            currentCall = inviter;
            outgoingSession = inviter;
            callID = outgoingSession.request.callId;
            extensionCdr(extensionNumber, destination_number, audioCall);
        })
        .catch((error) => {
            console.error("Failed to sent Invite", error);
        });

}

function outgoingSessionVideoCallback(newState) {
    switch (newState) {
        case "Establishing":
            console.warn("outgoing Establishing");
            break;
        case "Established":
            console.warn("outgoing Established");

            setupTimer();
            setupRemoteMedia(currentCall);
            updateCallAnsTime();
            //
            // let senderStream = currentCall.sessionDescriptionHandler.peerConnection;
            // let localStream = new MediaStream();
            //
            // senderStream.getSenders().forEach((sender) => {
            //     if(sender.track){
            //         localStream.addTrack(sender.track);
            //         localVideo.srcObject = localStream;
            //     }
            // });
            // localVideo.muted = true;
            // localVideo.play();
            //
            // let remoteStream = new MediaStream();
            // senderStream.getReceivers().forEach((receiver) =>{
            //     if(receiver.track){
            //         remoteStream.addTrack(receiver.track);
            //         remoteVideo.srcObject = remoteStream;
            //     }
            // });
            // remoteVideo.muted = true;
            // remoteVideo.play();
            break;
        case "Terminated":
            updateCallEndTime();
            endCall();
            clearTimer();
            resetSettings();
            getCallLogData();
            console.warn("outgoing Terminated");
            break;
        default:
            break;
    }
}
function outgoingDelegateCallback(eventState, response) {
    if (eventState == 'trying') {
        console.info(eventState);
    } else if (eventState == 'ringing') {
        console.info(eventState);
    } else if (eventState == 'accepted') {
        currentCall.sessionDescriptionHandlerOptions = {
            modifiers: [
                (sessionDescription) => {
                    sessionDescription.sdp = preferVP8(sessionDescription.sdp);
                    return sessionDescription;
                }
            ]
        };

    } else if (eventState == 'rejected') {

    } else if (eventState == 'redirected') {
        console.info(eventState);
    }
}
function outgoingSessionCallback(newState) {
    switch (newState) {
        case "Establishing":
            console.warn("outgoing Establishing");
            break;
        case "Established":
            console.warn("outgoing Established");
            setupTimer();
            enableCallerIcon();
            setupRemoteMedia(outgoingSession);
            updateCallAnsTime();
            //setupRemoteMedia1();
            break;
        case "Terminated":
            updateCallEndTime();
            endCall();
            clearTimer();
            resetSettings();
            getCallLogData();
            console.warn("outgoing Terminated");
            break;
        default:
            break;
    }
}

function outgoingDelegateVideoCallback(eventState, response) {
    if (eventState == 'trying') {
        console.info(eventState);
    } else if (eventState == 'ringing') {
        console.info(eventState);
    } else if (eventState == 'accepted') {

    } else if (eventState == 'rejected') {

    } else if (eventState == 'redirected') {
        console.info(eventState);
    }
}
function handleIncomingCall(invitation){

    var incomingNumber = invitation.request.getHeader('X-caller_id_number')

    const sdp = invitation.body;
    isRequestIncludeVideo = sdp.includes('m=video');
    if(isRequestIncludeVideo){
        audioCall = false;
    }else{
        audioCall = true;
    }

    //     alert(hasVideo);
    // if (hasVideo) {
    //     console.log('Incoming call has video stream.');
    //     // Handle the call with video...
    // } else {
    //     console.log('Incoming call does not have video stream.');
    //     // Handle the call without video...
    // }
    callDirection = 'incoming';
    currentCall = invitation;

    showNotification("Incoming Call", "You have an incoming call from "+ incomingNumber +" ! ");

    currentCall.delegate = {
        onBye: (byeRequest) => {
            handleBye(byeRequest);
        },
        onCancel: (byeRequest) => {
            handleBye(byeRequest);
        },
    };
    callID = invitation.request.getHeader('X-cid');
    //callID = invitation.request.callId;

    extensionCdr(incomingNumber, extensionNumber, audioCall)

    if(invitation.state == 'Initial'){
        handleRingingState();
    }
    incomingSession = invitation;

    ringParams = {
            ringfile: "http://localhost/uctenant_master/web/theme/sound/bell_ring2.mp3",
            audioFileContainer: audioContainer
    };
    customSIPModule.handleIncomingCall(invitation,ringParams,incomingSessionCallback,incomingDelegateCallback);
}
function incomingSessionCallback(newState){
    switch (newState) {
        case "Initial":
            break;
        case "Establishing":
            console.warn("Incoming Establishing");
            break;
        case "Established":
            //ringAudioContainer.pause();
            setupTimer();
            enableCallerIcon();
            setupRemoteMedia(currentCall);
            updateCallAnsTime();
            //setupRemoteMedia(incomingSession);
            // let peerConnection = incomingSession.sessionDescriptionHandler.peerConnection;
            // let localStream = new MediaStream();
            //
            // peerConnection.getSenders().forEach((sender) => {
            //     if(sender.track){
            //         localStream.addTrack(sender.track);
            //         localVideo.srcObject = localStream;
            //     }
            // });
            // localVideo.play();
            //
            // let remoteStream = new MediaStream();
            // peerConnection.getReceivers().forEach((receiver) =>{
            //     if(receiver.track){
            //         remoteStream.addTrack(receiver.track);
            //         remoteVideo.srcObject = remoteStream;
            //     }
            // });
            // remoteVideo.play();

            break;
        case "Terminating":
            console.warn("Incoming Terminating");
            break;
        case "Terminated":
            updateCallEndTime();
            clearTimer();
            endIncomingCall();
            resetSettings();
            getCallLogData();
            console.warn("Incoming Terminated");
            break;
        default:
            break;
    }
}
function handleRingingState(){

    if (currentCall.request.getHeader('X-caller_id_number')) {
        $('.caller-details').text(currentCall.request.getHeader('X-caller_id_number'));
    }
    $('.dialer-section').addClass('d-none');
    $('.incoming-call').removeClass('d-none');
    // let audioContainer = ringAudioContainer;
    // audioContainer.setAttribute("src",ringFile);
    // audioContainer.play();
}
function handleBye(byeRequest) {
    $('.dial-pad-call, .dial-pad-Videocall, .call-minimizer').addClass("d-none");
    $('.dialer-section').removeClass('d-none');
    $('#tabs-swipe-demo li a[href="#swipe-tab-2"]')[0].click();
    resetSettings();
    cleanupMedia();
    getCallLogData();
}
function setupRemoteMedia(session){
        let remoteStream = new MediaStream();
        console.log('remoteStream', session.sessionDescriptionHandler.peerConnection.getReceivers());
        session.sessionDescriptionHandler?.peerConnection?.getReceivers().forEach((receiver) => {
            console.log('receiver track',receiver.track);
            if (receiver.track) {
                remoteStream.addTrack(receiver.track);
                console.log("---- >>> receiver track ");
            }
        });

        if (audioCall == 1) {
            console.log('audio call defined');
            audioContainer.srcObject = remoteStream;
            //audioContainer.play();
            audioContainer.play().catch(error => {
                console.error('Error playing audio:', error);
            });
        } else {
            const remoteVideoElement = document.getElementById('remoteVideo');
            const localVideoElement = document.getElementById('localVideo');

            if (typeof (remoteVideoElement) != 'undefined' && remoteVideoElement != null) {
                console.log('remote video element defined');
                console.log("---- >>> receiver video element defined ");
                remoteVideoElement.srcObject = remoteStream;
                //remoteVideoElement.play();
                remoteVideoElement.play().catch(error => {
                    console.error('Error playing remote video:', error);
                });
            }


            const senderStream = new MediaStream();
            console.log('senderStream', session.sessionDescriptionHandler.peerConnection.getSenders());
            session.sessionDescriptionHandler.peerConnection.getSenders().forEach((sender) => {
                console.log('sender track',sender.track);
                if (sender.track.kind === 'video') {
                    senderStream.addTrack(sender.track);
                }
            });
            if (typeof (localVideoElement) != 'undefined' && localVideoElement != null) {
                console.log('local video element defined');
                localVideoElement.srcObject = senderStream;
                //localVideoElement.play();
                localVideoElement.play().catch(error => {
                    console.error('Error playing local video:', error);
                });
            }
        }
}


function handleTabClose() {
    //window.removeEventListener('beforeunload', handleTabClose);
    customSIPModule.unregisterUA();
     if(currentCall){
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
                 currentCall.bye()
                     .then(() => {
                         let tracks = audioContainer.srcObject.getTracks();
                         tracks.forEach(track => track.stop());

                     }).catch((error) => {
                     console.log("Error in Bye ", error);
                 });
                 break;
             case 'Terminating':
             case 'Terminated':
                 break;
         }
         if(callUniqueId) {
             $.ajax({
                 async: true,
                 data: 'callId=' + callUniqueId,
                 type: 'POST',
                 url: baseURL + "index.php?r=extension/extension-cdr/update-cdr",
                 success: function (result) {
                 }
             });
         }
    }else{
         $.ajax({
             async: true,
             type: 'POST',
             url: baseURL + "index.php?r=extension/extension-cdr/remove-sip",
             success: function (result) {
             }
         });
     }
}

// Add event listener for tab close event
window.addEventListener('beforeunload', handleTabClose);

function extensionCdr(fromNumber, toNumber, callType) {
    var call_type = (callType == true ? 1 : 0);
    $.ajax({
        data: {
            fromNumber: fromNumber,
            toNumber: toNumber,
            callId: callID,
            direction: callDirection,
            callType: call_type
        },
        type: 'POST',
        url: baseURL + "index.php?r=extension/extension-cdr/extension-cdr",
        success: function (result) {
            if (result) {
            }
        }
    });
}
