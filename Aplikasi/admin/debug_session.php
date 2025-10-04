<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h2>Session Debug Information</h2>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Session Status:</strong> " . session_status() . "</p>";
echo "<p><strong>Session Save Path:</strong> " . session_save_path() . "</p>";
echo "<p><strong>Session Cookie Params:</strong></p>";
echo "<pre>";
print_r(session_get_cookie_params());
echo "</pre>";

echo "<h3>Session Data:</h3>";
if (empty($_SESSION)) {
    echo "<p style='color: red;'>No session data found!</p>";
} else {
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
}

echo "<h3>Direct Session Check:</h3>";
echo "<p><strong>isset(\$_SESSION['id_user']):</strong> " . (isset($_SESSION['id_user']) ? 'true' : 'false') . "</p>";
echo "<p><strong>empty(\$_SESSION['id_user']):</strong> " . (empty($_SESSION['id_user']) ? 'true' : 'false') . "</p>";
if (isset($_SESSION['id_user'])) {
    echo "<p><strong>\$_SESSION['id_user'] value:</strong> " . $_SESSION['id_user'] . "</p>";
}

echo "<h3>Session Functions Test:</h3>";
include 'validation.php';

echo "<p><strong>isLoggedIn():</strong> " . (isLoggedIn() ? 'true' : 'false') . "</p>";
if (isLoggedIn()) {
    echo "<p><strong>hasRole('admin'):</strong> " . (hasRole('admin') ? 'true' : 'false') . "</p>";
    echo "<p><strong>getCurrentUser():</strong></p>";
    echo "<pre>";
    print_r(getCurrentUser());
    echo "</pre>";
} else {
    echo "<p style='color: red;'>User not logged in according to validation functions</p>";
}

echo "<h3>Manual Login Test</h3>";
echo "<p><a href='manual_login_test.php'>Test Manual Session Set</a></p>";

echo "<h3>Manual Session Test (Direct)</h3>";
if (isset($_GET['test_session'])) {
    $_SESSION['id_user'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['nama_lengkap'] = 'Administrator';
    $_SESSION['role'] = 'admin';
    echo "<p style='color: green;'>Session manually set! <a href='debug_session.php'>Refresh to test</a></p>";
} else {
    echo "<p><a href='debug_session.php?test_session=1'>Set Session Manually</a></p>";
}
?>
