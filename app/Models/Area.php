<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_area_id',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Area::class, 'parent_area_id');
    }

    public function children()
    {
        return $this->hasMany(Area::class, 'parent_area_id');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'staff_areas', 'area_id', 'staff_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryStaff()
    {
        return $this->belongsToMany(User::class, 'staff_areas', 'area_id', 'staff_id')
                    ->wherePivot('is_primary', true);
    }

    // Helper methods
    public function getFullPath()
    {
        if ($this->parent) {
            return $this->parent->getFullPath() . ' > ' . $this->name;
        }
        
        return $this->name;
    }
}