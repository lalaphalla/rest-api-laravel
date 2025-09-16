<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Chat Application</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(90deg, #4a6cf7, #6a82fb);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .chat-container {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            animation: fadeIn 0.3s ease;
        }

        .message-content {
            padding: 12px 16px;
            border-radius: 18px;
            max-width: 80%;
            word-wrap: break-word;
        }

        .message.received .message-content {
            background-color: #e9ecef;
            border-top-left-radius: 4px;
            align-self: flex-start;
        }

        .message.sent .message-content {
            background: linear-gradient(90deg, #4a6cf7, #6a82fb);
            color: white;
            border-top-right-radius: 4px;
            align-self: flex-end;
        }

        .message-info {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 4px;
        }

        .message.received .message-info {
            text-align: left;
        }

        .message.sent .message-info {
            text-align: right;
        }

        .input-area {
            display: flex;
            padding: 15px;
            background-color: white;
            border-top: 1px solid #dee2e6;
        }

        #messageInput {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #ced4da;
            border-radius: 24px;
            outline: none;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        #messageInput:focus {
            border-color: #4a6cf7;
        }

        #sendButton {
            margin-left: 10px;
            padding: 12px 24px;
            background: linear-gradient(90deg, #4a6cf7, #6a82fb);
            color: white;
            border: none;
            border-radius: 24px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s, opacity 0.2s;
        }

        #sendButton:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        #sendButton:active {
            transform: translateY(0);
        }

        .status {
            text-align: center;
            padding: 10px;
            font-size: 0.9rem;
            color: #6c757d;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .connection-status {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .connected {
            background-color: #28a745;
        }

        .disconnected {
            background-color: #dc3545;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scrollbar styling */
        .chat-container::-webkit-scrollbar {
            width: 6px;
        }

        .chat-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .chat-container::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 3px;
        }

        .chat-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Real-Time Chat</h1>
        </div>

        <div class="chat-container" id="messages">
            <!-- Messages will appear here -->
            <div class="message received">
                <div class="message-content">
                    Welcome to the chat! Messages will appear here in real-time.
                </div>
                <div class="message-info">System • Just now</div>
            </div>
        </div>

        <div class="status">
            <span id="connectionStatus" class="connection-status disconnected"></span>
            <span id="statusText">Disconnected</span>
        </div>

        <div class="input-area">
            <input type="text" id="messageInput" placeholder="Type your message...">
            <button id="sendButton" onclick="sendTestMessage()">Send</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messagesContainer = document.getElementById('messages');
            const statusElement = document.getElementById('statusText');
            const connectionStatus = document.getElementById('connectionStatus');
            const messageInput = document.getElementById('messageInput');

            // Simulate Pusher connection and events for demonstration
            // In a real application, you would use the actual Pusher library

            // Simulate connection
            setTimeout(() => {
                statusElement.textContent = 'Connected to Pusher';
                connectionStatus.className = 'connection-status connected';

                // Add a sample received message after connection
                setTimeout(() => {
                    simulateIncomingMessage({
                        user: 'Test User',
                        message: 'This is a test message from the system',
                        timestamp: new Date().toLocaleString()
                    });
                }, 1000);
            }, 2000);

            // Handle Enter key in message input
            messageInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    sendTestMessage();
                }
            });

            // Function to add a message to the chat
            function addMessage(user, message, timestamp, isSent = false) {
                const messageEl = document.createElement('div');
                messageEl.className = `message ${isSent ? 'sent' : 'received'}`;

                messageEl.innerHTML = `
                    <div class="message-content">${message}</div>
                    <div class="message-info">${user} • ${timestamp}</div>
                `;

                messagesContainer.appendChild(messageEl);

                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Function to simulate receiving a message
            window.simulateIncomingMessage = function (data) {
                addMessage(data.user, data.message, data.timestamp);
            };

            // Function to send a message
            window.sendTestMessage = function () {
                const message = messageInput.value.trim();
                if (!message) return;

                const timestamp = new Date().toLocaleString();

                // Add the message to the UI immediately
                addMessage('You', message, timestamp, true);

                // Simulate sending to server (in a real app, this would be a fetch call)
                console.log('Sending message:', message);

                // Clear input
                messageInput.value = '';

                // Simulate a response after a short delay
                setTimeout(() => {
                    simulateIncomingMessage({
                        user: 'Test User',
                        message: 'Thanks for your message!',
                        timestamp: new Date().toLocaleString()
                    });
                }, 1000);
            };

            // Simulate receiving a Pusher event
            window.simulatePusherEvent = function () {
                const events = [
                    {
                        user: 'John Doe',
                        message: 'Hello there! How is everyone doing?',
                        timestamp: new Date().toLocaleString()
                    },
                    {
                        user: 'Jane Smith',
                        message: 'I just finished the project! 🎉',
                        timestamp: new Date().toLocaleString()
                    },
                    {
                        user: 'Alex Johnson',
                        message: 'Has anyone tried the new API?',
                        timestamp: new Date().toLocaleString()
                    }
                ];

                const randomEvent = events[Math.floor(Math.random() * events.length)];
                simulateIncomingMessage(randomEvent);
            };
        });
    </script>
</body>

</html>