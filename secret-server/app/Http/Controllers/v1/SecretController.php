<?php
namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
        // Query a secret with hash which exists and not invalid
        $secret = Secret::where('hash', '=', $hash)
                        ->where('remainingViews', '>', '0')
                        ->where(function($q) {
                                    $q->where('expiresAt', '>', Carbon::now())
                                      ->orWhereRaw('expiresAt = createdAt');
                                })
                        ->first();

        if($secret){
            $secret->remainingViews--;
            $secret->save();

            return ApiHelper::response($secret, 'Secret');
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
            'expiresAfterViews' => 'required|numeric|min:1',
            'expireAfter' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return ApiHelper::error('Invalid input', 405);
        }

        $secret = new Secret();
        $secret->secretText = $request->secret;
        $secret->remainingViews = $request->expiresAfterViews;
        $secret->expiresAt = Carbon::now()->addMinutes($request->expireAfter);
        $secret->save();

        return ApiHelper::response($secret, 'Secret');
    }
}
