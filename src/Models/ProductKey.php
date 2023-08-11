<?php

namespace Lamine\License\Models;

use Lamine\License\Tools\Hardware;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class ProductKey extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'license';

    protected $fillable = [
        'license_key',
        'license_type',
        'expires_at',
        'mac',
        'cpu',
        'motherboard'
    ];

    public function getExpiresAtAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } else {
            return null;
        }
    }

    public function isExpired() : bool
    {
        $mac = Hardware::mac();

        $cpuId = Hardware::cpu();

        $motherboardId = Hardware::motherboard();

        if ($this->mac != $mac || $this->cpu != $cpuId || $this->motherboard != $motherboardId) {
            Log::error('Invalid license!!');
            Log::warning('License is being used on another computer.');
            return true;
        }

        /************************************
         *
         * 1 => Trial
         * 2 => One year subscription
         * 3 => Permanent
         *
         */

        switch ($this->license_type) {
            case 1:
                if ($this->expires_at < now() || $this->created_at->addDays(30) < now()) {
                    Log::warning('Trial license expired.');
                    return true;
                } else {
                    return false;
                }
                break;
            case 2:
                if ($this->expires_at < now()->format('Y-m-d') || $this->created_at->addYear() < now()) {
                    Log::warning('One year subscription expired.');
                    return true;
                } else {
                    return false;
                }
                break;
            case 3:
                return false;
                break;
            default:
                Log::info('invalid license type');
                return true;
                break;
        }
    }
}
