# CodeIgniter 4 (4.6.0) + AdminLTE (3.2.0)

## Server Requirements (default codeigniter 4 requirements)

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Installation
create database using spark or other tools

```cli
php spark db:create your_database
```

run database setup command, 
this part will run migration and seeders
to create the tables and adding some dummy data

```cli
php spark setup:datatabse
```

## Template Library Usage

Using database in mandatory, because I use model to populate the menu, so make sure you already done the installation section.

### Details
- Locations: `app/Libraries`
- File name: `Template.php`

### Page type

According to AdminLTE, in my opinion, there are 2 type of page. 
1. Full page that contain header, menus, sidebar, footer, and other. for example Dashboard
2. Blank page which is didn't have any menus, sidebar, or else. for example Login and Registration page

That is why there are 2 template view in this repo,
1. `Default` view, which you can find in `Views/template/` as `default.php`
2. `Blank` view, which you can find in `Views/template/` as `blank.php`

The partial of each view are located in the same folder with `default` and `blank` as a folder name. I know this is not a best practice in naming partial but hey, you can change it if you want.

Example to create a view, either its default or blank one

```html
<?= $this->extend('template/default') ?>
<!-- Use template/blank if you want to use blank template -->

<?= $this->section('content') ?>

<!-- Put your main content in here -->

<?= $this->endSection('content') ?>
```

----

### Render the template
- Method: `render()`

This method has 2 parameter,
- `$view` (* mandatory): Its your view page file so its mandatory otherwise error will occurred
- `$data`: Data for your page

```php
namespace App\Controllers;

use App\Libraries\Template;

class Home extends BaseController
{
  public function index()
  {
    $template = new Template();

    return $template->render('starter_page');

    // or render it with data
    return $template->render('startes_page', [
      'curdate' => date('d-m-Y')
    ]);
  }
}
```

----

### Set Page Title
- Method: `page_title()`

I bet you dont need an explanation for this method right? come on.. its a freaking page title man, what else should I explain.

```php
$template->page_title('Welcome Page');
```

----

### Add Custom Page CSS
- Method: `page_css()`

if you have a custom css for a specific page, you can load it by using this method.

```php
$template->page_css("assets/dist/css/custom_page.css);
```

you could also set the parameter as an array if you have multiple custom css for one page.

```php
$template->page_css([
  "assets/dist/css/demo1.css", 
  "assets/dist/css/demo2.css"
]);
```

----

### Add Custom Page JS
- Method: `page_js()`

if you have a custom js file for a specific page, you can load it by using this method.

```php
$template->page_js("assets/dist/js/custom_page.css);
```

you could also set the parameter as an array if you have multiple custom js for one page.

```php
$template->page_js([
  "assets/dist/js/demo1.css", 
  "assets/dist/js/demo2.css"
]);
```

----

### Plugins
- Method: `plugins()`

First things first, there are new file in `app\Config` named as `Plugins.php`. (yeah, dont ask me why I name it `Plugins` LOL)
Its contain list of javascript/jquery library that consist of `css` and `js` property. 
There are so many 3rd parties libraries that been use in AdminLTE, I only list some of them that I use in this project.
Feel free to edit it according to you need in you project. Just dont expect me to list all of them for you, ok.

The usage of this method is pretty simple. It only has 1 parameter, it can be string or an array, just in case you need more than one library in one page, which is most likely happen all the time (Duhh). All you have to do just put the property name that you made in `Plugins.php` file.

```php
$template->plugins('datatables');
// or
$template->plugins(['datatable', 'select2']);
```

----

### Hiding Content Toolbar, Hiding Breadcrums, Hiding Footer, Hiding things
- Method: `hide_content_toolbar()`, `hide_breadcrums()`, `hide_footer()`, `hide()`

Yes, you read it right, you can hide stuff in here. Magic? No it is not, wake up already.
I do believe there should be a better way to do this, but I haven't found it yet.

```php
$template
  ->hide_content_toolbar()
  ->hide_breadcrums()
  ->hide_footer();

$template->hide([
  'content-toolbar',
  'breadcrums',
  'footer'
]);
```

----

### Set Custom Class

This methode is use if you want to add a custom or additional class to some specific tags. Access the tag classes data in view by calling `$classes` variable. For now its only accepting `body` tag.

```php
$template->tag_class("body", "hold-transition login-page");
```

----

### Examples

```php
$template->page_title('Welcome');
$template->plugins('datatables');
$template->page_js('assets/dist/js/pages/demo.js');
$template->render('welcome');

// or
$data = []; // set your data
$template
  ->page_title("Welcome")
  ->plugins(['datatables'])
  ->page_js("assets/dist/js/pages/demo.js")
  ->render('welcome', $data);

// or
$template
  ->page_title('Login page')
  ->tag_class('body', 'hold-transition login-page')
  ->render('login');
```

----

## Datatables Library Usage

Datatable library is a simple library that I create to generate data for datatables. This library only work with simple task or query, but not gonna work with really complex task or query.
You can use `loadQuery()` method, to get the data using `sql query`, but you have to set your own filter and stuff.

### Library Usage

```php
$dt = new \App\Libraries\Datatables(service('request'));
// note: `service(request)` is mandatory
```

----

## Return data format

The return data format of this library will be an array like this

```php
$response = [
  "recordsTotal" => 1000,
  "recordsFiltered" => 500,
  "data" => $data,
];
```

----

### Set Up Database Group
- Method: `dbgroup()`

Use this method only when you decided to use `loadQuery` instead of `loadData` method, by default it will load the `default` group.

```php
$dt->dbGroup('db_group2');
```

----

### Set Columns
- Method: `select($column, $escape)`

Use this when you want to specify the column, by default it will show all column (*).
This method accept 2 parameter:
1. `$columns` List of the column, could be in string or an array
2. `$escape` default is false

```php
$dt->select([
  'id',
  'name',
  'address'
]);

// or 
$dt->select('id, name, address, count(item_id) as total_item', false);
```

----

### Set Joins table
- Method: `joins($joins)`

The parameter is consist of list of join array. I know, it doesn't explain anything so lets jump to the example.

```php
$dt->joins([
  [
    'table_2 as tb2',
    'tb2.table_1_id = tb1.id',
    'inner'
  ],
  // or you can also write it like this
  [
    'table' => 'table_3 as tb3',
    'on' => 'tb3.table_2_id = tb2.id AND tb3.is_active = 1',
    'type' => 'inner'
  ]
]);
```

----

### Set Conditions or Filter
- Method: `conditions($conditions)`

Because this is a simpel datatables library, the filter / conditions that you can use is limited to:
1. `where`
2. `whereIn`
3. `whereNotIn`
4. `like`
5. `orLike`
6. `notLike`
7. `groupBy`

```php
$dt->conditions([
  'where' => [
    'id' => 123,
    'is_active' => 1
  ],

  // whereIn, whereNotIn
  'whereIn' => [
    [
      'id',
      [1, 2, 3]
    ],
    [
      'column' => 'role',
      'value' => ['admin', 'user']
    ]
  ],

  // like, orLike, notLike
  'like' => [
    [
      'name',
      'john',
      'both'
    ],
    [
      'column' => 'name',
      'keyword' => 'john',
      'type' => 'both'
    ]
  ],

  'groupBy' => 'id',
]);
```

----

### Set Searching Type
- Method: `searchType($type)`

There are 2 type of search that set for this datatables, `simple` one which is datatable default search, and `column` one which you can do search base on the column in datatable. The default value will be `simple`

```php
$dt->searchType('column');
```

----

### Set Order By
- Method: `orderBy($orders)`

```php
$dt->orderBy([
  [
    'id',
    'ASC'
  ],
  [
    'column' => 'id',
    'dir' => 'DESC'
  ]
]);
```

----

### Load the data by the configs that
- Method: `loadData($model)`

This method is to load all the data base on whatever configs that you set above. Just pass the model name class.

```php
$dt->loadData('UserModel');
// or
$dt->loadData('App\Models\UserModel');
// or
$dt->loadData(\App\Models\UserModel::class )
```

----

### Load by Sql Query
- Method: `loadQuery($sql, $binding)`

You can use this method if you prefer using sql query, or you have really complex query, but you have to generate your own search proses.

```php
$dt->loadQuery('select * from users order by name ASC');
```

----