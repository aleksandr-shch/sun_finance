<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Client;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ClientsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/clients');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains(
            [
                '@context' => '/api/contexts/Client',
                '@id' => '/api/clients',
                '@type' => 'hydra:Collection',
                'hydra:totalItems' => 25,
                'hydra:view' => [
                    '@id' => '/api/clients?page=1',
                    '@type' => 'hydra:PartialCollectionView',
                    'hydra:first' => '/api/clients?page=1',
                    'hydra:last' => '/api/clients?page=4',
                    'hydra:next' => '/api/clients?page=2',
                ],
            ]
        );

        self::assertCount(30, $response->toArray()['hydra:member']);
        self::assertMatchesResourceCollectionJsonSchema(Client::class);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateClient(): void
    {
        $response = static::createClient()->request(
            'POST',
            '/api/clients',
            [
                'json' => [
                    'firstName' => '',
                    'lastName' => '',
                    'email' => '',
                    'phoneNumber' => '',
                ]
            ]
        );

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains(
            [
                '@context' => '/api/contexts/Client',
                '@type' => 'Client',
                'firstName' => '',
                'lastName' => '',
                'email' => '',
                'phoneNumber' => '',
            ]
        );
        self::assertRegExp('~^/api/clients/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Client::class);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateInvalidClient(): void
    {
        static::createClient()->request(
            'POST',
            '/api/clients',
            [
                'json' => [
                    'firstName' => '',
                    'lastName' => '',
                    'email' => '',
                    'phoneNumber' => '',
                ]
            ]
        );

        self::assertResponseStatusCodeSame(422);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains(
            [
                '@context' => '/api/contexts/ConstraintViolationList',
                '@type' => 'ConstraintViolationList',
                'hydra:title' => 'An error occurred',
                'hydra:description' => 'firstName: This value should not be blank.
                                        lastName: This value should not be blank.
                                        email: This value should not be blank.
                                        phoneNumber: This value should not be null.',
            ]
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testUpdateClient(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Client::class, ['id' => 320]);

        $client->request(
            'PUT',
            $iri,
            [
                'json' => [
                    'firstName' => 'updated name',
                ]
            ]
        );

        self::assertResponseIsSuccessful();
        self::assertJsonContains(
            [
                '@id' => $iri,
                'firstName' => 'updated name',
            ]
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteClient(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Client::class, ['id' => 320]);

        $client->request('DELETE', $iri);

        self::assertResponseStatusCodeSame(204);
        self::assertNull(
            static::$container->get('doctrine')->getRepository(Client::class)->findOneBy(['id' => 320])
        );
    }
}