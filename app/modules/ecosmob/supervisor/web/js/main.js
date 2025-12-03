(function () {
    var vertoHandle, vertoCallbacks, currentCall;
    // put your code here!
    $.verto.init({}, bootstrap);

    function bootstrap(status) {
        var loginHost = wss_url;
        var server_wss_url = extensionRegisterURL;

        console.log("Supervisor - loginhost ="+ loginHost);
        console.log("Supervisor - extensionRegisterURL =" + server_wss_url);
        vertoHandle = new jQuery.verto({
            login: extensionNumber + '@' + server_wss_url,
            passwd: extensionPassword,
            socketUrl: 'wss://'+ loginHost + ':8088',
            iceServers: [
                {
                    urls: [
                        "turn:global.turn.twilio.com:3478?transport=udp",
                        "turn:global.turn.twilio.com:3478?transport=tcp",
                        "turn:global.turn.twilio.com:443?transport=tcp",
                    ],
                    username: "689fd83dba4f541399f53af072ca63e449e7b28c03de591ada43b490f9f5cfe1",
                    credential: "WgMgzG9aw9m2xYc0K/EBN5d3ql8FY0OVmefV0jIX7Rk="
                },
                {
                    urls: [
                        "stun:stun.l.google.com:3478"
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

    vertoCallbacks = {
        onWSLogin: onWSLogin,
        onWSClose: onWSClose,
        //onDialogState: onDialogState,
        onMessage: onMessage,
    };

    function onWSLogin(verto, success) {
        console.log('onWSLogin', success);
    }

    function onWSClose(verto, success) {
        console.log('onWSClose', success);
    }


    function makeBargeignCallDisable(uuid) {
        var caller_id_name = extensionName;
        var caller_id_number = extensionNumber;
        if(currentCall){
            currentCall.hangup();
        }
    }

    function makeBargeignCall(uuid) {
        var caller_id_name = extensionName;
        var caller_id_number = extensionNumber;
        if(currentCall){
            currentCall.hangup();
        }
        currentCall = vertoHandle.newCall({
            // Extension to dial.
            destination_number: '*93',
            caller_id_name: caller_id_name,
            caller_id_number: caller_id_number,
            verto_dvar_barge_uuid: uuid,
            useStereo: true,
            useVideo: false,
            userVariables: {
                barge_uuid: uuid
            },
            dedEnc: false,
        });
    }


    function makeHangupCall(uuid) {
        var caller_id_name = extensionName;
        var caller_id_number = extensionNumber;
        currentCall = vertoHandle.newCall({
            // Extension to dial.
            destination_number: '*94',
            caller_id_name: caller_id_name,
            caller_id_number: caller_id_number,
            verto_dvar_barge_uuid: uuid,
            useStereo: true,
            useVideo: false,
            userVariables: {
                barge_uuid: uuid
            },
            dedEnc: false,
        });
    }

    function makeListenCallDisable(uuid)
    {
        var caller_id_name = extensionName;
        var caller_id_number = extensionNumber;
        if(currentCall){
            currentCall.hangup();
        }
    }

    function makeListenCall(uuid) {
        var caller_id_name = extensionName;
        var caller_id_number = extensionNumber;
        if(currentCall){
            currentCall.hangup();
        }

        currentCall = vertoHandle.newCall({
            // Extension to dial.
            destination_number: '*95',
            caller_id_name: caller_id_name,
            caller_id_number: caller_id_number,
            verto_dvar_barge_uuid: uuid,
            useStereo: true,
            useVideo: false,
            userVariables: {
                barge_uuid: uuid
            },
            dedEnc: false,
        });
    }

    function makeWhisperCallDisable(uuid) {
        var caller_id_name = extensionName;
        var caller_id_number = extensionNumber;
        if(currentCall){
            currentCall.hangup();
        }
    }

    function makeWhisperCall(uuid) {
        var caller_id_name = extensionName;
        var caller_id_number = extensionNumber;
        if(currentCall){
            currentCall.hangup();
        }

        currentCall = vertoHandle.newCall({
            // Extension to dial.
            destination_number: '*96',
            caller_id_name: caller_id_name,
            caller_id_number: caller_id_number,
            verto_dvar_barge_uuid: uuid,
            useStereo: true,
            useVideo: false,
            userVariables: {
                barge_uuid: uuid
            },
            dedEnc: false,
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


    function onMessage(verto, dialog, message, data) {
        switch (message) {
            case $.verto.enum.message.pvtEvent:
                if (data.pvtData) {
                    switch (data.pvtData.action) {
                        case "conference-liveArray-join":
                            initLiveArray(verto, dialog, data);
                            break;
                        case "conference-liveArray-part":
                            break;
                    }
                }
                break;
            case $.verto.enum.message.info:
                break;
            case $.verto.enum.message.display:
                break;
            case $.verto.enum.message.clientReady:
                break;
        }
    }

})();
