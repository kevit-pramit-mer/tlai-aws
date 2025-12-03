self.addEventListener('message', (e) => {
    console.warn(e.data);
    sendMessage(e.data);
});
self.addEventListener('accept-call', async(event) => {
    const clientList = await event.currentTarget.clients.matchAll({
        includeUncontrolled: true,
        type: 'window',
    });

    for (const client of clientList) {
        console.log(client);
        client?.postMessage({
            action: 'acceptCall',
        });
    }
});
self.addEventListener('reject-call', async(event) => {
    const clientList = await event.currentTarget.clients.matchAll({
        includeUncontrolled: true,
        type: 'window',
    });

    for (const client of clientList) {
        console.log(client);
        client?.postMessage({
            action: 'rejectCall',
        });
    }
});
self.addEventListener('notificationclick', function(event) {
    console.log(event.target.clients);
    var session = (event.notification.data.currentCallId);

     var action = event.action;
    //
    // // Handle notification action click
    switch (action) {
      case 'accept':
        const event1 = new Event('accept-call');
        dispatchEvent(event1);
        break;
      case 'reject':
       const event2 = new Event('reject-call');
       dispatchEvent(event2);
        break;
    }
    //clients.openWindow("http://localhost/uctenant_master/web/index.php?r=extension/extension");
    // Close the notification
    //event.notification.close();


    //event.notification.close();

    // event.notification.close();
    event.waitUntil(event.target.clients.matchAll({
        includeUncontrolled: true,
        type: 'window',
        // eslint-disable-next-line consistent-return
    }).then((clientList) => {
        // eslint-disable-next-line no-restricted-syntax
        for (const client of clientList) {
            const { hostname } = new URL(`${client?.url}`);
            client.focus();
            return;
        }
        // clients.openWindow(`http://localhost/uctenant_master/web/index.php?r=extension/extension`);
    }));
});

  // Example function for accepting calls
// self.addEventListener('push', function(event) {
//     const data = event.data.json();
//     console.warn("niraliData=" + data);
//     if (data.type === 'incomingCall') {
//         event.waitUntil(self.registration.showNotification('Incoming Call', {
//             body: 'You have an incoming call from ' + data.callerName,
//             // Include call data in notification options
//             data: {
//                 type: 'incomingCall',
//                 callData: data.callData
//             }
//         }));
//     }
// });

function acceptCall(){
    // const data = event.data.json();
    // console.warn(data);
     console.warn('call Aceepted');
        const event = new Event('message');
        dispatchEvent(event);
}

self.addEventListener('install', function(event) {
    // Perform installation steps, like caching static assets
    console.warn('Service Worker installed');
});

self.addEventListener('activate', () => {
    console.warn('[Service Worker] Activating Service Worker ...');
    return self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    event.respondWith(fetch(event?.request));
});
