<?php
// api/tests/BooksTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;


class JwtTest extends ApiTestCase
{
    /*Test if login path works*/
    public function testLogin(): void
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'test@test0.fr',
            'password' => 'password',
        ]]);

        $this->assertResponseIsSuccessful();
    }

    /*Get a token for a normal user*/
    public function authorizeGetUserBearerToken(): string
    {
        $client = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'test@test0.fr',
            'password' => 'password',
        ]]);
        
        $token=json_decode($client->getContent(),true);

        return $token["token"];
    }

    /*Get a token for the admin user*/
    public function authorizeGetAdminBearerToken(): string
    {
        $client = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'testAdmin@test0.fr',
            'password' => 'password',
        ]]);
        
        $token=json_decode($client->getContent(),true);

        return $token["token"];
    }
}