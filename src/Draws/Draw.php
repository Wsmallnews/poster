<?php

namespace Smallnews\Poster\Draws;

use Smallnews\Poster\Contracts\Draw as ContractDraw;

class Draw implements ContractDraw
{

    /**
     * 将内容绘制在 image 上
     */
    public function applyToImage()
    {
    }


    /**
     * 字符串下划线转驼峰
     * @param  string $uncamelizedWords 要处理的字符串
     * @param  string $separator        下划线
     * @return string 驼峰字符串
     */
    private function camelize($uncamelizedWords, $separator='_') {
        $uncamelizedWords = $separator . str_replace($separator, " ", strtolower($uncamelizedWords));
        return ltrim(str_replace(" ", "", ucwords($uncamelizedWords)), $separator);
    }


    /**
     * 将内容配置设置到类属性
     * @param  Array $config 配置信息
     */
    protected function initConfig (Array $config) {
        foreach ($config as $key => $value) {
            $protectName = $this->camelize($key);
            if (property_exists($this, $protectName)) {
                $this->{$protectName} = $value;
            }
        }
    }
}
