<h1 align="center"> poster </h1>

<p align="center"> A make poster package.</p>

## Requirements

* PHP >= 5.4
* intervention/image >= 2.0
* Fileinfo PHP Extension


## Installing

```
$ composer require smallnews/poster -vvv
```

## Usage

```
require __DIR__ .'/vendor/autoload.php';

use Smallnews\Poster\MakePoster;


$makePoster = new MakePoster();

$makePoster->init([
    'path' => './posterBG.png'),
    'width' => 750,
    'height' => 1334
])->addTexts([[
    'string' => '这里是很长很长很长的商家名称',
    'is_hidden' => true,        // 超出打印宽度是否 显示省略号
    'x' => 250,                 // 打印位置 x 坐标
    'y' => 206,                 // 打印位置 y 坐标
    'line' => [                 // 文字划线，删除线|下划线|上划线
        'type' => 'through',    // through |overline | underline
        'color' => '#FF0000',
    ],
    'line' => 'through',        // 文字划线，简写版 颜色默认为字体颜色
    'color' => '#ffffff',       // 字体颜色
    'size' => 28,               // 字体大小
    'font_file' => './pingfang.ttf',    // 自定义字体文件，默认字体文件 pingfang.ttf
    'align' => 'left',          // 对齐方式，同 intervention/image align
    'valign' => 'top',          // 垂直对齐方式，同 intervention/image valign
    'line_height' => 38,        // 每一行的高度，也就是下一行开始打印的位置为 y + line_height
    'width' => 375,             // 文本打印宽度
    'lines' => 1,               // 打印行数
    'start_len' => 8,           // 最大中文字符数，width 能容下的最多的文字个数减去 1-5
],[
    ...
]])->addImages([[
    // 产品图
    'path' => (new ImageManager())->make('./poster-logo.png'),
    'width' => 634,
    'height' => 380,
    'x' => 58,
    'y' => 276,
],[
    // 头像
    'path' => './poster-logo.png',
    'width' => 80,
    'height' => 80,
    'x' => 66,
    'y' => 1062,
]])->addTagGroups([[                                       // 所有配置参数
    'tags' => ['第一个标签', '90 后', ...],  // 标签数组
    'tags' => '第一个标签',                  // 单个标签
    'x' => 58,                              // 开始打印 x 坐标
    'y' => 276,                             // 开始打印 y 坐标
    'width' => 450,                         // 最大打印宽度，超出隐藏或换行
    'color' => '#FFBE41',                   // 字体颜色
    'size' => 28,                           // 文字大小
    'lines' => 2,                           // 打印行数，超出剩余的标签将不显示
    'spacing' => 30,                        // 两个标签之间的距离
    'padding' => [10, 24, 10, 24],          // 文字距离背景的 padding  【上，右，下，左】
    'bg_color' => '#FFF6DC',                // 背景颜色
    'border' => 1,                          // 背景边框，默认 0 没有边框
    'bd_color' => "#FFBE41",                // 边框颜色
    'height_spacing' => 20,                 // 两行之间的距离
    'font_file' => './pingfang.ttf',        // 字体文件，默认 苹方常规字体
],[                                       // 简单打印一个标签
    'tags' => '第一个标签',                  // 单个标签
    'x' => 58,                              // 开始打印 x 坐标
    'y' => 476,                             // 开始打印 y 坐标
]])
->draw()->save('./abc.jpg');

```

## Api

初始化图像实例
```
$makePoster->init([]);
```

添加水印文字
```
$makePoster->addText([字体属性]);
```

添加多个水印文字

```
$makePoster->addTexts([
    [字体属性],
    [字体属性],
]);
```

添加水印图片
```
$makePoster->addImage([图片属性]);
```

添加多个水印图片

```
$makePoster->addImages([
    [图片属性],
    [图片属性],
]);
```

添加标签组
```
$makePoster->addTagGroup([标签属性]);
```

添加多个标签组

```
$makePoster->addTagGroups([
    [标签属性],
    [标签属性],
]);
```

将添加的水印 绘制到图像实例
```
$makePoster->draw();
```

将未绘制的水印，添加到图像实例，并保存图像实例
```
$makePoster->save();
```

本扩展包仅仅对 intervention/image 的部分进行了功能行封装， 可通过 image 属性直接调用 intervention/image 方法

```
$makePoster->image->text('文本', 0, 0, function($font) {
    $font->size(24);
    $font->color('#fdf6e3');
};
```

## License

The MIT License (MIT). Please see License File for more information.
