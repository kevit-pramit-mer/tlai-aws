
const customSIPModule = (function () {


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

                // Add a listener for registration state changes and invoke the callback
                registerer.stateChange.addListener((newState) => {
                    console.log("UA Registration State ==> ", newState);

                    if (typeof registrationCallback === 'function') {
                        registrationCallback(newState);
                    }

                    switch (newState) {
                        case "Initial":
                            console.log("UserAgent ==> Initial");
                            break;
                        case "Registered":
                            console.log("UserAgent ==> Registered");
                            break;
                        case "Unregistered":
                            console.log("UserAgent ==> Unregistered");
                            break;
                        case "Terminating":
                            console.log("UserAgent ==> Terminating");
                            break;
                        case "Terminated":
                            console.log("UserAgent ==> Terminated");
                            break;
                        default:
                            console.log("UserAgent ==> Unidentified");
                            break;
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
                let constraints = {
                    audio: true,
                    video: params.video,
                }
                let inviterOptions;
                if (!inviterOptions) {
                    inviterOptions = {};
                }
                if (!inviterOptions.sessionDescriptionHandlerOptions) {
                    inviterOptions.sessionDescriptionHandlerOptions = {};
                }
                if (!inviterOptions.sessionDescriptionHandlerOptions.constraints) {
                    inviterOptions.sessionDescriptionHandlerOptions.constraints = constraints;
                }
                inviterOptions.extraHeaders = params.extraHeaders;

                const target = params.target;
                let inviter = new SIP.Inviter(UserAgent, target, inviterOptions);
                inviter.stateChange.addListener((newState) => {
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
    function handleIncomingCall(invitation, incomingSessionCallback,incomingDelegateCallback){
        return new Promise(async (resolve, reject) => {
            try {
                invitation.stateChange.addListener((newState) => {
                    incomingSessionCallback(newState);
                });
                resolve(invitation);

            } catch (error) {
                console.log("error occured:",error);
                reject(error);
            }
        })
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
        initiateCall:initiateCall,
        muteCall: muteCall,
        unmuteCall:unmuteCall,
        handleIncomingCall:handleIncomingCall


    };
})();

// Initialize the module when the document is ready
document.addEventListener("DOMContentLoaded", function () {
    customSIPModule.init();
});

