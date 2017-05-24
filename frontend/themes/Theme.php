<?php

namespace frontend\themes;


use common\components\PackageInfo;
use common\components\ThemeManager;

abstract class Theme extends PackageInfo
{
    public $manager;
    public function __construct(ThemeManager $manager, array $config = [])
    {
        $this->manager = $manager;
        parent::__construct($config);
    }

    public function isActive()
    {
        return $this->getPackage() == $this->manager->getDefaultTheme();
    }
}