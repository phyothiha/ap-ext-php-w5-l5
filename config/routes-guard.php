<?php 

// Check CSRF token when performing POST REQUEST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! hash_equals($_SESSION['_token'], $_POST['_token'])) {
        // get_template_part( 'partials/errors/419' );
        die();
    } 
    
    unset($_SESSION['_token']);
} 

// Regenerate CSRF token
if (empty($_SESSION['_token'])) {
    if (function_exists('random_bytes')) {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    } elseif (function_exists('mcrypt_create_iv')) {
        $_SESSION['_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

// Redirect to specific login page when there is no user_id in the session

// Get current route and trim suffix and prefix slash from URI
$current = trim($_SERVER['PHP_SELF'], '/');

// Admin
// If the user is not logged in
if (
    ! isset($_SESSION['user_id']) && 
    ( 
        preg_match('/^admin\/categories/i', $current) ||
        preg_match('/^admin\/products/i', $current) ||
        preg_match('/^admin\/users/i', $current)
    )
) {
    header('Location: /admin/login.php');
    exit;
}

// If the user is logged in
if (
    isset($_SESSION['user_id']) && 
    ( 
        preg_match('/^admin\/login/i', $current)
    )
) {
    header('Location: /admin/categories/index.php');
    exit;
}

// Site Visitor
// implementation