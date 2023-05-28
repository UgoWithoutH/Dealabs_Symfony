<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();

            $user->setEmail($this->faker->email);
            $user->setPseudo($this->faker->userName);
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);

            $manager->persist($user);

            // Générer des Deals pour l'utilisateur
            for ($j = 0; $j < 5; ++$j) {
                $deal = new Deal();
                $deal->setTitle($this->faker->sentence);
                // Définir les autres attributs du Deal

                $deal->setAuthor($user);
                $manager->persist($deal);

                // Générer des Comments pour chaque Deal
                for ($k = 0; $k < 3; ++$k) {
                    $comment = new Comment();
                    $comment->setContent($this->faker->paragraph);
                    // Définir les autres attributs du Comment

                    $comment->setUtilisateur($user);
                    $comment->setDeal($deal);
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
