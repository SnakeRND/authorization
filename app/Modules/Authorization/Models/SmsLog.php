<?php

namespace App\Modules\Authorization\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string error_num
 * @property string message_id
 * @property string phone
 * @property mixed sent_at
 * @property mixed delivered_at
 * @property string error_code
 * @property string message
 * @property string delivery_status
 */
class SmsLog extends Model
{
    use HasFactory;

    protected $table = 'auth.sms_log';

    protected $fillable = [
        'error_num',
        'message_id',
        'phone',
        'sent_at',
        'delivered_at',
        'error_code',
        'message',
        'delivery_status'
    ];
    protected $guarded = [];
}
