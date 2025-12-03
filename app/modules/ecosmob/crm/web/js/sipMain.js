const customSIPModule = (function () {

    let registerVar;
    // ... other variables and functions ...
    function startUserAgent(config) {
        return new Promise(async (resolve, reject) => {
            try {
                const UserAgent = new SIP.UserAgent(config);
                await UserAgent.start();
                resolve(UserAgent); // Resolve with the UserAgent if it starts successfully
            } catch (error) {
                reject(error); // Reject with the error if there's a problem
            }
        });
    }
    function registerUA(UserAgent, registrationCallback) {
        return new Promise(async (resolve, reject) => {
            try {
                const registererOptions = {
                    // ... Registerer options here ...
                };

                const registerer = new SIP.Registerer(UserAgent, registererOptions);
                registerVar = registerer;
                // Add a listener for registration state changes and invoke the callback
                registerer.stateChange.addListener((newState) => {
                    console.log("UA Registration State ==> ", newState);

                    if (typeof registrationCallback === 'function') {
                        registrationCallback(newState);
                    }

                });

                // Send registration request
                registerer.register()
                    .then(() => {
                        console.log("Registered successfully");
                        resolve(UserAgent); // Resolve with the UserAgent if it starts successfully
                    })
                    .catch((error) => {
                        console.log("Registration Fail");
                        reject(error); // Reject with the error if there's a problem
                    });
            } catch (error) {
                reject(error); // Reject with the error if there's a problem during setup
            }
        });
    }
    function muteCall(session){
        console.log(`Muting the call`);

        if (session && session.sessionDescriptionHandler) {
            const peerConnection = session.sessionDescriptionHandler.peerConnection;

            peerConnection.getSenders().forEach((sender) => {
                if (sender.track && sender.track.kind === 'audio') {
                    sender.track.enabled = false;
                    console.log("Audio track muted");
                }
            });
        } else {
            console.warn("Session or sessionDescriptionHandler is not defined.");
        }
    }
    function unmuteCall(session){
        console.log(`UnMuting the call`);

        if (session && session.sessionDescriptionHandler) {
            const peerConnection = session.sessionDescriptionHandler.peerConnection;

            peerConnection.getSenders().forEach((sender) => {
                if (sender.track && sender.track.kind === 'audio') {
                    sender.track.enabled = true;
                    console.log("Audio track unmuted");
                }
            });
        } else {
            console.warn("Session or sessionDescriptionHandler is not defined.");
        }
    }
    function initiateCall(UserAgent, params,outgoingSessionCallback,outgoingDelegateCallback){
        return new Promise(async (resolve, reject) => {
            try {
                let inviterOptions;
                if (!inviterOptions) {
                    inviterOptions = {};
                }
                if (!inviterOptions.sessionDescriptionHandlerOptions) {
                    inviterOptions.sessionDescriptionHandlerOptions = {};
                }
                if (!inviterOptions.sessionDescriptionHandlerOptions.constraints) {
                    inviterOptions.sessionDescriptionHandlerOptions.constraints = params.constraints;
                }
                inviterOptions.extraHeaders = params.extraHeaders;

                const target = params.target;
                let inviter = new SIP.Inviter(UserAgent, target, inviterOptions);
                inviter.stateChange.addListener((newState) => {
                    ringTonePlay(newState,params.ringParams);
                    outgoingSessionCallback(newState);
                });
                inviterOptions.requestDelegate = {
                    onTrying: (tryingResponse) => {
                        console.info("Trying");
                        outgoingDelegateCallback('trying', tryingResponse);
                    },
                    onProgress: (progressResponse) => {
                        console.info("Invitation On Progress");
                        outgoingDelegateCallback('ringing', progressResponse);
                    },
                    onAccept: (acceptResponse) => {
                        console.info("Invitation accepted");
                        outgoingDelegateCallback('accepted', acceptResponse);
                    },
                    onReject: (rejectResponse) => {
                        console.info("Invitation rejected");
                        outgoingDelegateCallback('rejected', rejectResponse);
                    },
                    onRedirect: (redirectResponse) => {
                        onsole.info("Invitation redirected");
                        outgoingDelegateCallback('redirected', redirectResponse);
                    },
                }
                inviter.invite(inviterOptions).then(() => {
                    console.log("Successfully sent INVITE ....");
                    resolve(inviter);
                }).catch((error) => {
                    console.log("Failed to send INVITE ==> ", error);
                    reject(error);
                });


            } catch (error) {
                console.log("error occured:",error);
                reject(error);
            }
        })
    }
    function ringTonePlay(state,ringParmas){
        console.log("ringTonePlay  states are - State:", state);
        if(state == 'Establishing'){
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
        }
        else{
            ringtone.pause();
        }
    }
    function handleIncomingCall(invitation, incomingSessionCallback,incomingDelegateCallback){
        return new Promise(async (resolve, reject) => {
            try {
                invitation.stateChange.addListener((newState) => {
                    incomingSessionCallback(newState);
                });
                resolve(invitation);

            } catch (error) {
                console.log("error occured:", error);
                reject(error);
            }
        })
    }

    function holdCall(currentCall) {
        if (currentCall) {
            currentCall.sessionDescriptionHandlerOptionsReInvite = {
                hold: true,
            };

            // Mute the audio
            if (currentCall.sessionDescriptionHandler.peerConnection) {
                currentCall.sessionDescriptionHandler.peerConnection.getSenders().forEach((sender) => {
                    if (sender.track) {
                        if (sender.track.kind === 'audio') {
                            sender.track.enabled = false; // Mute audio
                        }
                    }
                });
            }

            // Send the re-INVITE request to put the call on hold
            const options = {
                requestDelegate: {
                    onAccept: () => {
                        console.log('Call is on hold.');
                    },
                    onReject: () => {
                        console.log('Hold request rejected.');
                    },
                },
            };

            // Send the re-INVITE
            currentCall.invite(options).catch((error) => {
                console.log('Hold Error: ' + error);
            });
        }
    }
    function unregisterUA(){
        registerVar.unregister();
    }
    function unholdCall(currentCall) {
        if (currentCall) {
            currentCall.sessionDescriptionHandlerOptionsReInvite = {
                hold: false,
            };

            // Mute the audio
            if (currentCall.sessionDescriptionHandler.peerConnection) {
                currentCall.sessionDescriptionHandler.peerConnection.getSenders().forEach((sender) => {
                    if (sender.track) {
                        if (sender.track.kind === 'audio') {
                            sender.track.enabled = true; // UnMute audio
                        }
                    }
                });
            }

            // Send the re-INVITE request to put the call on hold
            const options = {
                requestDelegate: {
                    onAccept: () => {
                        console.log('Call is on unhold.');
                    },
                    onReject: () => {
                        console.log('UnHold request rejected.');
                    },
                },
            };

            // Send the re-INVITE
            currentCall.invite(options).catch((error) => {
                console.log('UnHold Error: ' + error);
            });
        }
    }

    // Export the functions and variables you want to make available
    return {
        init: function () {
            // Initialize your code here
            // Add event listeners, set up user agents, etc.
        },
        // Define other functions that can be called from the outside
        connectWs: startUserAgent,
        registerUA: registerUA,
        unregisterUA: unregisterUA,
        initiateCall: initiateCall,
        muteCall: muteCall,
        unmuteCall: unmuteCall,
        holdCall: holdCall,
        unholdCall: unholdCall,
        handleIncomingCall: handleIncomingCall


    };
})();

// Initialize the module when the document is ready
document.addEventListener("DOMContentLoaded", function () {
    customSIPModule.init();
});
