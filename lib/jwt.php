<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/jwt.php';

require_once __DIR__ . '/../vendor/autoload.php';

use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function gen_jwt(int $admin_id, int $ttl = 3600): string {
  $conn = get_db();
  $jwt_config = get_jwt_config();

  $jti = Uuid::uuid4()->toString();
  $iat = time();
  $exp = $iat + $ttl;

  $payload = [
    'jti' => $jti,
    'sub' => $admin_id,
    'iat' => $iat,
    'exp' => $exp,
  ];

  $jwt = JWT::encode($payload, $jwt_config['secret'], 'HS256');

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

function verify_jwt(): ?array {
  if (!token_exists_locally()) return null;

  $conn = get_db();
  $jwt_config = get_jwt_config();

  $jwt = $_COOKIE['token'];
  $signing_key = $jwt_config['secret'];
  $decoded = JWT::decode($jwt, new Key($signing_key, 'HS256'));
  $decoded_payload = (array) $decoded;

  if (!$decoded_payload) return null;

  if (isset($decoded_payload['exp']) && time() >= $decoded_payload['exp']) return null;

  $jti = $decoded_payload['jti'];

  $stmt = $conn->prepare("SELECT COUNT(*) as cnt
    FROM auth_tokens
    WHERE auth_token_jti = ?
    AND auth_token_revoked_at IS NULL
    AND auth_token_expires_at > NOW()
    LIMIT 1
  ");
  $stmt->bind_param('s', $jti);
  $stmt->execute();

  $row = $stmt->get_result()->fetch_assoc();
  if ($row['cnt'] < 1) return null;

  return $decoded_payload;
}

function discard_jwt() {
  $conn = get_db();

  if (!token_exists_locally()) return;
  
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

function token_exists_locally() {
  return isset($_COOKIE['token']);
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
