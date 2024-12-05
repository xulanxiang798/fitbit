<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserSleep;

class UpdateFitbitSleep extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fitbit:update-sleep';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Fitbit sleep data for all users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 获取所有用户
        $users = User::all();
        $this->info('Total users found: ' . $users->count());

        foreach ($users as $user) {
            $this->info('Processing user: ' . $user->fitbit_user_id);
            if ($user->access_token) {
                try {
                    // 使用 Fitbit API 获取最近7天的睡眠数据
                    $startDate = now()->subDays(7)->format('Y-m-d'); // 改为 7 天前
                    $endDate = now()->format('Y-m-d');

                    $response = Http::withToken($user->access_token)
                        ->get("https://api.fitbit.com/1.2/user/-/sleep/date/{$startDate}/{$endDate}.json");

                    if ($response->successful()) {
                        $sleepData = $response->json();

                        // 遍历睡眠数据并保存到数据库中
                        foreach ($sleepData['sleep'] as $sleep) {
                            $date = $sleep['dateOfSleep'];
                            $durationMinutes = $sleep['duration'] / 60000; // 将毫秒转换为分钟
                            $minutesToFallAsleep = $sleep['minutesToFallAsleep'] ?? null;
                            $startTime = isset($sleep['startTime']) ? date('H:i:s', strtotime($sleep['startTime'])) : null;

                            UserSleep::updateOrCreate(
                                [
                                    'fitbit_user_id' => $user->fitbit_user_id,
                                    'date' => $date,
                                ],
                                [
                                    'total_minutes_asleep' => $sleep['minutesAsleep'] ?? null,
                                    'deep_minutes' => $sleep['levels']['summary']['deep']['minutes'] ?? null,
                                    'light_minutes' => $sleep['levels']['summary']['light']['minutes'] ?? null,
                                    'rem_minutes' => $sleep['levels']['summary']['rem']['minutes'] ?? null,
                                    'wake_minutes' => $sleep['minutesAwake'] ?? null,
                                    'minutes_to_fall_asleep' => $minutesToFallAsleep,
                                    'start_time' => $startTime,
                                    'duration_minutes' => $durationMinutes,
                                ]
                            );
                        }

                        Log::info('Successfully updated sleep data for user: ' . $user->fitbit_user_id);
                        $this->info('Successfully updated sleep data for user: ' . $user->fitbit_user_id);
                    } else {
                        Log::error('Failed to fetch sleep data for user: ' . $user->fitbit_user_id . ' - ' . $response->body());
                        $this->error('Failed to fetch sleep data for user: ' . $user->fitbit_user_id);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception occurred while fetching sleep data for user: ' . $user->fitbit_user_id . ' - ' . $e->getMessage());
                    $this->error('Exception occurred while fetching sleep data for user: ' . $user->fitbit_user_id);
                }
            } else {
                $this->warn('No access token found for user: ' . $user->fitbit_user_id);
            }
        }

        return 0;
    }
}
