<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Grille;
use App\Entity\Evaluation;
use App\Entity\Criteres;

class GrilleUnitTest extends TestCase
{
    public function testGetId()
    {
        $grille = new Grille();
        $this->assertNull($grille->getId());
    }

    public function testGetSetAnnee()
    {
        $grille = new Grille();
        $grille->setAnnee(2024);
        $this->assertEquals(2024, $grille->getAnnee());
    }

    public function testAddRemoveEvaluation()
    {
        $grille = new Grille();
        $evaluation = new Evaluation();

        $grille->addEvaluation($evaluation);
        $this->assertTrue($grille->getEvaluations()->contains($evaluation));

        $grille->removeEvaluation($evaluation);
        $this->assertFalse($grille->getEvaluations()->contains($evaluation));
    }

    public function testAddRemoveCritere()
    {
        $grille = new Grille();
        $critere = new Criteres();

        $grille->addCritere($critere);
        $this->assertTrue($grille->getCriteres()->contains($critere));

        $grille->removeCritere($critere);
        $this->assertFalse($grille->getCriteres()->contains($critere));
    }

    public function testAddRemoveEvaluationOwnership()
    {
        $grille = new Grille();
        $evaluation = new Evaluation();

        $grille->addEvaluation($evaluation);
        $this->assertSame($grille, $evaluation->getGrille());

        $grille->removeEvaluation($evaluation);
        $this->assertNull($evaluation->getGrille());
    }

    public function testAddRemoveCritereOwnership()
    {
        $grille = new Grille();
        $critere = new Criteres();

        $grille->addCritere($critere);
        $this->assertSame($grille, $critere->getGrille());

        $grille->removeCritere($critere);
        $this->assertNull($critere->getGrille());
    }
}

?>