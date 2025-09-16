<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSetAndGetEmail(): void
    {
        $user = new User();
        $email = "test@example.com";

        $user->setEmail($email);

        $this->assertSame($email, $user->getEmail());
        $this->assertSame($email, $user->getUserIdentifier());
    }

    public function testSetAndGetPassword(): void
    {
        $user = new User();
        $password = "Password123";

        $user->setPassword($password);

        $this->assertSame($password, $user->getPassword());
    }

    public function testDefaultRolesContainsUser(): void
    {
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());
    }

    public function testSetRoles(): void
    {
        $user = new User();
        $roles = ['ROLE_ADMIN'];

        $user->setRoles($roles);

        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles(), "ROLE_USER doit toujours être présent.");
    }
}
