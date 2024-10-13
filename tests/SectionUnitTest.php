<?php

namespace App\Tests\Entity;

use App\Entity\Section;
use App\Entity\Article;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class SectionTest extends TestCase
{
    public function testSectionCanBeCreated()
    {
        $section = new Section();
        $this->assertInstanceOf(Section::class, $section);
    }

    public function testSectionProperties()
    {
        $section = new Section();
        $section->setNom('Nom de la section');

        $this->assertSame('Nom de la section', $section->getNom());
    }

    public function testAddRemoveArticle()
    {
        $section = new Section();
        $this->assertCount(0, $section->getArticle());

        $article = new Article();

        $section->addArticle($article);
        $this->assertCount(1, $section->getArticle());
        $this->assertSame($section, $article->getSection());

        $section->removeArticle($article);
        $this->assertCount(0, $section->getArticle());
        $this->assertNull($article->getSection());
    }

    public function testSectionEditorAssociation()
    {
        $section = new Section();
        $user = new User();

        $section->setSectionEditor($user);
        $this->assertSame($user, $section->getSectionEditor());
    }

    public function testSeniorEditorAssociation()
    {
        $section = new Section();
        $user = new User();

        $section->setSeniorEditor($user);
        $this->assertSame($user, $section->getSeniorEditor());
    }
}

?>