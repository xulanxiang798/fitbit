<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserCalorie;
use App\Models\UserSleep;
use Illuminate\Http\Request;



class FitbitController extends Controller
{
    /**
     * 处理 Fitbit 授权回调。
     */
    public function redirectToProvider()
    {
        return Socialite::driver('fitbit')->redirect();
    }
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('fitbit')->user();
            $fitbitProfile = $user->user;

            $fitbitUser = User::updateOrCreate(
                ['fitbit_user_id' => $user->getId()],
                $this->getUserDataFromFitbit($user, $fitbitProfile)
            );

            if ($this->refreshAccessToken($fitbitUser)) {
                $this->fetchAndStoreSleepData($fitbitUser);
                $this->fetchAndStoreCalorieData($fitbitUser); // 添加卡路里数据同步
            }

            session(['user_id' => $fitbitUser->id]);

            Log::info("Fitbit authentication successful for user: {$fitbitUser->fitbit_user_id}");
            return redirect()->route('fitbit.profile');
        } catch (\Exception $e) {
            Log::error("Fitbit authentication failed: {$e->getMessage()}");
            return redirect()->route('fitbit.redirect')->with('error', 'Fitbit 授权失败');
        }
    }

    /**
     * 获取并存储卡路里数据。
     */
    private function refreshAccessToken(User $user)
{
    try {
        $clientId = config('services.fitbit.client_id');
        $clientSecret = config('services.fitbit.client_secret');

        // 请求新的访问令牌
        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
        ])->post('https://api.fitbit.com/oauth2/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->refresh_token,
        ]);

        if ($response->successful()) {
            $tokens = $response->json();

            // 更新用户的 access_token 和 refresh_token
            $user->update([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'expires_in' => $tokens['expires_in'],
                'token_updated_at' => now(),
            ]);

            Log::info("Successfully refreshed token for user: {$user->fitbit_user_id}");
            return true;
        } else {
            Log::error("Failed to refresh token for user: {$user->fitbit_user_id} - {$response->body()}");
            return false;
        }
    } catch (\Exception $e) {
        Log::error("Error refreshing token for user: {$user->fitbit_user_id} - {$e->getMessage()}");
        return false;
    }
}
private function fetchAndStoreSleepData(User $user)
{
    try {
        // 调用 Fitbit API 获取睡眠数据
        $response = Http::withToken($user->access_token)
            ->get('https://api.fitbit.com/1.2/user/-/sleep/date/today.json');

        if ($response->successful()) {
            $sleepData = $response->json();

            if (isset($sleepData['sleep']) && is_array($sleepData['sleep'])) {
                foreach ($sleepData['sleep'] as $sleep) {
                    // 存储睡眠数据到数据库
                    UserSleep::updateOrCreate(
                        [
                            'fitbit_user_id' => $user->fitbit_user_id,
                            'date' => $sleep['dateOfSleep'],
                        ],
                        [
                            'duration' => $sleep['duration'],
                            'efficiency' => $sleep['efficiency'],
                            'start_time' => $sleep['startTime'],
                            'end_time' => $sleep['endTime'],
                        ]
                    );
                }

                Log::info("Successfully updated sleep data for user: {$user->fitbit_user_id}");
            } else {
                Log::warning("No valid sleep data found for user: {$user->fitbit_user_id}");
            }
        } else {
            Log::error("Failed to fetch sleep data for user: {$user->fitbit_user_id} - {$response->body()}");
        }
    } catch (\Exception $e) {
        Log::error("Exception while fetching sleep data for user: {$user->fitbit_user_id} - {$e->getMessage()}");
    }
}

    private function fetchAndStoreCalorieData(User $user)
    {
        try {
            $response = Http::withToken($user->access_token)
                ->get('https://api.fitbit.com/1/user/-/activities/calories/date/today/30d.json');

            if ($response->successful()) {
                $calorieData = $response->json()['activities-calories'];
                foreach ($calorieData as $day) {
                    UserCalorie::updateOrCreate(
                        [
                            'fitbit_user_id' => $user->fitbit_user_id,
                            'date' => $day['dateTime'],
                        ],
                        [
                            'calories' => $day['value'],
                        ]
                    );
                }
                Log::info("Calorie data updated for user: {$user->fitbit_user_id}");
            } else {
                Log::error("Failed to fetch calorie data for user: {$user->fitbit_user_id} - {$response->body()}");
            }
        } catch (\Exception $e) {
            Log::error("Exception while fetching calorie data for user: {$user->fitbit_user_id} - {$e->getMessage()}");
        }
    }

    /**
     * 提取 Fitbit 用户数据。
     */
    private function getUserDataFromFitbit($user, $fitbitProfile)
    {
        return [
            'name' => $fitbitProfile['user']['fullName'],
            'email' => $user->getEmail(),
            'access_token' => $user->token,
            'refresh_token' => $user->refreshToken,
            'userAvatar' => $user->getAvatar(), // 传递 $userAvatar 变量
            'expires_in' => $user->expiresIn,
            'age' => $fitbitProfile['user']['age'] ?? null,
            'gender' => $fitbitProfile['user']['gender'] ?? null,
            'height' => $fitbitProfile['user']['height'] ?? null,
            'weight' => $fitbitProfile['user']['weight'] ?? null,
        ];
    }
    public function profile()
{
    $userId = session('user_id');
    $user = User::findOrFail($userId);

    return view('fitbit.profile', [
        'user' => $user,
        'userId' => $user->fitbit_user_id, // 传递 Fitbit 用户 ID
            'userName' => $user->name,         // 传递用户名称
            'userAvatar' => asset('fitbit_default_avatar.jpg'), // 默认头像路径
        'userAvatar' => asset('fitbit_default_avatar.jpg'),
        'steps' => $user->steps()->where('date', '>=', now()->subDays(7))->get(),
        'sleeps' => $user->sleeps()->where('date', '>=', now()->subDays(7))->get(),
        'calories' => $user->calories()->where('date', '>=', now()->subDays(7))->get(),
        'heartRates' => $user->realtimeHeartRates()->where('timestamp', '>=', now()->subMinutes(5))->get(),
        'heartRateZones' => $user->heartRateZones()->where('date', '>=', now()->subDays(30))->get(),
    ]);
}

}
