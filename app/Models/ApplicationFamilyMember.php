<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationFamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'family_role',
        'name',
        'gender',
        'birth_date',
        'last_position',
        'last_company'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function getFamilyRoleNameAttribute()
    {
        $roles = [
            'ayah' => 'Ayah',
            'ibu' => 'Ibu',
            'suami/istri' => 'Suami/Istri',
            'anak' => 'Anak',
            'saudara' => 'Saudara'
        ];
        
        return $roles[$this->family_role] ?? $this->family_role;
    }
}