<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Evaluation;
use App\Entity\Section;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    public function testUserCanBeCreated()
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
    }

    public function testUserProperties()
    {
        $user = new User();
        $user->setEmail('test@example.com')
            ->setPassword('password')
            ->setNom('Doe')
            ->setPrenom('John')
            ->setNumTel('123456789')
            ->setLaboratoire('Lab1')
            ->setEtat(true)
            ->setConfirmationToken('token123');

        $this->assertSame('test@example.com', $user->getEmail());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame('Doe', $user->getNom());
        $this->assertSame('John', $user->getPrenom());
        $this->assertSame('123456789', $user->getNumTel());
        $this->assertSame('Lab1', $user->getLaboratoire());
        $this->assertTrue($user->isEtat());
        $this->assertSame('token123', $user->getConfirmationToken());
    }

    public function testUserRoles()
    {
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function testAddRemoveEvaluation()
    {
        $user = new User();
        $this->assertCount(0, $user->getEvaluations());

        $evaluation = $this->createMock(Evaluation::class);

        $user->addEvaluation($evaluation);
        $this->assertCount(1, $user->getEvaluations());

        $user->removeEvaluation($evaluation);
        $this->assertCount(0, $user->getEvaluations());
    }

    public function testLifecycleCallbacks()
    {
        $user = new User();
        $this->assertNull($user->isEtat());

        $user->setCreatedAtValue();
        $this->assertFalse($user->isEtat());

        $user->initializeConfirmationToken();
        $this->assertNotNull($user->getConfirmationToken());
    }

    public function testMySection()
    {
        $user = new User();
        $section = $this->createMock(Section::class);

        $user->setMySection($section);
        $this->assertSame($section, $user->getMySection());

        $user->setMySection(null);
        $this->assertNull($user->getMySection());
    }

    public function testMySenEdsections()
    {

        $user = new User();

        $section = $this->createMock(Section::class);

        $section->method('getSeniorEditor')->willReturn($user);

        $user->addMySenEdsection($section);
        $this->assertCount(1, $user->getMySenEdsections());
        $this->assertSame($user, $section->getSeniorEditor());

        $user->removeMySenEdsection($section);
        $section->method('getSeniorEditor')->willReturn(null);
        $this->assertCount(0, $user->getMySenEdsections());
    }

    public function testGetUserIdentifier()
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $this->assertSame('test@example.com', $user->getUserIdentifier());
    }

}

?>