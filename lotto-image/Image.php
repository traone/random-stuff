<?php
/**
 * Created by PhpStorm.
 * User: henryk
 * Date: 19.11.13
 * Time: 21:25
 */

namespace Lotto;


class Image {

    private $image;
    private $base_image = 'base.png';
    private $numbers     = [];

    public function __construct( array $numbers, $base_image = false )
    {
        if ($base_image) {
            $this->base_image = $base_image;
        }

        $this->numbers = $numbers;
        $this->image   = new \Imagick($this->base_image);
        $this->createImageFromNumbers($numbers);
    }

    private function createImageFromNumbers($numbers)
    {
        $draw = new \ImagickDraw();
        $draw->setfont('Bookman-DemiItalic');
        $draw->setfontsize(28);
        $draw->settextalignment(2); // 1 left, 2 center, 3 right
        $draw->setfillcolor('black');

        $grid = [
            ['x' => 52, 'y' => 58],
            ['x' => 120, 'y' => 58],
            ['x' => 185, 'y' => 58],
            ['x' => 252, 'y' => 58],
            ['x' => 317, 'y' => 58],
            ['x' => 382, 'y' => 58],
            ['x' => 450, 'y' => 58],
        ];

        foreach($numbers as $key => $number) {
            $this->image->annotateimage(
                $draw,
                $grid[$key]['x'],
                $grid[$key]['y'],
                0,
                $number
            );
        }
    }

    public function render()
    {
        header('Content-type: image/png;');
        echo $this->image;
    }

}


if ($_GET && isset($_GET['numbers'])) {

    $numbers = explode(',', strip_tags($_GET['numbers']));

    if (count($numbers) === 7) {

        try {
            $image = new \Lotto\Image($numbers);
            $image->render();
        }
        catch(\Exception $e) {
            echo $e->getTraceAsString();
        }



    } else {
        die('Needs seven numbers');
    }
} else {
    die('xoo');
}