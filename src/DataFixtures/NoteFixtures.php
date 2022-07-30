<?php
/**
 * Note fixtures.
 */

namespace App\DataFixtures;

namespace App\DataFixtures;

use App\Entity\Note;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class NoteFixtures.
 */
class NoteFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $note = new Note();
            $note->setTitle($this->faker->sentence);
            $note->setContent($this->faker->sentence);
            $note->setCreatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $note->setUpdatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $this->manager->persist($note);
        }

        $this->manager->flush();
    }
}