(function () {
    var vertoHandle, vertoCallbacks, currentCall;
    $.verto.init({}, bootstrap);

    function bootstrap(status) {
        var loginHost = '78.47.30.169';
        vertoHandle = new jQuery.verto({
            login: '1001@' + loginHost,
            passwd: 'Ext1001**',
            socketUrl: 'wss://' + '@' + loginHost + ':8088',
            iceServers: [
                {
                    urls: [
                        "stun:stun.l.google.com:19302",
                        "stun:stun.l.google.com:19302?transport=udp"
                    ]
                }
            ],

            tag: "audio-container",
            deviceParams: {
                useMic: 'any',
                useSpeak: 'any',
                useCamera: 'none',
            },

        }, vertoCallbacks);
    }

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
                break;
        }
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
        //var lg_contact_number = '+919726790126'
        //var lg_contact_number = '+919924373055';
        var lg_contact_number = '+918320609620';

        //var lg_id = $('#lg_id').val();
        var caller_id_name = 'Preview Campaign';
        var caller_id_number = '1001';

        var outgoingBandwidth = 'default';
        var incomingBandwidth = 'default';
        if (!lg_contact_number) {
            lg_contact_number = 0;
        }
        console.log(lg_contact_number);
        currentCall = vertoHandle.newCall({
            // Extension to dial.
            destination_number: lg_contact_number,
            caller_id_name: caller_id_name,
            caller_id_number: caller_id_number,
            outgoingBandwidth: outgoingBandwidth,
            incomingBandwidth: incomingBandwidth,
            useStereo: true,
            useVideo: false,
            userVariables: {
                email: 'test@test.com'
            },
            dedEnc: false,
        });
    };

    $(document).on("click", ".dialnext", function () {
        makeCall();
    });
    
})();
