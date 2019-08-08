<?php

namespace Smallnews\Poster\Contracts;

interface Draw
{

    /**
     * 将内容绘制在 image 上
     */
    public function applyToImage();

}
