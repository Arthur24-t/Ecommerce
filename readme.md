<div align="center" id="top"> 

  &#xa0;

</div>

<h1 align="center">Amazong</h1>

<p align="center">
  <a href="#dart-about">About</a> &#xa0; | &#xa0; 
  <a href="#sparkles-features">Features</a> &#xa0; | &#xa0;
  <a href="#rocket-technologies">Technologies</a> &#xa0; | &#xa0;
  <a href="#white_check_mark-requirements">Requirements</a> &#xa0; | &#xa0;
  <a href="#checkered_flag-starting">Starting</a> &#xa0; | &#xa0;
  <a href="#memo-license">License</a> &#xa0; | &#xa0;
</p>

<br>

## :dart: About ##

The goal of the project is to create a generic API for e-commerce merchant sites with a maximum of features. 

## :sparkles: Features ##

:heavy_check_mark: Register and login users;\
:heavy_check_mark: Can create, get, delete, update products;\
:heavy_check_mark: Can add and remove product to cart;\
:heavy_check_mark: Can validate cart to make a order;\

## :rocket: Technologies ##

The following tools were used in this project:

- [Composer](https://getcomposer.org/)
- [Symfony](https://symfony.com/)
- [React](https://fr.reactjs.org/)
- [Docker](https://www.docker.com/)
- [Mariadb](https://mariadb.org/)
- [Ansible](https://www.ansible.com/)


## :white_check_mark: Requirements ##

Before starting :checkered_flag:, you need to have [Git](https://git-scm.com), [Composer](https://getcomposer.org/) and [Symfony](https://symfony.com/) installed.

## :checkered_flag: Starting ##

To launch localy this app : 
Warning : Make sure to have a .env in the app/ (you have a exemple in envexmeple a the root)
```bash
# Clone this project
$ git clone https://github.com/EpitechMscProPromo2025/T-WEB-600-LIL-6-1-ecommerce-arthur.trusgnach.git
# Access
$ cd t-web-600-LIL-6-1-ecommerce-arthur.trusgnach
# To launch the Mariadb docker
$ docker-compose up -d 
# Go into the back folder
$ cd app/
# Install dependencies
$ composer install
# migrate the databases to mariadb
$ php bin/console doctrine:migrations:migrate
#In case of your dependencies are outdated
$ somfony server:start
```

To launch deploy on one or multiple server (Debian11): 

```bash
# Clone this project
$ git clone https://github.com/EpitechMscProPromo2025/T-WEB-600-LIL-6-1-ecommerce-arthur.trusgnach.git
# Access
$ cd t-web-600-LIL-6-1-ecommerce-arthur.trusgnach
# launch the ansible playbook
# make sur to put your server in the file host (and are config with ssh)
$ ansible-playbook playbook.yml -i host
```

To launch the Front : 

```bash
# Install Npm
$ sudo apt install npm
# Install dependencies
$ npm i 
# Lauch the front
$ npm start
```
## :memo: License ##


Made with :heart: by </a><a href="https://github.com/Skuzo" target="_blank">Hugo, </a><a href="https://github.com/Arthur24-t" target="_blank">Arthur.</a>

&#xa0;

<a href="#top">Back to top</a>
