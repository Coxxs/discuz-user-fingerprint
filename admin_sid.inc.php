<?php

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

require_once __DIR__ . '/class/table/table_user_fingerprint.php';
require_once __DIR__ . '/function/function_helpers.php';
require_once __DIR__ . '/function/function_admin.php';

user_fingerprint_admin_show_stylesheet();

$table = new table_user_fingerprint;

$page = user_fingerprint_admin_page();
$perpage = user_fingerprint_admin_per_page();
$start = user_fingerprint_admin_query_start($page, $perpage);

showtableheader('Same Fingerprint Count Top');
showsubtitle([
    '#',
    'Fingerprint',
    'Session ID',
    'Count',
    'User ID',
    'Username',
    'IP',
    'User Agent',
    'Hit Count',
    'Created At',
    'Last Online Time',
    'Operation',
]);

$counts = $table->fetch_sid_fingerprint_count_desc($start, $perpage);
$records = $table->fetch_all_by_sid(array_keys($counts));
foreach ($counts as $key => $item) {
    showtablerow('class="user-fingerprint-row-count"', [], dhtmlspecialchars([
        '-',
        '-',
        $key,
        $item['fingerprint_count'],
        '-',
        '-',
        '-',
        '-',
        $item['hit'],
        '-',
        '-',
        '-',
    ]));
    foreach ($records[$key] as $row) {
        showtablerow('', [
            '',
            '',
            'title="' . dhtmlspecialchars($key) . '"',
            'title="' . dhtmlspecialchars($item['fingerprint_count']) . '"',
            '',
            '',
            'title="' . dhtmlspecialchars(convertip(long2ip($row['ip']))) . '"',
            'title="' . dhtmlspecialchars($row['ua']) . '"',
            '',
            'title="' . dhtmlspecialchars(date('Y-m-d H:i:s', $row['created_at'])) . '"',
            'title="' . dhtmlspecialchars(date('Y-m-d H:i:s', $row['last_online_time'])) . '"',
            '',
        ], [
            dhtmlspecialchars($row['id']),
            dhtmlspecialchars($row['fingerprint']),
            dhtmlspecialchars('-'),
            dhtmlspecialchars('-'),
            $row['uid'],
            $row['username'],
            dhtmlspecialchars(long2ip($row['ip'])),
            dhtmlspecialchars(substr($row['ua'], 0, 32) . '...'),
            dhtmlspecialchars($row['hit']),
            dhtmlspecialchars(date('m-d H:i', $row['created_at'])),
            dhtmlspecialchars(date('m-d H:i', $row['created_at'])),
            '<a target="_blank" rel="noopener" href="' . ADMINSCRIPT . '?frames=yes&action=members&operation=search&submit=1&uid=' . $row['uid'] . '">Search</a> ' .
            '<a target="_blank" rel="noopener" href="home.php?mod=space&uid=' . $row['uid'] . '">User page</a>',
        ]);
    }
}
showtablefooter();

$count = $table->fetch_sid_count();
$mpurl = ADMINSCRIPT . '?' . user_fingerprint_admin_query_without_page();
$multipage = multi($count, $perpage, $page, $mpurl);
echo $multipage;
