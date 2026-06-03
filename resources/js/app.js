import './bootstrap';
// Real-time notifications for customer
if (window.Laravel.userRole === 'customer') {
    window.Echo.channel(`orders.${window.Laravel.userId}`)
        .listen('order.status.updated', (e) => {
            // Show notification
            if (Notification.permission === 'granted') {
                new Notification('Order Update', {
                    body: e.message,
                    icon: '/favicon.ico'
                });
            }
            
            // Update order status in UI
            const orderElement = document.querySelector(`#order-${e.order_id}`);
            if (orderElement) {
                orderElement.querySelector('.order-status').innerHTML = e.status;
            }
        });
}

// Real-time notifications for staff
if (window.Laravel.userRole === 'staff' || window.Laravel.userRole === 'admin') {
    window.Echo.channel('staff.orders')
        .listen('new.order', (e) => {
            // Play sound
            const audio = new Audio('/notification.mp3');
            audio.play();
            
            // Show notification
            if (Notification.permission === 'granted') {
                new Notification('New Order!', {
                    body: `New order from ${e.customer}`,
                    icon: '/favicon.ico'
                });
            }
            
            // Reload orders list
            location.reload();
        });
}