<?php
session_start();

/* ===============================
   1. Only allow POST request
================================ */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

/* ===============================
   2. CSRF token validation
================================ */
if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    exit('Invalid CSRF token');
}

/* ===============================
   3. Constants
================================ */
define("RECIPIENT_NAME", "John Doe");
define("RECIPIENT_EMAIL", "mail@mail.com");

/* ===============================
   4. Helper: remove CRLF (header injection protection)
================================ */
function clean_header($value) {
    return str_replace(["\r", "\n"], '', trim($value));
}

/* ===============================
   5. Read & sanitize input
================================ */
$name         = isset($_POST['name']) ? clean_header(strip_tags($_POST['name'])) : '';
$senderEmail  = isset($_POST['email']) ? clean_header(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$phone        = isset($_POST['phone']) ? strip_tags($_POST['phone']) : '';
$services     = isset($_POST['services']) ? strip_tags($_POST['services']) : '';
$subject      = isset($_POST['subject']) ? strip_tags($_POST['subject']) : '';
$address      = isset($_POST['address']) ? strip_tags($_POST['address']) : '';
$website      = isset($_POST['website']) ? filter_var($_POST['website'], FILTER_SANITIZE_URL) : '';
$message      = isset($_POST['message']) ? strip_tags($_POST['message']) : '';

/* ===============================
   6. Basic validation
================================ */
if (!$name || !$senderEmail || !$message || !filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
    echo "<div class='inner error'><p class='error'>Invalid input. Please try again.</p></div>";
    exit;
}

/* ===============================
   7. Email content
================================ */
$mail_subject = 'New contact request from ' . $name;

$body  = "Name: {$name}\n";
$body .= "Email: {$senderEmail}\n";
if ($phone)    $body .= "Phone: {$phone}\n";
if ($services) $body .= "Services: {$services}\n";
if ($subject)  $body .= "Subject: {$subject}\n";
if ($address)  $body .= "Address: {$address}\n";
if ($website)  $body .= "Website: {$website}\n";
$body .= "\nMessage:\n{$message}";

/* ===============================
   8. Headers (safe)
================================ */
$headers = [
    'From: ' . RECIPIENT_NAME . ' <' . RECIPIENT_EMAIL . '>',
    'Reply-To: ' . $senderEmail,
    'Content-Type: text/plain; charset=UTF-8'
];

/* ===============================
   9. Send email
================================ */
$success = mail(
    RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">",
    $mail_subject,
    $body,
    implode("\r\n", $headers)
);

/* ===============================
   Redirect back to same page
================================ */
$_SESSION['form_status'] = $sent ? 'success' : 'error';

header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;