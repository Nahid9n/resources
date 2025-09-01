<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;
    protected $fillable = ['id','guest_name','guest_phone','status','last_activity_at'];

    public function messages()
    {
        return $this->hasMany(LiveMessage::class);
    }
}
