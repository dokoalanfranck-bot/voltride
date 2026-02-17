<?php
// Simulate a browser login flow with proper session/cookie handling

echo "=== BROWSER LOGIN SIMULATION ===\n\n";

// Step 1: GET /login to receive CSRF token and session cookie
echo "Step 1: Fetching login page with cookies...\n";

$cookie_jar = tempnam(sys_get_temp_dir(), 'curl_cookies_');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$login_page = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if (!$login_page) {
    echo "✗ Failed to fetch login page\n";
    exit(1);
}

echo "✓ Login page retrieved (HTTP $http_code)\n\n";

// Extract CSRF token
$matches = [];
if (!preg_match('/<input[^>]*name="_token"[^>]*value="([^"]+)"/', $login_page, $matches)) {
    echo "✗ CSRF token not found in form\n";
    exit(1);
}

$csrf_token = $matches[1];
echo "✓ CSRF token extracted: " . substr($csrf_token, 0, 20) . "...\n";

// Step 2: POST /login with CSRF token and saved cookies
echo "\nStep 2: Posting login credentials with saved session...\n";

$post_data = http_build_query([
    '_token' => $csrf_token,
    'email' => 'admin@scooter.com',
    'password' => 'password123'
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);  // Don't auto-follow to see redirect

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

echo "Response HTTP code: $http_code\n";
echo "Content-Type: $content_type\n\n";

if ($http_code === 302 || $http_code === 303) {
    echo "✓ Login successful! Received redirect response.\n";
} else if ($http_code === 419) {
    echo "✗ CSRF Token Mismatch or Session Invalid (HTTP 419)\n";
    echo "  This means the session wasn't properly maintained between GET and POST\n";
} else if ($http_code === 200) {
    // Check for error message
    if (strpos($response, 'credentials') !== false) {
        echo "✗ Authentication failed - invalid credentials\n";
    } else {
        echo "⚠️  Unexpected response - check form manually\n";
    }
} else {
    echo "✗ Unexpected HTTP " . $http_code . "\n";
}

// Cleanup
@unlink($cookie_jar);

?>
