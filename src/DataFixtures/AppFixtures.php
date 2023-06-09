<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use App\Entity\Comment;
use App\Entity\Deal;
use App\Entity\User;
use App\Enum\Group;
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
        for ($i = 0; $i < 3; ++$i) {
            $user = new User();

            $user->setEmail($this->faker->email);
            $user->setPseudo($this->faker->userName);
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);

            $manager->persist($user);

            // Générer des Deals pour l'utilisateur
            for ($j = 0; $j < 4; ++$j) {
                $deal = new Deal();
                $deal->setPublicationDatetime(new \DateTime());
                $deal->setExpirationDatetime(new \DateTime());
                $deal->setTitle($this->faker->text(100));
                $deal->setDescription($this->faker->text(255));
                $deal->setFreeDelivery(false);
                $deal->setGroupDeal(Group::HIGHTECH);
                $deal->setAuthor($user);
                $deal->setHotLevel(0);

                $manager->persist($deal);

                // Générer des Comments pour chaque Deal
                for ($k = 0; $k < 3; ++$k) {
                    $comment = new Comment();
                    $comment->setContent($this->faker->text(255));
                    $comment->setDatetime(new \DateTime());
                    $comment->setUtilisateur($user);
                    $comment->setDeal($deal);
                    $manager->persist($comment);
                }
            }
        }

        $badges = [
            'Surveillant' => 'Vous avez voté pour 10 deals',
            'Cobaye' => 'Vous avez posté au moins 10 deals',
            'Rapport de stage' => 'Vous avez posté au moins 10 commentaires',
        ];

        foreach ($badges as $clef => $valeur) {
            $badge = new Badge();
            $badge->setTitle($clef);
            $badge->setDescription($valeur);
            $manager->persist($badge);
        }

        $manager->flush();
    }
}
