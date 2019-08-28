<?php

namespace Smallnews\Poster\Draws;

use Intervention\Image\Image as InterventionImage;
use Intervention\Image\ImageManager;
use GuzzleHttp\Client;

class Image extends Draw
{
    protected $path = '';
    protected $width = 0;
    protected $height = 0;
    protected $position = 'bottom-left';
    protected $x = 0;
    protected $y = 0;
    protected $opacity = 100;


    public function __construct (InterventionImage $image, Array $config) {
        // 海报底图资源
        $this->image = $image;

        Parent::initConfig($config);
    }


    /**
     * 将图片绘制在 image 底图资源上
     */
    public function applyToImage () {
        $imgResource = $this->getResource();

        if ($this->width || $this->height) {
            $imgResource->resize($this->width ? : null, $this->height ? : null);
        }

        if ($this->getOpacity() < 100) {
            $imgResource->opacity($this->getOpacity());
        }

        $this->image->insert($imgResource, $this->position, $this->x, $this->y);
    }


    /**
     * 获取传入的图片资源
     * @return InterventionImage
     */
    public function getResource () {
        if (empty($this->path)) {
            throw new \Exception('draw image 缺少参数 path');
        }

        $resource = $this->path;

        if (is_string($this->path)) {
            if (preg_match("/^(http:\/\/|https:\/\/)/", $this->path)) {
                $result = (new Client())->get($this->path);

                $resource = $result->getBody();
            }
        }

        return $this->imageManager()->make($resource);
    }


    /**
     * 获取 Intervention\Image\ImageManager 对象
     * @return Intervention\Image\ImageManager
     */
    public function imageManager () {
        return new ImageManager();
    }


    /**
     * 获取透明度
     */
    private function getOpacity() {
        return abs($this->opacity);
    }
}
