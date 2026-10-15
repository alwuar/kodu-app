<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealerRequest extends Model
{
    public const STATUSES = [
        'new' => 'Nueva',
        'contacted' => 'Contactado',
        'approved' => 'Aprobado',
        'rejected' => 'Descartado',
    ];

    protected $fillable = ['first_name', 'last_name', 'city', 'phone', 'email', 'company', 'source', 'status', 'notes'];

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
