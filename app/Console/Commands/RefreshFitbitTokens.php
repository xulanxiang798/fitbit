<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RefreshFitbitTokens extends Command
{
    protected $signature = 'fitbit:refresh-tokens';
    protected $description = 'Refresh Fitbit tokens for all users';

    public function handle()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->info('No users found.');
            return 0;
        }

        $this->info('Refreshing tokens for ' . $users->count() . ' users.');

        foreach ($users as $user) {
            $this->info("Processing user: {$user->fitbit_user_id}");

            // 检查是否存在 Refresh Token
            if (!$user->refresh_token) {
                $this->warn("No refresh token found for user: {$user->fitbit_user_id}. Skipping.");
                continue;
            }

            // 检查 Token 是否即将过期
            if ($user->token_updated_at && now()->diffInSeconds($user->token_updated_at) < ($user->expires_in - 3600)) {
                $this->info("Token for user: {$user->fitbit_user_id} is still valid. Skipping refresh.");
                continue;
            }

            try {
                $clientId = config('services.fitbit.client_id');
                $clientSecret = config('services.fitbit.client_secret');

                $response = Http::asForm()->withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
                ])->post('https://api.fitbit.com/oauth2/token', [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $user->refresh_token,
                ]);

                if ($response->successful()) {
                    $tokens = $response->json();

                    DB::transaction(function () use ($user, $tokens) {
                        $user->update([
                            'access_token' => $tokens['access_token'],
                            'refresh_token' => $tokens['refresh_token'],
                            'expires_in' => $tokens['expires_in'],
                            'token_updated_at' => now(), // 更新时间戳
                        ]);
                    });

                    Log::info("Tokens refreshed successfully for user: {$user->fitbit_user_id}");
                    $this->info("Tokens refreshed successfully for user: {$user->fitbit_user_id}");
                } else {
                    Log::error("Failed to refresh tokens for user: {$user->fitbit_user_id} - Status: {$response->status()} - Body: {$response->body()}");
                    $this->error("Failed to refresh tokens for user: {$user->fitbit_user_id}. Check logs for details.");
                }
            } catch (\Exception $e) {
                Log::error("Exception occurred while refreshing tokens for user: {$user->fitbit_user_id} - {$e->getMessage()}");
                $this->error("Exception occurred for user: {$user->fitbit_user_id}. Check logs for details.");
            }
        }

        $this->info('Token refresh process completed.');
        return 0;
    }
}
