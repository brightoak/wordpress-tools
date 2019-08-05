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

    protected $singularLabel = null;

    private function __construct(string $name)
    {
        $this->stringHelper = new Str;
        $this->postType = $this->stringHelper::singular($name);
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

    protected function calculateLabels($calculatedLabel)
    {
        if($this->getSingularLabel() === null){
            $singularLabel = str_replace('_', ' ', $calculatedLabel);
            $singularLabel = str_replace('-', ' ', $singularLabel);
            $plural = $this->stringHelper::title($this->stringHelper::plural($singularLabel));
            $singularLabel = $this->stringHelper::title($singularLabel);
        } else {
            $singularLabel = $this->getSingularLabel();
            $plural = $this->stringHelper::plural($singularLabel);
        }

        return [
            'name' => $plural,
            'singular_name' => $singularLabel,
            'add_new' => 'Add '.$singularLabel,
            'add_new_item' => 'Add New '.$singularLabel,
            'edit' => 'Edit',
            'edit_item' => 'Edit '.$singularLabel,
            'new_item' => 'New '.$singularLabel,
            'view' => 'View',
            'view_item' => 'View '.$singularLabel,
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
        $args['labels'] = $this->calculateLabels($this->postType);
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
