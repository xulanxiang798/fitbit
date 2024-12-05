<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshFitbitUserProfile extends Command
{
    protected $signature = 'fitbit:refresh-user-profile';
    protected $description = 'Refresh Fitbit user profile information from Fitbit API';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::whereNotNull('fitbit_user_id')->get();

        foreach ($users as $user) {
            $response = Http::withToken($user->access_token)
                ->get('https://api.fitbit.com/1/user/' . $user->fitbit_user_id . '/profile.json');

            if ($response->successful()) {
                $profile = $response->json();

                $user->update([
                    'name' => $profile['user']['fullName'] ?? null,
                    'age' => $profile['user']['age'] ?? null,
                    'gender' => $profile['user']['gender'] ?? null,
                    'height' => $profile['user']['height'] ?? null,
                    'weight' => $profile['user']['weight'] ?? null,
                ]);

                $this->info('Successfully updated profile for user: ' . $user->fitbit_user_id);
                Log::info('Successfully updated profile for user: ' . $user->fitbit_user_id);
            } else {
                $this->error('Failed to refresh profile for user: ' . $user->fitbit_user_id);
                Log::error('Failed to refresh profile for user: ' . $user->fitbit_user_id . ' - ' . $response->body());
            }
        }

        return 0;
    }
}
