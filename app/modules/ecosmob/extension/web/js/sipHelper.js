function setupTimer(){
    timer.start({
        startValues: {countdown: false, seconds: 0}
    });
}
function clearTimer(){
    timer.stop();
    $('.call-connect').text('Connecting...');
}
function endCall(){
    $('#output').val('');
    $('.dial-pad-call, .call-minimizer, .dial-pad-Videocall').addClass("d-none");

    $('.dialer-section').removeClass('d-none');
    $('#tabs-swipe-demo li a[href="#swipe-tab-2"]')[0].click();

}
function endIncomingCall(){
    $('.incoming-call').addClass("d-none");
    $('.dial-pad-call, .call-minimizer, .dial-pad-Videocall').addClass("d-none");

    $('.dialer-section').removeClass('d-none');
    $('#tabs-swipe-demo li a[href="#swipe-tab-2"]')[0].click();
}

/*function cleanupMedia() {
    try {
        const remoteAudioElement = document.getElementById('audio-container');
        if (typeof (remoteAudioElement) != 'undefined' && remoteAudioElement != null) {
            remoteAudioElement.srcObject = null;
            remoteAudioElement.pause();
        }
        if (localVideo) {   //  Media element pause
            localVideo.srcObject = null;
            localVideo.muted = true;
            localVideo.pause();
        }

        if (remoteVideo) {   //  Remote audio element pause
            remoteVideo.srcObject = null;
            remoteVideo.muted = true;
            remoteVideo.pause();
        }

        //  Disable the media track
        var pc = currentCall.sessionDescriptionHandler.peerConnection;
        pc.getSenders().forEach((stream) => {
            if (stream !== null && stream !== undefined) {
                stream.track.enabled = false;
            }
        });

    } catch (error) {
        console.log("Clean media audio session error - ", error);
    }
}*/

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

function toggleVideo(video=false){
    let session = currentCall;
    var option = {video: video, audio: true};

    navigator.mediaDevices.getUserMedia(option)
        .then(function (streams) {
            mediaStream = streams;
            var pc = session.sessionDescriptionHandler.peerConnection;
            const localStream = new MediaStream();
            const trackUpdates = [];
            const updateTrack = (newTrack) => {
                const kind = newTrack.kind;
                if (kind !== "audio" && kind !== "video") {
                    throw new Error(`Unknown new track kind ${kind}.`);
                }
                const sender = pc.getSenders().find((sender) => sender.track && sender.track.kind === kind);
                if (sender) {
                    trackUpdates.push(new Promise((resolve) => {
                        console.log(`SessionDescriptionHandler.setLocalMediaStream - replacing sender ${kind} track`);
                        resolve();
                    }).then(() => sender
                        .replaceTrack(newTrack)
                        .then(() => {
                            const oldTrack = localStream.getTracks().find((localTrack) => localTrack.kind === kind);
                            if (oldTrack) {
                                oldTrack.stop();
                                localStream.removeTrack(oldTrack);
                                dispatchRemoveTrackEvent(localStream, oldTrack);
                            }
                            localStream.addTrack(newTrack);
                            dispatchAddTrackEvent(localStream, newTrack);
                        })
                        .catch((error) => {
                            console.error(`SessionDescriptionHandler.setLocalMediaStream - failed to replace sender ${kind} track`);
                            throw error;
                        })))

                } else {
                    trackUpdates.push(new Promise((resolve) => {
                        console.log(`SessionDescriptionHandler.setLocalMediaStream - adding sender ${kind} track`);
                        resolve();
                    }).then(() => {
                        try {
                            pc.addTrack(newTrack, localStream);
                        } catch (error) {
                            console.error(`SessionDescriptionHandler.setLocalMediaStream - failed to add sender ${kind} track`);
                            throw error;
                        }
                        localStream.addTrack(newTrack);
                        dispatchAddTrackEvent(localStream, newTrack);
                    }));

                }
            };
            const audioTracks = streams.getAudioTracks();
            if (audioTracks.length) {
                updateTrack(audioTracks[0]);
            }
            // update peer connection video tracks
            const videoTracks = streams.getVideoTracks();
            if (videoTracks.length) {
                updateTrack(videoTracks[0]);
            }


            let reinviteOptions = {
                requestDelegate: {
                    onProgress: (response) => {
                        console.warn("Invitation On Progress");
                    },
                    onAccept: (response) => {
                        //setupRemoteMedia1();
                        let senderStream = currentCall.sessionDescriptionHandler.peerConnection;
                        let localStream = new MediaStream();

                        senderStream.getSenders().forEach((sender) => {
                            if(sender.track){
                                localStream.addTrack(sender.track);
                            }
                        });
                        localVideo.srcObject = localStream;
                        localVideo.play();

                        let remoteStream = new MediaStream();
                        senderStream.getReceivers().forEach((receiver) =>{
                            if(receiver.track){
                                remoteStream.addTrack(receiver.track);
                            }
                        });
                        remoteVideo.srcObject = remoteStream;
                        remoteVideo.play();
                    },
                    onReject: (response) => {
                        console.log("Invitation On Rejected");
                    },
                },
                sessionDescriptionHandlerOptions: {
                    constraints: {
                        audio: true,
                        video: true
                    },
                }
            };

            const sessionDescriptionHandlerOptions = session.sessionDescriptionHandlerOptionsReInvite;
            session.sessionDescriptionHandlerOptionsReInvite = sessionDescriptionHandlerOptions;
            session
                .invite(reinviteOptions)
                .then(() => {
                    console.warn('ReInvite sent');

                })
                .catch((error) => {
                    console.log(error);
                });
            session.stateChange.addListener((newState) => {
                switch (newState) {
                    case "Establishing":
                        console.warn("outgoing Establishing");
                        break;
                    case "Established":
                        console.warn("outgoing Established");

                        setupTimer();
                        // setupRemoteMedia(currentCall);
                        setupRemoteMedia1();
                        // let senderStream = currentCall.sessionDescriptionHandler.peerConnection;
                        // let localStream = new MediaStream();
                        //
                        // senderStream.getSenders().forEach((sender) => {
                        //     if(sender.track){
                        //         localStream.addTrack(sender.track);
                        //         localVideo.srcObject = localStream;
                        //     }
                        // });
                        // localVideo.play();
                        //
                        // let remoteStream = new MediaStream();
                        // senderStream.getReceivers().forEach((receiver) =>{
                        //     if(receiver.track){
                        //         remoteStream.addTrack(receiver.track);
                        //         remoteVideo.srcObject = remoteStream;
                        //     }
                        // });
                        // remoteVideo.play();
                        break;
                    case "Terminated":
                        endCall();
                        clearTimer();
                        console.warn("outgoing Terminated");
                        break;
                    default:
                        break;
                }
            });

            streams.getVideoTracks()[0].onended = function () {
                callHangup();
            };

        }, function (error) {
            console.log(error);
            callHangup();
        });
}
function dispatchAddTrackEvent(stream, track) {
    stream.dispatchEvent(new MediaStreamTrackEvent("addtrack", {track}));
}

function dispatchRemoveTrackEvent(stream, track) {
    stream.dispatchEvent(new MediaStreamTrackEvent("removetrack", {track}));
}
function toggleMic() {
    var micIcon = document.getElementById('micIcon');
    if (micIcon.textContent === 'mic') {
        micIcon.textContent = 'mic_off';
        $('#muteCall').trigger('click');
        customSIPModule.muteCall(currentCall);
    } else {
        micIcon.textContent = 'mic';
        $('#unmuteCall').trigger('click');
        customSIPModule.unmuteCall(currentCall);
    }
    document.querySelectorAll('[data-id="mic-ele"]').forEach(function(element) {
        if (element.textContent === 'mic') {
            element.textContent = 'mic_off';
            $('#muteCall').trigger('click');
            customSIPModule.muteCall(currentCall);
        } else {
            element.textContent = 'mic';
            $('#unmuteCall').trigger('click');
            customSIPModule.unmuteCall(currentCall);
        }
    });


}
function resetSettings(){

    var micIcon = document.getElementById('micIcon');
    micIcon.textContent = 'mic';

    var videoIcon = document.getElementById('videoIcon');
    videoIcon.textContent = 'videocam';

    $('#holdCall').removeClass('d-none');
    $('#unholdCall').addClass('d-none');

    $('#muteCall').removeClass('d-none');
    $('#unmuteCall').addClass('d-none');
    $('.dial-pad-call, .call-minimizer, .dial-pad-Videocall, .call-forward-dialer-section').addClass("d-none");

    $('#backToDialpad').addClass('d-none');
    $('.close-dialer').removeClass('d-none');
    $('#calls-action-tabs li a[href="#all-swipe"]')[0].click();

    audioCall = true;

    getCallLogData();

    document.querySelectorAll('[data-id="mic-ele"]').forEach(function(element) {
        element.textContent = 'mic';
        $('#unmuteCall').trigger('click');
        customSIPModule.unmuteCall(currentCall);
    });
    count = 0;
}
function toggleVideoIcon() {
    var videoIcon = document.getElementById('videoIcon');
    const localStream = currentCall.sessionDescriptionHandler.peerConnection.getLocalStreams()[0];
    const videoTracks = localStream.getVideoTracks();

    if (videoIcon.textContent === 'videocam') {
        videoIcon.textContent = 'videocam_off';
        videoTracks[0].enabled = false;
    } else {
        videoIcon.textContent = 'videocam';
        videoTracks[0].enabled = true;
    }
}
function muteCall(){
    if (currentCall){
        customSIPModule.muteCall(currentCall);
        var micIcon = document.getElementById('micIcon');
        micIcon.textContent = 'mic_off';
    }
    $('#muteCall').addClass('d-none');
    $('#unmuteCall').removeClass('d-none');
}
function unmuteCall(){
    if (currentCall){
        customSIPModule.unmuteCall(currentCall);
        var micIcon = document.getElementById('micIcon');
        micIcon.textContent = 'mic';
    }
    $('#muteCall').removeClass('d-none');
    $('#unmuteCall').addClass('d-none');
}
function holdCall(){
    if(currentCall){
        customSIPModule.holdCall(currentCall);
    }
    $('#holdCall').addClass('d-none');
    $('#unholdCall').removeClass('d-none');
}
function unholdCall(){
    if(currentCall){
        customSIPModule.unholdCall(currentCall);
        unmuteCall();
    }
    $('#holdCall').removeClass('d-none');
    $('#unholdCall').addClass('d-none');


}
function transferCall() {

    var destinationNumber = $('#transfer-call-number').val();
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
function disableDialpadIcons(divId) {

    const div = document.getElementById(divId);
    if (div) {
        const elements = div.getElementsByTagName('*');
        for (let i = 0; i < elements.length; i++) {
            if(elements[i].id != 'switchToDialer'){
                elements[i].disabled = true;
                elements[i].style.backgroundColor = '#999';
                div.style.pointerEvents = 'none';
            }
        }
        // Prevent pointer events on the div
    }
}
function enableDialpadIcons(divId) {
    const div = document.getElementById(divId);
    if (div) {
        const elements = div.getElementsByTagName('*');
        for (let i = 0; i < elements.length; i++) {
            elements[i].disabled = false;
            elements[i].style.backgroundColor = '';
        }
        div.style.pointerEvents = ''; // Restore pointer events on the div
        div.style.backgroundColor = ''; // Prevent pointer events on the div
    }
}
function disableCallerIcon(){
    $("#caller-action").removeClass('call-connected-icons');

    // const div = document.getElementById('caller-action');
    // if (div) {
    //     const elements = div.getElementsByTagName('*');
    //     for (let i = 0; i < elements.length; i++) {
    //         if(elements[i].id != 'switchToDialer' && elements[i].id != 'hangup-call'){
    //             elements[i].disabled = true;
    //             div.style.pointerEvents = 'none';
    //         }
    //     }
    //      // Restore pointer events on the div
    // }
}
function enableCallerIcon(){
    $("#caller-action").addClass('call-connected-icons');
    const div = document.getElementById('caller-action');
    if (div) {
        const elements = div.getElementsByTagName('*');
        for (let i = 0; i < elements.length; i++) {
            elements[i].disabled = false;
        }
        div.style.pointerEvents = ''; // Restore pointer events on the div
    }
}
function updateCallEndTime() {
    var call_id = callID;
    $.ajax({
        async: true,
        data: 'callId=' + call_id,
        type: 'POST',
        url: baseURL + "index.php?r=extension/extension-cdr/update-call-end-time",
        success: function (result) {
            getCallLogData();
        }
    });
}

function updateCallAnsTime() {
    var call_id = callID;
    $.ajax({
        async: false,
        data: 'callId=' + call_id,
        type: 'POST',
        url: baseURL + "index.php?r=extension/extension-cdr/update-call-ans-time",
        success: function (result) {
        }
    });
}
