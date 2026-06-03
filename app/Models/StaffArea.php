<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffArea extends Model
{
    use HasFactory;

    protected $table = 'staff_areas';

    protected $fillable = [
        'staff_id',
        'area_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}