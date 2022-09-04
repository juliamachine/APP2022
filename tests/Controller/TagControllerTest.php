<?php

namespace App\Test\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TagRepository $repository;
    private string $path = '/tag/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Tag::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

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
        self::assertSelectorTextContains("table", "My Tag");
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'tag[title]' => 'Testing',
        ]);

        self::assertResponseRedirects('/tag/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Tag();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Tag');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains("table", "My Tag");
    }

    public function testEdit(): void
    {
        $fixture = new Tag();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Tag');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'tag[title]' => 'My New Tag',
        ]);

        self::assertResponseRedirects('/tag/');

        $fixture = $this->repository->findAll();

        self::assertSame('My New Tag', $fixture[0]->getTitle());
    }

    public function testRemove(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Tag();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Tag');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/tag/');
    }
}
