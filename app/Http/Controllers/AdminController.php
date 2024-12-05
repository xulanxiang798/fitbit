<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with(['steps', 'realtimeHeartRates', 'heartRateZones', 'sleeps', 'calories'])->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function showUserSteps($userId)
    {
        $user = User::with('steps')->findOrFail($userId);
        return view('admin.user_steps', ['user' => $user, 'steps' => $user->steps]);
    }

    public function showUserHeartRates($userId)
    {
        $user = User::with(['realtimeHeartRates', 'heartRateZones'])->findOrFail($userId);

        return view('admin.user_heart_rates', [
            'user' => $user,
            'realtimeHeartRates' => $user->realtimeHeartRates,
            'heartRateZones' => $user->heartRateZones,
        ]);
    }

    public function showUserSleeps($userId)
    {
        $user = User::with('sleeps')->findOrFail($userId);
        return view('admin.user_sleeps', ['user' => $user, 'sleeps' => $user->sleeps]);
    }

    public function showUserCalories($userId)
    {
        $user = User::with('calories')->findOrFail($userId);
        return view('admin.user_calories', ['user' => $user, 'calories' => $user->calories]);
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            Log::info('Successfully deleted user: ' . $user->fitbit_user_id);
            return redirect()->route('admin.index')->with('success', '用户删除成功');
        } catch (ModelNotFoundException $e) {
            Log::error("User not found: {$id}");
            return redirect()->route('admin.index')->with('error', '用户未找到');
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            return redirect()->route('admin.index')->with('error', '用户删除失败');
        }
    }
}
