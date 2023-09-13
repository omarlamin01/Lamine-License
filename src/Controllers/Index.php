<?php

namespace Lamine\License\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Lamine\License\Models\ProductKey;
use Lamine\License\Tools\Hardware;

class Index
{
    static function store(Request $request)
    {
        $license_key = $request->input('key');
        $secret_key = $request->input('secret');
        $license_type = $request->input('type');

        $access_point_url = env('COMPANY_URL');

        $mac = Hardware::mac();

        $cpuId = Hardware::cpu();

        $motherboardId = Hardware::motherboard();

        if ($secret_key) {
            $response = Http::post($access_point_url . 'generate-license', [
                'secret' => $secret_key,
                'software' => env('APP_NAME'),
                'type' => $license_type,
                'mac' => $mac,
                'cpu' => $cpuId,
                'motherboard' => $motherboardId
            ]);

            $response_body = json_decode($response->getBody());

            if ($response_body->status == 'success') {
                if (ProductKey::first()) {
                    ProductKey::first()->delete();
                }
                $license = ProductKey::create([
                    'license_key' => $response_body->product_key,
                    'license_type' => $response_body->license_type,
                    'expires_at' => $response_body->expires_at,
                    'mac' => $mac,
                    'cpu' => $cpuId,
                    'motherboard' => $motherboardId
                ]);

                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'License activated successfully',
                    ], 200);
                } else {
                    return redirect()->route('login');
                }
            } else {
                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'Invalid secret key'
                    ], 401);
                } else {
                    return redirect()->back()->withErrors(['product_key' => 'Product key is invalid.']);
                }
            }
        } elseif ($license_key) {
            $response = Http::post($access_point_url . 'validate-license', [
                'product_key' => $license_key,
                'software' => env('APP_NAME'),
                'mac' => $mac,
                'cpu' => $cpuId,
                'motherboard' => $motherboardId
            ]);

            $response_body = json_decode($response->getBody());

            if ($response_body->status == 'success') {
                if (ProductKey::first()) {
                    ProductKey::first()->delete();
                }
                $license = ProductKey::create([
                    'license_key' => $license_key,
                    'license_type' => $response_body->license_type,
                    'expires_at' => $response_body->expires_at,
                    'mac' => $mac,
                    'cpu' => $cpuId,
                    'motherboard' => $motherboardId
                ]);

                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'License activated successfully',
                    ], 200);
                } else {
                    return redirect()->route('login');
                }
            } else {
                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'Invalid license key'
                    ], 401);
                } else {
                    return redirect()->back()->withErrors(['product_key' => 'Product key is invalid.']);
                }
            }
        } else {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Invalid request'
                ], 400);
            } else {
                return redirect()->back()->withErrors(['product_key' => 'Invalid request.']);
            }
        }
    }

    static function webStore(Request $request)
    {
        $license_key = $request->input('key');
        $secret_key = $request->input('secret');
        $license_type = $request->input('type');

        $access_point_url = env('COMPANY_URL');

        $mac = Hardware::mac();

        $cpuId = Hardware::cpu();

        $motherboardId = Hardware::motherboard();

        if ($secret_key) {
            $response = Http::post($access_point_url . 'generate-license', [
                'secret' => $secret_key,
                'software' => env('APP_NAME'),
                'type' => $license_type,
                'mac' => $mac,
                'cpu' => $cpuId,
                'motherboard' => $motherboardId
            ]);

            $response_body = json_decode($response->getBody());

            if ($response_body->status == 'success') {
                if (ProductKey::first()) {
                    ProductKey::first()->delete();
                }
                $license = ProductKey::create([
                    'license_key' => $response_body->product_key,
                    'license_type' => $response_body->license_type,
                    'expires_at' => $response_body->expires_at,
                    'mac' => $mac,
                    'cpu' => $cpuId,
                    'motherboard' => $motherboardId
                ]);
                return redirect()->route('login');
            } else {
                return redirect()->back()->withErrors(['product_key' => 'Product key is invalid.']);
            }
        } elseif ($license_key) {
            $response = Http::post($access_point_url . 'validate-license', [
                'product_key' => $license_key,
                'software' => env('APP_NAME'),
                'mac' => $mac,
                'cpu' => $cpuId,
                'motherboard' => $motherboardId
            ]);

            $response_body = json_decode($response->getBody());

            if ($response_body->status == 'success') {
                if (ProductKey::first()) {
                    ProductKey::first()->delete();
                }
                $license = ProductKey::create([
                    'license_key' => $license_key,
                    'license_type' => $response_body->license_type,
                    'expires_at' => $response_body->expires_at,
                    'mac' => $mac,
                    'cpu' => $cpuId,
                    'motherboard' => $motherboardId
                ]);
                return redirect()->route('login');
            } else {
                return redirect()->back()->withErrors(['product_key' => 'Product key is invalid.']);
            }
        } else {
            return redirect()->back()->withErrors(['product_key' => 'Invalid request.']);
        }
    }


    static function checkLicense()
    {
        $access_point_url = env('COMPANY_URL');
        $license = ProductKey::first();

        if ($license) {
            // call http request to check license
            $response = Http::post($access_point_url . 'check-license', [
                'license_key' => $license->license_key,
                'mac' => $license->mac,
                'cpu' => $license->cpu,
                'motherboard' => $license->motherboard
            ]);

            if ($response->status() == 200) {
                $response_body = json_decode($response->getBody());
                if ($response_body->status == 'success') {
                    $license->update([
                        'license_key' => $response_body->license_key,
                        'license_type' => $response_body->license_type,
                        'expires_at' => date('Y-m-d H:i:s', time() + (strtotime($response_body->expires_at) - strtotime($response_body->time))),
                    ]);

                    session(['license_is_valid' => true]);
                } else {
                    session(['license_is_valid' => true]);
                    $license->delete();
                }
            } else if ($response->status() == 401 || $response->status() == 404 || $response->status() == 403) {
                session(['license_is_valid' => false]);
                $license->delete();
            } else {
                Log::info('Problem checking license. (schedule)');
                Log::info('Response status: ' . $response->status());
            }
        }
    }

    static function validateLicense(): bool
    {
        $license = ProductKey::first();

        session(['license_is_valid' => $license && !$license->isExpired()]);

        return $license && !$license->isExpired();
    }
}
