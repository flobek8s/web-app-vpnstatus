<?php
$raw = @file_get_contents('/tmp/vpn_metrics.json');
$m = $raw ? json_decode($raw, true) : null;

// How old is the data?
$age = $m ? (time() - $m['last_collected']) : 999;
$stale = ($age > 600) ? 1 : 0;  // stale if older than 2x the collection interval

header('Content-Type: text/plain; version=0.0.4');

echo "# HELP vpn_status VPN connection status (1=connected, 0=disconnected)\n";
echo "# TYPE vpn_status gauge\n";
echo "vpn_status " . ($m ? $m['vpn_status'] : 0) . "\n\n";

echo "# HELP vpn_check_duration_seconds Time taken to fetch VPN status\n";
echo "# TYPE vpn_check_duration_seconds gauge\n";
echo "vpn_check_duration_seconds " . ($m ? $m['vpn_check_duration'] : 0) . "\n\n";

echo "# HELP vpn_check_errors_total Whether the last VPN API call failed\n";
echo "# TYPE vpn_check_errors_total gauge\n";
echo "vpn_check_errors_total " . ($m ? $m['vpn_check_error'] : 1) . "\n\n";

echo "# HELP public_ip_check_errors_total Whether the last public IP call failed\n";
echo "# TYPE public_ip_check_errors_total gauge\n";
echo "public_ip_check_errors_total " . ($m ? $m['vpn_check_error'] : 1) . "\n\n";

echo "# HELP vpn_metrics_stale 1 if metrics are older than 120 seconds\n";
echo "# TYPE vpn_metrics_stale gauge\n";
echo "vpn_metrics_stale $stale\n\n";

echo "# HELP vpn_metrics_age_seconds How old the current metrics data is\n";
echo "# TYPE vpn_metrics_age_seconds gauge\n";
echo "vpn_metrics_age_seconds $age\n";
?>