<?php

namespace App\Controllers;

use App\Support\Classes\ClassBreadcrumb;

/**
 * Class Controller
 * @package App\Controllers
 */
class Controller
{
    /** @var object */
    private $loader;

    /** @var object */
    protected $twig;

    /**
     * @var
     */
    protected $router;

    /**
     * Controller constructor.
     * @param $router
     */
    protected function __construct($router)
    {
        $this->router = $router;

        $this->loader = new \Twig_Loader_Filesystem(SITE["root"]."/views");
        $this->twig = new \Twig_Environment($this->loader, [
//            "cache" => SITE["root"]."/views/cache"
        ]);

        $functions = [
            "breadcrumb" => new \Twig_SimpleFunction("breadcrumb", function () {
                return (new ClassBreadcrumb)->addBreadcrumb();
            }),
            "site" => new \Twig_SimpleFunction("site", function ($param) {
                return site($param);
            }),
            "asset" => new \Twig_SimpleFunction("asset", function (
                string $path
            ) {
                return asset($path);
            }),
            "flash" => new \Twig_SimpleFunction("flash", function (
                string $type = null,
                string $message = null
            ) {
                return flash($type, $message);
            })
        ];

        foreach ($functions as $function) {
            $this->twig->addFunction($function);
        }
    }
}