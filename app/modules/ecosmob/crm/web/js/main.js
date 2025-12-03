(function () {

    var vertoHandle, vertoCallbacks, currentCall, currentCallNew;
    var current_uuid = '';
    var timer = new Timer();
    var currentCallTime;
    var is_call_active = false;
    var is_stop_playback = false;
    var is_m_call = false;
    var is_disp_submit = true;
    var queue_join_time = "";

    // put your code here!
    $.verto.init({}, bootstrap);
    var date = new Date();

    function bootstrap(status) {
        var loginHost = wss_url;
        var server_wss_url = extensionRegisterURL;
        console.log("loginhost =" + loginHost);
        console.log("extensionRegisterURL =" + server_wss_url);
        console.log("extensionNumber =" + extensionNumber);
        console.log("extensionPassword =" + extensionPassword);
        vertoHandle = new jQuery.verto({
            login: extensionNumber + '@' + server_wss_url,
            passwd: extensionPassword,
            socketUrl: 'wss://' + loginHost + ':8088',
            ringFile: 'https://' + loginHost + '/web/theme/sound/bell_ring2.mp3',
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
        onDialogState: onDialogState,
        onMessage: onMessage,
    };

    function onWSLogin(verto, success) {
        console.log('onWSLogin', success);
    }

    function onWSClose(verto, success) {
        console.log('onWSClose', success);
    }

    timer.addEventListener('secondsUpdated', function () {
        currentCallTime = timer.getTimeValues().toString();
        $('.call-timer').html(currentCallTime);
    });

    function callHangupBtn() {
        $('#hold-call').attr('src', urlDisableUnHold);
        $('#hold-call').attr('title', titleUnhold);
        $('#mute-call').attr('src', urlUmute);
        $('#mute-call').attr('title', titleUnmute);
        $('#transfer-call').attr('src', urlDisableTransfercall);
        $('#hang-up-call').attr('src', urlDisableHangup);
    }

    function makeCall() {
        var lg_contact_number = $('#lg_contact_number').val();
        var lg_id = $('#lg_id').val();
        var caller_id_name = cmp_caller_name;
        var caller_id_number = extensionNumber;
        var recording_file = '/usr/local/freeswitch/recordings/' + new Date().getTime() + '_' + lg_contact_number + '_' + extensionNumber + '.mp3';


        var outgoingBandwidth = 'default';
        var incomingBandwidth = 'default';
        if (!lg_contact_number) {
            lg_contact_number = 0;
        }

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
                agent_id: agent_id,
                lead_id: lg_id,
                campaign_id: campaign_id,
                cmp_caller_id: cmp_caller_id,
                recording_file: recording_file,
            },
            dedEnc: false,
        });
    }

    $('.minus').click(function () {
        decreaseVolume();
    });
    $('.plus').click(function () {
        increaseVolume();
    });

    function increaseVolume() {
        if (currentCall) {
            var $input = $('#volumn');
            if ($input.val() < 1) {
                let volumn = $input.val() + 1.0;
                $input.val(volumn);
                currentCall.audioStream.volume = volumn;
            }
        }
    }

    function decreaseVolume() {
        if (currentCall) {
            var $input = $('#volumn');
            if ($input.val() > 0) {
                let volumn = $input.val() - 1.0;
                $input.val(volumn);
                currentCall.audioStream.volume = volumn;
            }
        }
    }

    function makeCallDialPad() {
        var lg_contact_number = $("#output").text();
        var lg_id = $('#lg_id').val();
        var caller_id_name = extensionName;
        var caller_id_number = extensionNumber;

        var outgoingBandwidth = 'default';
        var incomingBandwidth = 'default';
        if (!lg_contact_number) {
            lg_contact_number = 0;
        }

        if (currentCall) {
            currentCall.hold();
        }

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
                agent_id: agent_id,
                lead_id: lg_id,
                campaign_id: campaign_id
            },
            dedEnc: false,
        });
        campaignCdr(extensionNumber);
    }

    var count = 0;

    $(".digit").on('click', function () {
        var num = ($(this).clone().children().remove().end().text());
        if (count < 17) {
            $("#output").append('<span>' + num.trim() + '</span>');
            count++
        }
    });

    $("#clear").click(function () {
        $("#output :last").remove();
        count--;
    });

    $("#call").click(function () {
        if (count) {
            is_m_call = true;
            makeCallDialPad();
            $("#dialPadModal").modal('close');
            $("#output").html('');
            count = 0;
        }
    });

    $('#dis-sub').attr('disabled', true);
    $('#agentdispositionmapping-disposition').on('change', function(){
        if($(this).val() != ''){
            $('#dis-sub').attr('disabled', false);
            $('#dis-sub').addClass('amber');
        }else{
            $('#dis-sub').attr('disabled', true);
            $('#dis-sub').removeClass('amber');
        }
    });

    function onDialogState(d) {
        if (!currentCall) {
            currentCall = d;
        }
        if (d.state.name == 'hangup') {
            console.log("is_stop_playback ====> " + is_stop_playback);
            if (is_stop_playback == true) {
                //is_stop_playback = false;
                return;
            }
            callHangupBtn();
            $('#modal1').modal({
                dismissible: false, // Modal can be dismissed by clicking outside of the modal
            });
            $('#modal1').modal('open');
            is_disp_submit = false;

            deleteActiveCallStatus();
            updateCallStatus();
            $('#crmmodal').modal('close');
            if (campaignType != 'Blended' && campaignDialerType != 'AUTO') {
                //updateHangUpStatus();
            }
            updateHangUpStatus();

            $('.dialnext').attr('src', urlDialnext);
            $('.pause').attr('src', urlPause);

            //$(".call-timer").html("00:00:00");
            $(".call-timer").html("");
        }
        if (d.state.name == 'answering') {
            updateAnswertime();
            updateAnswerStatus();
        }
        if (d.state.name == 'active') {
            $('#mute-call').attr('src', EnableUnmute);
            $('#hold-call').attr('src', urlUnHold);
            $('#hang-up-call').attr('src', urlHangup);
            var laststate = currentCall.lastState['name'];
            if (laststate != 'held') {
                updateAnswertime();
                updateCallStatus();
                updateAnswerStatus();
            }
        }
        if (d.state.name == 'ringing') {
            is_call_active = true;
            if (typeof (Storage) !== "undefined") {
                window.localStorage.setItem('call_mute', true);
                window.localStorage.setItem('call_hold', true);
            }

            if ($('.pause').attr('src') == urlDisablePause) {
                return false;
            }
            $('.pause').attr('src', urlDisablePause);

            current_uuid = currentCall.params.callID;
            campaignCdr(extensionNumber);

            $('#transfer-call').attr('src', urlTransfercall);

            updateCallStatus();
            activeCallScreen();
            if (currentCall.params.verto_h_lead_id) {
                callProgresiveType();
            } else if (currentCall.params.remote_caller_id_number) {
                callProgresiveType();
            }

            if (campaignType == 'Inbound') {
                if (currentCall.params.verto_h_auto_answer == 'yes') {
                    currentCall.answer();
                } else if (currentCall.params.verto_h_auto_answer == 'no') {
                    $('#incomingCall').modal({
                        dismissible: false, // Modal can be dismissed by clicking outside of the modal
                    });
                    $('#incomingCall').modal('open');
                    if (currentCall.params.caller_id_number) {
                        console.log('Caller Id Number', currentCall.params.caller_id_number);
                        $('#caller_id_number').val(currentCall.params.caller_id_number);
                    }
                } else if (!currentCall.params.verto_h_auto_answer) {
                    currentCall.answer();
                }

                /*campaignCdr();*/
                console.log("Inbound Current Call", currentCall);
            } else if (campaignType == 'Outbound' && campaignDialerType == 'PREVIEW') {
                $('#incomingCall').modal({
                    dismissible: false, // Modal can be dismissed by clicking outside of the modal
                });
                $('#incomingCall').modal('open');
                if (currentCall.params.caller_id_number) {
                    console.log('Caller Id Number', currentCall.params.caller_id_number);
                    $('#caller_id_number').val(currentCall.params.caller_id_number);
                }
            } else if (campaignType == 'Outbound' && campaignDialerType == 'PROGRESSIVE') {
                console.log('Progresive');
                currentCall.answer();
            } else if (campaignType == 'Blended' && campaignDialerType == 'AUTO') {

                $('#incomingCall').modal({
                    dismissible: false, // Modal can be dismissed by clicking outside of the modal
                });
                $('#incomingCall').modal('open');
                if (currentCall.params.caller_id_number) {
                    console.log('Caller Id Number', currentCall.params.caller_id_number);
                    $('#caller_id_number').val(currentCall.params.caller_id_number);
                }

            }

            if ($('.dialnext').attr('src') == urlDialnext) {
                $('.dialnext').attr('src', urlDisableDialnext);
                return false;
            }
        }

        switch (d.state.name) {
            case "trying":
                if ($('.pause').attr('src') == urlDisablePause) {
                    return false;
                }
                $('.pause').attr('src', urlDisablePause);
                updateCallStatus();
                break;
                $('#transfer-call').attr('src', urlTransfercall);
            case "active":

                unHoldCall();
                if ((campaignType == 'Inbound') || (campaignType == 'Blended' && campaignDialerType == 'AUTO')) {
                    stopPlayback(currentCall.params.verto_h_aleg_uuid);
                }
                is_call_active = true;
                $('#mute-call').attr('src', EnableUnmute);
                $('#hold-call').attr('src', urlUnHold);
                $('#hang-up-call').attr('src', urlHangup);

                if (window.localStorage.getItem('call_hold')) {
                    if (window.localStorage.getItem("call_hold") == "false") {
                        $('.hold-unhold').toggleClass('hold-unhold');
                        $('#hold-call').attr('src', urlHold);
                        $('#hold-call').attr('title', titlehold);
                        currentCall.unhold();
                        currentCall.hold();
                    }
                }

                if (window.localStorage.getItem('call_mute')) {
                    if (window.localStorage.getItem("call_mute") == "false") {
                        $('.mute-unmute').toggleClass('mute-unmute');
                        $('#mute-call').attr('src', urlMute);
                        $('#mute-call').attr('title', titleMute);
                        currentCall.setMute("off");
                    }
                }

                if ($('.dialnext').attr('src') == urlDialnext) {
                    $('.dialnext').attr('src', urlDisableDialnext);
                    return false;
                }

                updateCallStatus();
                if (currentCall.params.userVariables) {
                    var activeAgentName = currentCall.params.userVariables.agent_id;
                } else {
                    var activeAgentName = currentCall.params.verto_h_agent_id;
                }

                if (currentCall.params.verto_h_queue_join_time) {
                    queue_join_time = currentCall.params.verto_h_queue_join_time;
                }

                $(".call-timer").show();

                if ($('.pause').attr('src') == urlDisablePause) {
                    return false;
                }
                $('.pause').attr('src', urlDisablePause);

                $('#transfer-call').attr('src', urlTransfercall);

                $(".call-timer").show();
                break;
            case "answering":
                is_call_active = true;

                if ($('.pause').attr('src') == urlDisablePause) {
                    return false;
                }
                $('.pause').attr('src', urlDisablePause);

                updateCallStatus();
                $('#transfer-call').attr('src', urlTransfercall);
                break;
            case "hangup":

                if (is_stop_playback == true) {
                    return;
                }

                is_call_active = false;
                callHangupBtn();

                $('.dialnext').attr('src', urlDialnext);
                $('.dialnext').attr('src', urlDialnext);
                updateCallStatus();
                deleteActiveCallStatus();

                $('#modal1').modal({
                    dismissible: false, // Modal can be dismissed by clicking outside of the modal
                });

                $('#modal1').modal('open');
                is_disp_submit = false; //20-1-2020

                $('.leftside-navigation li a').unbind('click', false);

                timer.stop();
                //$(".call-timer").html("00:00:00");
                $(".call-timer").html("");

                if (typeof (Storage) !== "undefined") {
                    window.localStorage.setItem('call_mute', true);
                    window.localStorage.setItem('call_hold', true);
                }

                break;
            case "destroy":
                deleteActiveCallStatus();
                if (!is_call_active) {
                    $('#incomingCall').modal('close');
                }
                break;
        }
    }

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

    function calldialNext() {
        $.ajax({
            async: false,
            url: baseURL + "index.php?r=crm/crm/dial-next-data",
            success: function (data) {
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
                    $('.crm-submit').show();
                    makeCall();
                    activeCallScreen();
                    campaignCdr(extensionNumber);

                } else {
                    $('#lead-group-member-form').html(no_more_lead_in_hopper);
                    $('.crm-submit').hide();
                }
            }
        });
    }

    // Location Storage Start
    $(document).ready(function () {

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
    });


// Location storage end

    function callProgresiveType() {
        if (currentCall.params.verto_h_lead_id) {
            var leadId = currentCall.params.verto_h_lead_id;
        } else if (currentCall.params.remote_caller_id_number) {
            var leadCallerIdNumber = currentCall.params.remote_caller_id_number;
        }

        $.ajax({
            type: 'POST',
            data: {leadCallerIdNumber: leadCallerIdNumber, lead_id: leadId},
            async: false,
            url: baseURL + "index.php?r=crm/crm/progresive-data",
            success: function (data) {

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

    function callcrm() {
        $.ajax({
            async: false,
            url: baseURL + "index.php?r=crm/crm/crm",
            success: function (result) {
                $('#form_elem').html(result);
            }
        });
    }

    function submitDisposition() {
        is_disp_submit = true;
        updateHangUpStatuscustom();

        if (currentCall.params.callID === "undefined")
            var uuId = current_uuid;
        else
            var uuId = currentCall.params.callID;

        var cause = currentCall.cause;
        var data = $('#submit-disposition-form').serializeArray();
        data.push({'name': 'callId', 'value': uuId}, {'name': 'cause', 'value': cause});

        currentCall = null;
        $.ajax({
            async: false,
            data: data,
            type: 'POST',
            url: baseURL + "index.php?r=crm/crm/submit-disposition",
            success: function (result) {
                if (is_m_call == true) {
                    is_m_call = false;
                    window.location.reload();
                }
            }
        });
    }

    $(document).on("submit", "#submit-disposition-form", function (e) {
        e.preventDefault();
        submitDisposition();
        if (campaignType == 'Blended' && campaignDialerType == 'AUTO') {
            //updateHangUpStatus();
        }
        document.getElementById("submit-disposition-form").reset();
        $('#modal1').modal('close');
    });


    function callscript() {
        $.ajax({
            async: false,
            url: baseURL + "index.php?r=crm/crm/script",
            success: function (result) {
                $('#Script').html(result);
            }
        });
    }

    function transferCall() {

        var destinationNumber = $('#destination-number').val();
        if (destinationNumber) {
            currentCall.transfer(destinationNumber);
        }
    }

    $(document).on("click", "#transfer-call", function () {

        console.log($('#transfer-call').attr('src'));
        console.log(urlDisableTransfercall);
        if ($('#transfer-call').attr('src') == urlDisableTransfercall) {
            return false;
        }
        console.log("Click Transfer-call Current Call", currentCall);
        $('#transferCallModal').modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
        $('#transferCallModal').modal('open');
    });
    $(document).on("click", "#transferCallBtn", function () {
        transferCall();
        $('#transferCallModal').modal('close');
        $('#destination-number').val('');
    });
    $(document).on("click", "#hang-up-call", function () {
        if ($('#hang-up-call').attr('src') == urlDisableHangup) {
            return false;
        }
        callHangupBtn();
        $('.pause').attr('src', urlPause);
        $('.dialnext').attr('src', urlDialnext);
        currentCall.hangup();
        $('#modal1').modal('open');
        is_disp_submit = false; //20-1-2020
    });

    $(document).on("click", "#acceptCall", function () {
        if (!currentCall) {
            $('#incomingCall').modal('close');
            return false;
        }
        currentCall.answer();
        $('#incomingCall').modal('close');
    });
    $(document).on("click", "#rejectCall", function () {
        if (!currentCall) {
            $('#incomingCall').modal('close');
            return false;
        }
        currentCall.hangup();
        callHangupBtn();
        $('.pause').attr('src', urlPause);
        $('.dialnext').attr('src', urlDialnext);
        $('#modal1').modal('open');
        is_disp_submit = false; //20-1-2020
        $('#incomingCall').modal('close');
    });

    $(document).on("click", ".crm", function () {
        callcrm();
    });
    $(document).on("click", ".script", function () {
        callscript();
    });
    $(document).on("click", ".dialnext", function () {
        if ($('.dialnext').attr('src') == urlDisableDialnext) {
            return false;
        }
        $('.dialnext').attr('src', urlDisableDialnext);
        calldialNext();
    });
    $(document).ready(function () {
        callscript();
    });

    $('.mute-unmute').click(function () {
        // if ($('#mute-call').attr('src') == urlUmute) {
        //
        //     return false;
        //
        // }
        var $this = $(this).toggleClass('mute-unmute');
        if ($(this).hasClass('mute-unmute')) {

            if (currentCall)
                currentCall.setMute("on");

            $('#mute-call').attr('src', EnableUnmute);
            $('#mute-call').attr('title', titleUnmute);

            // CALL MUTE
            if (typeof (Storage) !== "undefined") {
                window.localStorage.setItem('call_mute', true);
            }

        } else {

            if (currentCall)
                currentCall.setMute("off");

            $('#mute-call').attr('src', urlMute);
            $('#mute-call').attr('title', titleMute);

            // CALL UNMUTE
            if (typeof (Storage) !== "undefined") {
                window.localStorage.setItem('call_mute', false);
            }

        }
    });

    function unHoldCall() {
        $('#hold-call').attr('src', urlUnHold);
        $('#hold-call').attr('title', titleUnhold);
        currentCall.unhold();
        // CALL HOLD
        if (typeof (Storage) !== "undefined") {
            window.localStorage.setItem('call_hold', true);
        }
    }

    $('.hold-unhold').click(function () {
        // if ($('#hold-call').attr('src') == urlDisableUnHold) {
        //     return false;
        // }

        var $this = $(this).toggleClass('hold-unhold');
        if ($(this).hasClass('hold-unhold')) {
            $('#hold-call').attr('src', urlUnHold);
            $('#hold-call').attr('title', titleUnhold);
            currentCall.unhold();

            // CALL HOLD
            if (typeof (Storage) !== "undefined") {
                window.localStorage.setItem('call_hold', true);
            }

        } else {
            $(this).text(unhold);
            currentCall.hold();
            $('#hold-call').attr('src', urlHold);
            $('#hold-call').attr('title', titlehold);

            // CALL UNHOLD
            if (typeof (Storage) !== "undefined") {
                window.localStorage.setItem('call_hold', false);
            }

        }
    });

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


    $(document).on("submit", "#lead-ajax-form", function (e) {
        e.preventDefault();
        updateLead();
    });

    function cancelLead() {
        $.ajax({
            async: false,
            data: $('#lead-ajax-form').serializeArray(),
            type: 'POST',
            url: baseURL + "index.php?r=crm/crm/cancel-lead",
            success: function (result) {
                var final_data = JSON.parse(result);
                console.log(final_data);
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
        });
    }

    $(document).on("click", ".cancel", function (e) {
        e.preventDefault();
        cancelLead();
    });


    /* Pause Button Call   */
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

    $(document).on("click", ".pause", function (e) {
        if ($('.pause').attr('src') == urlDisablePause) {
            return false;
        }
        submitPause();
    });

    /* Resume Button Call   */
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

    $(document).on("click", ".resume", function (e) {
        submitResume();
    });

    /* Active Call Screen */
    function activeCallScreen() {
        if (is_stop_playback == true) {
            return;
        }
        var activeCallerId = currentCall.params.caller_id_number;
        var activeCallId = currentCall.params.callID;
        if (currentCall.params.destination_number) {
            var activeDestiNumber = currentCall.params.destination_number;
        } else {
            var activeDestiNumber = currentCall.params.remote_caller_id_number;
        }

        var activeStateName = currentCall.state.name;

        if (currentCall.params.userVariables) {
            var activeAgentName = currentCall.params.userVariables.agent_id;
            var activeCampaignName = currentCall.params.userVariables.campaign_id;
        } else {
            var activeAgentName = currentCall.params.verto_h_agent_id;
            var activeCampaignName = campaign_id;
        }

        if (currentCall.params.verto_h_queue_join_time) {
            queue_join_time = currentCall.params.verto_h_queue_join_time;
            console.log(" 222 queue_join_time " + queue_join_time);
        }

        if (currentCall.params.verto_h_cc_queue) {
            var activeQueueName = currentCall.params.verto_h_cc_queue;
        } else {
            var activeQueueName = '';
        }

        $.ajax({
            data: {
                activeCallerId: activeCallerId,
                activeCallId: activeCallId,
                activeDestiNumber: activeDestiNumber,
                activeStateName: activeStateName,
                activeAgentName: activeAgentName,
                activeCampaignName: activeCampaignName,
                activeQueueName: activeQueueName
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
        var uuId = currentCall.params.callID;
        $.ajax({
            async: false,
            data: 'uuID=' + uuId,
            type: 'POST',
            url: baseURL + "index.php?r=crm/crm/active-call-delete",
            success: function (result) {
            }
        });
    }

    function updateAnswerStatus() {
        var uuIdStatus = currentCall.params.callID;
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

    function updateHangUpStatuscustom() {
        $.ajax({
            async: false,
            url: baseURL + "index.php?r=crm/crm/hangup-updatecustom",
            success: function (result) {
            }
        });
    }


    function updateRingingStatus() {
        var uuIdStatus = currentCall.params.callID;
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
        var uuIdStatus = currentCall.params.callID;
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
        var uuIdStatus = currentCall.params.callID;
        var activeStateName = currentCall.state.name;
        $.ajax({
            async: false,
            data: {uuIdStatus: uuIdStatus, activeStateName: activeStateName},
            type: 'POST',
            url: baseURL + "index.php?r=crm/crm/call-status-update",
            success: function (result) {
            }
        });
    }

    function campaignCdr(extensionNumber) {
        var activeCallerId = currentCall.params.caller_id_number;

        var activeCallId = currentCall.params.callID;
        if (currentCall.params.verto_h_did_number) {
            var activeDestiNumber = currentCall.params.verto_h_did_number;
        } else if (currentCall.params.destination_number) {
            var activeDestiNumber = currentCall.params.destination_number;
        } else if (currentCall.params.remote_caller_id_number) {
            var activeDestiNumber = currentCall.params.remote_caller_id_number;
        }
        /*var activeDestiNumber = lg_contact_number;*/
        var activeStateName = currentCall.state.name;
        if (currentCall.params.userVariables) {
            var activeAgentName = currentCall.params.userVariables.agent_id;
            var activeCampaignName = currentCall.params.userVariables.campaign_id;
        } else {
            var activeAgentName = currentCall.params.verto_h_agent_id;
            var activeCampaignName = campaign_id;
        }

        if (currentCall.params.verto_h_queue_join_time) {
            queue_join_time = currentCall.params.verto_h_queue_join_time;
        }

        //var temp_queue_name = '';
        if (currentCall.params.verto_h_cc_queue) {
            var temp_queue_name = currentCall.params.verto_h_cc_queue;
        } else {
            var temp_queue_name = '';
        }

        // Start Recording file
        if (currentCall.params.verto_h_recording_file) {
            var recording_file = currentCall.params.verto_h_recording_file;
        } else if (currentCall.params.userVariables) {
            var recording_file = currentCall.params.userVariables.recording_file;
        } else {
            var recording_file = '';
        }
        // End  Recording file
        $.ajax({
            data: {
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

    function updateDashboard() {
        /* $.ajax({
                type: 'GET',
                url: baseURL + "index.php?r=agents/agents/updatedashboard",
                async: false,
                success: function (result) {
                    let final_data = $.parseJSON(result);
                    $("#query").text(final_data.query);
                    $("#totalCalls").text(final_data.totalCalls);
                    $("#totalTalkTimeMinute").text(final_data.totalTalkTimeMinute);

                }
            }); */
    }

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

    function stopPlayback(verto_h_aleg_uuid) {
        if (window.localStorage.getItem('call_hold') && window.localStorage.getItem('call_mute')) {
            if (window.localStorage.getItem("call_hold") == "false" || window.localStorage.getItem("call_mute") == "false") {
                return;
            }
        }

        is_stop_playback = true;
        //is_call_active = false;
        currentCallNew = vertoHandle.newCall({
            destination_number: '*99',
            caller_id_name: caller_id_number,
            caller_id_number: caller_id_number,
            verto_dvar_aleg_uuid: verto_h_aleg_uuid,
            useStereo: true,
            useVideo: false,
            userVariables: {
                aleg_uuid: verto_h_aleg_uuid
            },
            dedEnc: false,
        });
        //currentCall.dtmf("*");
        //currentCall.dtmf("9");


    }


    window.addEventListener('beforeunload', function (e) {
        if (is_disp_submit == false) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

})();
