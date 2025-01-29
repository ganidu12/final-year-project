<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class PatientHistory
 *
 * @property int $id
 * @property string $user_id
 * @property string $diagnosis
 * @property string fracture_size
 * @property string healing_time
 * @property string|null $image_url
 * @property string $doctor_id
 * @property string $feedback
 * @property Carbon|null $deleted_at
 */
class PatientHistory extends Model
{
    use HasFactory;

    /**
     * The primary key type.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'diagnosis',
        'image_url',
        'doctor_id',
        'fracture_size',
        'healing_time',
        'feedback',
        'deleted_at'
    ];

    /**
     * Relationship: PatientHistory belongs to a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
