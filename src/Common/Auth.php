<?php

namespace Hp\LearningRoute\Common;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\KEY;
use Firebase\JWT\SignatureInvalidException;

class Auth
{
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = $_ENV['SECRET_KEY'];
    }

    /**
     * Generate a JWT for the given user.
     *
     * @param string $userId The user's ID.
     * @param array $data Additional data to include in the JWT (optional).
     *
     * @return string The JWT.
     */
    public function generate($userId, $data = [])
    {
        $now = strtotime("now");
        // Create a JWT with the user's information
        return JWT::encode(array(
            'sub' => $userId,
            'iat' => $now,
            "nbf" => $now,
            'exp' => $now + (60 * 60) * 24 * 3, // Set the token to expire in 3 days
            'data' => $data,
        ), $this->secretKey, 'HS256',);
    }

    /**
     * Verify a JWT and extract the user information.
     *
     * @param string $jwt The JWT to verify.
     *
     * @return object|null The decoded JWT, or null if the JWT is invalid.
     */
    public function verify($auth)
    {
        try {
            if (!isset($auth['authorization'])) {
                Functions::abort("Authorization token not found", SC401);
            }

            $bearer = explode(' ', $auth['authorization']);

            //Check if the authorization exit
            if (!isset($bearer[1])) {
                Functions::abort("Authorization token not found", SC401);
            }

            $jwt = $bearer[1];

            if (!$jwt) {
                // No token was able to be extracted from the authorization header
                Functions::abort("Unauthorized user", SC401);
            }

            // Verify the JWT and extract the user information
            $decode = JWT::decode($jwt, new KEY($this->secretKey, 'HS256'));

            if (!$decode || !$decode->sub) {
                Functions::abort("Invalid authorization token", SC401);
            }
            return $decode->sub;
        } catch (\PDOException $e) {
            Functions::abort("Unauthorized user", SC401);
        } catch (\DomainException $e) {
            Functions::abort("Invalid auhorization token", SC401);
        } catch (SignatureInvalidException $e) {
            Functions::abort("Invalid auhorization token", SC401);
        } catch (ExpiredException $e) {
            Functions::abort("Expired token", SC401);
        } catch (BeforeValidException $e) {
            Functions::abort("Expired token", SC401);
        }
    }
}
