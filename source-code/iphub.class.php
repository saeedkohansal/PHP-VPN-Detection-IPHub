<?php
// Declare a namespace for the class
namespace IPHub;

// Define a class named Lookup
class Lookup
{
    // Define a public static function named isBadIP
    public static function isBadIP(string $ip, string $key, bool $strict = false)
    {
        // Initialize a new cURL session
        $ch = curl_init();

        // Set options for the cURL session
        curl_setopt_array($ch, [
            // Set the URL for the cURL session
            CURLOPT_URL => "http://v2.api.iphub.info/ip/{$ip}",

            // Set the option to return the transfer as a string
            CURLOPT_RETURNTRANSFER => true,

            // Set the HTTP header for the cURL session
            CURLOPT_HTTPHEADER => ["X-Key: {$key}"]
        ]);

        // Use a try-catch block to handle exceptions
        try {
            // Execute the cURL session, decode the JSON response, and store it in $ce
            $ce = json_decode(curl_exec($ch));

            // If the 'block' property is set in the response, store its value in $block
            if (isset($ce->block)) {
                $block = $ce->block;
            } else {
                // If the 'block' property is not set in the response, set the HTTP status to 503 Service Unavailable
                header('HTTP/1.1 503 Service Unavailable');

                // Output a styled error message and terminate the script
                die('<style>* { color: #444; background-color: #0fb; }</style><pre><h1>The API limit has been exceeded or is currently unavailable.</h1></pre>');
            }
        } catch (Exception $e) {
            // If an exception is caught, re-throw it
            throw $e;
        }

        // If $block is true
        if ($block) {
            // If $strict is true, return true
            if ($strict) {
                return true;
            }
            // If $strict is false and $block is 1, return true
            elseif (!$strict && $block === 1) {
                return true;
            }
        }

        // If none of the above conditions are met, return false
        return false;
    }


    // Define a public static function named getIPInfo
    public static function getIPInfo(string $ip, string $key)
    {
        // Initialize a new cURL session
        $ch = curl_init();

        // Set options for the cURL session
        curl_setopt_array($ch, [
            // Set the URL for the cURL session
            CURLOPT_URL => "http://v2.api.iphub.info/ip/{$ip}",

            // Set the option to return the transfer as a string
            CURLOPT_RETURNTRANSFER => true,

            // Set the HTTP header for the cURL session
            CURLOPT_HTTPHEADER => ["X-Key: {$key}"]
        ]);

        // Execute the cURL session and store the response
        $response = curl_exec($ch);

        // If the response is false, throw an exception
        if ($response === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        // Decode the JSON response
        $ipInfo = json_decode($response);

        // If there is an error in decoding, throw an exception
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON decode error: ' . json_last_error_msg());
        }

        // Return the decoded response
        return $ipInfo;
    }
}
