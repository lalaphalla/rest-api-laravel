{{-- resources/views/chat.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simple Broadcasting Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .chat-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .messages-container {
            height: 400px;
            overflow-y: auto;
            border: 2px solid #e1e5e9;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fafbfc;
        }
        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
        }
        .message-user {
            font-weight: bold;
            color: #1976d2;
        }
        .message-time {
            font-size: 0.8em;
            color: #666;
            margin-left: 10px;
        }
        .input-container {
            display: flex;
            gap: 10px;
        }
        .message-input {
            flex: 1;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .send-button {
            padding: 12px 24px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .send-button:hover {
            background-color: #45a049;
        }
        .send-button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        .status {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .status.connected {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.disconnected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .test-buttons {
            margin-bottom: 20px;
        }
        .test-button {
            margin-right: 10px;
            padding: 8px 16px;
            background-color: #17a2b8;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .test-button:hover {
            background-color: #138496;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h1>🚀 Laravel Broadcasting Chat Demo</h1>
        
        <!-- Connection Status -->
        <div id="connection-status" class="status disconnected">
            📡 Connecting to chat server...
        </div>

        <!-- Test Buttons -->
        <div class="test-buttons">
            <button class="test-button" onclick="sendTestMessage()">Send Test Message</button>
            <button class="test-button" onclick="clearMessages()">Clear Messages</button>
            <button class="test-button" onclick="checkConnection()">Check Connection</button>
        </div>

        <!-- Messages Container -->
        <div id="messages" class="messages-container">
            <div class="message">
                <span class="message-user">System:</span> 
                <span>Welcome to the chat! Waiting for messages...</span>
                <span class="message-time">{{ now()->format('H:i:s') }}</span>
            </div>
        </div>

        <!-- Input Form -->
        <div class="input-container">
            <input type="text" 
                   id="message-input" 
                   class="message-input" 
                   placeholder="Type your message here..." 
                   onkeypress="handleEnterKey(event)">
            <button id="send-button" class="send-button" onclick="sendMessage()">
                Send Message
            </button>
        </div>

        <!-- Debug Information -->
        <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; font-size: 12px;">
            <strong>Debug Info:</strong>
            <div id="debug-info">Loading...</div>
        </div>
    </div>

    <script>
        // Global variables
        let isConnected = false;
        let messagesContainer, messageInput, sendButton, connectionStatus, debugInfo;

        document.addEventListener('DOMContentLoaded', function () {
            console.log('🚀 Chat page loaded');
            
            // Get DOM elements
            messagesContainer = document.getElementById('messages');
            messageInput = document.getElementById('message-input');
            sendButton = document.getElementById('send-button');
            connectionStatus = document.getElementById('connection-status');
            debugInfo = document.getElementById('debug-info');

            // Initialize Echo
            initializeEcho();
            
            // Focus on input
            messageInput.focus();
        });

        function initializeEcho() {
            console.log('🔧 Initializing Echo...');
            
            // Check if Echo is available
            if (typeof Echo === 'undefined') {
                updateConnectionStatus('❌ Echo not available', false);
                updateDebugInfo('Echo is not defined. Check if Laravel Echo is properly loaded.');
                return;
            }

            console.log('✅ Echo is available:', Echo);

            // Enable Pusher debug logging
            if (window.Pusher) {
                Pusher.logToConsole = true;
            }

            // Set up connection event listeners
            if (Echo.connector && Echo.connector.pusher) {
                const connection = Echo.connector.pusher.connection;
                
                connection.bind('connected', function() {
                    console.log('🟢 Connected to Pusher');
                    updateConnectionStatus('🟢 Connected to chat server', true);
                    updateDebugInfo('Connected to Pusher successfully');
                });

                connection.bind('disconnected', function() {
                    console.log('🔴 Disconnected from Pusher');
                    updateConnectionStatus('🔴 Disconnected from chat server', false);
                    updateDebugInfo('Disconnected from Pusher');
                });

                connection.bind('error', function(error) {
                    console.error('❌ Pusher error:', error);
                    updateConnectionStatus('❌ Connection error', false);
                    updateDebugInfo('Pusher error: ' + JSON.stringify(error));
                });

                connection.bind('state_change', function(states) {
                    console.log('🔄 Connection state:', states.current);
                    updateDebugInfo('Connection state: ' + states.current);
                });
            }

            // Subscribe to chat channel
            console.log('📡 Subscribing to chat channel...');
            const channel = Echo.channel('chat');

            // Channel subscription events
            channel.subscribed(() => {
                console.log('✅ Successfully subscribed to chat channel');
                addMessage('System', 'Successfully joined chat channel!', new Date());
            });

            channel.error((error) => {
                console.error('❌ Chat channel error:', error);
                addMessage('System', 'Error joining chat channel: ' + JSON.stringify(error), new Date());
            });

            // Listen for MessageSent events
            channel.listen('MessageSent', (e) => {
                console.log('✅ Received MessageSent event:', e);
                
                // Add message to chat
                addMessage(e.user, e.message, new Date(e.timestamp));
                
                // Update debug info
                updateDebugInfo('Last message received: ' + new Date().toLocaleTimeString());
            });

            // Listen for any notifications (debugging)
            channel.notification((notification) => {
                console.log('📢 Channel notification:', notification);
            });
        }

        function addMessage(user, message, timestamp) {
            const messageEl = document.createElement('div');
            messageEl.className = 'message';
            
            const timeStr = timestamp.toLocaleTimeString();
            
            messageEl.innerHTML = `
                <span class="message-user">${escapeHtml(user)}:</span>
                <span>${escapeHtml(message)}</span>
                <span class="message-time">${timeStr}</span>
            `;
            
            messagesContainer.appendChild(messageEl);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;

            // Disable send button
            sendButton.disabled = true;
            sendButton.textContent = 'Sending...';

            // Clear input
            messageInput.value = '';

            // Send via API
            fetch('/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message,
                    user: 'User-' + Math.floor(Math.random() * 1000) // Random user for demo
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('📤 Message sent:', data);
                updateDebugInfo('Message sent successfully: ' + new Date().toLocaleTimeString());
            })
            .catch(error => {
                console.error('❌ Error sending message:', error);
                addMessage('System', 'Error sending message: ' + error.message, new Date());
            })
            .finally(() => {
                // Re-enable send button
                sendButton.disabled = false;
                sendButton.textContent = 'Send Message';
                messageInput.focus();
            });
        }

        function sendTestMessage() {
            fetch('/test-broadcast')
                .then(response => response.json())
                .then(data => {
                    console.log('📤 Test message sent:', data);
                    addMessage('System', 'Test message triggered from server', new Date());
                })
                .catch(error => {
                    console.error('❌ Test message error:', error);
                    addMessage('System', 'Error sending test message: ' + error.message, new Date());
                });
        }

        function clearMessages() {
            messagesContainer.innerHTML = `
                <div class="message">
                    <span class="message-user">System:</span> 
                    <span>Messages cleared. Ready for new messages...</span>
                    <span class="message-time">${new Date().toLocaleTimeString()}</span>
                </div>
            `;
        }

        function checkConnection() {
            if (Echo && Echo.connector && Echo.connector.pusher) {
                const state = Echo.connector.pusher.connection.state;
                addMessage('System', `Connection state: ${state}`, new Date());
                updateDebugInfo('Manual connection check: ' + state);
            } else {
                addMessage('System', 'Echo or Pusher not available', new Date());
            }
        }

        function handleEnterKey(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }

        function updateConnectionStatus(message, connected) {
            isConnected = connected;
            connectionStatus.textContent = message;
            connectionStatus.className = 'status ' + (connected ? 'connected' : 'disconnected');
        }

        function updateDebugInfo(info) {
            const timestamp = new Date().toLocaleTimeString();
            debugInfo.textContent = `[${timestamp}] ${info}`;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Initialize debug info
        setTimeout(() => {
            updateDebugInfo('Page loaded, Echo initialization attempted');
        }, 100);
    </script>
</body>
</html>