<?php


namespace Application\Controllers;


class PriceController extends BaseController
{
    public function PriceVokruglamp(){
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
        $textInFile = "model;sku;upc;quantity;stock_status_id;image;shipping;price;weight;weight_class_id;length;width;height;length_class_id;subtract;minimum;sort_order;status;manufacturer;name;description;tag;meta_title;meta_description;meta_keyword;additional_images;product_attribute;product_category;product_special";
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
//            product_category;
//            product_special';

            $textInFile .= "\n{$singleproduct[0]};";  //модель
            $textInFile .= "{$singleproduct[11]};";  //артикул
            $textInFile .= "{$singleproduct[53]};";       //штрихкод upc
            $textInFile .= "{$singleproduct[65]};";           //кол-во

            $status = -1;

            if($singleproduct[1]== "true"){
                $status = 7;
            }
            else{
                $status = 5;
            }
            $textInFile .= "{$status};";       //есть в наличии или нет
            $textInFile .= "{$singleproduct[7]};"; //изображение
            $textInFile .= "0;"; //доставка 0-нет 1-да

            $price = 0;

            var_dump($singleproduct[62] == '0');
            if($singleproduct[62] == '0'){
                $price = $singleproduct[61];
            }
            else{
                $price = $singleproduct[62];
            }
            $textInFile .= "$price;"; //цена
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

            //=====================АТРИБУТЫ============================//

            $attributes = '';

            //Processor:No. of Cores:1|Memory:test 1:8gb   группа:название атрибута:значение

            //производитель

            $attributes.="Технические характеристики:Производитель:{$singleproduct[3]}";
            if(!$singleproduct[11]==""){
                $attributes.="|Основные:Артикул:{$singleproduct[11]}";
            }
            if(!$singleproduct[12]==""){
                $attributes.="|Основные:Бренд:{$singleproduct[12]}";
            }
            if(!$singleproduct[13]==""){
                $attributes.="|Основные:Страна:{$singleproduct[13]}";
            }
            if(!$singleproduct[14]==""){
                $attributes.="|Основные:Коллекция:{$singleproduct[14]}";
            }
            if(!$singleproduct[15]==""){
                $attributes.="|Размеры:Высота, мм:{$singleproduct[15]}";
            }
            if(!$singleproduct[16]==""){
                $attributes.="|Размеры:Высота встраиваемой части, мм:{$singleproduct[16]}";
            }
            if(!$singleproduct[17]==""){
                $attributes.="|Размеры:Длина, мм:{$singleproduct[17]}";
            }
            if(!$singleproduct[18]==""){
                $attributes.="|Размеры:Ширина, мм:{$singleproduct[18]}";
            }
            if(!$singleproduct[19]==""){
                $attributes.="|Размеры:Глубина, мм:{$singleproduct[19]}";
            }
            if(!$singleproduct[20]==""){
                $attributes.="|Размеры:Диаметр, мм:{$singleproduct[20]}";
            }
            if(!$singleproduct[21]==""){
                $attributes.="|Размеры:Диаметр врезного отверстия, мм:{$singleproduct[21]}";
            }
            if(!$singleproduct[22]==""){
                $attributes.="|Лампы:Количество ламп:{$singleproduct[22]}";
            }
            if(!$singleproduct[23]==""){
                $attributes.="|Лампы:Форма лампочки:{$singleproduct[23]}";
            }
            if(!$singleproduct[24]==""){
                $attributes.="|Лампы:Мощность лампы, W:{$singleproduct[24]}";
            }
            if(!$singleproduct[25]==""){
                $attributes.="|Лампы:Общая мощность, W:{$singleproduct[25]}";
            }
            if(!$singleproduct[26]==""){
                $attributes.="|Лампы:Площадь освещения, м2:{$singleproduct[26]}";
            }
            if(!$singleproduct[27]==""){
                $attributes.="|Лампы:Тип цоколя:{$singleproduct[27]}";
            }
            if(!$singleproduct[28]==""){
                $attributes.="|Лампы:Тип лампочки (основной):{$singleproduct[28]}";
            }
            if(!$singleproduct[29]==""){
                $attributes.="|Лампы:Тип лампочки (дополнительный:{$singleproduct[29]}";
            }
            if(!$singleproduct[30]==""){
                $attributes.="|Лампы:Световой поток, lm:{$singleproduct[30]}";
            }
            if(!$singleproduct[31]==""){
                $attributes.="|Технические характеристики:Цветовая температура:{$singleproduct[31]}";
            }
            if(!$singleproduct[32]==""){
                $attributes.="|Технические характеристики:Срок службы:{$singleproduct[32]}";
            }
            if(!$singleproduct[33]==""){
                $attributes.="|Лампы:Лампы в комплекте:{$singleproduct[33]}";
            }
            if(!$singleproduct[34]==""){
                $attributes.="|Лампы:Напряжение, V:{$singleproduct[34]}";
            }
            if(!$singleproduct[35]==""){
                $attributes.="|Дополнительно:Степень защиты, IP:{$singleproduct[35]}";
            }
            if(!$singleproduct[36]==""){
                $attributes.="|Цвет и материал:Виды материалов:{$singleproduct[36]}";
            }
            if(!$singleproduct[37]==""){
                $attributes.="|Цвет и материал:Материал арматуры:{$singleproduct[37]}";
            }
            if(!$singleproduct[38]==""){
                $attributes.="|Цвет и материал:Материал плафонов:{$singleproduct[38]}";
            }
            if(!$singleproduct[39]==""){
                $attributes.="|Цвет и материал:Цвет:{$singleproduct[39]}";
            }
            if(!$singleproduct[40]==""){
                $attributes.="|Цвет и материал:Цвет арматуры:{$singleproduct[40]}";
            }
            if(!$singleproduct[41]==""){
                $attributes.="|Цвет и материал:Цвет плафонов:{$singleproduct[41]}";
            }
            if(!$singleproduct[42]==""){
                $attributes.="|Дополнительно:Место установки:{$singleproduct[42]}";
            }
            if(!$singleproduct[43]==""){
                $attributes.="|Дополнительно:Пульт ДУ:{$singleproduct[43]}";
            }
            if(!$singleproduct[44]==""){
                $attributes.="|Основные:Стиль:{$singleproduct[44]}";
            }
            if(!$singleproduct[45]==""){
                $attributes.="|Цвет и материал:Форма плафона:{$singleproduct[45]}";
            }
            if(!$singleproduct[46]==""){
                $attributes.="|Дополнительно:Интерьер:{$singleproduct[46]}";
            }
            if(!$singleproduct[47]==""){
                $attributes.="|Дополнительно:Наличие датчика движения:{$singleproduct[47]}";
            }
            if(!$singleproduct[48]==""){
                $attributes.="|Дополнительно:Наличие диммера:{$singleproduct[48]}";
            }
            if(!$singleproduct[51]==""){
                $attributes.="|Дополнительно:Гарантия:{$singleproduct[51]}";
            }
            if(!$singleproduct[54]==""){
                $attributes.="|Дополнительно:Вес, кг:{$singleproduct[54]}";
            }


            $textInFile .= "{$attributes};"; //атрибуты товара



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
        <pre>
            <?php
            //            print_r();
            print_r($textInFile);
            ?>
            </pre>

        <?php

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
    }//PriceVokruglamp

    public function PriceDecomo(){
        $nameFile = "content_26_06_2019_11_47_test.csv";

        $directory = __DIR__;

        $index  = strripos($directory, "\\");
        $directory = substr($directory, 0 , $index);

        $index  = strripos($directory, "\\");
        $directory = substr($directory, 0 , $index);

        $directory = "{$directory}\\public\\price_list\\{$nameFile}";

        $dataInFile = file_get_contents("{$directory}");

//        $dataInFile =  mb_convert_encoding($dataInFile, "utf-8", "windows-1251");

        $arrProduct = explode("\n", $dataInFile); //разбиваем строку на заголовок и продукты

//        ?>
        <!--        <pre>-->
        <!--            --><?php
//            print_r($arrProduct);
//            ?>
        <!--        </pre>-->
        <!---->
        <!--        --><?php
        $textInFile = "model;sku;upc;quantity;stock_status_id;image;shipping;price;weight;weight_class_id;length;width;height;length_class_id;subtract;minimum;sort_order;status;manufacturer;name;description;tag;meta_title;meta_description;meta_keyword;additional_images;product_attribute;product_category;product_special";
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
//            product_category;
//            product_special';

            $model= "{$singleproduct[8]} {$singleproduct[7]} {$singleproduct[1]}";

            $textInFile .= "\n{$model};";  //модель
            $textInFile .= "{$singleproduct[1]};";  //артикул
            $textInFile .= ";";       //штрихкод upc
            $textInFile .= "{$singleproduct[3]};";           //кол-во

            $status = -1;

            if($singleproduct[108]== "true"){
                $status = 7;
            }
            else{
                $status = 5;
            }
            $textInFile .= "{$status};";       //есть в наличии или нет
            $textInFile .= "{$singleproduct[6]};"; //изображение
            $textInFile .= "0;"; //доставка 0-нет 1-да

            $price = 0;

            if($singleproduct[103] == ''){
                $price = $singleproduct[2];
            }
            else{
                $price = $singleproduct[103];
            }
            $textInFile .= "{$price};"; //цена
            $textInFile .= "{$singleproduct[26]};"; //вес  !!!!!!!!!!!!!!!!!!!!!!!
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

            //=====================АТРИБУТЫ============================//

            $attributes = '';

            //Processor:No. of Cores:1|Memory:test 1:8gb   группа:название атрибута:значение

            //производитель

            $attributes.="Технические характеристики:Производитель:{$singleproduct[3]}";
            if(!$singleproduct[11]==""){
                $attributes.="|Основные:Артикул:{$singleproduct[11]}";
            }
            if(!$singleproduct[12]==""){
                $attributes.="|Основные:Бренд:{$singleproduct[12]}";
            }
            if(!$singleproduct[13]==""){
                $attributes.="|Основные:Страна:{$singleproduct[13]}";
            }
            if(!$singleproduct[14]==""){
                $attributes.="|Основные:Коллекция:{$singleproduct[14]}";
            }
            if(!$singleproduct[15]==""){
                $attributes.="|Размеры:Высота, мм:{$singleproduct[15]}";
            }
            if(!$singleproduct[16]==""){
                $attributes.="|Размеры:Высота встраиваемой части, мм:{$singleproduct[16]}";
            }
            if(!$singleproduct[17]==""){
                $attributes.="|Размеры:Длина, мм:{$singleproduct[17]}";
            }
            if(!$singleproduct[18]==""){
                $attributes.="|Размеры:Ширина, мм:{$singleproduct[18]}";
            }
            if(!$singleproduct[19]==""){
                $attributes.="|Размеры:Глубина, мм:{$singleproduct[19]}";
            }
            if(!$singleproduct[20]==""){
                $attributes.="|Размеры:Диаметр, мм:{$singleproduct[20]}";
            }
            if(!$singleproduct[21]==""){
                $attributes.="|Размеры:Диаметр врезного отверстия, мм:{$singleproduct[21]}";
            }
            if(!$singleproduct[22]==""){
                $attributes.="|Лампы:Количество ламп:{$singleproduct[22]}";
            }
            if(!$singleproduct[23]==""){
                $attributes.="|Лампы:Форма лампочки:{$singleproduct[23]}";
            }
            if(!$singleproduct[24]==""){
                $attributes.="|Лампы:Мощность лампы, W:{$singleproduct[24]}";
            }
            if(!$singleproduct[25]==""){
                $attributes.="|Лампы:Общая мощность, W:{$singleproduct[25]}";
            }
            if(!$singleproduct[26]==""){
                $attributes.="|Лампы:Площадь освещения, м2:{$singleproduct[26]}";
            }
            if(!$singleproduct[27]==""){
                $attributes.="|Лампы:Тип цоколя:{$singleproduct[27]}";
            }
            if(!$singleproduct[28]==""){
                $attributes.="|Лампы:Тип лампочки (основной):{$singleproduct[28]}";
            }
            if(!$singleproduct[29]==""){
                $attributes.="|Лампы:Тип лампочки (дополнительный:{$singleproduct[29]}";
            }
            if(!$singleproduct[30]==""){
                $attributes.="|Лампы:Световой поток, lm:{$singleproduct[30]}";
            }
            if(!$singleproduct[31]==""){
                $attributes.="|Технические характеристики:Цветовая температура:{$singleproduct[31]}";
            }
            if(!$singleproduct[32]==""){
                $attributes.="|Технические характеристики:Срок службы:{$singleproduct[32]}";
            }
            if(!$singleproduct[33]==""){
                $attributes.="|Лампы:Лампы в комплекте:{$singleproduct[33]}";
            }
            if(!$singleproduct[34]==""){
                $attributes.="|Лампы:Напряжение, V:{$singleproduct[34]}";
            }
            if(!$singleproduct[35]==""){
                $attributes.="|Дополнительно:Степень защиты, IP:{$singleproduct[35]}";
            }
            if(!$singleproduct[36]==""){
                $attributes.="|Цвет и материал:Виды материалов:{$singleproduct[36]}";
            }
            if(!$singleproduct[37]==""){
                $attributes.="|Цвет и материал:Материал арматуры:{$singleproduct[37]}";
            }
            if(!$singleproduct[38]==""){
                $attributes.="|Цвет и материал:Материал плафонов:{$singleproduct[38]}";
            }
            if(!$singleproduct[39]==""){
                $attributes.="|Цвет и материал:Цвет:{$singleproduct[39]}";
            }
            if(!$singleproduct[40]==""){
                $attributes.="|Цвет и материал:Цвет арматуры:{$singleproduct[40]}";
            }
            if(!$singleproduct[41]==""){
                $attributes.="|Цвет и материал:Цвет плафонов:{$singleproduct[41]}";
            }
            if(!$singleproduct[42]==""){
                $attributes.="|Дополнительно:Место установки:{$singleproduct[42]}";
            }
            if(!$singleproduct[43]==""){
                $attributes.="|Дополнительно:Пульт ДУ:{$singleproduct[43]}";
            }
            if(!$singleproduct[44]==""){
                $attributes.="|Основные:Стиль:{$singleproduct[44]}";
            }
            if(!$singleproduct[45]==""){
                $attributes.="|Цвет и материал:Форма плафона:{$singleproduct[45]}";
            }
            if(!$singleproduct[46]==""){
                $attributes.="|Дополнительно:Интерьер:{$singleproduct[46]}";
            }
            if(!$singleproduct[47]==""){
                $attributes.="|Дополнительно:Наличие датчика движения:{$singleproduct[47]}";
            }
            if(!$singleproduct[48]==""){
                $attributes.="|Дополнительно:Наличие диммера:{$singleproduct[48]}";
            }
            if(!$singleproduct[51]==""){
                $attributes.="|Дополнительно:Гарантия:{$singleproduct[51]}";
            }
            if(!$singleproduct[54]==""){
                $attributes.="|Дополнительно:Вес, кг:{$singleproduct[54]}";
            }


            $textInFile .= "{$attributes};"; //атрибуты товара



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
    }//PriceDecomo
}