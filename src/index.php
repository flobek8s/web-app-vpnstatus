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
?>