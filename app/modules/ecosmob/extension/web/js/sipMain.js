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

                inviterOptions.sessionDescriptionHandlerFactoryOptions = {
                    peerConnectionOptions: {
                        offerToReceiveVideo: 1,
                        mandatory: {
                            OfferToReceiveVideo: true
                        }
                    },
                    modifiers: [
                        (sessionDescription) => {
                            sessionDescription.sdp = preferVP8(sessionDescription.sdp);
                            return sessionDescription;
                        }
                    ]
                };

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

    function preferVP8(sdp) {
        // Match and prioritize VP8 codec
        // return sdp.replace(/m=video.*\r\n/, (match) => {
        //     const vp8Line = match.match(/a=rtpmap:\d+ VP8\/90000.*\r\n/)[0];
        //     return match.replace(vp8Line, '') + vp8Line;
        // });
        return sdp.replace(/m=video.*\r\n([^\r\n]*)\r\n/g, (match, p1) => {
            const vp8Line = p1.match(/a=rtpmap:\d+ VP8\/\d+\r\n/);
            return vp8Line ? match : match.replace(/a=rtpmap:\d+.*\r\n/g, '').replace(/a=rtcp-fb:\d+.*\r\n/g, '');
        });
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
    function ringTonePlayForIncomingCall(state,ringParmas){
        if (state === 'Initial') {
            // Check if the audio is paused or has not started playing
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
        } else {
            ringtone.pause();
        }
    }
    function handleIncomingCall(invitation, ringParams, incomingSessionCallback,incomingDelegateCallback){
        return new Promise(async (resolve, reject) => {
            try {
                ringTonePlayForIncomingCall(invitation.state, ringParams);
                invitation.stateChange.addListener((newState) => {
                    incomingSessionCallback(newState);
                    ringTonePlayForIncomingCall(newState,ringParams);
                });
                invitation.delegate = {
                    onBye: (byeRequest) => {
                        incomingDelegateCallback('bye',byeRequest)
                    },
                    onCancel: (byeRequest) => {
                        incomingDelegateCallback('cancel',byeRequest)
                    },
                };
                invitation.sessionDescriptionHandlerModifiers = [
                    (sessionDescription) => {
                        sessionDescription.sdp = preferVP8(sessionDescription.sdp);
                        return sessionDescription;
                    }
                ];
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
    function unregisterUA(){
        registerVar.unregister();
    }
    function subscribeUA(UserAgent,targetUri){
        const options = {
            expires: 3600, // Subscription duration in seconds
            extraHeaders: ['Accept: application/pidf+xml'] // Headers for the SUBSCRIBE request
        };
        const targetURI = 'sip:8005@ucdemo2.ecosmob.net';

        // Create the subscription
        //const subscription = UserAgent.subscribe(UserAgent, targetURI, 'presence', options);
        var subscription = UserAgent.subscribe(targetURI, 'presence');

        subscription.stateChange.addListener((newState) => {
            console.warn('Subscription state changed:', newState);
            if (newState === SIP.SubscriptionState.Subscribed) {
                console.warn('Subscription is active');
            } else if (newState === SIP.SubscriptionState.Terminated) {
                console.warn('Subscription is terminated');
            }
            switch (newState) {
                case SIP.SubscriptionState.NotifyWait:
                    console.log('Waiting for initial NOTIFY');
                    break;
                case SIP.SubscriptionState.Subscribed:
                    console.log('Subscription is active');
                    break;
                case SIP.SubscriptionState.Terminated:
                    console.log('Subscription is terminated');
                    break;
                default:
                    console.log(`Unknown state: ${newState}`);
            }
        });
        // Handle subscription state changes
        subscription.delegate = {
            onNotify: (notification) => {
                console.log('Received NOTIFY request:', notification);
                // Parse and handle the NOTIFY request content here
            },
            onSubscribe: (subscribed) => {
                console.warn('Subscription state changed:', subscribed.state);
                if (subscribed.state === SIP.SubscriptionState.Subscribed) {
                    console.log('Subscription is active');
                } else if (subscribed.state === SIP.SubscriptionState.Terminated) {
                    console.log('Subscription is terminated');
                }
            }
        };

        // Subscribe
        subscription.subscribe();
        // function retrySubscribe(retries = 3) {
        //     if (retries > 0) {
        //         console.log(`Retrying subscription... attempts remaining: ${retries}`);
        //         subscription.subscribe().catch(error => {
        //             console.error('Subscription failed:', error);
        //             setTimeout(() => retrySubscribe(retries - 1), 1000);
        //         });
        //     } else {
        //         console.error('Max subscription retry attempts reached');
        //     }
        // }
        //
        // // Subscribe with retry logic
        // retrySubscribe();
    }
    function subscribeToPresence(userAgent, targetUri) {

      const targetURI = new SIP.URI("sip", "8005", "ucdemo2.ecosmob.net");
      const eventType = "presence"; // https://www.iana.org/assignments/sip-events/sip-events.xhtml
      const subscriber = new SIP.Subscriber(userAgent, targetURI, eventType);

      // Add delegate to handle event notifications.
      subscriber.delegate = {
          onNotify: (notification) => {
                 // send a response
                 notification.accept();
                 // handle notification here
               }
      };

     // Monitor subscription state changes.
     subscriber.stateChange.addListener((newState) => {
         console.warn('Subscription state changed:', newState);
         if (newState === SIP.SubscriptionState.Subscribed) {
             console.warn('Subscription is active');
         } else if (newState === SIP.SubscriptionState.Terminated) {
             console.warn('Subscription is terminated');
         }
         switch (newState) {
             case SIP.SubscriptionState.NotifyWait:
                 console.log('Waiting for initial NOTIFY');
                 break;
             case SIP.SubscriptionState.Subscribed:
                 console.log('Subscription is active');
                 break;
             case SIP.SubscriptionState.Terminated:
                 console.log('Subscription is terminated');
                 break;
             default:
                 console.log(`Unknown state: ${newState}`);
         }
     });
     // Attempt to establish the subscription
      subscriber.subscribe();
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
        initiateCall: initiateCall,
        muteCall: muteCall,
        unregisterUA: unregisterUA,
        unmuteCall: unmuteCall,
        holdCall: holdCall,
        unholdCall: unholdCall,
        handleIncomingCall: handleIncomingCall,
        subscribeUA: subscribeUA,
        subscribeToPresence: subscribeToPresence
    };
})();

// Initialize the module when the document is ready
document.addEventListener("DOMContentLoaded", function () {
    customSIPModule.init();
});

