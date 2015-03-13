<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use LesTilleuls\DemoBundle\Entity\Foo;
use LesTilleuls\DemoBundle\Entity\Bar;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * Entity manager
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * Schema tool
     *
     * @var SchemaTool
     */
    protected $schemaTool;

    /**
     * Classes
     *
     * @var array
     */
    protected $classes;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->schemaTool = new SchemaTool($em);
        $this->classes = $em->getMetadataFactory()->getAllMetadata();
        $this->em = $em;
    }

    /**
     * @Given I have a foo
     */
    public function iHaveAFoo()
    {
        $foo = new Foo();
        $foo->setName('Hello World!');
        $foo->setDescription('Lorem ipsum dolor sit amet');
        $bar = new Bar();
        $bar->setName('Test');
        $foo->setBar($bar);
        $this->em->persist($foo);
        $this->em->flush();
    }

    /**
     * @BeforeScenario @createSchema
     */
    public function createDatabase()
    {
        $this->schemaTool->createSchema($this->classes);
    }

    /**
     * @AfterScenario @dropSchema
     */
    public function dropDatabase()
    {
        $this->schemaTool->dropSchema($this->classes);
    }
}
