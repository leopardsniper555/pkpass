<?php

/**
 * Copyright (c) 2017, Thomas Schoffelen BV.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 */

use PKPass\PKPass;

require('vendor/autoload.php');

function generateImage($back_path,$photo_path,$member_fisrt_name,$member_last_name,$flag=1)
    {
        // Load the big image
        $back_image = imagecreatefrompng($back_path);
        $photo_image = imagecreatefrompng($photo_path);
        $photo_width = imagesx($photo_image);
        $photo_height = imagesy($photo_image);

        $back_height = imagesy($back_image);

        $photo_rate=$photo_width/$photo_height;// 

        // Create a new image with the new dimensions
        $new_photo = imagecreatetruecolor($back_height*$photo_rate, $back_height);

        // Resize the original image to the new dimensions
        imagecopyresampled($new_photo, $photo_image, 0, 0, 0, 0, $back_height*$photo_rate, $back_height, $photo_width, $photo_height);
        
        imagecopy($back_image, $new_photo, 30, 0, 0, 0, $back_height*$photo_rate, $back_height);
        $text_x_offset = 40+$back_height*$photo_rate;
        $text = 'FIRST NAME';
        $font_size = 8;
        $font_color = imagecolorallocate($back_image, 247, 168, 27);
        $font = 'FrutigerLTStd-Bold.otf';
        imagettftext($back_image, $font_size, 0, $text_x_offset, 30, $font_color, $font, $text);
        $text = $member_fisrt_name;
        $font_size = 12;
        $font_color = imagecolorallocate($back_image, 255, 255, 255);
        imagettftext($back_image, $font_size, 0, $text_x_offset, 50, $font_color, $font, $text);
        $text = 'NAME';
        $font_size = 8;
        $font_color = imagecolorallocate($back_image, 247, 168, 27);
        imagettftext($back_image, $font_size, 0, $text_x_offset, 70, $font_color, $font, $text);
        $text = $member_last_name;
        $font_size = 12;
        $font_color = imagecolorallocate($back_image, 255, 255, 255);
        imagettftext($back_image, $font_size, 0, $text_x_offset, 90, $font_color, $font, $text);
        $outputpath='';
        if($flag==1){
            $outputpath='images/strip.png';
        }else{
            $outputpath='images/strip@2x.png';
        }
        imagepng($back_image, $outputpath);
        imagedestroy($back_image);
        imagedestroy($photo_image);
    }
    
function generatePass(){
    // Replace the parameters below with the path to your .p12 certificate and the certificate password!
    $pass = new PKPass('Certificates.p12', 'experience789');
    // Pass content
    $data = [
        'description' => 'Rotary Membership Card ',
        'formatVersion' => 1,
        'organizationName' => 'Rotary',
        'passTypeIdentifier' => 'rotary.membership.card', // Change this!
        'serialNumber' => 'p69f2J',
        'teamIdentifier' => '94UK2ZVHU2', // Change this!
        'authenticationToken' => 'vxwxd7J8AlNNFPS8k0a0FfUFtq0ewzFdc',
        'barcode' => [
            'message' => '123456789',
            'format' => 'PKBarcodeFormatQR',
            'messageEncoding' => 'iso-8859-1'
        ],
        'storeCard' => [
            'headerFields' => [
                [
                    'key' => 'member',
                    'label' => 'CARTE DE MEMBER',
                    'value' => 'MEMBERSHIP CARD',
                ]
            ],
            
            'secondaryFields' => [
                [
                    'key' => 'memberid',
                    'label' => 'MEMBER/ID',
                    'value' => '123456',
                ],
                [
                    'key' => 'clubid',
                    'label' => 'CLUB ID',
                    'value' => '123456',
                ],
            ],
            'auxiliaryFields' => [
                [
                    'key' => 'country',
                    'label' => 'PAYS/COUNTRY',
                    'value' => 'France',
                ],
            ],
        ],
        'backgroundColor' => 'rgb(12,60,124)',
        'foregroundColor' => 'rgb(255, 255, 255)',
        'labelColor' => 'rgb(247, 168, 27)',
        'logoText' => 'Rotary',
    ];
    $pass->setData($data);
    // Add files to the pass package
    $pass->addFile('images/icon.png');
    $pass->addFile('images/icon@2x.png');
    $pass->addFile('images/logo.png');
    $pass->addFile('images/strip.png');
    $pass->addFile('images/strip@2x.png');

    // Create and output the pass
    
    $pass->create(true);
}

    generateImage('background.png','image.png','Franko','DECLERCQ',1);
    generateImage('background1.png','image.png','Franko','DECLERCQ',2);
    generatePass();


