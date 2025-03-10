<?php
// Supabase Credentials
define("SUPABASE_URL", "https://rbborpwwkrfhkcqvacyz.supabase.co"); // Replace with your Supabase URL
define("SUPABASE_KEY", "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM");
define("STORAGE_BUCKET", "documents"); // Replace with your actual bucket name

// Function to list all files in Supabase Storage
function listSupabaseFiles($folder = "") {
  $url = "https://rbborpwwkrfhkcqvacyz.supabase.co/storage/v1/object/list/documents";
  // $url = SUPABASE_URL . "/storage/v1/object/list/";


    
    // if (!empty($folder)) {
    //     $url .= "?prefix=" . urlencode($folder) . "/";
    // }

    $headers = [
        "Authorization: Bearer " . SUPABASE_KEY,
        "Content-Type: application/json"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FAILONERROR, false);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Debugging Output
    echo "<h3>Debugging Information:</h3>";
    echo "<p><b>HTTP Code:</b> $http_code</p>";
    echo "<p><b>cURL Error:</b> $curl_error</p>";
    echo "<p><b>Response:</b></p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";

    if ($http_code == 200) {
        $files = json_decode($response, true);
        if (is_array($files) && count($files) > 0) {
            echo "<h3>Files in Supabase Storage:</h3><ul>";
            foreach ($files as $file) {
                echo "<li>" . htmlspecialchars($file["name"]) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No files found in the bucket.</p>";
        }
    } else {
        echo "<p>Failed to retrieve files.</p>";
    }
}

// Call the function (list all files)
listSupabaseFiles();
?>
