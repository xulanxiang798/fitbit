<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserHeartRateZone;

class UpdateHeartRateZones extends Command
{
    protected $signature = 'fitbit:update-heart-rate-zones';
    protected $description = 'Update Fitbit heart rate zone data for all users';

    public function handle()
    {
        $users = User::all();
        $this->info('Total users found: ' . $users->count());

        foreach ($users as $user) {
            $this->info('Processing user: ' . $user->fitbit_user_id);
            if ($user->access_token) {
                try {
                    $response = Http::withToken($user->access_token)
                        ->get('https://api.fitbit.com/1/user/-/activities/heart/date/today/1d.json');

                    if ($response->successful()) {
                        $zones = $response->json()['activities-heart'][0]['value']['heartRateZones'];
                        UserHeartRateZone::updateOrCreate(
                            ['fitbit_user_id' => $user->fitbit_user_id, 'date' => now()->toDateString()],
                            [
                                'out_of_range_minutes' => $zones[0]['minutes'],
                                'fat_burn_minutes' => $zones[1]['minutes'],
                                'cardio_minutes' => $zones[2]['minutes'],
                                'peak_minutes' => $zones[3]['minutes'],
                            ]
                        );
                        $this->info('Heart rate zone data updated for user: ' . $user->fitbit_user_id);
                        Log::info('Heart rate zone data updated for user: ' . $user->fitbit_user_id);
                    } else {
                        Log::error('Failed to fetch heart rate zones for user: ' . $user->fitbit_user_id . ' - ' . $response->body());
                    }
                } catch (\Exception $e) {
                    Log::error('Exception fetching heart rate zones for user: ' . $user->fitbit_user_id . ' - ' . $e->getMessage());
                }
            } else {
                $this->warn('No access token found for user: ' . $user->fitbit_user_id);
            }
        }
        return 0;
    }
}
