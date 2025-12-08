<?php

// app/Models/Enrollment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'user_enrollments';

    protected $fillable = [
        'TICKET_NUMBER',
        'BVN',
        'AGT_MGT_INST_NAME',
        'AGT_MGT_INST_CODE',
        'AGENT_NAME',
        'AGENT_CODE',
        'ENROLLER_CODE',
        'LATITUDE',
        'LONGITUDE',
        'FINGER_PRINT_SCANNER',
        'BMS_IMPORT_ID',
        'VALIDATION_STATUS',
        'VALIDATION_MESSAGE',
        'AMOUNT',
        'CAPTURE_DATE',
        'SYNC_DATE',
        'VALIDATION_DATE',
        'AGENT_STATE',
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;   // Laravel won’t auto-increment
    protected $keyType = 'int';

    /**
     * Generate next ID manually
     */
    public static function nextId(): int
    {
        return (self::max('id') ?? 0) + 1;
    }
}
