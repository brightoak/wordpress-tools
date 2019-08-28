<?php

namespace Brightoak\WordPressTools\Tests;

use PHPUnit\Framework\TestCase;
use Brightoak\WordPressTools\CustomTaxonomy;
use Brightoak\WordPressTools\Exceptions\InvalidArgumentException;

class CustomTaxonomyTest extends TestCase
{
    /** @test */
    public function it_returns_an_instance_of_custom_taxonomy()
    {
        $taxonomy = CustomTaxonomy::init('sample');
        $this->assertInstanceOf(CustomTaxonomy::class, $taxonomy);
    }

    /** @test */
    public function it_throws_an_exception_if_the_post_name_is_too_long()
    {
        try {
            $taxonomy = CustomTaxonomy::init('thisislongerthantwentycharactersandshouldnotwork');
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    /** @test */
    public function it_allows_you_to_set_supported_object_types()
    {
        $taxonomy = CustomTaxonomy::init('example')->setObjectTypes('category', 'custom');
        $this->assertContains('category', $taxonomy->getObjectTypes());
        $this->assertContains('custom', $taxonomy->getObjectTypes());
    }
}
