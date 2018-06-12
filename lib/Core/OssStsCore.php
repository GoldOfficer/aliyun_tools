<?php
/**
 * Created by PhpStorm.
 * User: Baihuzi
 * Date: 2018/6/12
 * Time: 17:44
 */

namespace MyOK\AliyunTools\Core;

use MyOK\OssSts\OssSts;

class OssStsCore extends OssSts
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }
}