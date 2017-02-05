<?php namespace Bedard\BlogTags\Models;

use Config;
use Model;
use RainLab\Blog\Models\Post;

/**
 * Tag Model
 */
class Tag extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_blogtags_tags';

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'posts' => [
            'RainLab\Blog\Models\Post',
            'table' => 'bedard_blogtags_post_tag',
            'order' => 'published_at desc'
        ]
    ];

    /**
     * @var array Fillable fields
     */
    public $fillable = ['name'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|unique:bedard_blogtags_tags'
    ];

    public $customMessages = [
        'name.required' => 'bedard.blogtags::lang.form.name_required',
        'name.unique'   => 'bedard.blogtags::lang.form.name_unique',
        'name.regex'    => 'bedard.blogtags::lang.form.name_invalid',
    ];

    public function __construct(array $attributes = [])
    {
        if (Config::get('bedard.blogtags::slugify', true)) {
            $this->rules['name'] .= '|regex:/^[a-z0-9-]+$/';
        }

        parent::__construct($attributes);
    }

    /**
     * Convert tag names to lower case
     */
    public function setNameAttribute($value)
    {
        if (Config::get('bedard.blogtags::slugify', true)) {
            $value = str_replace(' ', '-', $value);
        }

        $this->attributes['name'] = mb_strtolower($value);
    }

     /**
     * Sets the "url" attribute with a URL to this object
     *
     * @param string                    $pageName
     * @param Cms\Classes\Controller    $controller
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }
}
