<?php

namespace Smallnews\Poster;

use GuzzleHttp\Client;
use Intervention\Image\ImageManager;

class MakePoster
{
    protected $config = [];

    protected $texts = [];

    protected $images = [];

    protected $tagGroups = [];

    public $image = null;

    public function __construct (Array $config = []) {
        $this->setConfig($config);

        if (isset($config['init'])) {
            return $this->init($config['init']);
        }
    }


    /**
     * 全局配置信息
     * @param array $config 配置
     */
    public function setConfig (Array $config = []) {
        $this->config = $config;

        $this->texts = isset($config['texts']) ? $config['texts'] : [];

        $this->images = isset($config['images']) ? $config['images'] : [];
    }



    /**
     * 初始化底图
     * @param  array  $init 底图资源
     * @return 当前对象
     */
    public function init (Array $init = []) {
        if ($init['path']) {
            // 返回一个背景资源
            $this->image = $this->imageManager()->make($init['path']);

            if ($init['width'] || $init['height']) {
                $this->image->resize($init['width'] ? : null, $init['height'] ?? null);
            }
        } else if ($init['width'] && $init['height']) {
            // 返回一个 画布资源
            $this->image = $this->imageManager()->canvas($init['width'], $init['height']);
        } else {
            throw new \Exception('No basic picture of poster');
        }

        return $this;
    }


    /**
     * 获取 Intervention\Image\ImageManager 对象
     * @return [type] Intervention\Image\ImageManager
     */
    public function imageManager () {
        return new ImageManager();
    }


    /**
     * 增加一行文本
     * @param Array 文本配置
     */
    public function addText(Array $text) {
        if (is_string($text)) {
            $text = ['string' => $text];
        }

        return $this->addTexts([$text]);
    }



    /**
     * 增加多个文本
     * @param Array 多个文本配置
     */
    public function addTexts (Array $texts) {
        $this->texts = array_merge($this->texts, $texts);

        return $this;
    }


    /**
     * 增加一个标签组
     * @param Array 标签组配置
     */
    public function addTagGroup(Array $tagGroup) {
        if (is_string($tagGroup)) {
            $tagGroup = ['tags' => $tagGroup];
        }

        return $this->addTagGroups([$tagGroup]);
    }



    /**
     * 增加多个标签组
     * @param Array 多个标签组配置
     */
    public function addTagGroups (Array $tagGroups) {
        $this->tagGroups = array_merge($this->tagGroups, $tagGroups);

        return $this;
    }


    /**
     * 增加一张图片
     * @param Array 图片资源配置
     */
    public function addImage ($image) {
        if (is_string($image)) {
            $image = ['path' => $image];
        }

        return $this->addImages([$image]);
    }


    /**
     * 增加多张图片
     * @param Array 多张图片资源配置
     */
    public function addImages ($images) {
        $this->images = array_merge($this->images, $images);

        return $this;
    }



    /**
     * 将 加入的 文本，图像 绘制到底图
     * @return [type] [description]
     */
    public function draw () {
        $this->makeText();

        $this->makeImage();

        $this->makeTagGroup();
        return $this;
    }


    /**
     * 循环绘制图片
     */
    public function makeImage() {
        foreach ($this->images as $key => $image) {
            $this->drawContent('image', $image);

            unset($this->images[$key]);      // 绘制过的 图片 移除待处理数组
        }

        return $this;
    }


    /**
     * 循环绘制文字
     */
    public function makeText () {
        foreach ($this->texts as $key => $text) {
            $this->drawContent('text', $text);

            unset($this->texts[$key]);      // 绘制过的 文本 移除待处理数组
        }

        return $this;
    }


    /**
     * 循环绘制标签
     */
    public function makeTagGroup () {
        foreach ($this->tagGroups as $key => $tagGroup) {
            $this->drawContent('tagGroup', $tagGroup);

            unset($this->tagGroups[$key]);      // 绘制过的 标签组 移除待处理数组
        }

        return $this;
    }


    /**
     * 调用对应类绘制对应内容
     * @param  [type] $type          [description]
     * @param  [type] $contentConfig [description]
     * @return [type]                [description]
     */
    public function drawContent ($type, $contentConfig) {
        if (is_null($this->image)){
            throw new \Exception("No basic picture of poster");
        }

        $className = "Smallnews\\Poster\\Draws\\" . ucfirst($type);
        if (class_exists($className)) {
            $drawContent = new $className($this->image, $contentConfig);
            $drawContent->applyToImage();
        } else if (method_exists($this, 'draw' . ucfirst($type))) {
            $this->{'draw' . ucfirst($type)}($contentConfig);

        } else {
            throw new \Exception("no draw {$type} methods");
        }
    }



    /**
     * 绘制水印，并保存图片到指定位置
     * @param  string $path          图片路径
     */
    public function save($path = '') {
        // 将 未 绘制的 文字，图片进行绘制
        $this->draw();

        // 保存海报
        $this->image->save($path);
    }
}
