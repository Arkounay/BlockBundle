# Arkounay BlockBundle - Symfony 3 wysiwyg inline edition

This lightweight and opinionated bundle allows to quickly render HTML blocks editable with a WYSIWYG editor ([TinyMCE](https://www.tinymce.com/)), either via the provided PageBlock entity, or directly already existing entities through custom twig functions.

![alt tag](http://outerark.com/symfony/arkounay_block_bundle.png)


## Getting started

- Download the files:
```bash
composer require arkounay/block-bundle
```

- In `AppKernel.php` add the bundle:
```php       
new Arkounay\BlockBundle\ArkounayBlockBundle()
```
- Then, run the following command:
```bash    
php bin/console assets:install 
```     
- In your twig template, you will then need to import the required assets:
    
    - CSS:
        ```twig
        {% include '@ArkounayBlock/assets/include_css.html.twig' %}
        ```
    - JS (**requires [jQuery](https://jquery.com/) and [TinyMCE](https://www.tinymce.com/)**):
        ```twig
        {# Import jQuery and TinyMCE: #}
        {% if has_inline_edit_permissions() %}
            <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.4.3/tinymce.min.js"></script>
        {% endif %}

        {# Then the default bundle's JavaScript: #}
        {% include '@ArkounayBlock/assets/include_js.html.twig' %}
        ```
- In `routing.yml`, you will need to import the Ajax route:
```yml 
 block:
     resource: "@ArkounayBlockBundle/Resources/config/routing.yml"
```
- Then update your database schema to add the provided PageBlock entity `php bin/console doctrine:schema:update --force`
        
## Usage
        
To add an editable block, simply add this in a twig file:
```twig
{{ render_block('block_id') }}
```   
The first time you're going to edit it, a new PageBlock entity will be created in your database.
    
To edit an already existing entity text, you can add the following line: 
```twig
{{ render_entity_field(entity, 'field') }}
```   
For example, with a "News" entity, you could add this to make its content editable:
```twig    
{{ render_entity_field(news, 'content') }}
{# instead of {{ news.content }} #}
```    
Those who don't have editing permissions will see the field as if `{{ news.content }}` was directly called.

There is also another version with less editing options, usually for shorter texts:
```twig
{{ render_plain_entity_field(entity, 'field') }}
```  
Once you click on "Save", a single Ajax request is sent to persist and flush changed entities.

#### Notes
- By default, only users with the `ROLE_ADMIN` permission can edit inline text. To edit the allowed roles, you can edit your `config.yml`
 ```yml       
arkounay_block:
    roles: ['IS_AUTHENTICATED_ANONYMOUSLY'] # Will allow anyone to edit inline!
 ```         
- To edit TinyMCE, create your own `@ArkounayBlock/assets/include_js.html.twig`

- You can also edit the `.js-arkounay-block-bundle-editable` to change the borders of the editable blocks.

- In a twig file, you can use the function `has_inline_edit_permissions()` to see if a user has inline edit permissions. This can be useful if you want to import [jQuery](https://jquery.com/) or [TinyMCE](https://www.tinymce.com/) only for these users.

- By default, a div will be surrounding the editable text when connected. You can choose another tag with the third parameter, i.e for a span:
```twig
 {{ render_block('block_id', true, 'span') }}
``` 
  you can also call this shortcut:
```twig   
{{ render_span_block('block_id') }}
```
