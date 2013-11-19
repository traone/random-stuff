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

    /**
     * @param array $numbers
     * @param bool|string $base_image
     */
    public function __construct( array $numbers, $base_image = false )
    {
        if ($base_image) {
            $this->base_image = $base_image;
        }

        $this->numbers = $numbers;
        $this->image   = new \Imagick($this->base_image);
        $this->createImageFromNumbers($this->numbers);
    }

    /**
     * @param $numbers array
     */
    private function createImageFromNumbers($numbers)
    {
        $draw = $this->getPencil();

        $grid = [
            ['x' => 51, 'y' => 58],
            ['x' => 117, 'y' => 58],
            ['x' => 183, 'y' => 58],
            ['x' => 250, 'y' => 58],
            ['x' => 315, 'y' => 58],
            ['x' => 381, 'y' => 58],
            ['x' => 448, 'y' => 58],
        ];

        foreach($numbers as $key => $number) {
            $this->image->annotateimage(
                $draw,
                $grid[$key]['x'],
                $grid[$key]['y'],
                0,
                (int) $number
            );
        }
    }

    /**
     * Send headers and print the image
     */
    public function render()
    {
        header('Content-type: image/png;');
        echo $this->image;
    }

    /**
     * @return \ImagickDraw
     */
    private function getPencil()
    {
        $draw = new \ImagickDraw();
        $draw->setfont('Bookman-DemiItalic');
        $draw->setfontsize(28);
        $draw->settextalignment(2); // 1 left, 2 center, 3 right
        $draw->setfillcolor('black');
        $draw->setfillopacity(0.7);

        return $draw;
    }

}


if ($_GET && isset($_GET['numbers'])) {

    $numbers = explode(',', strip_tags($_GET['numbers']));

    $numbers = array_map(function($val) {
        return (int) $val;
    }, $numbers);

    if (count($numbers) === 7) {
        try {
            (new \Lotto\Image($numbers))->render();
        }
        catch(\ImagickException $e) {
            echo $e->getTraceAsString();
        }
    } else {
        die('Needs seven numbers');
    }
} else {
    die(); // Silence
}