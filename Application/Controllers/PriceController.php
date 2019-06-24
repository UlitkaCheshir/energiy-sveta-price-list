<?php


namespace Application\Controllers;


class PriceController extends BaseController
{
    public function Price(){
        $nameFile = "energiya-svetacom_opt_test.csv";

        $directory = __DIR__;

        $index  = strripos($directory, "\\");
        $directory = substr($directory, 0 , $index);

        $index  = strripos($directory, "\\");
        $directory = substr($directory, 0 , $index);

        $directory = "{$directory}\\public\\price_list\\{$nameFile}";

        $dataInFile = file_get_contents("{$directory}");

        $dataInFile =  mb_convert_encoding($dataInFile, "utf-8", "windows-1251");

        $arrProduct = explode("\n", $dataInFile);

//        ?>
<!--        <pre>-->
<!--            --><?php
//            print_r($arrProduct);
//            ?>
<!--        </pre>-->
<!---->
<!--        --><?php
        $textInFile = "model;sku;upc;quantity;stock_status_id;image;shipping;price;weight;weight_class_id;length;width;height;length_class_id;subtract;minimum;sort_order;status;manufacturer;name;description;tag;meta_title;meta_description;meta_keyword;additional_images;product_attribute;product_category;product_special\n";
        foreach ($arrProduct as $key=>$product){
//            if($key === 0 || $product === ''){
            if($product === ''){
                continue;
            }//
            $singleproduct = explode(";", $product);

            ?>
            <pre>
            <?php
//            print_r();
            print_r($singleproduct);
            ?>


        </pre>

            <?php

//            $textInFile =
//            product_attribute;
//            product_category;
//            product_special';

            $textInFile .= "{$singleproduct[0]};";  //модель
            $textInFile .= "{$singleproduct[11]};";  //артикул
            $textInFile .= "{$singleproduct[53]};";       //штрихкод upc
            $textInFile .= "{$singleproduct[65]};";           //кол-во
            $textInFile .= intval($singleproduct[1]).";";       //есть в наличии или нет  intval(true)
            $textInFile .= "{$singleproduct[7]};"; //изображение
            $textInFile .= "0;"; //доставка 0-нет 1-да
            $textInFile .= "{$singleproduct[61]};"; //цена
            $textInFile .= "{$singleproduct[54]};"; //вес
            $textInFile .= "1;"; //вес в кг 1, в г 2
            $textInFile .= "{$singleproduct[17]};"; //длина
            $textInFile .= "{$singleproduct[18]};"; //ширина
            $textInFile .= "{$singleproduct[15]};"; //высота
            $textInFile .= "2;"; //единицы измерения длины См — 3,1; мм — 2
            $textInFile .= "1;"; //вычисление со склада
            $textInFile .= "1;"; //минимальное количество в заказе
            $textInFile .= "{$singleproduct[60]};"; //порядок сортировки
            $textInFile .= "1;"; //включен или отключен
            $textInFile .= "{$singleproduct[3]};"; //производитель
            $textInFile .= "{$singleproduct[2]};"; //название

            //================ФОРМИРОВАНИЕ ОПИСАНИЯ======================//
            $description = "<p>Расширенная гарантия на товар составляет ";
            $description .= "{$singleproduct[51]}</p>";
            $description .= "<p>Поставщик рекомендует использовать лампы накаливания с цоколем {$singleproduct[27]} ";
            $description .= "для освещения площади {$singleproduct[26]}м2. ";
            $description .= "{$singleproduct[2]} в стиле {$singleproduct[44]} ";

            mb_internal_encoding("UTF-8");
            $intererIndex = stripos("{$singleproduct[46]}", ' ');
            $interer = substr("{$singleproduct[46]}", $intererIndex);
            $description .= "используется в интерьере{$interer} ";



            if(!$singleproduct[42]==""){
                $place_setup = mb_strtolower($singleproduct[42]);
                $description .= "с установкой {$place_setup}";
            }

            $description .= ".";
            //================КОНЕЦ ФОРМИРОВАНИЕ ОПИСАНИЯ======================//
            $textInFile .= "{$description};"; //описание

            $textInFile .= "{$singleproduct[12]},{$singleproduct[14]};"; //тег
            $textInFile .= "{$singleproduct[2]};"; //meta tag
            $textInFile .= "{$singleproduct[2]} купить в Донецке ДНР;"; //meta description
            $textInFile .= "{$singleproduct[2]} купить в Донецке, Макеевке, Горловке, Торезе, Снежном, Шахтёрске, Енакиево, Ясиноватой, Харцизске, Амросиевке, Новоазовске, Москве, Ростове, Шахтах, Каменск-Шахтинске, Новошахтинске;"; //meta keyword

            //===========ДОПОЛНИТЕЛЬНЫЕ ИЗОБРАЖЕНИЯ=====================
            $additionalImg = '';

            if(!$singleproduct[8]==""){
                $additionalImg.= "{$singleproduct[8]}";
            }
            if(!$singleproduct[9]==""){
                $additionalImg.= "|{$singleproduct[9]}";
            }
            if(!$singleproduct[10]==""){
                $additionalImg.= "|{$singleproduct[10]}";
            }

            $textInFile .= "{$additionalImg};"; //дополнительные изображения


//            date_default_timezone_set('Europe/Minsk');
//            $date = date('d-m-Y H-i-s ', time());
//
//            if( !file_exists("/home/rhscom/of-w.com/candidates/files")){
//                mkdir("/home/rhscom/of-w.com/candidates/files");
//            }//if
//
//            $file = "/home/rhscom/of-w.com/candidates/files/{$date}_candidates.csv";
//
//            $data = mb_convert_encoding($textInFile, 'UTF-8', 'OLD-ENCODING');
//            $resultfile = file_put_contents($file, $data);

        }//foreach
        ?>


                <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8" />
                    <title>PriceList</title>
                </head>

                <body>

                <div>Hello</div>


                </body>
             </html>
    <?php
    }//Price
}