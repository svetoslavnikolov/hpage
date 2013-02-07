<?php
// header("Content-type: image/png");


    function circular_inc($current, $maximum)
    {
        $ret_val = $current + 1;
        if ($ret_val > $maximum) $ret_val = 1;
        return $ret_val;
    }
    
    
    function circular_dec($current, $maximum)
    {
        $ret_val = $current - 1 ;
        if ($ret_val < 1) $ret_val = 1;
        return $ret_val;
    }

function get_picture_name()
{
    $list_descr_name = "tmp/list_description";
    if (file_exists($list_descr_name)){
        $fd = fopen($list_descr_name,"r");
        while(!feof($fd)){
            $buffer = fgets($fd, 2048);
            parse_str($buffer);
        }
        fclose($fd);
    }
    /*
     *  Update the circular buffer
     */ 
    
    $next_head = circular_inc($head, $max_items);
    if ($next_head == $tail){
        $next_tail = circular_inc($tail, $max_items);
    }else{
        $next_tail=$tail;
    }
    
    /*
     *  Write down the circular buffer to a file
     */
    
    $base_name = trim($base_name);
    $fd = fopen($list_descr_name,"w");
    fputs($fd, "max_items=$max_items");
    fputs($fd, "base_name=$base_name\n");
    fputs($fd, "head=$next_head\n");
    fputs($fd, "tail=$next_tail\n");
    fputs($fd,"\n");
    fclose($fd);
    return($base_name . "_" . sprintf("%d",$next_head). ".png");
}


function create_image($text, $colorscheme, $filename){
    $im = ImageCreate(600,20) 
          or die ("Cannot Initialize new GD image stream");
    switch($colorscheme){
        case 'green':
            $background_color=ImageColorAllocate($im, 0, 51, 51);
            $face_color =ImageColorAllocate($im, 255, 255, 255);
            $shade_color =ImageColorAllocate($im, 34, 85, 85);
            break;
        case 'blue':
            $background_color=ImageColorAllocate($im, 8, 50, 119);
            $face_color =ImageColorAllocate($im, 255, 255, 255);
            $shade_color =ImageColorAllocate($im, 17, 67, 119);
            break;
        case 'red':
            $background_color=ImageColorAllocate($im, 92, 32, 0);
            $face_color =ImageColorAllocate($im, 255, 255, 255);
            $shade_color =ImageColorAllocate($im, 102, 10, 0);
            break;
            
        case 'brown':
            $background_color=ImageColorAllocate($im, 102, 25, 0);
            $face_color =ImageColorAllocate($im, 255, 255, 255);
            $shade_color =ImageColorAllocate($im, 102, 10, 0);
            break;
            
        case 'cyan':
            $background_color=ImageColorAllocate($im, 0, 92, 92);
            $face_color =ImageColorAllocate($im, 255, 255, 255);
            $shade_color =ImageColorAllocate($im, 102, 10, 0);
            break;

        case 'lila':
            $background_color=ImageColorAllocate($im, 20, 3, 72);
            $face_color =ImageColorAllocate($im, 255, 255, 255);
            $shade_color =ImageColorAllocate($im, 10, 0, 102);
            break;
        case 'augreen':
            $background_color=ImageColorAllocate($im, 0x20, 0x32, 0x0d);
            $face_color =ImageColorAllocate($im, 255, 255, 255);
            $shade_color =ImageColorAllocate($im, 10, 0, 102);
            break;
    }

    //$fontname="Fonts/Frutiger/FTB_____.TTF"; // Good
    //$fontname="Fonts/Frutiger/FTUBL___.TTF";
    $fontname="Fonts/Frutiger/FTBL____.TTF"; // Good
    //$fontname="Fonts/Frutiger_Condensed/FTBC____.PFB";  // VERRY GOOD
    
   ImageFill($im, 0,0, $background_color);
//  ImageTTFText($im, 22, 0, 29, 47, $shade_color, $fontname, $text);
    ImageTTFText($im, 7, 0, 20, 12, $face_color, $fontname,$text);
//  $font = ImagePsLoadFont($fontname);
//  ImagePsEncodeFont($font,'Fonts/IsoLatin1.enc');
//  ImagePsText($im, "$text",$font, 35, $shade_color, $background_color, 23, 10);
//  ImagePsText($im, "$text",$font, 14, $face_color, $background_color, 20, 10);
    
    ImagePng($im, $filename);
}


function usage($script_name)
{
    print "USAGE : $script_name    text    file_name    colors \n";
    print "   text      - Text to be printed \n";
    print "   file_name - File name for the picture \n";
    print "   colors    - String for the colors. Possible options: \n";
    print "               *  blue  - Blue \n";
    print "               *  green - Green \n";
    print "               *  red   - Red\n";
    print "               *  cyan  - Cyan\n";
    print "               *  lila  - Lila\n";
    print "\nEXAMPLE:  $script_name \"My Home Page\" my_home.png ogreen\n\n";
    
    
}


//Header("Content-type: image/png");

$text = $HTTP_POST_VARS['text'];
$colorscheme = $HTTP_POST_VARS['colors'];;
$colorscheme = $colorscheme[0];
$filename = get_picture_name();

create_image($text, $colorscheme, "tmp/" . $filename);
echo("<img src=\"tmp/$filename\">");
?>
