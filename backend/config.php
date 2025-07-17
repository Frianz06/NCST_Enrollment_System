<?php
    mysqli_report(MYSQLI_REPORT_OFF); // Disable default error reporting

    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'ncst_enrollment_system'; // Database Name

    // Create connection
    $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // Check connection
    if ($db->connect_errno) {
        echo 'Connection failed: ' . $db->connect_error;
    } 
    // leave it as a comment
    // else {
    //     echo 'Connection established';
    // }
?>


<!-- ALL IN ONE CSS -->
<style>
    html, body {
    overflow: auto; /* Allows scrolling */
}

/* Hide scrollbar for WebKit browsers (Chrome, Safari, Edge) */
html::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for Firefox */
html {
    scrollbar-width: none;
}

/* Hide scrollbar for Internet Explorer & old Edge */
html {
    -ms-overflow-style: none;
}

</style>
