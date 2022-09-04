<?php

namespace App\Test\Controller;

use App\Entity\Task;
use App\Entity\Category;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TaskRepository $repository;
    private Category $category;
    private string $path = '/task/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Task::class);
        $this->categoryRepository = (static::getContainer()->get('doctrine'))->getRepository(Category::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }

        foreach ($this->categoryRepository->findAll() as $object) {
            $this->categoryRepository->remove($object, true);
        }

        $this->category = new Category();
        $this->category->setCreatedAt(new \DateTimeImmutable());
        $this->category->setUpdatedAt(new \DateTimeImmutable());
        $this->category->setTitle('My Category');
        $this->categoryRepository->add($this->category, true);
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
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
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
        $fixture = new Task();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Task');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%d/edit', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Update', [
            'task[title]' => 'Testing',
        ]);

        self::assertResponseRedirects('/task/');

        $tasks = $this->repository->findAll();

        self::assertSame('Testing', $tasks[0]->getTitle());
    }

    public function testDelete(): void
    {
        $fixture = new Task();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setUpdatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Task');
        $fixture->setCategory($this->category);

        $this->repository->add($fixture, true);

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->client->request('GET', sprintf('%s%d', $this->path, $fixture->getId()));

        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/task/');

        self::assertSame($originalNumObjectsInRepository - 1, count($this->repository->findAll()));
    }
}
