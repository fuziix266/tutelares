<?php
$ctx = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);
$data = @file_get_contents('https://getcomposer.org/composer.phar', false, $ctx);
if ($data === false) {
    echo "Failed to download composer.phar\n";
    exit(1);
}
file_put_contents('composer.phar', $data);
?>
