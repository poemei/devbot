<?php
class devthink
{
    public static $logfile = __DIR__ . '/../dev/dev.log';

    public static function load()
    {
        if (!file_exists(self::$logfile)) return [];
        return json_decode(file_get_contents(self::$logfile), true) ?: [];
    }

    public static function save($data)
    {
        $dir = dirname(self::$logfile);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        file_put_contents(self::$logfile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public static function today()
    {
        $data = self::load();
        $date = date('Y-m-d');
        return $data[$date] ?? [];
    }

    public static function add($task, $done = false)
    {
        $data = self::load();
        $date = date('Y-m-d');

        if (!isset($data[$date])) $data[$date] = [];
        $data[$date][] = ['task' => $task, 'done' => $done];

        self::save($data);
    }

    public static function toggle_done($task)
    {
        $data = self::load();
        $date = date('Y-m-d');

        if (!isset($data[$date])) return false;

        foreach ($data[$date] as &$item) {
            if ($item['task'] === $task) {
                $item['done'] = !$item['done'];
                break;
            }
        }

        self::save($data);
        return true;
    }

    public static function init($tasks = [])
    {
        $data = self::load();
        $date = date('Y-m-d');

        if (!isset($data[$date])) {
            $data[$date] = [];
            foreach ($tasks as $task) {
                $data[$date][] = ['task' => $task, 'done' => false];
            }
            self::save($data);
        }
    }

    public static function digest()
    {
        $log = self::load();
        if (!$log || !is_array($log)) return [];

        $cutoff = strtotime('-6 days'); // includes today
        $done = [];
        $pending = [];
        $alerts = [];

        $icon_done = '<img src="/admin/includes/assets/icons/check.png" alt="Done" width="16" height="16">';
        $icon_pending = '<img src="/admin/includes/assets/icons/gear.png" alt="Pending" width="16" height="16">';

        $today = strtotime(date('Y-m-d'));

        foreach ($log as $date => $entries) {
            $ts = strtotime($date);
            if ($ts < $cutoff) continue;

            foreach ($entries as $entry) {
                $task = htmlspecialchars($entry['task'] ?? '');
                $flag = $entry['done'] ?? false;

                if ($flag) {
                    $done[] = "$icon_done [$date] $task";
                } else {
                    if (strtotime($date) < $today) {
                        $alerts[] = "<strong style='color:red;'>?? [$date] $task</strong>";
                    } else {
                        $pending[] = "$icon_pending [$date] $task";
                    }
                }
            }
        }

        if (!$done && !$pending && !$alerts) return [];

        $output = [];
        $output[] = "<strong>?? DevThink Digest: Weekly Recap</strong>";

        if ($alerts) {
            $output[] = "<br><u>?? Overdue Tasks:</u><br>" . implode("<br>", $alerts);
        }

        if ($pending) {
            $output[] = "<br><u>?? Still in progress:</u><br>" . implode("<br>", $pending);
        }

        if ($done) {
            $output[] = "<br><u>? Completed this week:</u><br>" . implode("<br>", $done);
        }

        return $output;
    }

    public static function email_report($lines = [], $subject = 'DevBot Report')
    {
        if (empty($lines)) return;

        $to = 'you@yourdomain.com';
        $headers = "From: devbot@yourdomain.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $body = "Greetings from DevBot.\n\n";
        $body .= implode("\n• ", array_merge([''], $lines));
        $body .= "\n\n— DevBot";

        @mail($to, $subject, $body, $headers);
    }
}

// ?? Hook into DevBot
if (isset($todays_logs)) {
    if (date('N') === '4') {
        // Thursday digest
        $digest = devthink::digest();
        $todays_logs = array_merge($todays_logs, $digest);
        devthink::email_report(strip_tags_array($digest), '?? Weekly DevThink Digest');
    } else {
        // Daily report
        $tasks = devthink::today();
        if ($tasks) {
            $report = [];
            foreach ($tasks as $item) {
                if (!$item['done']) {
                    $report[] = $item['task'];
                }
            }
            if (!empty($report)) {
                devthink::email_report($report, "??? Daily DevBot Report");
            }
        }
    }
}

// Helper to strip HTML tags before emailing
function strip_tags_array($arr) {
    return array_map('strip_tags', $arr);
}