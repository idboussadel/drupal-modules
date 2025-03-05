# Custom modules :
I added a config page for the admin ( Movie module ):

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/fddc3a4d-9ce2-4a29-b8fc-11bbd46bae39" />

<img width="1439" alt="image" src="https://github.com/user-attachments/assets/e8a68bde-257c-414c-a70c-58c12189abd6" />

## Results :
I used :
✅ Services
✅ Movie API Integration
✅ Dependency Injection
✅ Routing
✅ Templates using Twig

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/2b20958c-86e1-4ebd-89d0-9e35aabb64a9" />

## Block - Plugin

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/b7d57d26-6dda-49f0-93f0-344564c09db7" />

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/d14f9c6e-24bd-4c3d-9f88-43b866b4ec54" />

## Day 2 Questions : 
1.  **How do i control or sort the menus (weight) ?**

You can control menu sorting by setting the weight property in the menu link definition (.links.menu.yml).
Lower weights appear first, higher weights appear later.
Example:
```yaml
my_module.custom_link:
  title: 'Custom Page'
  route_name: 'my_module.custom_route'
  weight: 5
```
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

3.  **Retrieve Query String in a Controller**

Use `$request->query->get('key')` inside your controller.
    
 ```php
use Symfony\Component\HttpFoundation\Request;
    
 public function view(Request $request){
   $value = $request->query->get('my_param'); 
   dump($value);
 }
```

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/c9b977f6-b3ed-475b-90e5-acf6264515b9" />

4.  **Guzzle & Logger**

- **Guzzle**: HTTP client for making requests in Drupal.
- **Logger**: Logs messages via Drupal's logging system

5.  **Logging Messages in a Controller**

- Inject `logger.factory` service and log messages:
    
    ```php
    $this->logger->info('This is an info log message.');
    ```

  **Where do logs appear?**
   - In the database (`watchdog` table) using `drush ws` .
   - In the admin UI (`/admin/reports/dblog`).
   - In system logs (`syslog` if enabled).

6.  **Finding Drupal Core Services (YAML File)**
Located in core/core.services.yml.

<img width="1440" alt="image" src="https://github.com/user-attachments/assets/3f3d30f7-1476-415c-9857-4542deb287a9" />

7.  **Injecting Services in a Custom Service**

- Define service in `my_module.services.yml`:

```yaml
services:
  movies.api_connector:
    class: Drupal\movies\MovieAPIConnector
    arguments: ["@http_client_factory"]
```

- we used http_client_factory drupal service , lets Inject into a class:
    
    ```php
    <?php
    
    namespace Drupal\movies;
    
    use Drupal\Core\Http\ClientFactory;
    use Drupal\movies\Form\MoviesApiConfigForm;
    use GuzzleHttp\Exception\RequestException;
    
    class MovieAPIConnector{
        private $client;
    
        /**
         * MovieAPIConnector constructor.
         *
         * @param \GuzzleHttp\ClientFactory $client
         *   The client factory used to create the HTTP client.
         */
        public function __construct(ClientFactory $client){
            // ....
        }
    }
    ```
