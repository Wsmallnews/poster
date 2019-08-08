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

        $this->image->insert($imgResource, $this->position, $this->x, $this->y);
    }


    public function getResource () {
        if (is_string($this->path)) {
            if (!preg_match("/^(http:\/\/|https:\/\/)/", $this->path)) {
                $resource = (new Client())->get($this->path)->getBody();
                print_r($resource);exit;
            } else {
                $resource = $this->path;
            }
        }

        return $this->imageManager()->make($resource);
    }


    public function imageManager () {
        return new ImageManager();
    }
}
