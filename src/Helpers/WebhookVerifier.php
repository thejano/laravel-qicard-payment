<?php

namespace TheJano\QiCardPayment\Helpers;

class WebhookVerifier
{
    /**
     * Verify the RSA signature of a QiCard webhook payload.
     *
     * @param array $payload The JSON-decoded webhook payload
     * @param string $signature The base64-encoded signature from the X-Signature header
     * @param string $publicKey The RSA public key in PEM format
     * @return bool True if valid, false otherwise
     */
    public static function verify(array $payload, string $signature, string $publicKey): bool
    {
        if (empty($signature) || empty($publicKey)) {
            return false;
        }

        $fields = [
            $payload['paymentId'] ?? '-',
            isset($payload['amount']) ? number_format((float) $payload['amount'], 3, '.', '') : '-',
            $payload['currency'] ?? '-',
            $payload['creationDate'] ?? '-',
            $payload['status'] ?? '-',
        ];

        $dataString = implode('|', $fields);

        $signatureBuffer = base64_decode($signature, true);
        
        if ($signatureBuffer === false) {
            return false;
        }

        $isValid = openssl_verify(
            $dataString,
            $signatureBuffer,
            $publicKey,
            OPENSSL_ALGO_SHA256
        );

        return $isValid === 1;
    }
}
