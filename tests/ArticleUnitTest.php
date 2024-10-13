<?php

namespace App\Tests\Entity;

use App\Entity\Article;
use PHPUnit\Framework\TestCase;

class ArticleUnitTest extends TestCase
{
    public function testArticleCanBeCreated()
    {
        $article = new Article();
        $this->assertInstanceOf(Article::class, $article);
    }

    public function testArticleProperties()
    {
        $article = new Article();
        $article->setPmid(123456)
            ->setDoi('doi123')
            ->setTitre('Titre de l\'article')
            ->setAuteurPrincipale('Auteur Principal')
            ->setAuteurs('Auteur1, Auteur2')
            ->setAffiliation('Affiliation')
            ->setPdfFileName('article.pdf');

        $this->assertSame(123456, $article->getPmid());
        $this->assertSame('doi123', $article->getDoi());
        $this->assertSame('Titre de l\'article', $article->getTitre());
        $this->assertSame('Auteur Principal', $article->getAuteurPrincipale());
        $this->assertSame('Auteur1, Auteur2', $article->getAuteurs());
        $this->assertSame('Affiliation', $article->getAffiliation());
        $this->assertSame('article.pdf', $article->getPdfFileName());
    }

    public function testSectionAssociation()
    {
        $article = new Article();
        $section = $this->createMock('App\Entity\Section');

        $article->setSection($section);
        $this->assertSame($section, $article->getSection());
    }

    public function testAddRemoveEvaluation()
    {
        $article = new Article();
        $this->assertCount(0, $article->getEvaluations());

        $evaluation = $this->createMock('App\Entity\Evaluation');

        $article->addEvaluation($evaluation);
        $this->assertCount(1, $article->getEvaluations());

        $article->removeEvaluation($evaluation);
        $this->assertCount(0, $article->getEvaluations());
    }

    public function testEvaluationsAssociation()
    {
        $article = new Article();
        $evaluation1 = $this->createMock('App\Entity\Evaluation');
        $evaluation2 = $this->createMock('App\Entity\Evaluation');

        $article->addEvaluation($evaluation1);
        $article->addEvaluation($evaluation2);

        $evaluations = $article->getEvaluations();

        $this->assertCount(2, $evaluations);
        $this->assertContains($evaluation1, $evaluations);
        $this->assertContains($evaluation2, $evaluations);

        $article->removeEvaluation($evaluation1);

        $this->assertCount(1, $article->getEvaluations());
        $this->assertNotContains($evaluation1, $article->getEvaluations());
        $this->assertContains($evaluation2, $article->getEvaluations());
    }

    public function testPmidIsNullByDefault()
    {
        $article = new Article();
        $this->assertNull($article->getPmid());
    }

    public function testDoiIsNullByDefault()
    {
        $article = new Article();
        $this->assertNull($article->getDoi());
    }

    public function testPdfFileNameIsNullByDefault()
    {
        $article = new Article();
        $this->assertNull($article->getPdfFileName());
    }

    public function testArticleIdIsNullByDefault()
    {
        $article = new Article();
        $this->assertNull($article->getId());
    }

    public function testArticleCanHavePmid()
    {
        $article = new Article();
        $article->setPmid(123456);
        $this->assertSame(123456, $article->getPmid());
    }

    public function testArticleCanHaveDoi()
    {
        $article = new Article();
        $article->setDoi('doi123');
        $this->assertSame('doi123', $article->getDoi());
    }

    public function testArticleCanHavePdfFileName()
    {
        $article = new Article();
        $article->setPdfFileName('article.pdf');
        $this->assertSame('article.pdf', $article->getPdfFileName());
    }
}

?>