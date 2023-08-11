<?php

namespace Lamine\License\Tools;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lamine\License\Models\ProductKey;

class TimeChecker
{
    static function checkTime() {
        $previous_time = DB::table('timestamps')->orderBy('id', 'desc')->first();
        $current_time = time();

        $license = ProductKey::first();

        if ($previous_time) {
            $previous_time = $previous_time->timestamp;
            if ($current_time < $previous_time) {
                Log::info('Time changed.');
                Log::info('previous time: ' . $previous_time);
                Log::info('current time: ' . $current_time);

                if ($license) {
                    Log::info('Updating license...');
                    Log::debug('expires_at: ' . $license->expires_at);
                    Log::debug('new expiry date: ' . date('Y-m-d H:i:s', $current_time + (strtotime($license->expires_at) - $previous_time)));
                    $license->update([
                        'expires_at' => date('Y-m-d H:i:s', $current_time + (strtotime($license->expires_at) - $previous_time))
                    ]);
                }
            } else {
                Log::info('Time check passed.');
                Log::info('previous time: ' . $previous_time);
                Log::info('current time: ' . $current_time);
            }
        }

        DB::table('timestamps')->insert([
            'timestamp' => $current_time
        ]);
    }

    static function truncateTimestamps() {
        DB::table('timestamps')->truncate();
        DB::table('timestamps')->insert([
            'timestamp' => time()
        ]);
        Log::info('Truncated timestamps table.');
    }
}