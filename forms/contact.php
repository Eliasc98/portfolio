<?php
  $receiving_email_address = 'oluwadamilare.c99@gmail.com';

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
  }

  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if ($name === '' || $email === '' || $subject === '' || $message === '') {
    http_response_code(400);
    echo 'Please complete all required fields.';
    exit;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Please enter a valid email address.';
    exit;
  }

  $safe_subject = preg_replace('/[\r\n]+/', ' ', $subject);
  $safe_name = preg_replace('/[\r\n<>]+/', ' ', $name);
  $safe_host = preg_replace('/[^a-zA-Z0-9.-]/', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
  $email_body = "You received a new message from your portfolio contact form.\n\n";
  $email_body .= "Name: {$name}\n";
  $email_body .= "Email: {$email}\n";
  $email_body .= "Subject: {$safe_subject}\n\n";
  $email_body .= "Message:\n{$message}\n";

  $headers = [
    'From: Portfolio Contact Form <no-reply@' . $safe_host . '>',
    'Reply-To: ' . $safe_name . ' <' . $email . '>',
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion()
  ];

  if (mail($receiving_email_address, 'Portfolio Contact: ' . $safe_subject, $email_body, implode("\r\n", $headers))) {
    echo 'OK';
  } else {
    http_response_code(500);
    echo 'Message could not be sent. Please try again later.';
  }
?>
