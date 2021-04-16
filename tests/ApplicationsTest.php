<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Application;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApplicationsTest extends ApiTestCase
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
        $response = static::createClient()->request('GET', '/api/applications');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains(
            [
                '@context' => '/api/contexts/Application',
                '@id' => '/api/applications',
                '@type' => 'hydra:Collection',
                'hydra:totalItems' => 25,
                'hydra:view' => [
                    '@id' => '/api/applications?page=1',
                    '@type' => 'hydra:PartialCollectionView',
                    'hydra:first' => '/api/applications?page=1',
                    'hydra:last' => '/api/applications?page=4',
                    'hydra:next' => '/api/applications?page=2',
                ],
            ]
        );

        self::assertCount(30, $response->toArray()['hydra:member']);
        self::assertMatchesResourceCollectionJsonSchema(Application::class);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateApplication(): void
    {
        $response = static::createClient()->request(
            'POST',
            '/api/applications',
            [
                'json' => [
                    'clientId' => '/api/clients/320',
                    'term' => 10,
                    'amount' => 100.00,
                    'currency' => 'USD',
                ]
            ]
        );

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains(
            [
                '@context' => '/api/contexts/Application',
                '@type' => 'Application',
                'clientId' => '/api/clients/320',
                'term' => 10,
                'amount' => 100.00,
                'currency' => 'USD',
            ]
        );
        self::assertRegExp('~^/api/applications/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Application::class);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateInvalidApplication(): void
    {
        static::createClient()->request(
            'POST',
            '/api/applications',
            [
                'json' => [
                    'clientId' => '/api/clients/0',
                    'term' => 0,
                    'amount' => 0,
                    'currency' => 0,
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
                'hydra:description' => 'clientId: This value should not be blank.
                                        term: This value should not be blank.
                                        amount: This value should not be blank.
                                        currency: This value should not be null.',
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
    public function testUpdateApplication(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Application::class, ['id' => 320]);

        $client->request(
            'PUT',
            $iri,
            [
                'json' => [
                    'currency' => 'RUB',
                ]
            ]
        );

        self::assertResponseIsSuccessful();
        self::assertJsonContains(
            [
                '@id' => $iri,
                'currency' => 'RUB',
            ]
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteApplication(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Application::class, ['id' => 320]);

        $client->request('DELETE', $iri);

        self::assertResponseStatusCodeSame(204);
        self::assertNull(
            static::$container->get('doctrine')->getRepository(Application::class)->findOneBy(['id' => 320])
        );
    }
}