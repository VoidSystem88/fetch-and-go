<!DOCTYPE html>
<html>
<head>
    <title>Test Push Notification</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Request permission
        if ('Notification' in window) {
            Notification.requestPermission();
        }
        
        async function subscribeToPush() {
            if (!('serviceWorker' in navigator)) {
                alert('Service Worker not supported');
                return;
            }
            
            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                console.log('Service Worker registered');
                
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: '{{ env("VAPID_PUBLIC_KEY") }}'
                });
                
                console.log('Subscribed:', subscription);
                
                // Send to server
                const response = await fetch('/api/push-subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(subscription)
                });
                
                const result = await response.json();
                alert('Push notification enabled!');
            } catch (error) {
                console.error('Error:', error);
                alert('Error subscribing to push notifications');
            }
        }
        
        function sendTestNotification() {
            fetch('/api/send-test-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => alert('Test notification sent!'));
        }
        
        // Auto-subscribe on page load
        window.onload = function() {
            if (Notification.permission === 'granted') {
                subscribeToPush();
            }
        }
    </script>
</head>
<body style="padding: 20px; font-family: sans-serif;">
    <h1>🔔 Push Notification Test</h1>
    <button onclick="subscribeToPush()" style="padding: 10px 20px; margin: 10px;">Enable Notifications</button>
    <button onclick="sendTestNotification()" style="padding: 10px 20px; margin: 10px;">Send Test Notification</button>
</body>
</html>