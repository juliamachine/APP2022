<?php

namespace App\Test\Controller;

use App\Entity\Task;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class TaskControllerTest extends WebTestCase
{
    use ReloadDatabaseTrait;

    private KernelBrowser $client;
    private TaskRepository $repository;
    private Category $category;
    private string $path = '/task/';
    private User $user;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Task::class);
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

        $fixture = new Task();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Task');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains("table", "My Task");
    }

    public function testNew(): void
    {
        $this->client->loginUser($this->user);

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('submit', [
            'task[title]' => 'Testing',
            'task[category]' => $this->category->getId(),
        ]);

        self::assertResponseRedirects('/task/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $fixture = new Task();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Task');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%d', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains("body", "My Task");
    }

    public function testEdit(): void
    {
        $this->client->loginUser($this->user);

        $fixture = new Task();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Task');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%d/edit', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('submit', [
            'task[title]' => 'Testing',
        ]);

        self::assertResponseRedirects('/task/');

        $tasks = $this->repository->findAll();

        self::assertSame('Testing', $tasks[0]->getTitle());
    }

    public function testDelete(): void
    {
        $this->client->loginUser($this->user);

        $fixture = new Task();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Task');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%s%d', $this->path, $fixture->getId()));

        $this->client->submitForm('delete');

        self::assertResponseRedirects('/task/');

        self::assertSame($originalNumObjectsInRepository - 1, count($this->repository->findAll()));
    }
}
