<h1 align="center">Laravel Easy Admin</h1>
<h3 align="center">~ A simple admin panel for Laravel projects.</h3>
<h4 align="center"><a target="_blank" href="https://www.youtube.com/watch?v=QFzzwpDsQ0U" align="center">YouTube Demo / Tutorial</a></h4>
<br><br>
<p align="center"><img src="https://raw.githubusercontent.com/devs-ryan/img-storage/master/easy-admin-header.png"></p>
<p align="center">
<a target="_blank" href="https://laravel.com/"><img src="https://img.shields.io/badge/Built%20For-Laravel-orange" alt="Built For Laravel"></a>
<a target="_blank" href="https://packagist.org/packages/devs-ryan/laravel-easy-admin"><img src="https://img.shields.io/badge/Current%20Version-0.1.1-blue" alt="Version"></a>
<a target="_blank" href="https://packagist.org/packages/devs-ryan/laravel-easy-admin"><img src="https://img.shields.io/badge/License-GNU-green" alt="License"></a>
<a target="_blank" href="https://laravel.com/"><img src="https://img.shields.io/badge/Requires-Laravel%20%5E7.0-red" alt="Requires"></a>
</p>

## What is Laravel Easy Admin

- To Be Determined.


## Installation
- `composer require devs_ryan/laravel-easy-admin`
- `php artisan vendor:publish --tag="public" --provider="DevsRyan\LaravelEasyAdmin\LaravelEasyAdminServiceProvider"`
- `php artisan migrate` (Your app is assumed to have a users table at this point)
- Access from <a href="https://github.com/devs-ryan/laravel-easy-admin">http(s)://your-project-url.com/easy-admin</a>

## Usage

#### Setting env variables
The following optional URL variables can be set in the Laravel .env file:
- `APP_URL` (provides a link back to your app on the login page nav bar)
- `EASY_ADMIN_APP_NAME` (Change the name shown in the top left of the Easy Admin nav bar)
- `EASY_ADMIN_SUPPORT_EMAIL` (Provide a help email address to your Easy Admin users)
- `EASY_ADMIN_DEFAULT_NAMESPACE` (Set true to user namespace App\Models)
- `EASY_ADMIN_DEFAULT_PASSWORD` (This password will be set for any fields matching `password` during seed generation. Default is `secret`)
- `EASY_ADMIN_BASE_URL` (Change the base URL for your admin area. Default is `easy-admin)

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
Add all models within a given namespace to Easy Admin

#### Reset Easy Admin
In case you would like to return Easy Admin to the original state, use the command below.
- `php artisan easy-admin:reset`

#### List Image Sizes
Lists the supported image sizes that are generated when uploading an image file.
- `php artisan easy-admin:image-sizes`

#### Get Image Helper
Used to retrieve an image that was created using Easy Admin (see size options using command above)
- `easyImg($model_name, $field_name, $file_name, $size = 'original')`

#### Get Image Details Helper
Used to retrieve an image that was created using Easy Admin (with full image details, does not work with general storage)
- `easyImgDetails($model_name, $field_name, $file_name)`
- Note: $field_name argument can be set to `null` or `general_storage` for WYSIWYG related images

#### Get File Helper
Used to retrieve a file that was created using Easy Admin
- `easyFile($model_name, $field_name, $file_name)`

#### Safe Text Helper
Used to strip any HTML away from text for wysiwyg fields
- `easySafeText($blog_post->content)`

## Limitations
This admin panel assumes that you follow the standard Laravel naming conventions for models and database tables. If you create migrations/models using `php artisan make:model {ModelName} -m` it should work, otherwise it may not. 

The users table is expected to contain some fields that ship with the Laravel base install, such as `email` and `password`. 

All model resources must contain and `id` attribute in their database table for the routing to function.

## Licence
Laravel Easy Admin is open-sourced software licensed under the [GNU Lesser General Public License v3.0](LICENSE.md).
