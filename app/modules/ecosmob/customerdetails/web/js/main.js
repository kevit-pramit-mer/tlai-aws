(function() {
    var vertoHandle, vertoCallbacks,currentCall;
    // put your code here!
    $.verto.init({}, bootstrap);

    function bootstrap(status) {
        // Create a new verto instance:
        // This step performs a user login to FreeSWITCH via secure websocket.
        // The user must be properly configured in the FreeSWITCH user directory.
        var loginHost = '78.47.30.169';
        vertoHandle = new jQuery.verto({
            login: extensionNumber + '@' + loginHost,
            passwd: extensionPassword,
            socketUrl: 'wss://' + '@' +loginHost + ':8088',
            ringFile: 'sounds/bell_ring2.wav',
            iceServers: [
                {
                    url: 'stun:stun.l.google.com:19302',
                },
            ],
            deviceParams: {
                // Set to 'none' to disable outbound audio.g
                useMic: 'any',
                // Set to 'none' to disable inbound audio.
                useSpeak: 'any',
                // Set to 'none' to disable outbound video.
                useCamera: 'none',
            },
        }, vertoCallbacks);
        //makeCall(),
        document.getElementById("make-call").addEventListener("click", makeCall);
    }
    vertoCallbacks = {
        onWSLogin: onWSLogin,
        onWSClose: onWSClose,
        onDialogState: onDialogState,
    };

    function onWSLogin(verto, success) {
        console.log('onWSLogin', success);
    }

    function onWSClose(verto, success) {
        console.log('onWSClose', success);
    }
    function makeCall() {

        currentCall = vertoHandle.newCall({
            // Extension to dial.
            destination_number: '3520',
            caller_id_name: 'Test Guy',
            caller_id_number: '1008',
            outgoingBandwidth: 'default',
            incomingBandwidth: 'default',
            // Enable stereo audio.
            useStereo: true,
            // Set to false to disable inbound video.
            useVideo: true,
            // You can pass any application/call specific variables here, and they will
            // be available as a dialplan variable, prefixed with 'verto_dvar_'.
            userVariables: {
                // Shows up as a 'verto_dvar_email' dialplan variable.
                email: 'test@test.com'
            },
            // Use a dedicated outbound encoder for this user's video.
            // NOTE: This is generally only needed if the user has some kind of
            // non-standard video setup, and is not recommended to use, as it
            // dramatically increases the CPU usage for the conference.
            dedEnc: false,
            // Example of setting the devices per-call.
            //useMic: 'any',
            //useSpeak: 'any',
        });
    };
    function onDialogState(d) {
        switch (d.state.name) {
            case "trying":
                break;
            case "answering":
                break;
            case "active":
                break;
            case "hangup":
                log("Call ended with cause: " + d.cause);
                break;
            case "destroy":
                // Some kind of client side cleanup...
                break;
        }
    }


})();



