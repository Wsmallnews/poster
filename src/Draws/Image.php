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
        // 默认字体
        $this->fontFile = dirname(__FILE__).'/../fonts/pingfang.ttf';

        // 海报底图资源
        $this->image = $image;

        Parent::initConfig($config);
    }


    public function applyToImage () {
        $imgResource = $this->getResource();

        if ($this->width && $this->height) {
            $imgResource->resize($this->width, $this->height);
        }

        if ($this->getOpacity() < 100) {
            $imgResource->opacity($this->getOpacity());
        }

        $this->image->insert($imgResource, $this->position, $this->x, $this->y);
    }


    public function getResource () {
        // print_r($this->path);exit;
        if (empty($this->path)) {
            throw new \Exception('draw image 缺少参数 path');
        }

        if (is_object($this->path)) {
            if ($this->path instanceof InterventionImage) {
                return $this->path;
            }
            throw new \Exception('图片资源不支持');
        }

        if (is_string($this->path)) {
            if (preg_match("/^(http:\/\/|https:\/\/)/", $this->path)) {
                $result = (new Client())->get($this->path);

                $resource = $result->getBody();
            } else {
                $resource = $this->path;
            }
        }

        return $this->imageManager()->make($resource);
    }


    public function imageManager () {
        return new ImageManager();
    }

    private function getOpacity() {
        return abs($this->opacity);
    }
}
