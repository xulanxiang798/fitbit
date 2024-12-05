<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\UserStep;

class UpdateFitbitSteps extends Command
{
    protected $signature = 'fitbit:update-steps';
    protected $description = 'Update Fitbit steps data for all users';

    public function handle()
    {
        $users = User::all();
        $this->info('Total users found: ' . $users->count());

        foreach ($users as $user) {
            $this->info('Processing user: ' . $user->fitbit_user_id);

            if (!$user->access_token) {
                $this->warn('No access token found for user: ' . $user->fitbit_user_id);
                continue;
            }

            if ($this->isAccessTokenExpiring($user)) {
                $this->warn('Access token expired for user: ' . $user->fitbit_user_id);
                if (!$this->refreshAccessToken($user)) {
                    $this->error('Failed to refresh access token for user: ' . $user->fitbit_user_id);
                    continue;
                }
            }

            try {
                $cacheKey = 'fitbit_request_steps_' . $user->fitbit_user_id;

               
                $response = Http::withToken($user->access_token)
                ->get('https://api.fitbit.com/1/user/-/activities/steps/date/today/7d.json');
            

                if ($response->successful()) {
                    $this->processStepsData($user, $response->json());
                } else {
                    Log::error('Failed to fetch steps data for user: ' . $user->fitbit_user_id . ' - ' . $response->body());
                    $this->error('Failed to fetch steps data for user: ' . $user->fitbit_user_id);
                }

                Cache::put($cacheKey, true, now()->addMinutes(1)); // Adjust cache duration as needed
            } catch (\Exception $e) {
                Log::error('Exception occurred while fetching steps data for user: ' . $user->fitbit_user_id . ' - ' . $e->getMessage());
                $this->error('Exception occurred while fetching steps data for user: ' . $user->fitbit_user_id);
            }
        }

        return 0;
    }

    private function processStepsData(User $user, array $stepsData)
    {
        if (isset($stepsData['activities-steps']) && is_array($stepsData['activities-steps'])) {
            foreach ($stepsData['activities-steps'] as $step) {
                UserStep::updateOrCreate(
                    [
                        'fitbit_user_id' => $user->fitbit_user_id,
                        'date' => $step['dateTime'],
                    ],
                    [
                        'steps' => $step['value'],
                    ]
                );
            }

            Log::info('Successfully updated steps data for user: ' . $user->fitbit_user_id);
            $this->info('Successfully updated steps data for user: ' . $user->fitbit_user_id);
        } else {
            Log::warning('No valid steps data found for user: ' . $user->fitbit_user_id);
            $this->warn('No valid steps data found for user: ' . $user->fitbit_user_id);
        }
    }

    private function isAccessTokenExpiring($user)
    {
        $expiresAt = $user->token_updated_at ? $user->token_updated_at->addSeconds($user->expires_in) : null;
        return $expiresAt && now()->greaterThanOrEqualTo($expiresAt->subHour());
    }

    private function refreshAccessToken($user)
    {
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
                $user->access_token = $tokens['access_token'];
                $user->refresh_token = $tokens['refresh_token'];
                $user->expires_in = $tokens['expires_in'];
                $user->token_updated_at = now();
                $user->save();

                Log::info('Successfully refreshed token for user: ' . $user->fitbit_user_id);
                return true;
            } else {
                Log::error('Failed to refresh token for user: ' . $user->fitbit_user_id . ' - ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred while refreshing token for user: ' . $user->fitbit_user_id . ' - ' . $e->getMessage());
            return false;
        }
    }
}
