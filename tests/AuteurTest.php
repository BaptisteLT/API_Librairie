<?php

namespace App\Tests;

use App\Entity\Auteur;
use App\Tests\JwtTest;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class AuteurTest extends JwtTest
{
    public function testGetCollectionUser(): void
    {
        //$stub = $this->createMock(JwtTest::class);
        $token = $this->authorizeGetUserBearerToken();
        
        //dd($token);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/auteurs',[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);

        dd($response);

        //dd($response);

        //$this->assertResponseIsSuccessful();

        $this->assertResponseStatusCodeSame(403);

        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        /*$this->assertJsonContains([
            '@context' => '/contexts/Book',
            '@id' => '/books',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 100,
            'hydra:view' => [
                '@id' => '/books?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/books?page=1',
                'hydra:last' => '/books?page=4',
                'hydra:next' => '/books?page=2',
            ],
        ]);*/

        // Because test fixtures are automatically loaded between each test, you can assert on them
        //$this->assertCount(30, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Auteur::class);
    }
}