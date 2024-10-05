<?php
require 'vendor/autoload.php'; // Ensure this path is correct


// Import necessary namespaces
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

// Create a ServiceAccount object from the JSON file
$serviceAccountPath = 'C:\Users\TJ\Desktop\edoc-doctor-appointment-system-main2\therapease.json';
$databaseUri = 'https://therapease-d9525-default-rtdb.asia-southeast1.firebasedatabase.app/';

// Initialize Firebase
$factory = (new Factory)
    ->withServiceAccount($serviceAccountPath)
    ->withDatabaseUri($databaseUri);

// Get the Realtime Database instance
$database = $factory->createDatabase();

// Get the Authentication instance
$auth = $factory->createAuth();

?>
