<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace xzncit;

use xzncit\exception\ClassNotFoundException;
use xzncit\exception\ConfigNotFoundException;

/**
 * Class SMS
 * @package xzncit
 */
class SMS {

    /**
     * Current version of program
     * @var string
     */
    public static $version = "1.0.0";

    /**
     * @param $name
     * @param array $options
     * @return mixed
     * @throws ClassNotFoundException
     * @throws ConfigNotFoundException
     */
    public static function create($name,$options=[]){
        $obj = "\\xzncit\\" . strtolower($name) . "\\" . ucfirst($name);
        if(!class_exists($obj)){
            throw new ClassNotFoundException("class [$name] does not exist",0);
        }

        if(empty($options)){
            throw new ConfigNotFoundException("config does not exist",0);
        }

        return new $obj($options);
    }

}