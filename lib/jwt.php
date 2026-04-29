<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/jwt.php';

require_once __DIR__ . '/../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

function gen_jwt(int $admin_id, int $ttl = 3600): string {
  $conn = get_db();
  $jwt_config = get_jwt_config();

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

  $token_exp = $exp - 180; // cookie expires 3 mins before token

  setcookie('token', $jwt, [
    'expires' => $token_exp,
    'path' => '/',
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict',
  ]);

  $stmt = $conn->prepare("
    INSERT INTO auth_tokens (auth_token_jti, auth_token_expires_at, admin_id)
    VALUES (?, FROM_UNIXTIME(?), ?)
  ");
  $stmt->bind_param("sii", $jti, $token_exp, $admin_id);
  $stmt->execute();

  return $jwt;
}

function verify_jwt(string $jwt): ?array {
  $conn = get_db();
  $jwt_config = get_jwt_config();

  $signing_key = $jwt_config['secret'];

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

  $stmt = $conn->prepare("SELECT COUNT(*) as cnt
    FROM auth_tokens
    WHERE auth_token_jti = ?
    AND auth_token_revoked_at IS NULL
    AND auth_token_expires_at > NOW()
  ");
  $stmt->bind_param('s', $jti);
  $stmt->execute();

  $row = $stmt->get_result()->fetch_assoc();
  if ($row['cnt'] < 1) return null;

  return $decoded_payload;
}

function discard_jwt() {
  $conn = get_db();

  if (!isset($_COOKIE['token'])) return;
  
  // decode the raw JWT string first
  $parts = explode('.', $_COOKIE['token']);
  if (count($parts) === 3) {
    $decoded_payload = json_decode(base64_url_decode($parts[1]), true);
    if ($decoded_payload && isset($decoded_payload['jti'])) {
      $jti = $decoded_payload['jti'];
      $stmt = $conn->prepare("UPDATE auth_tokens SET auth_token_revoked_at=NOW() WHERE auth_token_jti=?");
      $stmt->bind_param("s", $jti);
      $stmt->execute();
    }
  }

  setcookie('token', '', time() - (3600 * 24 * 30), '/');
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
