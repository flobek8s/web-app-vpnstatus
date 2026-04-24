<?php
// Force Unix line endings regardless of the OS/editor the file was created on
const EOL = "\n";

$raw = @file_get_contents('/tmp/vpn_metrics.json');
$m = $raw ? json_decode($raw, true) : null;

$age = $m ? (time() - $m['last_collected']) : 999;
$stale = ($age > 600) ? 1 : 0;

header('Content-Type: text/plain; version=0.0.4');

echo "# HELP vpn_check_status VPN connection status (1=connected, 0=disconnected)" . EOL;
echo "# TYPE vpn_check_status gauge" . EOL;
echo "vpn_check_status " . ($m ? $m['vpn_status'] : 0) . EOL . EOL;

echo "# HELP vpn_check_duration_seconds Time taken to fetch VPN status from API" . EOL;
echo "# TYPE vpn_check_duration_seconds gauge" . EOL;
echo "vpn_check_duration_seconds " . ($m ? $m['vpn_check_duration'] : 0) . EOL . EOL;

echo "# HELP vpn_check_api_error Whether the last VPN status API call failed (1=error, 0=ok)" . EOL;
echo "# TYPE vpn_check_api_error gauge" . EOL;
echo "vpn_check_api_error " . ($m ? $m['vpn_check_error'] : 1) . EOL . EOL;

echo "# HELP vpn_check_public_ip_error Whether the last public IP lookup failed (1=error, 0=ok)" . EOL;
echo "# TYPE vpn_check_public_ip_error gauge" . EOL;
echo "vpn_check_public_ip_error " . ($m ? $m['public_ip_check_error'] : 1) . EOL . EOL;

echo "# HELP vpn_check_metrics_stale 1 if metrics are older than 10 minutes" . EOL;
echo "# TYPE vpn_check_metrics_stale gauge" . EOL;
echo "vpn_check_metrics_stale $stale" . EOL . EOL;

echo "# HELP vpn_check_metrics_age_seconds How old the current metrics data is" . EOL;
echo "# TYPE vpn_check_metrics_age_seconds gauge" . EOL;
echo "vpn_check_metrics_age_seconds $age" . EOL . EOL;

if ($m && !empty($m['public_ip']) && $m['public_ip'] !== 'Unavailable') {
    echo "# HELP vpn_check_public_ip Current public IP address" . EOL;
    echo "# TYPE vpn_check_public_ip gauge" . EOL;
    echo "vpn_check_public_ip{address=\"" . $m['public_ip'] . "\"} 1" . EOL . EOL;
}

if ($m && !empty($m['vpn_ip']) && $m['vpn_ip'] !== 'N/A') {
    echo "# HELP vpn_check_vpn_ip Current VPN IP address" . EOL;
    echo "# TYPE vpn_check_vpn_ip gauge" . EOL;
    echo "vpn_check_vpn_ip{address=\"" . $m['vpn_ip'] . "\"} 1" . EOL . EOL;
}
?>