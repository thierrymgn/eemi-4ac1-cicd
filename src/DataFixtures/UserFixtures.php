<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
	private UserPasswordHasherInterface $passwordHasher;

	public function __construct(UserPasswordHasherInterface $passwordHasher)
	{
		$this->passwordHasher = $passwordHasher;
	}

	public function load(ObjectManager $manager): void
	{
		$user = new User();
		$user->setEmail($_ENV['AUTH_USER_EMAIL']);
		$user->setPassword($this->passwordHasher->hashPassword($user, $_ENV['AUTH_USER_PASS']));

		$manager->persist($user);
		$manager->flush();
	}
}
