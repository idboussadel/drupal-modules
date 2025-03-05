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

Day 2 Questions : 
* How do i control or sort the menus (weight) ?
You can control menu sorting by setting the weight property in the menu link definition (.links.menu.yml).
Lower weights appear first, higher weights appear later.
Example:
```yml
my_module.custom_link:
  title: 'Custom Page'
  route_name: 'my_module.custom_route'
  weight: 5
```
