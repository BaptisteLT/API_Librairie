<?php
// api/tests/BooksTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;


class JwtTest extends ApiTestCase
{

    public function testLogin(): void
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'test@test0.fr',
            'password' => 'password',
        ]]);

        $this->assertResponseIsSuccessful();
    }

    public function authorizeGetUserBearerToken(): string
    {
        $client = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'test@test0.fr',
            'password' => 'password',
        ]]);
        //dd(json_decode($client->getContent()));

        $token=json_decode($client->getContent(),true);

        return $token["token"];
    }
}