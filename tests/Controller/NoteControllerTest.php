<?php

namespace App\Test\Controller;

use App\Entity\Note;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class NoteControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    private KernelBrowser $client;
    private NoteRepository $repository;
    private Category $category;
    private string $path = '/note/';
    private User $user;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Note::class);
        $this->categoryRepository = (static::getContainer()->get('doctrine'))->getRepository(Category::class);

        $this->category = new Category();
        $this->category->setCreatedAt(new \DateTimeImmutable());
        $this->category->setUpdatedAt(new \DateTimeImmutable());
        $this->category->setTitle('My Category');
        $this->categoryRepository->add($this->category, true);

        $user = new User();
        $user->setEmail('admin@localhost');
        $user->setPassword('admin');
        $userRepository = (static::getContainer()->get('doctrine'))->getRepository(User::class);
        $userRepository->add($user, true);
        $this->user = $user;
    }

    public function testIndex(): void
    {
        $this->client->request('GET', $this->path);

        $fixture = new Note();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Note');
        $fixture->setContent('My Note Content');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains("table", "My Note");
    }

    public function testNew(): void
    {
        $this->client->loginUser($this->user);

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('submit', [
            'note[title]' => 'Testing',
            'note[content]' => 'Testing',
            'note[category]' => $this->category->getId(),
        ]);

        self::assertResponseRedirects('/note/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Note();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Note');
        $fixture->setContent('My Note Content');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%d', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains("table", "My Note");
    }

    public function testEdit(): void
    {
        $this->client->loginUser($this->user);

        $fixture = new Note();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Note');
        $fixture->setContent('My Note Content');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%d/edit', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('submit', [
            'note[title]' => 'Testing',
            'note[content]' => 'Testing',
        ]);

        self::assertResponseRedirects('/note/');

        $notes = $this->repository->findAll();

        self::assertSame('Testing', $notes[0]->getTitle());
    }

    public function testDelete(): void
    {
        $this->client->loginUser($this->user);

        $fixture = new Note();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Note');
        $fixture->setContent('My Note Content');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%s%s/delete', $this->path, $fixture->getId()));
        $this->client->submitForm('submit');

        self::assertResponseRedirects('/note/');

        self::assertSame($originalNumObjectsInRepository - 1, count($this->repository->findAll()));
    }
}
