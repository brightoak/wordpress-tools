<?php

namespace Brightoak\WordPressTools;

use Illuminate\Support\Str;
use Brightoak\WordPressTools\Exceptions\InvalidArgumentException;

class CustomTaxonomy
{
    /**
     * @var string
     */
    protected $taxonomy;
    /**
     * @var Str
     */
    protected $stringHelper;

    protected $objectTypes = [];

    protected $options = [
        'public' => true,
        'show_ui' => true,
    ];

    protected $singularLabel = null;

    private function __construct(string $name)
    {
        $this->stringHelper = new Str;
        $this->taxonomy = $this->stringHelper::singular($name);
    }

    public static function init(string $name)
    {
        if (strlen($name) >= 1 && strlen($name) < 20) {
            return new self($name);
        }
        throw new InvalidArgumentException('A Custom Taxonomy name must be between 1 and 20 characters.');
    }

    public function setObjectTypes(...$objects)
    {
        $this->objectTypes = $objects;

        return $this;
    }

    public function getObjectTypes()
    {
        return $this->objectTypes;
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

    public function getOptions()
    {
        return $this->options;
    }

    protected function calculateLabels($calculatedLabel)
    {
        if ($this->getSingularLabel() === null) {
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
            'singular_name' => __($singularLabel, 'brightoak'),
            'search_items'               => __("Search $plural", 'brightoak'),
            'popular_items'              => __("Popular $plural", 'brightoak'),
            'all_items'                  => __("All $plural", 'brightoak'),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __("Edit $singularLabel", 'brightoak'),
            'update_item'                => __("Update $singularLabel", 'brightoak'),
            'add_new_item'               => __("Add New $singularLabel", 'brightoak'),
            'new_item_name'              => __("New $singularLabel Name", 'brightoak'),
            'separate_items_with_commas' => __("Separate $plural with commas", 'brightoak'),
            'add_or_remove_items'        => __("Add or remove $plural", 'brightoak'),
            'choose_from_most_used'      => __("Choose from the most used $plural", 'brightoak'),
            'not_found'                  => __("No $plural found.", 'brightoak'),
            'menu_name'                  => __("$plural", 'brightoak'),
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
        $args['labels'] = $this->calculateLabels($this->taxonomy);
        // This is the default
        $args['rewrite'] = ['slug' => str_replace('_', '-', $this->taxonomy)];
        // Then we over write it from user provided settings
        $args = array_merge($args, $this->options);

        return $args;
    }

    public function register()
    {
        // function_exists wrapper here simply to make phpunit testing easier
        if (function_exists('register_post_type')) {
            register_taxonomy($this->taxonomy, $this->objectTypes, $this->getArgs());
        }

        return [$this->taxonomy, $this->objectTypes, $this->getArgs()];
    }
}
