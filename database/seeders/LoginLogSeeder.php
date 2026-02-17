<?php

namespace Database\Seeders;

use App\Models\LoginLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LoginLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $startDate = Carbon::create(2026, 1, 7);
        $endDate = Carbon::create(2026, 2, 17);

        $browsers = [
            ['browser' => 'Chrome 121', 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36'],
            ['browser' => 'Firefox 122', 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:122.0) Gecko/20100101 Firefox/122.0'],
            ['browser' => 'Safari 17', 'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_3) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Safari/605.1.15'],
            ['browser' => 'Edge 121', 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36 Edg/121.0.0.0'],
            ['browser' => 'Chrome 120 (Android)', 'user_agent' => 'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36'],
        ];

        $platforms = ['Windows 11', 'Windows 10', 'macOS Sonoma', 'Android 14', 'iOS 17'];

        $ips = [
            '83.45.127.12', '85.62.180.45', '90.174.55.88', '81.33.210.67',
            '88.12.45.190', '95.120.33.77', '79.148.200.15', '84.77.163.42',
            '91.205.44.108', '82.159.73.201', '86.42.188.55', '93.176.21.134',
            '80.25.167.89', '87.218.92.36', '94.69.140.73', '192.168.1.100',
            '192.168.1.105', '10.0.0.50', '172.16.0.25', '83.56.78.234',
            '89.130.45.67', '92.54.178.23', '81.47.92.155', '85.19.203.44',
            '90.88.156.31', '94.23.67.189', '83.71.214.98', '88.45.132.76',
            '91.162.88.43', '79.234.51.120', '86.91.177.65', '93.48.205.12',
            '80.157.34.88', '87.63.149.201', '95.81.42.167', '84.29.196.53',
            '82.116.73.140', '89.72.158.94', '90.43.211.37', '81.88.125.62',
            '92.175.66.108', '85.34.189.71', '94.57.103.28', '83.92.147.85',
            '88.63.234.19', '91.28.176.54', '79.85.62.133', '86.174.99.47',
        ];

        // Define login patterns per user
        $userPatterns = [
            // admin@klinea.eu - accede 2-3 veces por semana
            1 => ['min_per_week' => 2, 'max_per_week' => 3, 'hours' => [8, 9, 10, 14, 15], 'preferred_browser' => 0],
            // carlos.garcia - accede diariamente, horario comercial
            2 => ['min_per_week' => 4, 'max_per_week' => 6, 'hours' => [8, 9, 10, 11, 14, 15, 16, 17], 'preferred_browser' => 0],
            // maria.lopez - accede diariamente, muy activa
            3 => ['min_per_week' => 5, 'max_per_week' => 7, 'hours' => [7, 8, 9, 10, 11, 13, 14, 15, 16], 'preferred_browser' => 1],
            // ana.martinez (manager) - accede regularmente
            4 => ['min_per_week' => 4, 'max_per_week' => 5, 'hours' => [8, 9, 10, 11, 15, 16], 'preferred_browser' => 2],
            // pedro.sanchez - accede bastante, a veces desde móvil
            5 => ['min_per_week' => 3, 'max_per_week' => 5, 'hours' => [9, 10, 11, 14, 15, 16, 17, 18], 'preferred_browser' => 3],
            // laura.fernandez - accede regularmente
            6 => ['min_per_week' => 4, 'max_per_week' => 6, 'hours' => [8, 9, 10, 11, 14, 15, 16], 'preferred_browser' => 0],
            // sergio.ruiz - comercial activo
            7 => ['min_per_week' => 4, 'max_per_week' => 6, 'hours' => [8, 9, 10, 11, 14, 15, 16, 17], 'preferred_browser' => 0],
            // elena.torres - muy activa, madruga
            8 => ['min_per_week' => 5, 'max_per_week' => 7, 'hours' => [7, 8, 9, 10, 11, 14, 15], 'preferred_browser' => 1],
            // javier.moreno - horario estándar
            9 => ['min_per_week' => 3, 'max_per_week' => 5, 'hours' => [9, 10, 11, 15, 16, 17], 'preferred_browser' => 0],
            // raquel.diaz (manager) - accede regularmente
            10 => ['min_per_week' => 4, 'max_per_week' => 5, 'hours' => [8, 9, 10, 11, 14, 15, 16], 'preferred_browser' => 2],
            // david.romero - comercial, a veces tarde
            11 => ['min_per_week' => 3, 'max_per_week' => 5, 'hours' => [10, 11, 14, 15, 16, 17, 18], 'preferred_browser' => 3],
            // patricia.navarro - accede bastante
            12 => ['min_per_week' => 4, 'max_per_week' => 6, 'hours' => [8, 9, 10, 11, 14, 15, 16], 'preferred_browser' => 0],
            // alberto.gil - accede desde móvil a menudo
            13 => ['min_per_week' => 3, 'max_per_week' => 5, 'hours' => [8, 9, 10, 14, 15, 16, 17], 'preferred_browser' => 4],
            // marta.jimenez - muy activa
            14 => ['min_per_week' => 5, 'max_per_week' => 7, 'hours' => [7, 8, 9, 10, 11, 13, 14, 15, 16], 'preferred_browser' => 1],
            // roberto.castillo (manager) - acceso regular
            15 => ['min_per_week' => 3, 'max_per_week' => 4, 'hours' => [9, 10, 11, 15, 16], 'preferred_browser' => 0],
            // nuria.ortega - comercial estándar
            16 => ['min_per_week' => 4, 'max_per_week' => 6, 'hours' => [8, 9, 10, 11, 14, 15, 16, 17], 'preferred_browser' => 3],
        ];

        $logs = [];

        foreach ($users as $user) {
            $pattern = $userPatterns[$user->id] ?? $userPatterns[2];
            $currentDate = $startDate->copy();

            // Assign 2-3 IPs per user (simulating office + home + mobile)
            $userIps = array_slice($ips, ($user->id - 1) * 3, 3);
            if (empty($userIps)) {
                $userIps = [$ips[array_rand($ips)]];
            }

            while ($currentDate->lte($endDate)) {
                // Determine how many logins this week
                $weekStart = $currentDate->copy()->startOfWeek();
                $loginsThisWeek = rand($pattern['min_per_week'], $pattern['max_per_week']);

                // Pick random days this week
                $daysThisWeek = range(0, 6); // Mon-Sun
                shuffle($daysThisWeek);
                $selectedDays = array_slice($daysThisWeek, 0, $loginsThisWeek);

                foreach ($selectedDays as $dayOffset) {
                    $loginDay = $weekStart->copy()->addDays($dayOffset);

                    // Skip weekends for most users (70% chance to skip)
                    if ($loginDay->isWeekend() && rand(1, 100) <= 70) {
                        continue;
                    }

                    // Skip if outside date range
                    if ($loginDay->lt($startDate) || $loginDay->gt($endDate)) {
                        continue;
                    }

                    // Pick login hour
                    $hour = $pattern['hours'][array_rand($pattern['hours'])];
                    $minute = rand(0, 59);
                    $loginTime = $loginDay->copy()->setTime($hour, $minute, rand(0, 59));

                    // Session duration: 15 min to 8 hours
                    $sessionMinutes = rand(15, 480);
                    $logoutTime = $loginTime->copy()->addMinutes($sessionMinutes);

                    // Sometimes no logout (session expired)
                    $hasLogout = rand(1, 100) <= 85;

                    // Choose browser - mostly preferred, sometimes random
                    if (rand(1, 100) <= 75) {
                        $browserIdx = $pattern['preferred_browser'];
                    } else {
                        $browserIdx = rand(0, count($browsers) - 1);
                    }
                    $browserInfo = $browsers[$browserIdx];

                    // Choose platform based on browser
                    $platform = match (true) {
                        str_contains($browserInfo['browser'], 'Safari 17') => 'macOS Sonoma',
                        str_contains($browserInfo['browser'], 'Android') => 'Android 14',
                        default => rand(0, 1) ? 'Windows 11' : 'Windows 10',
                    };

                    $logs[] = [
                        'user_id' => $user->id,
                        'ip_address' => $userIps[array_rand($userIps)],
                        'user_agent' => $browserInfo['user_agent'],
                        'browser' => $browserInfo['browser'],
                        'platform' => $platform,
                        'status' => 'success',
                        'logged_in_at' => $loginTime,
                        'logged_out_at' => $hasLogout ? $logoutTime : null,
                    ];

                    // Occasionally add a failed login attempt before successful one (10%)
                    if (rand(1, 100) <= 10) {
                        $failedTime = $loginTime->copy()->subMinutes(rand(1, 5));
                        $logs[] = [
                            'user_id' => $user->id,
                            'ip_address' => $userIps[array_rand($userIps)],
                            'user_agent' => $browserInfo['user_agent'],
                            'browser' => $browserInfo['browser'],
                            'platform' => $platform,
                            'status' => 'failed',
                            'logged_in_at' => $failedTime,
                            'logged_out_at' => null,
                        ];
                    }

                    // Sometimes a second login in the same day (from mobile or different device)
                    if (rand(1, 100) <= 20) {
                        $secondHour = $pattern['hours'][array_rand($pattern['hours'])];
                        if ($secondHour != $hour) {
                            $secondLogin = $loginDay->copy()->setTime($secondHour, rand(0, 59), rand(0, 59));
                            $mobileBrowser = $browsers[4]; // Android
                            $logs[] = [
                                'user_id' => $user->id,
                                'ip_address' => $ips[array_rand($ips)],
                                'user_agent' => $mobileBrowser['user_agent'],
                                'browser' => $mobileBrowser['browser'],
                                'platform' => 'Android 14',
                                'status' => 'success',
                                'logged_in_at' => $secondLogin,
                                'logged_out_at' => $secondLogin->copy()->addMinutes(rand(5, 60)),
                            ];
                        }
                    }
                }

                // Move to next week
                $currentDate = $weekStart->copy()->addWeek();
            }
        }

        // Sort by login time and insert
        usort($logs, fn($a, $b) => $a['logged_in_at'] <=> $b['logged_in_at']);

        foreach ($logs as $log) {
            LoginLog::create($log);
        }
    }
}
