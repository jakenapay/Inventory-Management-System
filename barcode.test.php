<div>
    <?php
    require './vendor/autoload.php';

    $barcode = "./images/barcode/item". time() . ".png";

    $color = [0, 0, 0];

    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    file_put_contents($barcode, $generator->getBarcode('335733', $generator::TYPE_CODE_128, 3, 50, $color));

 
    ?>

    <!-- <h6><?php echo $generator->getBarcode('111111', $generator::TYPE_CODE_128) ?></h6> -->
    <br>
    <img src = "./images/items1706433619.png">
    <!-- <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('22222', $generator::TYPE_CODE_128) ?></h6>



    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('33333', $generator::TYPE_CODE_128) ?></h6>


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('4444', $generator::TYPE_CODE_128) ?></h6>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <h6><?php echo $generator->getBarcode('555555', $generator::TYPE_CODE_128) ?></h6>
    <br>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('1611297', $generator::TYPE_CODE_128) ?></h6>



    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('7481585', $generator::TYPE_CODE_128) ?></h6>


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('282344', $generator::TYPE_CODE_128) ?></h6>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('297191', $generator::TYPE_CODE_128) ?></h6>
    <br>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('502418', $generator::TYPE_CODE_128) ?></h6>



    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('198574', $generator::TYPE_CODE_128) ?></h6>


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('437405', $generator::TYPE_CODE_128) ?></h6>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('636402', $generator::TYPE_CODE_128) ?></h6>
    <br>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('556645', $generator::TYPE_CODE_128) ?></h6>



    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('335733', $generator::TYPE_CODE_128) ?></h6>


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('2792718', $generator::TYPE_CODE_128) ?></h6>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>


    <h6><?php echo $generator->getBarcode('976760', $generator::TYPE_CODE_128) ?></h6>
    <br>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('7542994', $generator::TYPE_CODE_128) ?></h6>



    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('275921', $generator::TYPE_CODE_128) ?></h6>


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('4444', $generator::TYPE_CODE_128) ?></h6>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>


    <h6><?php echo $generator->getBarcode('111111', $generator::TYPE_CODE_128) ?></h6>
    <br>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('22222', $generator::TYPE_CODE_128) ?></h6>



    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('33333', $generator::TYPE_CODE_128) ?></h6>


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <h6><?php echo $generator->getBarcode('123456', $generator::TYPE_CODE_128) ?></h6> -->
</div>