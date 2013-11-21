<?php
/**
 * Created by PhpStorm.
 * User: henryk
 * Date: 19.11.13
 * Time: 21:25
 */

namespace Lotto;

class Image {

    /**
     * @var \Imagick
     */
    private $image;
    /**
     * @var string
     */
    private $base_image  = 'base.png';
    /**
     * @var array
     */
    private $numbers     = array();

    /**
     * @param array $numbers
     */
    public function __construct( array $numbers )
    {
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
        $grid = $this->getGrid();

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

    private function getGrid()
    {
        return array(
            array('x' => 78, 'y' => 47),
            array('x' => 145, 'y' => 47),
            array('x' => 211, 'y' => 47),
            array('x' => 44, 'y' => 119),
            array('x' => 110, 'y' => 119),
            array('x' => 177, 'y' => 119),
            array('x' => 243, 'y' => 119),
        );

    }

}

if ($_GET && isset($_GET['numbers'])) {

    $numbers = explode('-', strip_tags($_GET['numbers']));
    $numbers = array_map(function($val) {
        return (int) $val;
    }, $numbers);

    if (count($numbers) === 7) {
        try {
            $lotto = new \Lotto\Image($numbers);
            $lotto->render();
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