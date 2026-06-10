<?php

use TheJano\QiCardPayment\Helpers\WebhookVerifier;

$privateKey = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCmEIrYigsfzK46\nUdQRWgZGU9uDyvLji0TtnTrnhJeuUweV9fCCFv1oLpQIKoUU8bcf3yPSbsfZtF6r\nU4GT0jVlouP/aRg04iJ3ZdQqOTCbIsn3V0SfFNgw7N38gc1up7j6LJMImovFllxf\nOnTad/LpfjEsAblCqxxPwuhYm2ph8JjanKYvzHV1pB+rDy9SkkYxu20B6XoNRkJc\nxJEyMGy1E86rXI3/HTrDLbbZEDT13KgWqiguiYO96jkimxIQz4WJalU5b1p8kSKH\nYE+vq1+QMur96IQLONEtgQqK4ZSaAtn4LqCToTFVbdfBfPH24/uakF0X/MigLe2m\nqTSwZX3fAgMBAAECggEACKo556aV6GjdRnAlz2n4UQvn5+6OnrTe03WUZA3ISr5M\nwDrLyKq7Ojs3JveHZ8DDb2EZrppwYlNF0hnsKIxolA8PZ0pSiwZDPVVA4GfvhojA\nK3nDb0S+xLH3VOLPZB2FF18WccJ7b8N/hzCDqTEAQ1994ImOfLeoJVLkNyT7RHTp\nBO1mPdFXeOTYDULIfHPYfICDtYReMPrSPl4neHXTFsptId8zmFwlnhMaEOp5UXVa\ny1rMxavhAFq8Au3Y5/oEv+ijTNvtRrXwWZ0dTdJ8AmVwWRBwMoTz1fFe8PsTEh3j\n0hzdikS9yJ0UuBXkoSjeOoSUxGedqfkn/eKWXsF1cQKBgQDd2gf3/wuNFJNnhYGA\nkVpLqQI/QpWVGmbMdjPazZSh0DxN75Q1OehRZK0BRyTKMCV/rER79Wj1zconhCRx\n7cVfq2JVtMfg/0xKquYigW97FnMYFOwKI+VeJ+C5TLfIzsL1KbexOtvgqceth7DC\nmlkVhrBvy1qB4iz11v6Vo2jtzQKBgQC/oD8KPXs/tg45FQVr+YU59ihJ9AZyyYho\nmxqfFGiezHkP4NwH1PJ3HL/h+iXSgceb1JzSbXtydf/SEibN751c+Lpo7q//r2VQ\nZLy8RxMADIVvbM4liWRmzBnynsPE/0CG/RFMLmhNThiTL4K0zrsJR3B3F6mT6U2C\nlpuZh7bOWwKBgQC9wByMuvIwSucA5imrw6bNi9LGNLcn/prqSjdz794uwKKzrjS/\nibXw2AE5VJZc8O1B1CAHPhH2G3vKbLWXVF06xKWo1KUZBoNEYgPQg5DMYJk0gcCZ\ns7Yc4FLPapTPBjmt426gAc3KzKxi+envqICFbtw94lqZONBnvOmnJ0s6CQKBgFWK\n65leqM5gLyk8QRdaZCbjxEh/LwG/Ba3sz42ERs/c5443CwJUBYk2xFHUoV2/oaPm\n3bryNNF765+Tis8T0GPYhR6irt6tJcUlszs5Xzn+XPLI153aH3/kTREJ3srqR5v7\nDOW9McKQwleyNd8RCl2yDnqbjZAB7iAFq6B1/R1RAoGBAI1/OKCiXVJZWfO2qDUQ\n8yRnyQBgEh7EEaRGTmzGBH9mMz2L4jfC7Rx0M5OQJh2FK58r8KL9l765xxzYStKX\ntV++vmdrG1xJZmhKHsrKIGgqEu4wOQz5hSqPDrYXI7qs5a4Pi9cErcq55qPex+F2\nAuI8hw3g8TkGP9uHyYimdONI\n-----END PRIVATE KEY-----";

$publicKey = "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAphCK2IoLH8yuOlHUEVoG\nRlPbg8ry44tE7Z0654SXrlMHlfXwghb9aC6UCCqFFPG3H98j0m7H2bReq1OBk9I1\nZaLj/2kYNOIid2XUKjkwmyLJ91dEnxTYMOzd/IHNbqe4+iyTCJqLxZZcXzp02nfy\n6X4xLAG5QqscT8LoWJtqYfCY2pymL8x1daQfqw8vUpJGMbttAel6DUZCXMSRMjBs\ntRPOq1yN/x06wy222RA09dyoFqooLomDveo5IpsSEM+FiWpVOW9afJEih2BPr6tf\nkDLq/eiECzjRLYEKiuGUmgLZ+C6gk6ExVW3XwXzx9uP7mpBdF/zIoC3tpqk0sGV9\n3wIDAQAB\n-----END PUBLIC KEY-----";

it('verifies a valid webhook signature correctly', function () use ($privateKey, $publicKey) {
    // Mock payload
    $payload = [
        'requestId' => '20250113-212519-073',
        'paymentId' => 'b91e8d70-1ab7-4275-85a2-61f7dbb31410',
        'status' => 'SUCCESS',
        'amount' => 10000,
        'currency' => 'IQD',
        'creationDate' => '2025-01-13T21:25:19',
    ];

    // Reconstruct data string exactly like the helper does
    $fields = [
        $payload['paymentId'],
        number_format((float) $payload['amount'], 3, '.', ''),
        $payload['currency'],
        $payload['creationDate'],
        $payload['status'],
    ];
    $dataString = implode('|', $fields);

    // Generate the valid signature using the private key
    openssl_sign($dataString, $signature, $privateKey, OPENSSL_ALGO_SHA256);
    $base64Signature = base64_encode($signature);

    // Test the verify function
    $isValid = WebhookVerifier::verify($payload, $base64Signature, $publicKey);

    expect($isValid)->toBeTrue();
});

it('rejects an invalid webhook signature', function () use ($publicKey) {
    // Mock payload
    $payload = [
        'requestId' => '20250113-212519-073',
        'paymentId' => 'b91e8d70-1ab7-4275-85a2-61f7dbb31410',
        'status' => 'SUCCESS',
        'amount' => 10000,
        'currency' => 'IQD',
        'creationDate' => '2025-01-13T21:25:19',
    ];

    // Fake invalid signature
    $fakeSignature = base64_encode('this-is-not-a-valid-signature');

    // Test the verify function
    $isValid = WebhookVerifier::verify($payload, $fakeSignature, $publicKey);

    expect($isValid)->toBeFalse();
});

it('rejects an empty signature or public key', function () {
    $payload = [
        'paymentId' => 'b91e8d70-1ab7-4275-85a2-61f7dbb31410',
        'status' => 'SUCCESS',
    ];

    expect(WebhookVerifier::verify($payload, '', 'fake-public-key'))->toBeFalse();
    expect(WebhookVerifier::verify($payload, 'fake-signature', ''))->toBeFalse();
});
