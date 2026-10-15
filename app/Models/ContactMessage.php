<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'company', 'is_read'];

    protected function casts(): array
    {
        return ['is_read' => 'boolean'];
    }

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}
