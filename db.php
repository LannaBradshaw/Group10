<?php
    define('DB_HOST', 'helmi.cs.colostate.edu');
    define('DB_USER', 'NETID');
    define('DB_PASS', 'PASSWORD');
    define('DB_NAME', 'NETID');

    define('SSL_CERT', '/usr/local/ssl/server-cert.pem');
    define('SSL_CA',   '/usr/local/ssl/ca-cert.pem');

    $conn = mysqli_init();
    if (!$conn) {
        die('mysqli_init failed.');
    }
    $conn->ssl_set(SSL_CERT, NULL, SSL_CA, NULL, NULL);
    mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
    if (!mysqli_real_connect($conn, DB_HOST, DB_USER, DB_PASS, DB_NAME)) {
        die('Connection failed: ' . mysqli_connect_error());
    }
?>