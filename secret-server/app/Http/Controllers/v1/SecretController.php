<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Events\SecretViewedEvent;
use App\Helpers\ApiHelper;
use App\Secret;

class SecretController extends Controller
{
    /**
     * Get the specified resource from storage.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function getSecretByHash($hash)
    {
        // Query a secret with hash which exists and valid
        $secret = Secret::where('hash', $hash)->valid()->first();

        if($secret)
        {
            event(new SecretViewedEvent($secret));
            return ApiHelper::response($secret);
        }
        else
        {
            return ApiHelper::error('Secret not found', 404);
        }
    }

    /**
     * Store a newly created secret in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSecret(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'secret' => 'required',
            'expireAfterViews' => 'required|numeric|min:1',
            'expireAfter' => 'required|numeric|min:0'
        ]);

        if ($validator->fails())
        {
            return ApiHelper::error('Invalid input', 405);
        }

        $secret = new Secret();
        $secret->secretText = $request->secret;
        $secret->remainingViews = $request->expireAfterViews;
        $secret->expiresAt = Carbon::now()->addMinutes($request->expireAfter);
        $secret->save();

        return ApiHelper::response($secret);
    }
}
