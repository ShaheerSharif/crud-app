<?php

require_once __DIR__ . '/../vendor/autoload.php';

$jwt_config = require_once __DIR__ . '/../config/jwt.php';

use Ramsey\Uuid\Uuid;

function gen_jwt($conn, int $admin_id, int $ttl = 3600): string {
  $jti = Uuid::uuid4()->toString();
  $iat = time();
  $exp = $iat + $ttl;

  $signing_key = $jwt_config['secret'];
  $header = [
    'alg' => 'HS512',
    'typ' => 'JWT'
  ];
  $header = base64_url_encode(json_encode($header, JSON_THROW_ON_ERROR));
  $payload = [
    'jti' => $jti,
    'sub' => $admin_id,
    'iat' => $iat,
    'exp' => $exp,
  ];
  $payload = base64_url_encode(json_encode($payload, JSON_THROW_ON_ERROR));
  $signature = base64_url_encode(hash_hmac('sha512', "$header.$payload", $signing_key, true));
  $jwt = "$header.$payload.$signature";

  setcookie('token', $jwt, [
    'expires' => $exp - 180, // cookie expires 3 mins before token
    'path' => '/',
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict',
  ]);

  $stmt = $conn->prepare("
    INSERT INTO auth_tokens (auth_token_jti, auth_token_expires_at, admin_id)
    VALUES (?, FROM_UNIXTIME(?), ?)
  ");
  $stmt->bind_param("sii", $jti, $exp, $admin_id);
  $stmt->execute();

  return $jwt;
}

function verify_jwt($conn, string $jwt): ?array {
  $signing_key = getenv('JWT_SECRET');

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

  $jti = $decoded_payload['jti'];

  $q = mysqli_query($conn, "SELECT COUNT(*) as cnt
    FROM auth_tokens
    WHERE auth_token_jti = ?
    AND auth_token_revoked_at IS NULL
    AND auth_token_expires_at > NOW()
  ");

  $row = $q->fetch_assoc();
  if ($row['cnt'] < 1) return null;

  return $decoded_payload;
}

function discard_jwt($conn) {
  $decoded_payload = $_COOKIE['token'];
  setcookie('token', '', time() - (3600 * 24 * 30), '/');

  $jti = $decoded_payload['jti'];
  $revoked_timestamp = time();
  mysqli_query($conn, "UPDATE auth_tokens SET auth_token_revoked_at='$revoked_timestamp' WHERE auth_token_jti='$jti'");

  unset($_COOKIE['token']);
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
