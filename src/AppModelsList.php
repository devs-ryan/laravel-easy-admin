<?php

namespace Raysirsharp\LaravelEasyAdmin;

class AppModelsList
{
    /**
     * Return array of models and namespaces
     *
     * @return Array
     */
    public static function models() 
    {
        return [
            //Models Here - Format: Namespace.Model
            'App.BlogPost',
        ];
    }
}