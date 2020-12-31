<?php

namespace App\Tests;

use App\Entity\Auteur;
use App\Tests\JwtTest;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class AuteurTest extends JwtTest
{
    /*Teste la collection Auteur avec un Utilisateur*/
    public function testGetCollectionForUser(): void
    {
        //get the USER token;
        $token = $this->authorizeGetUserBearerToken();
        
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/auteurs',[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        //Is auteur Resource
        $this->assertMatchesResourceCollectionJsonSchema(Auteur::class);

        //Not the permission
        $this->assertResponseStatusCodeSame(403);
    }


    /*Teste la collection Auteur avec un Administrateur*/
    public function testGetCollectionForAdmin(): void
    {
        //Get the ADMIN Token
        $token = $this->authorizeGetAdminBearerToken();

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/auteurs?order[id]=ASC&order[prenom]=ASC&order[nom]=ASC&order[dateNaissance]=ASC&livre.titre=Titre du livre&page=2',[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        /*20 Items on the page*/
        $this->assertCount(20, $response->toArray()['hydra:member']);
        /*50 Items in total*/
        $this->assertEquals(50, $response->toArray()['hydra:totalItems']);
        //Is auteur Resource
        $this->assertMatchesResourceCollectionJsonSchema(Auteur::class);

        //Response OK
        $this->assertResponseStatusCodeSame(200);
    }




    /*Teste la collection Auteur avec un Utilisateur*/
    public function testGetItemForUser(): void
    {
        //get the USER token;
        $token = $this->authorizeGetUserBearerToken();
            
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/auteurs/1',[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        //Is auteur Resource
        $this->assertMatchesResourceCollectionJsonSchema(Auteur::class);
    
        //Not the permission
        $this->assertResponseStatusCodeSame(403);
    }

    /*Teste la collection Auteur avec un Utilisateur*/
    public function testGetItemForAdmin(): void
    {
        //get the ADMIN token;
        $token = $this->authorizeGetAdminBearerToken();
                    
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/auteurs/1',[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);

        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        //Is auteur Resource
        $this->assertMatchesResourceCollectionJsonSchema(Auteur::class);

        //Test if the Auteur object has an "id" field
        $this->assertArrayHasKey('id', $response->toArray(),"Array doesn't contains 'id' as key");
        //Test if the Auteur object has a "nom" field
        $this->assertArrayHasKey('nom', $response->toArray(),"Array doesn't contains 'nom' as key");
        //Test if the Auteur object has a "prenom" field
        $this->assertArrayHasKey('prenom', $response->toArray(),"Array doesn't contains 'prenom' as key");
        //Test if the Auteur object has a "dateNaissance" field
        $this->assertArrayHasKey('dateNaissance', $response->toArray(),"Array doesn't contains 'dateNaissance' as key");
        //Test if the Auteur object has a "livre" field
        $this->assertArrayHasKey('livre', $response->toArray(),"Array doesn't contains 'livre' as key");

        //Not the permission
        $this->assertResponseStatusCodeSame(200);
    }





    //Test creating an Auteur with a normal User
    public function testCreateAuteurForUser(): void
    {
        //get the USER token;
        $token = $this->authorizeGetUserBearerToken();
                  
        $response = static::createClient()->request('POST', '/api/auteurs',[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => [
            'nom' => 'Toto',
            'prenom' => 'TotoPrenom',
            'dateNaissance' => '2020-12-19'
        ]]);
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        //Is auteur Resource
        $this->assertMatchesResourceCollectionJsonSchema(Auteur::class);
            
        //Not the permission
        $this->assertResponseStatusCodeSame(403);
    }

    
    //Test creating an Auteur with a normal Admin
    public function testCreateAuteurForAdmin(): void
    {
        //get the ADMIN token;
        $token = $this->authorizeGetAdminBearerToken();
                  
        $response = static::createClient()->request('POST', '/api/auteurs',[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => [
            'nom' => 'Toto',
            'prenom' => 'TotoPrenom',
            'dateNaissance' => '2020-12-19'
        ]]);

        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        //Verify if Json in response contains this
        $this->assertJsonContains([
            '@context' => '/api/contexts/Auteur',
            '@type' => 'Auteur',
            'nom' => 'Toto',
            'prenom' => 'TotoPrenom',
            'dateNaissance' => '2020-12-19T00:00:00+00:00',
            'livre' => [],
        ]);

        //Verify if the pattern is correct
        $this->assertRegExp('~^/api/auteurs/\d+$~', $response->toArray()['@id']);
        //Is auteur Resource
        $this->assertMatchesResourceItemJsonSchema(Auteur::class);
        //201 Created
        $this->assertResponseStatusCodeSame(201);
    }

    
    //Test creating an Auteur with a normal Admin
    public function testFailCreateAuteurForAdmin(): void
    {
        //get the ADMIN token;
        $token = $this->authorizeGetAdminBearerToken();
                
        $response = static::createClient()->request('POST', '/api/auteurs',[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => [

        ]]);

        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
            
        //Verify if Json in response contains this
        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',/*
            'hydra:description' => 'nom: This value should not be blank.
prenom: This value should not be blank.
dateNaissance: This value should not be blank.'*/
            ]);

        //Is auteur Resource
        $this->assertMatchesResourceItemJsonSchema(Auteur::class);

        //400 Bad Request
        $this->assertResponseStatusCodeSame(400);

    }




    //Test delete for USER
    public function testDeleteAuteurForUser(): void
    {
        //get the USER token;
        $token = $this->authorizeGetUserBearerToken();

        $client = static::createClient();

        $client->request('DELETE', '/api/auteurs/51',[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]   
        ]);

        //Forbidden 403
        $this->assertResponseStatusCodeSame(403);
    }


    //Test delete for ADMIN
    public function testDeleteAuteurForAdmin(): void
    {
        //get the ADMIN token;
        $token = $this->authorizeGetAdminBearerToken();

        $client = static::createClient();
        //Find auteur 51
        $iri = $this->findIriBy(Auteur::class, ['id' => '51']);

        //Methode DELETE
        $client->request('DELETE', $iri,[
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ]   
        ]);
        //Deleted
        $this->assertResponseStatusCodeSame(204);
        //Has been deleted
        $this->assertNull(
            // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
            static::$container->get('doctrine')->getRepository(Auteur::class)->findOneBy(['id' => '51'])
        );
    }


    //Update Auteur with a normal user
    public function testUpdateAuteurForUser(): void
    {
        //get USER token
        $token = $this->authorizeGetUserBearerToken();

        $client = static::createClient();

        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        $iri = $this->findIriBy(Auteur::class, ['id' => '51']);

        $client->request('PUT', $iri,[
        'headers' =>
        [
            'Authorization' => 'Bearer ' . $token,
        ],
        'json' => 
        [
            'nom' => 'Tata',
        ]]);
        //Forbidden 403
        $this->assertResponseStatusCodeSame(403);
    }

    //Update auteur with Admin
    public function testUpdateAuteurForAdmin(): void
    {
        //get ADMIN token
        $token = $this->authorizeGetAdminBearerToken();

        $client = static::createClient();

        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        $iri = $this->findIriBy(Auteur::class, ['id' => '51']);

        $client->request('PUT', $iri,[
        'headers' =>
        [
            'Authorization' => 'Bearer ' . $token,
        ],
        'json' => 
        [
            'nom' => 'Tata',
        ]]);
        //Created
        $this->assertResponseIsSuccessful();
        
        //Response contains the updated field nom 'Tata', with the correct Iri
        $this->assertJsonContains([
            '@id' => $iri,
            'nom' => 'Tata'
        ]);
    }
}