<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'position', 'message', 
        'status', 'admin_notes', 'reviewed_at', 'reviewed_by'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approve($adminId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'admin_notes' => $notes,
            'reviewed_at' => now(),
            'reviewed_by' => $adminId,
        ]);

        // Create user account
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => bcrypt($password),
            'role' => $this->position,
            'is_verified' => true,
        ]);

        if ($this->position === 'rider') {
            Rider::create([
                'user_id' => $user->id,
                'is_available' => true,
                'rating' => 5.0,
                'total_deliveries' => 0,
            ]);
        }

        // Send email with credentials
        // Mail::to($this->email)->send(new ApplicationApproved($user, $password));

        return $user;
    }

    public function reject($adminId, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $notes,
            'reviewed_at' => now(),
            'reviewed_by' => $adminId,
        ]);
    }
}