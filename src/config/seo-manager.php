<?php

return [
    /**
     * Database table name where your manager data will be stored
     */
    'database' => [
        'table' => 'seo_manager',
        'locales_table' => 'seo_manager_locales',
        'translates_table' => 'seo_manager_translates',
        'enable_table' => 'seo_enables',
    ],
    "morph_name"=>"seotable",
    "morph_class"=>"\Lionix\SeoManager\Models\SeoEnable",
    /**
     * Set default locale,
     * It will be added as default locale
     * when locales table migrated
     */
    'locale' => 'en',

    'locales' => [
        'en',
        'ar'
    ],
    'models_extends_class'=>[
        \Illuminate\Database\Eloquent\Model::class,
        \Illuminate\Foundation\Auth\User::class
    ],
    /**
     * Path where your eloquent models are
     * Leave this config empty if you want to look for models in whole project
     */
    'models_path' => 'Models',

    /**
     * Route from which your Dashboard will be available
     */
    'route' => 'seo-manager',

    /**
     * Middleware array for dashboard
     * to prevent unauthorized users visit the manager
     */
    'middleware' => [
        'web'
        //  'auth',
    ],

    /**
     * Routes which shouldn't be imported to seo manager
     */
    'except_routes' => [
        '*/seo-manager',
        'seo-manager',
        'translation-manager',
        'oauth',
        'password',
        'dashboard',
        'admin',
        'api',
    ],

    /**
     * Columns which shouldn't be used ( in mapping )
     */
    'except_columns' => [
         "created_at",
         "updated_at",
    ],

    'name' => 'seo-manager',
    'namespace' => '\Lionix\SeoManager',

    /**
     * Set this parameter to true
     * if you want to have "$metaData" variable
     * shared between all views in "web" middleware group
     */
    'shared_meta_data' => true
];
