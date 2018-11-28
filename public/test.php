<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$salt = generateRandomString(8);
$password = generateRandomString(15);
$hashedPassword = md5(md5($password).md5($salt));
echo "salt: {$salt}<br />";
echo "pass: {$password}<br />";
echo "hash: {$hashedPassword}<br />";
?>