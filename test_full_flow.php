<?php
// Complete end-to-end test: Login + Create Reservation

echo "=== END-TO-END TEST: LOGIN + RESERVATION ===\n\n";

// Step 1: GET /login
echo "Step 1: Fetching login page...\n";

$cookie_jar = tempnam(sys_get_temp_dir(), 'curl_cookies_');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_VERBOSE, false);

$login_page = curl_exec($ch);
curl_close($ch);

if (!$login_page) {
    echo "✗ Failed to fetch login page\n";
    exit(1);
}

echo "✓ Login page retrieved\n";

// Extract CSRF token
$matches = [];
if (!preg_match('/<input[^>]*name="_token"[^>]*value="([^"]+)"/', $login_page, $matches)) {
    echo "✗ CSRF token not found\n";
    exit(1);
}

$csrf_token = $matches[1];
echo "✓ CSRF token extracted\n\n";

// Step 2: POST /login
echo "Step 2: Logging in...\n";

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
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 302) {
    echo "✗ Login failed (HTTP $http_code)\n";
    if (strpos($response, 'credentials') !== false) {
        echo "  Error: Invalid credentials\n";
    }
    exit(1);
}

echo "✓ Login successful (HTTP 302 redirect)\n\n";

// Step 3: GET scooters page to find a scooter
echo "Step 3: Fetching scooters...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/scooters');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_VERBOSE, false);

$scooters_page = curl_exec($ch);
curl_close($ch);

if (!$scooters_page || strpos($scooters_page, 'scooter') === false) {
    echo "✗ Failed to fetch scooters\n";
    exit(1);
}

// Extract first scooter ID
$matches = [];
if (!preg_match('/\/scooters\/(\d+)/', $scooters_page, $matches)) {
    echo "✗ Could not find scooter\n";
    exit(1);
}

$scooter_id = $matches[1];
echo "✓ Found scooter ID: $scooter_id\n\n";

// Step 4: GET reservation creation page
echo "Step 4: Getting reservation form for scooter...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/scooters/$scooter_id/reserve");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_VERBOSE, false);

$reserve_form = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if (!$reserve_form) {
    echo "✗ Failed to fetch reservation form\n";
    exit(1);
}

// Extract CSRF token
$matches = [];
if (!preg_match('/<input[^>]*name="_token"[^>]*value="([^"]+)"/', $reserve_form, $matches)) {
    echo "✗ CSRF token not found in reservation form\n";
    exit(1);
}

$csrf_token_2 = $matches[1];
echo "✓ Reservation form loaded with CSRF token\n\n";

// Step 5: POST reservation
echo "Step 5: Creating reservation...\n";

$tomorrow = date('Y-m-d', strtotime('+1 day'));
$reservation_data = http_build_query([
    '_token' => $csrf_token_2,
    'scooter_id' => $scooter_id,
    'start_date' => $tomorrow,
    'start_time' => '09:00',
    'end_date' => $tomorrow,
    'end_time' => '12:00',
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/reservations');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $reservation_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_VERBOSE, false);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 302) {
    echo "✓ Reservation created successfully (HTTP 302)\n";
    echo "\n=== TEST PASSED ===\n";
    echo "✓ Full user flow works: Login → View Scooters → Create Reservation\n";
} else {
    echo "✗ Reservation creation failed (HTTP $http_code)\n";
    if (strpos($response, 'start_time') !== false) {
        echo "  Error: DateTime validation issue\n";
    } else if (strpos($response, 'field') !== false) {
        echo "  Error: Form validation failed\n";
    }
}

// Cleanup
@unlink($cookie_jar);

?>
