<?php

namespace Brightoak\WordPressTools;

use Illuminate\Support\Str;
use Brightoak\WordPressTools\Exceptions\InvalidArgumentException;

class CustomPostType
{
    /**
     * @var string
     */
    protected $postType;
    /**
     * @var Str
     */
    protected $stringHelper;

    protected $taxonomies = [];

    protected $supports = [];

    protected $options = [
        'public' => true,
        'show_ui' => true,
    ];

    protected $labels = [];

    protected $singularLabel = null;

    private function __construct(string $name)
    {
        $this->stringHelper = new Str;
        $this->postType = $this->stringHelper::singular($name);
        $this->calculateLabels($name);
    }

    public static function init(string $name)
    {
        if (strlen($name) >= 1 && strlen($name) < 20) {
            return new self($name);
        }
        throw new InvalidArgumentException('A Custom Post type name must be between 1 and 20 characters.');
    }

    public function setTaxonomies(...$taxonomies)
    {
        $this->taxonomies = $taxonomies;

        return $this;
    }

    public function setSingularLabel(string $value)
    {
        $this->singularLabel = $value;

        return $this;
    }

    protected function getSingularLabel()
    {
        return $this->singularLabel;
    }

    public function getTaxonomies()
    {
        return $this->taxonomies;
    }

    public function getSupports()
    {
        return $this->supports;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setSupports(...$supports)
    {
        $this->supports = $supports;

        return $this;
    }

    protected function calculateLabels($singular)
    {
        if ($singular = $this->getSingularLabel() === null) {
            $singular = str_replace('_', ' ', $singular);
            $singular = str_replace('-', ' ', $singular);
        }
        $plural = $this->stringHelper::title($this->stringHelper::plural($singular));
        $singular = $this->stringHelper::title($singular);
        $this->labels = [
            'name' => $plural,
            'singular_name' => $singular,
            'add_new' => 'Add '.$singular,
            'add_new_item' => 'Add New '.$singular,
            'edit' => 'Edit',
            'edit_item' => 'Edit '.$singular,
            'new_item' => 'New '.$singular,
            'view' => 'View',
            'view_item' => 'View '.$singular,
            'search_items' => 'Search '.$plural,
            'not_found' => 'No '.$plural.' found',
            'not_found_in_trash' => 'No '.$plural.' found in Trash',
            'parent' => 'Parent '.$plural,
        ];
    }

    public function setOptions(array $options = [])
    {
        if (! empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        return $this;
    }

    protected function getArgs() : array
    {
        $args = [];
        $args['labels'] = $this->labels;
        if (! empty($this->supports)) {
            $args['supports'] = $this->supports;
        }
        if (! empty($this->taxonomies)) {
            $args['taxonomies'] = $this->taxonomies;
        }
        // This is the default
        $args['rewrite'] = ['slug' => str_replace('_', '-', $this->postType)];
        // Then we over write it from user provided settings
        $args = array_merge($args, $this->options);

        return $args;
    }

    public function register()
    {
        // function_exists wrapper here simply to make phpunit testing easier
        if (function_exists('register_post_type')) {
            register_post_type($this->postType, $this->getArgs());
        }

        return [$this->postType, $this->getArgs()];
    }
}
