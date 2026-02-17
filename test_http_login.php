<?php
// Test the login endpoint with actual HTTP request
echo "=== TESTING LOGIN ENDPOINT VIA HTTP ===\n\n";

// First, get the login page to extract CSRF token
echo "Step 1: Fetching login page...\n";
$login_page = file_get_contents('http://localhost:8000/login');

if (!$login_page) {
    echo "✗ Could not fetch login page. Is the server running on http://localhost:8000?\n";
    exit(1);
}

echo "✓ Login page fetched\n\n";

// Extract CSRF token
$matches = [];
if (preg_match('/<input.*?name="_token".*?value="([^"]+)"/', $login_page, $matches)) {
    $csrf_token = $matches[1];
    echo "✓ CSRF token extracted: " . substr($csrf_token, 0, 20) . "...\n\n";
} else {
    echo "⚠️  Could not extract CSRF token from response\n";
    echo "First 500 chars of response:\n" . substr($login_page, 0, 500) . "\n\n";
    exit(1);
}

// Prepare login data
$post_data = [
    '_token' => $csrf_token,
    'email' => 'admin@scooter.com',
    'password' => 'password123'
];

echo "Step 2: Posting login credentials...\n";
echo "  Email: admin@scooter.com\n";
echo "  Password: password123\n";
echo "  CSRF: " . substr($csrf_token, 0, 20) . "...\n\n";

// Make HTTP POST request
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query($post_data),
        'follow_location' => false  // Don't auto-follow redirects
    ]
]);

$response = @file_get_contents('http://localhost:8000/login', false, $context);

// Check HTTP response headers
echo "Step 3: Analyzing response...\n";
echo "Response headers:\n";
foreach ($http_response_header as $header) {
    if (strlen($header) > 100) {
        echo "  " . substr($header, 0, 100) . "...\n";
    } else {
        echo "  $header\n";
    }
}

// Check if login was successful (look for redirect)  
$header_line = $http_response_header[0];
echo "\nResult:\n";

if (strpos($header_line, '302') !== false || strpos($header_line, '303') !== false) {
    echo "✓ Login successful! (Received redirect response " . trim($header_line) . ")\n";
    foreach ($http_response_header as $header) {
        if (strpos($header, 'Location') !== false) {
            echo "  Redirect to: " . trim($header) . "\n";
        }
    }
} else if (strpos($header_line, '200') !== false) {
    echo "⚠️  Page loaded with 200 status - checking for error message...\n";
    
    // Check for error messages
    if (strpos($response, 'credentials') !== false || strpos($response, 'Credentials') !== false) {
        echo "✗ FOUND ERROR: Credentials error message in response\n";
        preg_match('/<[^>]*style="[^"]*color: #c33;[^"]*"[^>]*>([^<]+)</', $response, $error_matches);
        if (!empty($error_matches[1])) {
            echo "  Error message: " . htmlspecialchars($error_matches[1]) . "\n";
        }
    } else {
        echo "Response OK but not redirecting. Check if already authenticated.\n";
    }
} else {
    echo "✗ Unexpected response: " . trim($header_line) . "\n";
}

?>
