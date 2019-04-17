<?php
//Delete user session data and redirect to login page
session_start();

session_destroy();

http_response_code(200);
echo '{"success":"Removing cached data..."}';
exit();
?>