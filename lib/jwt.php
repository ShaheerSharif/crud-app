<?php

function gen_jwt(int $userId): string {
  $signing_key = getenv('JWT_SECRET');
  $header = [
    'alg' => 'HS512',
    'typ' => 'JWT'
  ];
  $header = base64_url_encode(json_encode($header, JSON_THROW_ON_ERROR));
  $payload = [
    'sub' => $userId,
    'iat' => time(),
    'exp' => time() + 3600,
  ];
  $payload = base64_url_encode(json_encode($payload, JSON_THROW_ON_ERROR));
  $signature = base64_url_encode(hash_hmac('sha512', "$header.$payload", $signing_key, true));
  $jwt = "$header.$payload.$signature";

  return $jwt;
}

function verify_jwt(string $jwt): ?array {
  $signing_key = getenv('SIGNING_KEY');

  if (!$signing_key) return null;

  $parts = explode('.', $jwt);

  if (count($parts) !== 3) return null;

  [$header, $payload, $signature] = $parts;

  $valid_signature = base64_url_encode(
    hash_hmac('sha512', "$header.$payload", $signing_key, true)
  );

  if (!hash_equals($valid_signature, $signature)) return null;

  $decoded_payload = json_decode(
    base64_url_decode($payload),
    true,
  );

  if (!$decoded_payload) return null;

  if (isset($decoded_payload['exp']) && time() >= $decoded_payload['exp']) return null;

  return $decoded_payload;
}

function base64_url_encode(string $text): string {
  return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
}

function base64_url_decode(string $text): string {
  $remainder = strlen($text) % 4;
  if ($remainder !== 0) {
    $text .= str_repeat('=', 4 - $remainder);
  }

  return base64_decode(str_replace(['-', '_'], ['+', '/'], $text));
}
