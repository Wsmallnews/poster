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
        'color' => '#ffffff',       // 字体颜色
        'size' => 28,               // 字体大小
        'font_file' => './pingfang.ttf',    // 自定义字体文件，默认字体文件 pingfang.ttf
        'align' => 'left'           // 对齐方式，同 intervention/image align
        'valign' => 'top'           // 垂直对齐方式，同 intervention/image valign
        'line_height' => 38,        // 每一行的高度，也就是下一行开始打印的位置为 y + line_height
        'width' => 375              // 文本打印宽度
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

将添加的水印 绘制到图像实例
```
$makePoster->draw();
```

将未绘制的水印，添加到图像实例，并保存图像实例
```
$makePoster->save();
```

本扩展包仅仅优化了 intervention/image 的部分功能， 可通过 image 属性直接调用 intervention/image 方法

```
$makePoster->image->text('文本', 0, 0, function($font) {
    $font->size(24);
    $font->color('#fdf6e3');
};
```

## License

The MIT License (MIT). Please see License File for more information.
