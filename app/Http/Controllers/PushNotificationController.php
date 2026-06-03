<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationController extends Controller
{
    public function saveSubscription(Request $request)
    {
        $user = auth()->user();
        $user->update([
            'push_subscription' => json_encode($request->all())
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function sendOrderNotification($orderId, $type)
    {
        $order = Order::find($orderId);
        if (!$order) return false;
        
        $user = $order->customer;
        if (!$user->push_subscription) {
            return false;
        }
        
        $messages = [
            'confirmed' => [
                'title' => 'Order Confirmed ✅',
                'body' => "Order #{$order->id} has been confirmed! Waiting for rider assignment.",
                'icon' => '/favicon.ico'
            ],
            'assigned' => [
                'title' => 'Rider Assigned 🛵',
                'body' => "A rider has been assigned to order #{$order->id}! You can now track your delivery.",
                'icon' => '/favicon.ico'
            ],
            'approaching' => [
                'title' => 'Rider Approaching 📍',
                'body' => "Your rider is almost there! Get ready to receive your package.",
                'icon' => '/favicon.ico'
            ],
            'delivered' => [
                'title' => 'Order Delivered 🎉',
                'body' => "Order #{$order->id} has been delivered! Thank you for using Fetch and Go!",
                'icon' => '/favicon.ico'
            ]
        ];
        
        $this->sendPushNotification($user, $messages[$type]);
        return true;
    }
    
    public function sendTestNotification(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->push_subscription) {
            return response()->json(['error' => 'No subscription found. Please enable notifications first.'], 400);
        }
        
        $this->sendPushNotification($user, [
            'title' => 'Test Notification 🔔',
            'body' => 'This is a test notification from Fetch and Go! You will receive real notifications for your orders.',
            'icon' => '/favicon.ico'
        ]);
        
        return response()->json(['success' => true, 'message' => 'Test notification sent!']);
    }
    
    private function sendPushNotification($user, $message)
    {
        $subscriptionData = json_decode($user->push_subscription, true);
        if (!$subscriptionData) return;
        
        $auth = [
            'VAPID' => [
                'subject' => env('VAPID_SUBJECT', 'mailto:admin@fetchandgo.com'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY')
            ]
        ];
        
        try {
            $webPush = new WebPush($auth);
            
            $subscription = Subscription::create($subscriptionData);
            
            $payload = json_encode([
                'title' => $message['title'],
                'body' => $message['body'],
                'icon' => $message['icon'] ?? '/favicon.ico',
                'badge' => '/favicon.ico',
                'vibrate' => [200, 100, 200],
                'data' => ['url' => '/dashboard']
            ]);
            
            $webPush->queueNotification($subscription, $payload);
            
            foreach ($webPush->flush() as $report) {
                if (!$report->isSuccess()) {
                    \Log::error('Push notification failed: ' . $report->getReason());
                }
            }
        } catch (\Exception $e) {
            \Log::error('Push notification error: ' . $e->getMessage());
        }
    }
}