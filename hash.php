<?php
// Palitan mo ito ng gusto mong password
$password = 'admin123';

// I-generate ang hash
echo password_hash($password, PASSWORD_DEFAULT);
?>
