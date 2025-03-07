# Table of Contents
- [Block - Plugin](#block---plugin)
- [Day 2 - Questions](#day-2--questions)
- [Day 3 - Questions (Hooks)](#day-3---questions-hooks)
- [Day 4: Plugins & Forms](#day-4-plugins--forms)
- [Day 5: Data types](#day-5-data-types)

# Custom modules :
I added a config page for the admin ( Movie and Hello_world ):

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/fddc3a4d-9ce2-4a29-b8fc-11bbd46bae39" />

<img width="1439" alt="image" src="https://github.com/user-attachments/assets/e8a68bde-257c-414c-a70c-58c12189abd6" />

## Results :

I used:
- ✅ Services
- ✅ Movie API Integration
- ✅ Dependency Injection
- ✅ Routing
- ✅ Templates using Twig
- ✅ PHP and Twig Docs

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/2b20958c-86e1-4ebd-89d0-9e35aabb64a9" />

## Block - Plugin

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/b7d57d26-6dda-49f0-93f0-344564c09db7" />

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/d14f9c6e-24bd-4c3d-9f88-43b866b4ec54" />

## Day 2 - Questions

1.  **How do i control or sort the menus (weight) ?**

You can control menu sorting by setting the weight property in the menu link definition (.links.menu.yml).
Lower weights appear first, higher weights appear later.

```yaml
my_module.custom_link:
  title: "Custom Page"
  route_name: "my_module.custom_route"
  weight: 5
```
---

2.  **Setting Up Child Menus**

Define child menus in `.links.menu.yml` by specifying the `parent` key.

```yaml
movies.admin:
  title: "Movies API Configuration"
  description: "Configure the Movies API settings"
  parent: system.admin_config_services
  route_name: movies.api_config_page
  weight: 300
```
---

3.  **Retrieve Query String in a Controller**

Use `$request->query->get('key')` inside your controller.

```php
<?php

use Symfony\Component\HttpFoundation\Request;

public function view(Request $request){
  $value = $request->query->get('my_param');
  dump($value);
}
```

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/c9b977f6-b3ed-475b-90e5-acf6264515b9" />

---

4.  **Guzzle & Logger**

- **Guzzle**: HTTP client for making requests in Drupal.
- **Logger**: Logs messages via Drupal's logging system

---

5.  **Logging Messages in a Controller**

- Inject `logger.factory` service and log messages:

  ```php
  $this->logger->info('This is an info log message.');
  ```

  **Where do logs appear?**

  - In the database (`watchdog` table) using `drush ws` .
  - In the admin UI (`/admin/reports/dblog`).
  - In system logs (`syslog` if enabled).
 
---

6.  **Finding Drupal Core Services (YAML File)**
    Located in `core/core.services.yml`.

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/3f3d30f7-1476-415c-9857-4542deb287a9" />

---

7.  **Injecting Services in a Custom Service**

- Define service in `my_module.services.yml`:

```yaml
services:
  movies.api_connector:
    class: Drupal\movies\MovieAPIConnector
    arguments: ["@http_client_factory"]
```

we used http_client_factory drupal service , lets Inject into a class:

```php
<?php

namespace Drupal\movies;

use Drupal\Core\Http\ClientFactory;
use Drupal\movies\Form\MoviesApiConfigForm;
use GuzzleHttp\Exception\RequestException;

class MovieAPIConnector {
    private $client;

    /**
     * MovieAPIConnector constructor.
     *
     * @param \GuzzleHttp\ClientFactory $client
     *   The client factory used to create the HTTP client.
     */
    public function __construct(ClientFactory $client) {
        // ....
    }
}
```

---

8.  **Return a Twig Template in a Controller**

```php
return [
  '#theme' => 'my_template',
  '#variables' => ['key' => 'value'],
];
```

but to enable your themes you should use the `hook_theme()`

```php
<?php

/**
 * Implements hook_theme().
 *
 */
function movies_theme($existing, $type,$theme,$path){
    return [
        'movies-listings' => [
            'variables'=>['content'=> NULL]
        ],
        'movies-card' => [
            'variables'=>['content'=> NULL]
        ],
    ];
}
```
---

9.  **Adding External JS in a Controller**
    Use `#attached`:

```php
public function view() {

    return [
        '#theme' => 'movies-listings',
        '#content' => [
            'movies' => $this->createMoviesCards(),
        ],
        '#attached' => [
            'library' => [
                'movies/movies-styling', // custom CSS library.
            ],
            'html_head' => [
                [
                    [
                        '#tag' => 'script', // script tag.
                        '#attributes' => [
                            'src' => 'https://unpkg.com/@tailwindcss/browser@4', // CDN URL.
                            'defer' => 'defer',
                        ],
                    ],
                    'tailwind-js', // Unique key for this script.
                ],
            ],
        ],
    ];
}
```

Or you can simply include it in the library :

```yaml
movies-styling:
  css:
    theme:
      assets/css/movies.css: {}
  js:
    https://unpkg.com/@tailwindcss/browser@4: { type: external, scope: footer }
```

---

10. **Translation Search in Admin UI**
    The search keyword is `"Hello, @name!"`

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/24eb5aba-c496-49f2-9b4a-94d26ddc74b8" />

12. **Making a String Translatable in JavaScript**

Use `Drupal.t()`:

```js
Drupal.t("Hello, World!");
```
---

13. **service path.alias_manager**

Get the alias of a node, given `node/1`, use the service to get the path alias.

```bash
./vendor/bin/drush php
\Drupal::service('path_alias.manager')->getAliasByPath('/node/1');
```

<img width="942" alt="image" src="https://github.com/user-attachments/assets/3e5b0f64-44af-4096-88c1-d8826760aa15" />

Use `Link` and `Url` to get the full URL of one of your routes.

```php
dump(Url::fromRoute('movies.listings')->toString());
$url = Url::fromRoute('movies.listings');
$link = Link::fromTextAndUrl('My Link', $url)->toString();
dump($link);
```

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/075c1f1e-1361-421c-936c-9a22bae9878f" />

---

14. **send JSON response in a Controller instead of a display :**

you can use `JsonResponse` of `Symfony\Component\HttpFoundation\JsonResponse`.

```php
<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
class HelloController extends ControllerBase
{

    public function hello(){
        return new JsonResponse(['status' => 'success', 'message' => 'hello world']);
    }
}
```

## Day 3 - Questions ( Hooks )

1. **What are the hooks provided by the metatag module**
`hook_metatag_route_entity()` :	Return an entity for a route to render meta tags.
`hook_metatags_alter()` :	Alter meta tags for non-entity pages.
`hook_metatags_attachments_alter()`	: Alter meta tags before they are attached to the page.

You can find all available hooks in the `metatag.api.php` :

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/ddfb75b2-f9b3-463c-b494-26a279e73909" />

---

2. **Hook responsible for altering a form**

The hook used to alter a form in Drupal is:

```php
function hook_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id)
```

- `$form`: An associative array representing the form structure.
- `$form_state`: An instance of `FormStateInterface`, which holds the form's current state.
- `$form_id`: The unique identifier of the form being altered.


Extend a form by adding a simple text field 

```php
/**
 * Implements hook_form_alter().
 */
function mymodule_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
  if ($form_id === 'user_register_form') {
    $form['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address Field'),
      '#description' => $this->t('Enter the address information.'),
      '#required' => TRUE,
    ];
  }
}
```
---

3. **isFrontPage and is_front**

`isFrontPage()` is a method provided by Drupal’s `path.matcher` service. It is used to determine whether the current page being requested is the front page (homepage) of the website.

```php
if (\Drupal::service('path.matcher')->isFrontPage()) {
  dump('You are on the front page!');
}else{
  dump('You are not on the front page!');
}
```

Add a variable is_front and pass it to your twig files.
In Drupal, `hook_preprocess_HOOK()` functions allow you to modify the variables that are sent to Twig templates. Add in the .module :

```php
function mymodule_preprocess_page(&$variables) {
  $variables['is_front'] = \Drupal::service('path.matcher')->isFrontPage();
}
```

add this to your page.html.twig template to test:

```twig
<h3>Is front page: {{ is_front ? 'Yes' : 'No' }}</h3>
```

---

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/8e82b99f-dbb9-4bfd-97ee-156f3b2a32cb" />

4. **Adding a `<meta name="viewport">` tag using `hook_page_attachments_alter`**

To add the viewport meta tag:

```php
function mymodule_page_attachments_alter(array &$attachments) {
  $attachments['#attached']['html_head'][] = [
    [
      '#tag' => 'meta',
      '#attributes' => [
        'name' => 'viewport',
        'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no',
      ],
    ],
    'mymodule_meta_viewport',
  ];
}

```

This will inject `<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">` into the page.

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/f2268117-f16e-47af-a13b-40f22d29f1b4" />

---

5. **Use the hook hook_preprocess_menu to add a CSS class to all yor menu items `.my-custom-class` **

```php
/**
 * Implements hook_preprocess_menu().
 */
function movies_preprocess_menu(&$variables) {
  if (isset($variables['items'])) {
    foreach ($variables['items'] as &$item) {
      $item['attributes']->addClass('my-custom-class');
    }
  }
}
```

6.  **Use the hook hook_preprocess_block to alter the system_branding_block block and make site_logo use the following logo `https://static.cdnlogo.com/logos/d/88/drupal-wordmark.svg`**

```php
/**
 * Implements hook_preprocess_block().
 */
function movies_preprocess_block(&$variables) {
  if ($variables['plugin_id'] === 'system_branding_block') {
    $variables['content']['site_logo']['#uri'] = 'https://static.cdnlogo.com/logos/d/88/drupal-wordmark.svg';
    dump($variables['content']['site_logo']);
  }
}

```

## Day 4: Plugins & Forms

1.  **Where can you validate you form data ?**

-  **Using `validateForm` in a Custom Form Class**

This is the most common and recommended approach when working with custom form classes.

```php
	 public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('name');

    // Validation: Name must be at least 3 characters long.
    if (strlen($name) < 3) {
      $form_state->setErrorByName('name', $this->t('The name must be at least 3 characters long.'));
    }

  }
}
```

-  **Using `#validate` and `hook_form_FORM_ID_alter` Property in the Form Definition**

You can use it in a form that you cannot modify directly (a form defined by another module or core).

```php
function my_module_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'my_custom_form') {
    // Add a custom validation function.
    $form['#validate'][] = 'my_module_form_validate';
  }
}

function my_module_form_validate(&$form, &$form_state) {
  $name = $form_state->getValue('name');

  // Validation: Name must be at least 3 characters long.
  if (strlen($name) < 3) {
    $form_state->setErrorByName('name', t('The name must be at least 3 characters long.'));
  }
}
```

2. **How would you render a form inside a block?**

You can render a form inside a block by using `\Drupal::formBuilder()->getForm()` inside the `build()` method of a custom block.

```php
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a block with a custom form.
 *
 * @Block(
 *   id = "custom_form_block",
 *   admin_label = @Translation("Custom Form Block")
 * )
 */
class CustomFormBlock extends BlockBase {
  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\my_module\Form\MyCustomForm');
  }
}
```

3.  **How would you redirect a user after submitting a form?**

You can set a redirect in the `submitForm()` method using `$form_state->setRedirect()`.

Example:

```php
public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('my_module.custom_page');

```

If you want to redirect to an external URL:

```php
$form_state->setRedirectUrl(\Drupal\Core\Url::fromUri('https://example.com'));
```

**How do you display a green message after submitting a form?**

Use `\Drupal::messenger()->addMessage()` inside `submitForm()`:

```php
public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage($this->t('Your form has been submitted successfully.'), 'status');
}
```

The `'status'` message type is green in the default Drupal theme.

4.  **Using #access, how can I hide a field for an anonymous user?**
You can use the #access property to conditionally hide a field for anonymous users.

Example:

```php
$form['my_field'] = [
  '#type' => 'textfield',
  '#title' => t('My Field'),
  '#access' => !\Drupal::currentUser()->isAnonymous(),
];
```

if you want to show it only to authenticated users :

```php
$form['personal_info']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
      '#access' => \Drupal::currentUser()->isAuthenticated(),
];
```

5.  **How do you group fields together in a form, like the field_group module**

You can use #type => 'details' or 'fieldset' or container.


```php
$form['group'] = [
    '#type' => 'fieldset',
    '#title' => $this->t('Grouped Fields'),
];

$form['group']['field_1'] = [
    '#type' => 'textfield',
    '#title' => $this->t('Field 1'),
];

$form['group']['field_2'] = [
    '#type' => 'textfield',
    '#title' => $this->t('Field 2'),
];
```

## Day 5: Data types

