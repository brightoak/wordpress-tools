<?php

namespace Brightoak\WordPressTools\Tests;

use Brightoak\WordPressTools\CustomPostType;
use Brightoak\WordPressTools\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CustomPostTypeTest extends TestCase
{
    /** @test */
    public function it_returns_an_instance_of_customposttype()
    {
        $postType = CustomPostType::init('sample');
        $this->assertInstanceOf(CustomPostType::class, $postType);
    }

    /** @test */
    public function it_throws_an_exception_if_the_post_name_is_too_long()
    {
        try {
            $postType = CustomPostType::init('thisislongerthantwentycharactersandshouldnotwork');
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    /** @test */
    public function it_allows_you_to_set_supported_elements()
    {
        $postType = CustomPostType::init('example')->setSupports('title', 'editor');
        $this->assertContains('title', $postType->getSupports());
        $this->assertContains('editor', $postType->getSupports());
    }

    /** @test */
    public function it_allows_you_to_set_taxonomies()
    {
        $postType = CustomPostType::init('example')->setTaxonomies('category', 'custom');
        $this->assertContains('category', $postType->getTaxonomies());
        $this->assertContains('custom', $postType->getTaxonomies());
    }

    /** @test */
    public function it_allows_you_to_set_options_in_the_constructor()
    {
        $postType = CustomPostType::init('example', ['show_in_rest' => 1])->setTaxonomies('category', 'custom');
        $options = $postType->getOptions();
        $this->assertEquals(1, $options['show_in_rest']);
    }

    /** @test */
    public function it_gets_an_array_of_all_the_args()
    {
        [$name, $args] = CustomPostType::init('example')->setSupports('title')->setTaxonomies('post_tags', 'categories')->register();
        $this->assertArrayHasKey('labels', $args);
        $this->assertArrayHasKey('supports', $args);
        $this->assertArrayHasKey('taxonomies', $args);

    }
}
