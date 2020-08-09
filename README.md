<h2 align="center">Laravel Easy Admin</h2>
<h3 align="center">~ A simple admin panel for Laravel projects.</h3>
<br><br>
<p align="center"><img src="https://raw.githubusercontent.com/raysirsharp/img-storage/master/easy-admin-header.png"></p>
<p align="center">
<a target="_blank" href="https://laravel.com/"><img src="https://img.shields.io/badge/Built%20For-Laravel-orange" alt="Built For Laravel"></a>
<a target="_blank" href="https://packagist.org/packages/raysirsharp/laravel-easy-admin"><img src="https://img.shields.io/badge/Current%20Version-0.1.1-blue" alt="Version"></a>
<a target="_blank" href="https://packagist.org/packages/raysirsharp/laravel-easy-admin"><img src="https://img.shields.io/badge/License-MIT-green" alt="License"></a>
<a target="_blank" href="https://laravel.com/"><img src="https://img.shields.io/badge/Requires-Laravel%20%5E7.0-red" alt="Requires"></a>
</p>

## What is Laravel Easy Admin

Laravel Easy Admin is a back end UI designed for developers, root users with decent database knowledge or basic projects. It is not meant to serve as a complete Admin panel with full capabilities (see <a href="https://nova.laravel.com/">Lavavel Nova</a> if this is what you are looking for). On the contrary it is mean to act as a basic admin panel, with limited customizability, that can get up and running within minutes.

Laravel Easy Admin leverages a powerful set of artisan commands to add/remove resources. This is combined with public files where functionality can be removed or added via commenting/uncommenting code which allows Easy Admin to give basic ability for customization. If you need a quick and dirty admin panel for your project, this package is for you! :)


## Installation
- `composer require raysirsharp/laravel-easy-admin`
- `php artisan vendor:publish --tag=public --force`
- `php artisan migrate` (Your app is assumed to have a users table at this point)
- Access from <a href="https://github.com/raysirsharp/laravel-easy-admin">http(s)://your-project-url.com/easy-admin</a>

## Usage

#### Setting env variables
The following optional URL variables can be set in the Laravel .env file:
- `APP_URL` (provides a link back to your app on the login page nav bar)
- `EASY_ADMIN_APP_NAME` (Change the name shown in the top left of the Easy Admin nav bar)
- `EASY_ADMIN_SUPPORT_EMAIL` (Provide a help email address to your Easy Admin users)

#### Creating an Easy Admin user
- `php artisan easy-admin:create-user`
- Follow the prompts to create a new user account with Easy Admin access

#### Remove a user from the database
- `php artisan easy-admin:remove-user`
- Follow the prompts to remove a user from the database

#### Grant an existing user Easy Admin Access
- `php artisan easy-admin:user`
- Enter a user_id or email to grant access

#### Remove an existing user from Easy Admin Access
- `php artisan easy-admin:user --remove`
- Enter a user_id or email to remove access

#### Add a model resource to Easy Admin
After running this command a CRUD resource will be added to the Easy Admin UI for the model specified.
- `php artisan easy-admin:add-model`
- Follow the prompts for namespace E.G. "App" and model name E.G. "User"
- This will generate a new file in the base projects app/EasyAdmin directory, where you can comment out any functionality you do not wish to provide to the Easy Admin UI

#### Remove a model resource from Easy Admin
- `php artisan easy-admin:remove-model`
- Follow the prompts for namespace E.G. "App" and model name E.G. "User"
- This will remove the model from showing in the UI and delete the app/EasyAdmin file for it as well

#### Refresh a model resource in Easy Admin
- `php artisan easy-admin:refresh-model`
- Follow the prompts for namespace E.G. "App" and model name E.G. "User"
- This will reload the public file in the app/EasyAdmin directory to the default and load/remove any fields that have changed in the model

#### Add all model resources to Easy Admin
This is not currently working, but on my TODO list.

#### Reset Easy Admin
In case you would like to return Easy Admin to the original state, use the command below.
- `php artisan easy-admin:reset`

## Limitations
This admin panel assumes that you follow the standard Laravel naming conventions for models and database tables. If you create migrations/models using `php artisan make:model {ModelName} -m` it should work, otherwise it may not. 

The users table is expected to contain some fields that ship with the Laravel base install, such as `email` and `password`. 

All model resources must contain and `id` attribute in their database table for the routing to function.

## Licence
Laravel Easy Admin is open-sourced software licensed under the [MIT license](LICENSE.md).
