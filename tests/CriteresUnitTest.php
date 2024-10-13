<?php

namespace App\Tests\Entity;

use App\Entity\Criteres;
use App\Entity\Grille;
use PHPUnit\Framework\TestCase;

class CriteresTest extends TestCase
{
    public function testCriteresCanBeCreated()
    {
        $criteres = new Criteres();
        $this->assertInstanceOf(Criteres::class, $criteres);
    }

    public function testCriteresProperties()
    {
        $criteres = new Criteres();
        $criteres->setIntitule('Intitulé du critère')
            ->setCoefficient(5);

        $this->assertSame('Intitulé du critère', $criteres->getIntitule());
        $this->assertSame(5, $criteres->getCoefficient());
    }

    public function testGrilleAssociation()
    {
        $criteres = new Criteres();
        $grille = $this->createMock(Grille::class);

        $criteres->setGrille($grille);
        $this->assertSame($grille, $criteres->getGrille());
    }

    public function testCriteresIdIsNullByDefault()
    {
        $criteres = new Criteres();
        $this->assertNull($criteres->getId());
    }

    public function testIntituleIsNullByDefault()
    {
        $criteres = new Criteres();
        $this->assertNull($criteres->getIntitule());
    }

    public function testCoefficientIsNullByDefault()
    {
        $criteres = new Criteres();
        $this->assertNull($criteres->getCoefficient());
    }
}

?>