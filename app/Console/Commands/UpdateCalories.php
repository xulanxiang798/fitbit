<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserCalorie;

class UpdateCalories extends Command
{
    // 命令签名
    protected $signature = 'fitbit:update-calories';

    // 命令描述
    protected $description = '更新所有用户最近7天的卡路里数据';

    public function __construct()
    {
        parent::__construct();
    }

    // 命令逻辑
    public function handle()
    {
        // 获取所有用户
        $users = User::all();
        $this->info('Total users found: ' . $users->count());

        foreach ($users as $user) {
            $this->info('Processing user: ' . $user->fitbit_user_id);

            if ($user->access_token) {
                try {
                    // 获取最近 7 天的卡路里数据
                    $startDate = now()->subDays(7)->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');

                    $response = Http::withToken($user->access_token)
                        ->get("https://api.fitbit.com/1/user/-/activities/calories/date/{$startDate}/{$endDate}.json");

                    if ($response->successful()) {
                        $caloriesData = $response->json();

                        if (isset($caloriesData['activities-calories']) && is_array($caloriesData['activities-calories'])) {
                            foreach ($caloriesData['activities-calories'] as $calorie) {
                                UserCalorie::updateOrCreate(
                                    [
                                        'fitbit_user_id' => $user->fitbit_user_id,
                                        'date' => $calorie['dateTime'],
                                    ],
                                    [
                                        'calories' => $calorie['value'],
                                    ]
                                );
                            }

                            Log::info('Successfully updated calorie data for user: ' . $user->fitbit_user_id);
                            $this->info('Successfully updated calorie data for user: ' . $user->fitbit_user_id);
                        } else {
                            Log::warning('No valid calorie data found for user: ' . $user->fitbit_user_id);
                            $this->warn('No valid calorie data found for user: ' . $user->fitbit_user_id);
                        }
                    } else {
                        Log::error('Failed to fetch calorie data for user: ' . $user->fitbit_user_id . ' - ' . $response->body());
                        $this->error('Failed to fetch calorie data for user: ' . $user->fitbit_user_id);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception occurred while fetching calorie data for user: ' . $user->fitbit_user_id . ' - ' . $e->getMessage());
                    $this->error('Exception occurred while fetching calorie data for user: ' . $user->fitbit_user_id);
                }
            } else {
                $this->warn('No access token found for user: ' . $user->fitbit_user_id);
            }
        }

        $this->info('所有用户的最近7天卡路里数据已更新');
        return 0;
    }
}
