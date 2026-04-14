<?php

/*
|--------------------------------------------------------------------------
| Prettus Repository Config
|--------------------------------------------------------------------------
|
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Repository Pagination Limit Default
    |--------------------------------------------------------------------------
    |
    */
    'pagination'=>[
        'limit'=>15
    ],

    'generator'  => [
        'basePath'      => base_path('Modules/Booking/app'),
        'rootNamespace' => 'Modules\Booking\\',
        'stubsOverridePath' => app_path(),
        'paths'         => [
            'models'       => 'Models',
            'repositories' => 'Repositories',
            'interfaces'   => 'Repositories',
            'transformers' => 'Transformers',
            'presenters'   => 'Presenters',
            'validators'   => 'Validators',
            'controllers'  => 'Http\Controllers',
            'provider'     => 'RepositoryServiceProvider',
            'criteria'     => 'Criteria',
        ]
    ]
];