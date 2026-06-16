<?php
$config = [
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
];
$res = openssl_pkey_new($config);
if (!$res) {
    while ($e = openssl_error_string()) {
        echo "OPENSSL ERROR: $e\n";
    }
    exit(1);
}
openssl_pkey_export($res, $priv);
file_put_contents(__DIR__ . '/../deploy_key', $priv);
echo "Private key written to deploy_key\n";
?>