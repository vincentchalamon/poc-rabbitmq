<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * Entity manager
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Schema tool
     *
     * @var \Doctrine\ORM\Tools\SchemaTool
     */
    protected $schemaTool;

    /**
     * Classes
     *
     * @var array
     */
    protected $classes;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $this->classes = $em->getMetadataFactory()->getAllMetadata();
        $this->em = $em;
    }

    /**
     * @Given I have a foo
     */
    public function iHaveAFoo()
    {
        $foo = new \LesTilleuls\DemoBundle\Entity\Foo();
        $foo->setName('Hello World!');
        $foo->setDescription('Lorem ipsum dolor sit amet');
        $bar = new \LesTilleuls\DemoBundle\Entity\Bar();
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
