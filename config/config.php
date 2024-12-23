<?php
// set the sessions for every page
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// error_reporting(E_ALL);
// ini_set('display_errors', 1);


// Set a custom error handler
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $message = "Error [$errno]: $errstr in $errfile on line $errline" . PHP_EOL;

    error_log($message, 3, '../errors.log');
});

// Set a custom exception handler
set_exception_handler(function ($exception) {
    $message = "Exception: " . $exception->getMessage() . PHP_EOL;

    error_log($message, 3, 'errors.log');
});
