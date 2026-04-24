<?php
$metrics = [
    'vpn_status'            => 0,
    'vpn_check_duration'    => 0.0,
    'vpn_check_error'       => 0,
    'public_ip_check_error' => 0,
    'last_collected'        => time(),
];

$realIP = @file_get_contents("http://ipecho.net/plain");
if ($realIP === false) {
    $metrics['public_ip_check_error'] = 1;
    $realIP = 'Unavailable';
}
$metrics['public_ip'] = trim($realIP);

$start = microtime(true);
$response = @file_get_contents('https://vpnstatus.lewiscrib.com/ip_status.php');
$metrics['vpn_check_duration'] = microtime(true) - $start;

if ($response === false) {
    $metrics['vpn_check_error'] = 1;
    $metrics['vpn_ip']     = 'N/A';
    $metrics['vpn_status_text'] = 'Error';
    $metrics['vpn_last_updated'] = 'N/A';
} else {
    $data = json_decode($response, true);
    $metrics['vpn_status']       = (strtolower($data['status']) === 'connected') ? 1 : 0;
    $metrics['vpn_ip']           = $data['ip'];
    $metrics['vpn_status_text']  = $data['status'];
    $metrics['vpn_last_updated'] = $data['dt'];
}

file_put_contents('/tmp/vpn_metrics.json', json_encode($metrics));