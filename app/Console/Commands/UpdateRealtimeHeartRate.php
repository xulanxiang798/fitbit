<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserRealtimeHeartRate;

class UpdateRealtimeHeartRate extends Command
{
    protected $signature = 'fitbit:update-realtime-heart-rate';
    protected $description = 'Update Fitbit realtime heart rate data for all users';

    public function handle()
    {
        $users = User::all();
        $this->info('Total users found: ' . $users->count());

        foreach ($users as $user) {
            $this->info('Processing user: ' . $user->fitbit_user_id);
            if ($user->access_token) {
                try {
                    $response = Http::withToken($user->access_token)
                        ->get('https://api.fitbit.com/1/user/-/activities/heart/date/today/1d/1min.json');

                    if ($response->successful()) {
                        $heartRates = $response->json()['activities-heart-intraday']['dataset'];
                        foreach ($heartRates as $rate) {
                            UserRealtimeHeartRate::updateOrCreate(
                                [
                                    'fitbit_user_id' => $user->fitbit_user_id,
                                    'timestamp' => now()->toDateString() . ' ' . $rate['time'],
                                ],
                                [
                                    'heart_rate' => $rate['value'],
                                ]
                            );
                        }
                        $this->info('Realtime heart rate data updated for user: ' . $user->fitbit_user_id);
                        Log::info('Realtime heart rate data updated for user: ' . $user->fitbit_user_id);
                    } else {
                        Log::error('Failed to fetch realtime heart rate data for user: ' . $user->fitbit_user_id . ' - ' . $response->body());
                    }
                } catch (\Exception $e) {
                    Log::error('Exception fetching realtime heart rate for user: ' . $user->fitbit_user_id . ' - ' . $e->getMessage());
                }
            } else {
                $this->warn('No access token found for user: ' . $user->fitbit_user_id);
            }
        }
        return 0;
    }
}
