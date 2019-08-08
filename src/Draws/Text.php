<?php

namespace Smallnews\Poster\Draws;

use Intervention\Image\Image as InterventionImage;

class Text extends Draw
{
    protected $string;
    protected $isHidden = false;
    protected $x = 0;
    protected $y = 0;
    protected $color = '#FFFFFF';
    protected $size = 24;
    protected $fontFile = '';
    protected $align = 'left';
    protected $valign = 'top';
    protected $lineHeight = 24;
    protected $startLen = 0;       // 最大中文字符;
    protected $lines = 1;           // 打印行;
    protected $width = 100;         // 文本打印宽;

    public function __construct (InterventionImage $image, Array $config) {
        // 默认字体
        $this->fontFile = dirname(__FILE__).'/../fonts/pingfang.ttf';

        // 海报底图资源
        $this->image = $image;

        Parent::initConfig($config);
    }



    /**
     * Get font size in points
     *
     * @return int
     */
    protected function getPointSize()
    {
        return intval(ceil($this->size * 0.75));
    }



    public function applyToImage () {
        $lines = $this->getLines();

        $this->drawText($lines);
    }


    public function getLines () {
        if ($this->isHidden) {
            $lines = $this->getBreakLines();
        } else {
            $lines = [$this->string];
        }

        return $lines;
    }


    public function drawText($lines) {
        $x = $this->x;
        $y = $this->y;
        $line_height = $this->lineHeight;

        $callback = function ($font) {
            $font->valign($this->valign);
            $font->align($this->align);
            $font->file($this->fontFile);
            $font->size($this->size);
            $font->color($this->color);
        };

        foreach ($lines as $key => $text) {
            $this->image->text($text, $x, $y, $callback);
            $y = $y + $line_height;
        }
    }


    /**
     * 根据传入行数，每行宽度，将文本换行，超出部分 显示 ...
     * @param  string $text   要处理的文本
     * @param  array $params 必要的参数
     * @return array         处理之后的文本数组
     */
    public function getBreakLines () {
        $text = $this->string;
        // 文本总长度
        $strlen = mb_strlen($text);
        // 区域宽度能放入的最大中文数量，因为中文，比字母数字宽，所以这里使用中文最大数量
        $min_len = $this->startLen;
        // 如果要处理的文本，小于一行最大中文数量，则无需处理，直接返回
        if ($strlen <= $min_len) {
            return [$text];
        }

        $lines = [];        // 最终处理的结果
        $start_sub = 0;     // 开始截取位置
        $current_line = 1;  // 当前处理的行数
        do {
            $is_break = false;      // 是否截断，用来判断最后一行是否需要 ...
            for ($i = ($min_len + 1); $i <= $strlen; $i ++) {
                $tmp_str = mb_substr($text, $start_sub, $i);
                $position = imagettfbbox($this->getPointSize(), 0, $this->fontFile, $tmp_str);

                $text_width = abs($position[4] - $position[6]);
                if ($text_width > $this->width) {
                    $is_break = true;
                    break;
                }
            }

            $lines[] = ($current_line >= $this->lines && $is_break) ? mb_substr($tmp_str, 0, mb_strlen($tmp_str) - 1)."..." : $tmp_str;

            // 下次循环开始截取的位置
            $start_sub = $start_sub + $i;
            $next_str = mb_substr($text, $start_sub);
            $next_len = mb_strlen($next_str);

            $current_line ++ ;
        } while ($next_len > 0 && $current_line <= $this->lines);       // 剩余的还有内容，并且 还需要下一行

        // 返回文本数组
        return $lines;
    }

}
