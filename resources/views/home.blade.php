<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Broadcast Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <h1>Home</h1>

    <div id="messages"></div>
    <button onclick="testBroadcast()">Send Test Message</button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Page loaded, checking Echo...');

            // Enable Pusher debug logging
            if (window.Pusher) {
                Pusher.logToConsole = true;
                console.log('✅ Pusher debug logging enabled');
            }

            if (typeof Echo !== 'undefined') {
                console.log('✅ Echo is available:', Echo);

                // Check connection immediately
                if (Echo.connector && Echo.connector.pusher) {
                    console.log('Current connection state:', Echo.connector.pusher.connection.state);

                    // More detailed connection events
                    Echo.connector.pusher.connection.bind('connected', function () {
                        console.log('🟢 Successfully connected to Pusher');
                    });

                    Echo.connector.pusher.connection.bind('disconnected', function () {
                        console.log('🔴 Disconnected from Pusher');
                    });

                    Echo.connector.pusher.connection.bind('error', function (error) {
                        console.error('🔴 Pusher connection error:', error);
                    });
                }

                // Listen for messages with more debugging
                const channel = Echo.channel('chat');

                console.log('📡 Attempting to subscribe to chat channel...');

                // Listen for subscription success
                channel.subscribed(() => {
                    console.log('✅ Successfully subscribed to chat channel');
                });

                // Listen for subscription errors  
                channel.error((error) => {
                    console.error('❌ Chat channel subscription error:', error);
                });

                // Listen for the specific event
                channel.listen('MessageSent', (e) => {
                    console.log('✅ Received MessageSent event:', e);

                    // Display message on page
                    const messagesDiv = document.getElementById('messages');
                    if (messagesDiv) {
                        const messageEl = document.createElement('div');
                        messageEl.innerHTML = `<strong>${e.user}:</strong> ${e.message} <em>(${e.timestamp})</em>`;
                        messagesDiv.appendChild(messageEl);
                    }
                });

                // Listen for ANY event on the channel (debugging)
                channel.notification((notification) => {
                    console.log('📢 Any notification on chat channel:', notification);
                });

            } else {
                console.error('❌ Echo is not defined');
            }
        });

        function testBroadcast() {
            console.log('🚀 Sending test broadcast...');
            fetch('/test-broadcast')
                .then(response => response.json())
                .then(data => {
                    console.log('📤 Test broadcast response:', data);
                })
                .catch(error => {
                    console.error('❌ Error sending test:', error);
                });
                
        }
    </script>
</body>

</html>