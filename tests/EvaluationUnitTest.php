<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Evaluation;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Grille;

class EvaluationUnitTest extends TestCase
{
    public function testGetId()
    {
        $evaluation = new Evaluation();
        $this->assertNull($evaluation->getId());
    }

    public function testGetSetNotes()
    {
        $evaluation = new Evaluation();
        $notes = [4, 5, 3];
        $evaluation->setNotes($notes);
        $this->assertEquals($notes, $evaluation->getNotes());
    }

    public function testGetSetMoyenne()
    {
        $evaluation = new Evaluation();
        $evaluation->setMoyenne(4.5);
        $this->assertEquals(4.5, $evaluation->getMoyenne());
    }

    public function testGetSetArticle()
    {
        $evaluation = new Evaluation();
        $article = new Article();
        $evaluation->setArticle($article);
        $this->assertSame($article, $evaluation->getArticle());
    }

    public function testGetSetUser()
    {
        $evaluation = new Evaluation();
        $user = new User();
        $evaluation->setUser($user);
        $this->assertSame($user, $evaluation->getUser());
    }

    public function testGetSetGrille()
    {
        $evaluation = new Evaluation();
        $grille = new Grille();
        $evaluation->setGrille($grille);
        $this->assertSame($grille, $evaluation->getGrille());
    }
}
?>