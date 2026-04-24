<?php
echo "<title>IP Check</title>";
echo "<br><br>";
echo "<center><strong><h4>IP Check</h4></strong>";

$realIP = file_get_contents("http://ipecho.net/plain");
echo "<br>IP: $realIP";

echo "<br><br>VPN Check Data";
$response = file_get_contents('https://vpnstatus.lewiscrib.com/ip_status.php');
$data = json_decode($response, true);

echo "<br>VPN Status: " . $data['status'];
echo "<br>IP: " . $data['ip'];
echo "<br>Last Updated: " . $data['dt'];
echo "</center>";

echo "<hr>";

$raw = @file_get_contents('/tmp/vpn_metrics.json');
$m = $raw ? json_decode($raw, true) : null;

echo "<center><strong><h4>IP Check - Metrics Data</h4></strong>";

if (!$m) {
    echo "<br>Data not yet available — collector hasn't run yet.";
    echo "</center>";
    exit;
}

echo "<br>IP: " . htmlspecialchars($m['public_ip']);
echo "<br><br>VPN Check Data";
echo "<br>VPN Status: " . htmlspecialchars($m['vpn_status_text']);
echo "<br>IP: " . htmlspecialchars($m['vpn_ip']);
echo "<br>Last Updated: " . htmlspecialchars($m['vpn_last_updated']);
echo "<br><small>Metrics collected: " . date('Y-m-d H:i:s', $m['last_collected']) . "</small>";
echo "</center>";
?>