<?php

namespace Smallnews\Poster;

use GuzzleHttp\Client;
use Intervention\Image\ImageManager;

class MakePoster
{
    protected $config = [];

    protected $texts = [];

    protected $images = [];

    protected $image = null;

    public function __construct (Array $config = []) {
        $this->setConfig($config);

        if ($config['init']) {
            return $this->init($config['init']);
        }
    }


    public function setConfig (Array $config = []) {
        $this->config = $config;

        $this->texts = isset($config['texts']) ? $config['texts'] : [];

        $this->images = isset($config['images']) ? $config['images'] : [];
    }



    public function init (Array $init = []) {
        if ($init['path']) {
            // 返回一个背景资源
            $this->image = $this->imageManager()->make($init['path']);

            if ($init['width'] && $init['height']) {
                $this->image->resize($init['width'], $init['height']);
            }
        } else if ($init['width'] && $init['height']) {
            // 返回一个 画布资源
            $this->image = $this->imageManager()->canvas($init['width'], $init['height']);
        } else {
            throw new \Exception('Don`t make background base poster');
        }

        return $this;
    }


    public function imageManager () {
        return new ImageManager();
    }


    // 增加文本
    public function addText($text) {
        if (is_string($text)) {
            $text = ['string' => $text];
        }

        return $this->addTexts([$text]);
    }


    // 增加多个文本
    public function addTexts ($texts) {
        $this->texts = array_merge($this->texts, $texts);

        return $this;
    }


    // 增加图片
    public function addImage ($image) {
        if (is_string($image)) {
            $image = ['path' => $image];
        }

        return $this->addImages([$image]);
    }


    // 增加多个图片
    public function addImages ($images) {
        $this->images = array_merge($this->images, $images);

        return $this;
    }



    public function draw () {
        $this->makeText();

        $this->makeImage();

        return $this;
    }



    public function makeImage() {
        foreach ($this->images as $key => $image) {
            $this->drawContent('image', $image);

            unset($this->images[$key]);      // 绘制过的 图片 移除待处理数组
        }

        return $this;
    }


    public function makeText () {
        foreach ($this->texts as $key => $text) {
            $this->drawContent('text', $text);

            unset($this->texts[$key]);      // 绘制过的 文本 移除待处理数组
        }

        return $this;
    }


    public function drawContent ($type, $config) {
        $className = "Smallnews\\Poster\\Draws\\" . ucfirst($type);
        if (class_exists($className)) {
            $drawContent = new $className($this->image, $config);
            $drawContent->applyToImage();
        } else if (method_exists($this, 'draw' . ucfirst($type))) {
            $this->{'draw' . ucfirst($type)}($config);

        } else {
            throw new \Exception("no draw {$type} methods");
        }
    }


    // public function drawImage($image) {
    //     $imgResource = $this->imageManager()->make($image['path']);
    //
    //     if ($image['width'] && $image['height']) {
    //         $imgResource->resize($image['width'], $image['height']);
    //     }
    //
    //     $this->image->insert($imgResource, $image['position'], $image['x'], $image['y']);
    // }



    public function save($path = '') {
        // 将 未 绘制的 文字，图片进行绘制
        $this->draw();

        // 保存海报
        $this->image->save($path);
    }
}
