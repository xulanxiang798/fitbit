<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserStep;
use App\Models\UserCalorie;
use App\Models\UserSleep;
use App\Models\UserHeartRateZone;

class UserComparisonController extends Controller
{
    public function showUserComparison(Request $request)
    {
        $fitbitUserId = session('fitbit_user_id');
        if (!$fitbitUserId) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        // 当前周和上一周的起始日期
        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();
        $previousWeekStart = now()->subWeek()->startOfWeek();
        $previousWeekEnd = now()->subWeek()->endOfWeek();

        // 当前周数据
        $currentWeekSteps = UserStep::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$currentWeekStart, $currentWeekEnd])
            ->sum('steps');

        $currentWeekCalories = UserCalorie::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$currentWeekStart, $currentWeekEnd])
            ->sum('calories');

        $currentWeekSleep = UserSleep::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$currentWeekStart, $currentWeekEnd])
            ->sum('total_minutes_asleep');

        // 上一周数据
        $previousWeekSteps = UserStep::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$previousWeekStart, $previousWeekEnd])
            ->sum('steps');

        $previousWeekCalories = UserCalorie::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$previousWeekStart, $previousWeekEnd])
            ->sum('calories');

        $previousWeekSleep = UserSleep::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$previousWeekStart, $previousWeekEnd])
            ->sum('total_minutes_asleep');

        // 差异与剩余目标
        $stepsNeededToSurpass = max(0, $previousWeekSteps - $currentWeekSteps);
        $caloriesNeededToSurpass = max(0, $previousWeekCalories - $currentWeekCalories);
        $daysRemaining = max(0, 7 - now()->dayOfWeek); // 计算剩余天数

        // 获取步数数据（图表用）
        $stepsData = UserStep::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$currentWeekStart, $currentWeekEnd])
            ->get(['date', 'steps']);

        // 获取睡眠数据（图表用）
        $sleepData = UserSleep::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$currentWeekStart, $currentWeekEnd])
            ->get(['date', 'total_minutes_asleep as duration']);

        // 获取卡路里数据（图表用）
        $caloriesData = UserCalorie::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$currentWeekStart, $currentWeekEnd])
            ->get(['date', 'calories']);

        // 获取心率区间数据（图表用）
        $heartRateData = UserHeartRateZone::where('fitbit_user_id', $fitbitUserId)
            ->whereBetween('date', [$currentWeekStart, $currentWeekEnd])
            ->get(['date', 'out_of_range_minutes as outOfRangeMinutes']);

        // 数据传递到视图
        return view('user.comparison', [
            'userName' => $fitbitUserId, // 使用用户账号作为名字
            'currentWeekSteps' => $currentWeekSteps,
            'previousWeekSteps' => $previousWeekSteps,
            'stepsNeededToSurpass' => $stepsNeededToSurpass,
            'currentWeekCalories' => $currentWeekCalories,
            'previousWeekCalories' => $previousWeekCalories,
            'caloriesNeededToSurpass' => $caloriesNeededToSurpass,
            'currentWeekSleep' => round($currentWeekSleep / 60, 1), // 转换为小时
            'previousWeekSleep' => round($previousWeekSleep / 60, 1), // 转换为小时
            'daysRemaining' => $daysRemaining,
            'stepsData' => $stepsData,
            'sleepData' => $sleepData,
            'caloriesData' => $caloriesData,
            'heartRateData' => $heartRateData,
        ]);
    }
}
