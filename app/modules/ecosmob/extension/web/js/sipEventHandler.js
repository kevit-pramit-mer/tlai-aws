$(document).ready(function (){

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
    // if ("serviceWorker" in navigator) {
    //     // declaring scope manually
    //     navigator.serviceWorker.register("../modules/ecosmob/extension/web/js/sw.js").then(
    //         (registration) => {
    //             notificationReg = registration;
    //             console.warn("Service worker registration succeeded:", registration);
    //             console.warn("Scope:", registration.scope);
    //         },
    //         (error) => {
    //             console.warn(`Service worker registration failed: ${error}`);
    //         },
    //     );
    // } else {
    //     console.warn("Service workers are not supported.");
    // }
    disableDialpadIcons('call');
    disableDialpadIcons('call-forward');

    getCallLogData();
    getSpeedDailData();
})

$(".digit").on('click', function () {
    var num = ($(this).clone().children().remove().end().text());

    if (num != 0 && count < 17) {
        $("#output").val($('#output').val() + num.trim());
        $("#transfer-call-number").val($('#transfer-call-number').val() + num.trim());
        $("#output").keyup();
        enableDialpadIcons('call');
        enableDialpadIcons('call-forward');
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
    $("#output").keyup();
    count = $('#output').val().length;
    if(count == 0){
        disableDialpadIcons('call');
    }
});
$("#transfer-text-clear").click(function () {
    // $("#output :last").remove();
    //let value = $('#output').val();
    $('#transfer-call-number').val(
        function(index, value){
            return value.substr(0, value.length - 1);
        })
    count = $('#transfer-call-number').val().length;
    if(count == 0){
        disableDialpadIcons('call-forward');
    }
});

$("#output, #transfer-call-number").on('input paste',function (evt) {
    if (evt.type === "paste") {
        evt.preventDefault(); // Prevent pasting
        return false;
    }
});
$("#output").on('keyup',function (evt) {
    /*var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43) {
        return false;
    }else if(charCode == 8 || charCode == 46){
        $('#clear').trigger('click');
    }else{
        if(charCode != 17){
            if (count < 17) {
                count = $('#output').val().length;
                count++;
                enableDialpadIcons('call');
                return true;
            }
        }

    }
    return false;*/

    if($("#output").val() && speedDial){
        if(speedDial[$("#output").val()] !== undefined && $("#output").val() in speedDial){
            if(speedDial[$("#output").val()]) {
                $("#output").val(speedDial[$("#output").val()]);
            }
        }
    }

});
$("#transfer-call-number").on('keydown',function (evt) {
    // var charCode = (evt.which) ? evt.which : evt.keyCode;
    //
    // if(charCode == 8 || charCode == 46){
    //     $('#transfer-call-number').val(
    //         function(index, value){
    //             return value.substr(0, value.length - 1);
    //         });
    //     digitCount = $('#transfer-call-number').val().length;
    // }
    // if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43) {
    //     return false;
    // }
    // if (digitCount < 17) {
    //     digitCount = $('#transfer-call-number').val().length;
    //     enableDialpadIcons('call-forward');
    //
    //     return true;
    // }

    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43) {
        return false;
    }else if(charCode == 8 || charCode == 46){
        $('#transfer-text-clear').trigger('click');
    }else{
        if(charCode != 17){
            if (count < 17) {
                count = $('#transfer-call-number').val().length;
                count++;
                enableDialpadIcons('call-forward');
                return true;
            }
        }
    }
    return false;

});
timer.addEventListener('secondsUpdated', function () {
    currentCallTime = timer.getTimeValues().toString();
    $('.call-connect').text(currentCallTime);
});

/*$('.call-tabs').on('click', function(){
    var id = $(this).attr('data-id');
    $.ajax({
        type: 'GET',
        url: baseURL + "index.php?r=extension/extension/get-data&id="+id,
        async: false,
        success: function (result) {
            let final_data = $.parseJSON(result);
            if(id == 1) {
                $("#all-swipe .all-swipe-ul").html(final_data);
            }else if(id == 2){
                $("#missed-swipe .missed-swipe-ul").html(final_data);
            }else if(id == 3){
                $("#incoming-swipe .incoming-swipe-ul").html(final_data);
            }else if(id == 4){
                $("#outgoing-swipe .outgoing-swipe-ul").html(final_data);
            }
        }
    });
});*/

$('.concat-list').on('click', function(){
    $.ajax({
        type: 'GET',
        url: baseURL + "index.php?r=extension/extension/get-contacts",
        async: false,
        success: function (result) {
            let final_data = $.parseJSON(result);
            $("#swipe-tab-3 .contact-ul").html(final_data);
        }
    });
});

jQuery("#search-contacts").keyup(function () {
    var filter = jQuery(this).val();
    jQuery(".contact-ul li").each(function () {
        if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
            jQuery(this).hide();
        } else {
            jQuery(this).show()
        }
    });
});
jQuery("#transfer-call-number").keyup(function () {
    var filter = jQuery(this).val();
    jQuery(".contact-suggestion .call-lists").each(function () {
        if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
            jQuery(this).hide();
        } else {
            jQuery(this).show()
        }
    });
});
$(document).on("click", ".call-hangup, .video-call-hangup", function () {

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
                    //currentCall.reject(603);
                }

                break;
            case 'Established':
                hanupCause = "NORMAL_CLEARING";
                // An established session
                cleanupMedia();
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
        currentCall = '';
        if (callDirection === 'outgoing') {
            outgoingSession = '';
        }
        if (callDirection === 'incoming') {
            incomingSession = '';
        }
    }
    cleanupMedia();
    //resetSettings();
    //updateCallEndTime();
    //getCallLogData();

});
$(document).on("click", "#makeAudioCall", function () {
    initiateAudioCall();
});
$(document).on("click", "#makeVideoCall", function () {
    initiateVideoCall();
});
$(document).on("click", "#acceptCall", function () {
    if (!currentCall) {
        $('.dial-pad-call, .dial-pad-Videocall, .call-minimizer').addClass("d-none");
        return false;
    }
    let video = false;
    if(hasVideo == 1 && isRequestIncludeVideo == 1){
        video = true;
    }
    let constrainsDefault = {
        audio: true,
        video: video
    }

    const options = {
        sessionDescriptionHandlerOptions: {
            constraints: constrainsDefault,
        },
    }
    currentCall.accept(options);

});
$(document).on("click", "#rejectCall", function () {
    if (!currentCall) {
        endIncomingCall();
        return false;
    }
    endIncomingCall();
    updateCallEndTime();
    getCallLogData();
    currentCall.reject();
});
$(document).on("click", "#turnOnVideo", function () {
    toggleVideo(true);
    $('#turnOnVideo').toggleClass('d-none');
    $('#turnOffVideo').toggleClass('d-none');
});
$(document).on("click", "#turnOffVideo", function () {
    toggleVideo(false);
    $('#turnOnVideo').toggleClass('d-none');
    $('#turnOffVideo').toggleClass('d-none');
});
$(document).on("click", "#audioCall", function () {
    var destination_number = $(this).attr('data-number');
    $('.dial-pad-call').removeClass("d-none");
    initiateAudioCall(destination_number);
});
$(document).on("click", "#videoCall", function () {
    var destination_number = $(this).attr('data-number');
    $('.dial-pad-Videocall').removeClass("d-none");
    initiateVideoCall(destination_number);
});
$(document).on("click", "#muteVideoCall", function () {
    muteVideoCall();
});
$(document).on("click", "#unmuteVideoCall", function () {
    unmuteVideoCall();
});
$(document).on("click", ".call-connected-icons #muteCall", function () {
   muteCall();
});
$(document).on("click", ".call-connected-icons #unmuteCall", function () {
    unmuteCall();
});
$("#muteCall").parent('.action-box').click(function(){
    if($("#muteCall").parent().parent().hasClass('call-connected-icons')){
        if (!$("#muteCall").hasClass("d-none")) {
            muteCall();
        } else {
            unmuteCall();
        }
    }
});
$(document).on("click", "#holdCall", function () {
    if($("#holdCall").parent().parent().hasClass('call-connected-icons')) {
        holdCall();
    }
});
$(document).on("click", "#unholdCall", function () {
    if($("#unholdCall").parent().parent().hasClass('call-connected-icons')) {
        unholdCall();
    }
});
$("#holdCall").parent('.action-box').click(function(){
    if($("#holdCall").parent().parent().hasClass('call-connected-icons')) {
        if (!$("#holdCall").hasClass("d-none")) {
            holdCall();
        } else {
            unholdCall();
        }
    }
});
$(document).on("click", "#transferCall", function () {
    $('.call-forward-dialer-section').addClass('d-none');
    transferCall();
});

function switchToTransferDialpad(){
    count = 0;
    $.ajax({
        type: 'GET',
        url: baseURL + "index.php?r=extension/extension/get-fwd-contacts",
        async: false,
        success: function (result) {
            let final_data = $.parseJSON(result);
            $(".call-forward-dialer .contact-suggestion").html(final_data);
        }
    });
    $('.dial-pad-call, .call-minimizer, .dial-pad-Videocall, .dialer-section').addClass("d-none");
    $('.call-forward-dialer-section').removeClass('d-none');
    $("#transfer-call-number").val('');

    // $('.dialer-section').removeClass('d-none');
    // $('#tabs-swipe-demo li a[href="#swipe-tab-1"]')[0].click();
    //
    // $('#call').css('display','none');
    // $('#transfer-call-btn').removeClass('d-none');
    disableDialpadIcons('call-forward');
}
$(document).on("click", "#switchToTransferDialpad", function () {
    switchToTransferDialpad();
});
$(document).on("click", ".call-connected-icons #switchToTransferDialpad", function () {
    if($("#switchToTransferDialpad").parent().parent().hasClass('call-connected-icons')) {
        switchToTransferDialpad();
    }

});
$("#switchToTransferDialpad").parent('.action-box').click(function(){
    if($("#switchToTransferDialpad").parent().parent().hasClass('call-connected-icons')) {
        if (!$("#switchToTransferDialpad").hasClass("d-none")) {
            switchToTransferDialpad();
        }
    }
});
$(document).on("click", "#switchToDialer", function () {
    count = 0;
    $('#output').val('');
    $('.dial-pad-call, .call-minimizer, .dial-pad-Videocall').addClass("d-none");

    $('.dialer-section').removeClass('d-none');
    disableDialpadIcons('call');
    $('#tabs-swipe-demo li a[href="#swipe-tab-1"]')[0].click();
    $('#backToDialpad').removeClass('d-none');
    $('.close-dialer').addClass('d-none');
});
$('.dialer-icon-set').click(function(){
    $('.ext-dialer').removeClass('d-none');
    $('#tabs-swipe-demo li a[href="#swipe-tab-1"]')[0].click();
    disableDialpadIcons('call');
});
$("#tabs-swipe-demo li a").click(function(){
    if(($('#output').val()).length   <= 0){
        disableDialpadIcons('call');
    }
});

$('.bfl-list-open').on('click', function(){
    if($('.bfl-list').hasClass('active')){
        $('.bfl-list').removeClass('active');
        $('.dialpad-section, .ext-dialer').removeClass("set-right");
    } else {
        $('.bfl-list').addClass('active');
        $('.dialpad-section, .ext-dialer').addClass("set-right");
    }
    $.ajax({
        type: 'GET',
        url: baseURL + "index.php?r=extension/extension/get-blf-list",
        async: false,
        success: function (result) {
            let final_data = $.parseJSON(result);
            $(".bfl-list .blf-list-ul").html(final_data);
        }
    });
});
$(document).on('click','#backToDialpad',function(){
    $('.call-forward-dialer-section').addClass("d-none");
    if(!audioCall){
        $('.dial-pad-Videocall').removeClass('d-none');
    }else{
        $('.dial-pad-call').removeClass('d-none');
    }
});
$(document).on("click", ".fwd-contact", function(){
    $('#transfer-call-number').val('');
    if($(this).find('.ml-auto').text() != '') {
        $('#transfer-call-number').val($(this).find('.ml-auto').text());
    }else{
        $('#transfer-call-number').val($(this).find('.contact-number').text());
    }
    enableDialpadIcons('call-forward');
})

function getCallLogData(){
    $.ajax({
        type: 'GET',
        url: baseURL + "index.php?r=extension/extension/get-data&id=1",
        async: false,
        success: function (result) {
            $("#all-swipe .all-swipe-ul").html('');
            let final_data = $.parseJSON(result);
            $("#all-swipe .all-swipe-ul").html(final_data);
        }
    });
    $.ajax({
        type: 'GET',
        url: baseURL + "index.php?r=extension/extension/get-data&id=2",
        async: false,
        success: function (result) {
            $("#missed-swipe .missed-swipe-ul").html('');
            let final_data = $.parseJSON(result);
            $("#missed-swipe .missed-swipe-ul").html(final_data);
        }
    });
    $.ajax({
        type: 'GET',
        url: baseURL + "index.php?r=extension/extension/get-data&id=3",
        async: false,
        success: function (result) {
            $("#incoming-swipe .incoming-swipe-ul").html('');
            let final_data = $.parseJSON(result);
            $("#incoming-swipe .incoming-swipe-ul").html(final_data);
        }
    });
    $.ajax({
        type: 'GET',
        url: baseURL + "index.php?r=extension/extension/get-data&id=4",
        async: false,
        success: function (result) {
            $("#outgoing-swipe .outgoing-swipe-ul").html('');
            let final_data = $.parseJSON(result);
            $("#outgoing-swipe .outgoing-swipe-ul").html(final_data);
        }
    });
}

document.getElementById('output').addEventListener('input', function (e) {
    // Define the valid characters
    const validChars = /^[0-9+*#]*$/;

    // If the input value does not match the valid characters, remove the invalid characters
    if (!validChars.test(e.target.value)) {
        e.target.value = e.target.value.replace(/[^0-9+*#]/g, '');
    }else{
        count++;
    }
    if (count < 18) {
        count = $('#output').val().length;
        if(count == 0){
            disableDialpadIcons('call');
        }else{
            enableDialpadIcons('call');
            return true;
        }
    }else{
        $('#clear').trigger('click');
        return false;
    }
});

function getSpeedDailData(){
    $.ajax({
        type: 'GET',
        url: baseURL + "index.php?r=extension/extension/get-speed-dial",
        async: false,
        success: function (result) {
            if(result){
                speedDial = $.parseJSON(result);
                console.log(speedDial);
            }
        }
    });
}

let isLongPress = false;
let keyPressTimer;
$("#zeroButton").on('mousedown', function (e) {
    console.warn('0 pressed');
    isLongPress = false;
    keyPressTimer = setTimeout(() => {
        isLongPress = true;
        $("#output").val($('#output').val() + '+');
        $("#transfer-call-number").val($('#transfer-call-number').val() + '+');
        $("#output").keyup();
        enableDialpadIcons('call');
        enableDialpadIcons('call-forward');
        count++;
    }, 1000);
});

$("#zeroButton").on('mouseup', function (e) {
    clearTimeout(keyPressTimer);
    if (!isLongPress && count < 17) {
        $("#output").val($('#output').val() + '0');
        $("#transfer-call-number").val($('#transfer-call-number').val() + '0');
        $("#output").keyup();
        enableDialpadIcons('call');
        enableDialpadIcons('call-forward');
        count++;
    }
});

$("#zeroButton_transfer").on('mousedown', function (e) {
    console.warn('0 pressed');
    isLongPress = false;
    keyPressTimer = setTimeout(() => {
        isLongPress = true;
        $("#output").val($('#output').val() + '+');
        $("#transfer-call-number").val($('#transfer-call-number').val() + '+');
        $("#output").keyup();
        enableDialpadIcons('call');
        enableDialpadIcons('call-forward');
        count++;
    }, 1000);
});

$("#zeroButton_transfer").on('mouseup', function (e) {
    clearTimeout(keyPressTimer);
    if (!isLongPress && count < 17) {
        $("#output").val($('#output').val() + '0');
        $("#transfer-call-number").val($('#transfer-call-number').val() + '0');
        $("#output").keyup();
        enableDialpadIcons('call');
        enableDialpadIcons('call-forward');
        count++;
    }
});
