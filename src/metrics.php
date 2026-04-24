<?php
$raw = @file_get_contents('/tmp/vpn_metrics.json');
$m = $raw ? json_decode($raw, true) : null;

$age = $m ? (time() - $m['last_collected']) : 999;
$stale = ($age > 600) ? 1 : 0;

header('Content-Type: text/plain; version=0.0.4');

echo "# HELP vpn_check_status VPN connection status (1=connected, 0=disconnected)\n";
echo "# TYPE vpn_check_status gauge\n";
echo "vpn_check_status " . ($m ? $m['vpn_status'] : 0) . "\n\n";

echo "# HELP vpn_check_duration_seconds Time taken to fetch VPN status from API\n";
echo "# TYPE vpn_check_duration_seconds gauge\n";
echo "vpn_check_duration_seconds " . ($m ? $m['vpn_check_duration'] : 0) . "\n\n";

echo "# HELP vpn_check_api_error Whether the last VPN status API call failed (1=error, 0=ok)\n";
echo "# TYPE vpn_check_api_error gauge\n";
echo "vpn_check_api_error " . ($m ? $m['vpn_check_error'] : 1) . "\n\n";

echo "# HELP vpn_check_public_ip_error Whether the last public IP lookup failed (1=error, 0=ok)\n";
echo "# TYPE vpn_check_public_ip_error gauge\n";
echo "vpn_check_public_ip_error " . ($m ? $m['public_ip_check_error'] : 1) . "\n\n";

echo "# HELP vpn_check_metrics_stale 1 if metrics are older than 10 minutes\n";
echo "# TYPE vpn_check_metrics_stale gauge\n";
echo "vpn_check_metrics_stale $stale\n\n";

echo "# HELP vpn_check_metrics_age_seconds How old the current metrics data is\n";
echo "# TYPE vpn_check_metrics_age_seconds gauge\n";
echo "vpn_check_metrics_age_seconds $age\n\n";

if ($m && !empty($m['public_ip']) && $m['public_ip'] !== 'Unavailable') {
    echo "# HELP vpn_check_public_ip Current public IP address\n";
    echo "# TYPE vpn_check_public_ip gauge\n";
    echo "vpn_check_public_ip{address=\"" . $m['public_ip'] . "\"} 1\n\n";
}

if ($m && !empty($m['vpn_ip']) && $m['vpn_ip'] !== 'N/A') {
    echo "# HELP vpn_check_vpn_ip Current VPN IP address\n";
    echo "# TYPE vpn_check_vpn_ip gauge\n";
    echo "vpn_check_vpn_ip{address=\"" . $m['vpn_ip'] . "\"} 1\n\n";
}
?>