<?php

//include 'account_routes.php';
include 'dev_routes.php';
include 'live_custom_routes.php';
//include 'new_routes.php';

/*
|--------------------------------------------------------------------------
| MODEL BINDING INTO ROUTE
|--------------------------------------------------------------------------
 */

//Route::model('article', 'App\Models\Article');
//Route::model('product', 'App\Models\product');
//Route::pattern('slug', '[a-z0-9- _]+');

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
 */

$languages = LaravelLocalization::getSupportedLocales();
foreach ($languages as $language => $values)
{
    $supportedLocales[] = $language;
}

$locale = Request::segment(1);
if (in_array($locale, $supportedLocales))
{
    LaravelLocalization::setLocale($locale);
    App::setLocale($locale);
}

Route::get('/', function ()
{
    return Redirect::to(LaravelLocalization::getCurrentLocale(), 302);
});

Route::group(['prefix' => LaravelLocalization::getCurrentLocale(), 'before' => ['localization', 'before']], function ()
{
    Session::put('my.locale', LaravelLocalization::getCurrentLocale());

    // frontend dashboard
    Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@index']);



    // article
    Route::get('/community/blog', ['as' => 'dashboard.article', 'uses' => 'ArticleController@index']);
    Route::get('/community/blog/{slug}', ['as' => 'dashboard.article.show', 'uses' => 'ArticleController@show']);

    // news
    Route::get('/community/news', ['as' => 'dashboard.news', 'uses' => 'NewsController@index']);
    Route::get('/community/news/{slug}', ['as' => 'dashboard.news.show', 'uses' => 'NewsController@show']);
    // video
    Route::get('/community/video', ['as' => 'dashboard.video', 'uses' => 'VideoController@index']);
    Route::get('/community/video/{slug}', ['as' => 'dashboard.video.show', 'uses' => 'VideoController@show']);

    // projects
    //Route::get('/community/project', ['as' => 'dashboard.project', 'uses' => 'ProjectController@index']);
    //Route::get('/community/project/{slug}', ['as' => 'dashboard.project.show', 'uses' => 'ProjectController@show']);

    // faq
    Route::get('/community/faq', ['as' => 'faq', 'uses' => 'FaqController@show']);

    // tags
    Route::get('/tag/{slug}', ['as' => 'dashboard.tag', 'uses' => 'TagController@index']);

    // categories
    Route::get('/category/{slug}', ['as' => 'dashboard.category', 'uses' => 'CategoryController@index']);

    // page
    Route::get('/page', ['as' => 'dashboard.page', 'uses' => 'PageController@index']);
    Route::get('/page/{slug}', ['as' => 'dashboard.page.show', 'uses' => 'PageController@show']);

    // photo gallery
    Route::get('/photo-gallery/{slug}', [
        'as'   => 'dashboard.photo_gallery.show',
        'uses' => 'PhotoGalleryController@show'
    ]);

    // contact
    Route::get('/contact', ['as' => 'dashboard.contact', 'uses' => 'FormPostController@getContact']);

    // rss
    Route::get('/rss', ['as' => 'rss', 'uses' => 'RssController@index']);

    // search
    Route::get('/search', ['as' => 'admin.search', 'uses' => 'SearchController@index']);

    // language
    // Route::get('/set-locale/{language}', array('as' => 'language.set', 'uses' => 'LanguageController@setLocale'));

    // maillist
    Route::get('/save-maillist', ['as' => 'frontend.maillist', 'uses' => 'MaillistController@getMaillist']);
    Route::post('/save-maillist', ['as' => 'frontend.maillist.post', 'uses' => 'MaillistController@postMaillist']);
});



/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
 */

Route::group(['prefix' => LaravelLocalization::getCurrentLocale()], function ()
{
    Route::group([
        'prefix'     => '/admin',

        'middleware' => ['before', 'sentinel.auth', 'sentinel.permission']
    ], function ()
    {
       
    });

    Route::group([
        'prefix'     => '/admin',
        'namespace'  => 'Admin',
        'middleware' => ['before', 'sentinel.auth', 'sentinel.permission']
    ], function ()
    {
        // admin dashboard
        Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'DashboardController@index']);

        // user
        Route::resource('user', 'UserController');
        Route::get('user/{id}/delete', [
            'as'   => 'admin.user.delete',
            'uses' => 'UserController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // role
        Route::resource('role', 'RoleController');
        Route::get('role/{id}/delete', [
            'as'   => 'admin.role.delete',
            'uses' => 'RoleController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // blog
        Route::resource('article', 'ArticleController', ['before' => 'hasAccess:article']);
        Route::get('article/{id}/delete', [
            'as'   => 'admin.article.delete',
            'uses' => 'ArticleController@confirmDestroy'
        ])->where('id', '\d+');

        // news
        Route::resource('news', 'NewsController', ['before' => 'hasAccess:news']);
        Route::get('news/{id}/delete', [
            'as'   => 'admin.news.delete',
            'uses' => 'NewsController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // category
        Route::resource('category', 'CategoryController', ['before' => 'hasAccess:category']);
        Route::get('category/{id}/delete', [
            'as'   => 'admin.category.delete',
            'uses' => 'CategoryController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // faq
        Route::resource('faq', 'FaqController', ['before' => 'hasAccess:faq']);
        Route::get('faq/{id}/delete', [
            'as'   => 'admin.faq.delete',
            'uses' => 'FaqController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // project
        Route::resource('project', 'ProjectController');
        Route::get('project/{id}/delete', [
            'as'   => 'admin.project.delete',
            'uses' => 'ProjectController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // page

        //Route::resource('admin/pages', 'Admin\PageController');
        Route::resource('page', 'PageController');
        Route::get('page/{id}/delete', [
            'as'   => 'admin.page.delete',
            'uses' => 'PageController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // photo gallery
        Route::resource('photo-gallery', 'PhotoGalleryController');
        Route::get('photo-gallery/{id}/delete', [
            'as'   => 'admin.photo-gallery.delete',
            'uses' => 'PhotoGalleryController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // video
        Route::resource('video', 'VideoController');
        Route::get('video/{id}/delete', [
            'as'   => 'admin.video.delete',
            'uses' => 'VideoController@confirmDestroy'
        ])->where('id', '[0-9]+');
        Route::post('/video/get-video-detail', [
            'as'   => 'admin.video.detail',
            'uses' => 'VideoController@getVideoDetail'
        ])->where('id', '[0-9]+');

        // ajax - blog
        Route::post('article/{id}/toggle-publish', [
            'as'   => 'admin.article.toggle-publish',
            'uses' => 'ArticleController@togglePublish'
        ])->where('id', '[0-9]+');

        // ajax - news
        Route::post('news/{id}/toggle-publish', [
            'as'   => 'admin.news.toggle-publish',
            'uses' => 'NewsController@togglePublish'
        ])->where('id', '[0-9]+');

        // ajax - photo gallery
        Route::post('photo-gallery/{id}/toggle-publish', [
            'as'   => 'admin.photo_gallery.toggle-publish',
            'uses' => 'PhotoGalleryController@togglePublish'
        ])->where('id', '[0-9]+');
        Route::post('photo-gallery/{id}/toggle-menu', [
            'as'   => 'admin.photo_gallery.toggle-menu',
            'uses' => 'PhotoGalleryController@toggleMenu'
        ])->where('id', '[0-9]+');

        // ajax - page
        Route::post('page/{id}/toggle-publish', [
            'as'   => 'admin.page.toggle-publish',
            'uses' => 'PageController@togglePublish'
        ])->where('id', '[0-9]+');

        Route::post('page/{id}/toggle-menu', [
            'as'   => 'admin.page.toggle-menu',
            'uses' => 'PageController@toggleMenu'
        ])->where('id', '[0-9]+');

        // ajax - form post
        Route::post('form-post/{id}/toggle-answer', [
            'as'   => 'admin.form-post.toggle-answer',
            'uses' => 'FormPostController@toggleAnswer'
        ])->where('id', '[0-9]+');

        // file upload photo gallery
        Route::post('/photo-gallery/upload/{id}', [
            'as'   => 'admin.photo.gallery.upload.image',
            'uses' => 'PhotoGalleryController@upload'
        ])->where('id', '[0-9]+');
        Route::post('/photo-gallery-delete-image', [
            'as'   => 'admin.photo.gallery.delete.image',
            'uses' => 'PhotoGalleryController@deleteImage'
        ]);

        // settings
        Route::get('/settings', ['as' => 'admin.settings', 'uses' => 'SettingController@index']);
        Route::post('/settings', [
            'as'   => 'admin.settings.save',
            'uses' => 'SettingController@save'
        ], ['before' => 'csrf']);

        // form post
        Route::resource('form-post', 'FormPostController', ['only' => ['index', 'show', 'destroy']]);
        Route::get('form-post/{id}/delete', [
            'as'   => 'admin.form-post.delete',
            'uses' => 'FormPostController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // slider
        Route::get('/slider', [
            'as' => 'admin.slider',
            function ()
            {
                return View::make('backend/slider/index');
            }
        ]);

        // slider
        Route::resource('slider', 'SliderController');
        Route::get('slider/{id}/delete', [
            'as'   => 'admin.slider.delete',
            'uses' => 'SliderController@confirmDestroy'
        ])->where('id', '[0-9]+');

        // file upload slider
        Route::post('/slider/upload/{id}', [
            'as'   => 'admin.slider.upload.image',
            'uses' => 'SliderController@upload'
        ])->where('id', '[0-9]+');
        Route::post('/slider-delete-image', [
            'as'   => 'admin.slider.delete.image',
            'uses' => 'SliderController@deleteImage'
        ]);

        // menu-managment
        Route::resource('menu', 'MenuController');
        Route::post('menu/save', ['as' => 'admin.menu.save', 'uses' => 'MenuController@save']);
        Route::get('menu/{id}/delete', [
            'as'   => 'admin.menu.delete',
            'uses' => 'MenuController@confirmDestroy'
        ])->where('id', '[0-9]+');
        Route::post('menu/{id}/toggle-publish', [
            'as'   => 'admin.menu.toggle-publish',
            'uses' => 'MenuController@togglePublish'
        ])->where('id', '[0-9]+');

        // log
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

        // language
        Route::get('language/set-locale/{language}', [
            'as'   => 'admin.language.set',
            'uses' => 'LanguageController@setLocale'
        ]);
    });
});

Route::post('/contact', [
    'as'   => 'dashboard.contact.post',
    'uses' => 'FormPostController@postContact'
], ['before' => 'csrf']);

// filemanager
Route::get('filemanager/show', function ()
{
    return View::make('backend/plugins/filemanager');
})->before('sentinel.auth');

// login
Route::get('/admin/login', [
    'as' => 'admin.login',
    function ()
    {
        return View::make('backend/auth/login');
    }
]);

Route::group(['namespace' => 'Admin'], function ()
{
    // admin auth
    Route::get('admin/logout', ['as' => 'admin.logout', 'uses' => 'AuthController@getLogout']);
    Route::get('admin/login', ['as' => 'admin.login', 'uses' => 'AuthController@getLogin']);
    Route::post('admin/login', ['as' => 'admin.login.post', 'uses' => 'AuthController@postLogin']);

    // admin password reminder
    Route::get('admin/forgot-password', [
        'as'   => 'admin.forgot.password',
        'uses' => 'AuthController@getForgotPassword'
    ]);

    Route::post('admin/forgot-password', [
        'as'   => 'admin.forgot.password.post',
        'uses' => 'AuthController@postForgotPassword'
    ]);

    Route::get('admin/{id}/reset/{code}', [
        'as'   => 'admin.reset.password',
        'uses' => 'AuthController@getResetPassword'
    ])->where('id', '[0-9]+');

    Route::post('admin/reset-password', [
        'as'   => 'admin.reset.password.post',
        'uses' => 'AuthController@postResetPassword'
    ]);
});

