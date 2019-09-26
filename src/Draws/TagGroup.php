<?php

namespace Smallnews\Poster\Draws;

use Intervention\Image\Image as InterventionImage;

class TagGroup extends Draw
{
    protected $tags = [];           // 标签数组
    protected $x = 0;               // 开始打印 x 坐标
    protected $y = 0;               // 开始打印 y 坐标
    protected $width = 0;           // 最大打印宽度，超出隐藏或换行
    protected $color = '#FFBE41';   // 字体颜色
    protected $size = 24;           // 文字大小
    protected $lines = 1;           // 打印行数，超出之后不再打印;
    protected $spacing = 20;        // 两个标签间隔
    protected $padding = [5, 12, 5, 12];    // padding 顺序， 【上，右，下，左】
    protected $bgColor = "#FFF6DC"; // 背景颜色
    protected $border = 0;          // 背景边框 默认没有边框
    protected $bdColor = "#FFBE41"; // 边框颜色
    protected $heightSpacing = 10;  // 行与行间隔
    protected $fontFile = '';       // 自定义字体文件

    private $current_lines = 1;     // 已打印行数

    public function __construct (InterventionImage $image, Array $config) {
        // 默认字体
        $this->fontFile = dirname(__FILE__).'/../fonts/pingfang.ttf';

        // 海报底图资源
        $this->image = $image;

        Parent::initConfig($config, function ($value, $name) {
            if ($name == 'tags') {
                return is_string($value) ? [$value] : $value;
            }

            return $value;
        });
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


    /**
     * 将图片绘制在 image 底图资源上
     */
    public function applyToImage () {
        $this->drawTags();
    }


    /**
     * 绘制标签
     * @param  Array $lines 文本内容
     */
    public function drawTags() {
        $start_x = $this->x;
        $start_y = $this->y;
        $tags = $this->tags;
        $padding = $this->padding;

        foreach ($tags as $key => $tag) {
            $position = imagettfbbox($this->getPointSize(), 0, $this->fontFile, $tag);
            $text_width = abs($position[4] - $position[6]);         // 标签文字宽度
            $text_height = abs($position[1] - $position[7]);         // 标签文字高度

            // 画标签背景框
            $end_x = ($start_x + $text_width + ($padding[1] + $padding[3]));
            $end_y = ($start_y + $text_height + ($padding[0] + $padding[2]));

            // 判断是否超过最大宽度
            // echo "--".$start_x . '--' . $end_x."<br>";
            $all_draw_width = $end_x - $this->x;      // 当前行讲要打印的总宽度 = 结束位置 - 起始位置
            // 如果 X 不是起始位置
            if ($start_x != $this->x && $this->width) {
                // 总打印宽度超过可打印宽度
                // echo $all_draw_width . '--' . $this->width."<br>";
                if ($all_draw_width > $this->width) {
                    // echo $this->current_lines .'--'. $this->lines."<br>";
                    if ($this->current_lines < $this->lines) {
                        // 换行，重置开始结束坐标
                        $end_x = ($end_x - $start_x) + $this->x;
                        $start_x = $this->x;
                        $start_y = $end_y + $this->heightSpacing;
                        $end_y = $start_y + $text_height + ($padding[0] + $padding[2]);
                        // echo $start_x ."-" . $start_y."<br>";exit;
                        $this->current_lines ++;        // 打印行数增加 1
                    } else {
                        // 超出最大打印行数;
                        break;
                    }
                }
            } else {
                // 本行第一个标签，直接打印
            }

            $tag_bg = $this->image->rectangle($start_x, $start_y, $end_x, $end_y, function ($draw) {
                $draw->background($this->bgColor);
                if ($this->border) {
                    $draw->border($this->border, $this->bdColor);
                }
            });

            $text_start_x = ($start_x + $padding[3]);
            $text_start_y = ($start_y + $padding[0]);
            $this->image->text($tag, $text_start_x, $text_start_y, function($font) {
                $font->valign('top');
                $font->file($this->fontFile);
                $font->size($this->size);
                $font->color($this->color);
            });

            $start_x = $end_x + $this->spacing;     // 下一个标签开始位置
        }
    }
}
