<?php

/**
 * Tag controller test.
 */

namespace App\Test\Controller;

use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tag Controller test.
 */
class TagControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    private KernelBrowser $client;
    private TagRepository $repository;
    private string $path = '/tag/';
    private User $user;

    /**
     * Set up function.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Tag::class);

        $user = new User();
        $user->setEmail('admin@localhost');
        $user->setPassword('admin');
        $userRepository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $userRepository->add($user, true);
        $this->user = $user;
    }

    /**
     * Test index function.
     */
    public function testIndex(): void
    {
        $this->client->request('GET', $this->path);

        $fixture = new Tag();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Tag');

        $this->repository->add($fixture, true);

        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('table', 'My Tag');
    }

    /**
     * Test new function.
     */
    public function testNew(): void
    {
        $this->client->loginUser($this->user);

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('submit', [
            'tag[title]' => 'Testing',
        ]);

        self::assertResponseRedirects('/tag/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    /**
     * Test show function.
     */
    public function testShow(): void
    {
        $fixture = new Tag();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Tag');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('table', 'My Tag');
    }

    /**
     * Test edit function.
     */
    public function testEdit(): void
    {
        $this->client->loginUser($this->user);

        $fixture = new Tag();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Tag');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('submit', [
            'tag[title]' => 'My New Tag',
        ]);

        self::assertResponseRedirects('/tag/');

        $fixture = $this->repository->findAll();

        self::assertSame('My New Tag', $fixture[0]->getTitle());
    }

    /**
     * Test remove function.
     */
    public function testRemove(): void
    {
        $this->client->loginUser($this->user);

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Tag();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Tag');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s/delete', $this->path, $fixture->getId()));
        $this->client->submitForm('submit');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/tag/');
    }
}
