<?php
// Include the IPHub class file
require_once('iphub.class.php');

// Use the Lookup class from the IPHub namespace and alias it as IPHub
use IPHub\Lookup as IPHub;

// Get the client's IP address from the server superglobal
$ip = $_SERVER['REMOTE_ADDR'];

// Define the API key for IPHub
$apiKey = 'REPLACE_WITH_YOUR_API_KEY';

/* •••••••••••••••••••••••••••••••• */
/* IP LIST FOR TESTING ON LOCALHOST */
/* •••••••••••••••••••••••••••••••• */
// Residential / Unclassified IP address
# $ip = '5.116.135.167';
# $ip = '2a01:5ec0:2807:d742:1:0:19fc:3db8';

// Non-residential IP address (server, VPN, proxy, etc..)
# $ip = '1.1.1.1';
# $ip = '8.8.8.8';
# $ip = '104.248.130.233';
# $ip = '2001:41d0:701:1100::c48';
# $ip = '2001:1608:10:25::1c04:b12f';
# $ip = '13.81.217.201';
# $ip = '51.15.242.202';
# $ip = '20.219.178.121';
/* •••••••••••••••••••••••••••••••• */
/* •••••••••••••••••••••••••••••••• */
/* •••••••••••••••••••••••••••••••• */

// Initialize a variable to store the access state
$accessState = '';

/* •••••••••••••••••••••••••••••••••••••••••••••••• */
/* BLOCK ACCESS FOR VPN/PROXY/SERVER WITH IPHUB API */
/* •••••••••••••••••••••••••••••••••••••••••••••••• */
// Use a try-catch block to handle exceptions
try {
    // Call the isBadIP method of the IPHub class to check if the IP is bad
    $block = IPHub::isBadIP($ip, $apiKey);

    // If the IP is bad
    if ($block) {
        // Set the access state to 'blocked'
        $accessState = 'blocked';

        /* ••••••••••••••••••••••••••• */
        /* SOME ACCESS DENIED EXAMPLES */
        /* ••••••••••••••••••••••••••• */
        // Send a 403 Forbidden header and terminate script execution
        # die(header('HTTP/1.1 403 Forbidden'));

        // Redirect to a specified domain and terminate script execution
        # die(header('Location: https://www.google.com'));

        // Display a styled message for blocked requests and provide IP information
        # die("<style>* { color: #fff; background-color: #f54; }</style><pre><h1>Request blocked as you appear to be browsing from a VPN/Proxy/Server.<br>Please contact IPHub.info if you believe this is a mistake.<br>Your IP address is: $ip</h1></pre>");
        /* ••••••••••••••••••••••••••• */
        /* ••••••••••••••••••••••••••• */
        /* ••••••••••••••••••••••••••• */
    } else {
        // Otherwise, set the access state to 'granted'
        $accessState = 'granted';
    }
} catch (Exception $e) {
    // If an exception occurs, set block to false
    $block = false;
}
/* •••••••••••••••••••••••••••••••••••••••••••••••• */
/* •••••••••••••••••••••••••••••••••••••••••••••••• */
/* •••••••••••••••••••••••••••••••••••••••••••••••• */


/* •••••••••••••••••••••••••• */
/* IP LOOKUP JSON API VERSION */
/* •••••••••••••••••••••••••• */
// // Use a try-catch block to handle exceptions
// try {
//     // Call the getIPInfo method of the IPHub class to get information about the IP
//     $ipInfo = IPHub::getIPInfo($ip, $apiKey);

//     // Set the content type of the HTTP response to application/json
//     header('Content-Type: application/json; charset=utf-8');

//     // Encode the IP information as JSON and output it, then terminate the script
//     die(json_encode($ipInfo, JSON_PRETTY_PRINT));
// } catch (Exception $e) {
//     // If an exception occurs, output the error message
//     echo 'An error occurred: ' . $e->getMessage();
// }
/* •••••••••••••••••••••••••• */
/* •••••••••••••••••••••••••• */
/* •••••••••••••••••••••••••• */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>gilgeekify programming</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nova+Square">
    <style>
        body {
            font-family: "Nova Square";
            margin: 0;
            padding: 0;
            background-image: url("gilgeekify.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: 900;
            overflow: hidden;
            <?= ($accessState === 'blocked') ? 'backdrop-filter: grayscale(1);' : ''; ?>
        }

        h1 {
            text-transform: uppercase;
            text-align: center;
            width: 100%;
            padding: 40px 0;
            letter-spacing: 6px;
        }

        .blocked {
            color: #fff;
            background-color: #ff0000e6;
        }

        .granted {
            color: #b5ff00;
            background-color: #111111e6;
        }

        span {
            display: block;
        }
    </style>
</head>

<body>
    <?php if ($accessState === 'blocked') : ?>
        <h1 class="animate__animated animate__flipInY animate__slower blocked">
            <span class="animate__animated animate__pulse animate__infinite animate__slower">
                Request blocked as you appear to be browsing<br>
                from a VPN/Proxy/Server.<br>
                Your IP address is: <?= $ip; ?>
            </span>
        </h1>
    <?php elseif ($accessState === 'granted') : ?>
        <h1 class="animate__animated animate__flipInY animate__slower granted">
            <span class="animate__animated animate__pulse animate__infinite animate__slower">
                Welcome to the service! 😍
            </span>
        </h1>
    <?php endif; ?>
</body>

</html>