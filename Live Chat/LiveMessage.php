<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveMessage extends Model
{
    use HasFactory;
    protected $fillable = ['id','chat_session_id','sender_type','admin_id','message','seen_by_guest','seen_by_admin'];

    public function session() {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }
}
