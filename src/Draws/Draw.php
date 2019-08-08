<?php

namespace Smallnews\Poster\Draws;

use Smallnews\Poster\Contracts\Draw as ContractDraw;

class Draw implements ContractDraw
{

    public function applyToImage()
    {
    }


    // 字符串下划线转驼峰
    private function camelize($uncamelizedWords, $separator='_') {
        $uncamelizedWords = $separator . str_replace($separator, " ", strtolower($uncamelizedWords));
        return ltrim(str_replace(" ", "", ucwords($uncamelizedWords)), $separator);
    }


    protected function initConfig ($config) {
        foreach ($config as $key => $value) {
            $protectName = $this->camelize($key);
            if (property_exists($this, $protectName)) {
                $this->{$protectName} = $value;
            }
        }
    }
}
