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
    
    $base_name = trim($base_name); //Uncommented
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
	$im = ImageCreateTrueColor(600,72) 
	      or die ("Cannot Initialize new GD image stream");
	switch($colorscheme){
		case 'green':
			//$background_color=ImageColorAllocate($im, 102, 153, 153);
			$background_color=ImageColorAllocate($im, 82, 143, 143);
			$face_color =ImageColorAllocate($im, 255, 255, 255);
			//$shade_color  =ImageColorAllocate($im, 17, 42, 42);
			$shade_color  =ImageColorAllocate($im, 10, 32, 32);
			break;
		case 'blue':
			$background_color=ImageColorAllocate($im, 8, 99, 196);
			$face_color =ImageColorAllocate($im, 255, 255, 255);
			$shade_color =ImageColorAllocate($im, 9, 35, 60);
			
			break;
		case 'red':
			$background_color=ImageColorAllocate($im, 200, 69, 56);
			$face_color =ImageColorAllocate($im, 255, 255, 255);
			$shade_color=ImageColorAllocate($im,51,5,0);
			break;
			
		case 'bred':
			$background_color=ImageColorAllocate($im, 255, 34, 0);
			$face_color =ImageColorAllocate($im, 255, 255, 255);
			$shade_color =ImageColorAllocate($im, 102, 10, 0);
			break;
		case 'brown':
			$background_color=ImageColorAllocate($im, 165, 75, 0);
			$face_color =ImageColorAllocate($im, 255, 255, 255);
			$shade_color =ImageColorAllocate($im, 55, 16, 0);
			break;
		case 'cyan' :
			$background_color=ImageColorAllocate($im, 20, 150, 150);
			$face_color =ImageColorAllocate($im, 255, 255, 255);
   		$shade_color  =ImageColorAllocate($im, 10, 32, 32);
			break;
		
		case 'lila' :
			$background_color=ImageColorAllocate($im, 90, 30, 186);
			$face_color =ImageColorAllocate($im, 255, 255, 255);
			$shade_color =ImageColorAllocate($im, 16, 4, 20);
			break;		

		case 'dtublue' :
			$background_color=ImageColorAllocate($im, 51, 102, 153);
			$face_color =ImageColorAllocate($im, 255, 255, 255);
			$shade_color =ImageColorAllocate($im, 25, 50, 75);
			break;
                 case 'augreen':
                      $background_color=ImageColorAllocate($im, 0x38, 0x74, 0x3c);
                      $face_color =ImageColorAllocate($im, 255, 255, 255);
                      $shade_color =ImageColorAllocate($im, 0x1b, 0x38, 0x1d);
                      break;
	}

	//$fontname="Fonts/Officina/LT_52477.pfb";
   $fontname="Fonts/OFFSNBD/IT291___.TTF";
	
//	$font = ImagePsLoadFont($fontname);
//	ImagePsEncodeFont($font,'Fonts/IsoLatin1.enc');
	
	
	
	$shade_colors = ImageColorsForIndex($im, $shade_color);
	$background_colors = ImageColorsForIndex($im, $background_color);
	
	for($j = 0; $j < 25; $j ++){
		$r = ($shade_colors['red']*j + $background_colors['red']*(25-$j))/25;
		$b = ($shade_colors['blue']*j + $background_colors['blue']*(25-$j))/25;
		$g = ($shade_colors['green']*j + $background_colors['green']*(25-$j))/25;
		$shade[$j] = ImageColorAllocate($im, $r, $g, $b);
	}
	
   ImageFill($im, 0,0, $background_color);
//	ImagePsText($im, "$text",$font, 35, $shade_color, $background_color, 25, 49);
	ImageTTFText($im, 26, 0, 25, 49, $shade_color, $fontname, $text);
	
	for ($x= 2; $x < 597; $x++){
		for ($y = 16; $y < 55; $y++){
		   $shade_r = 0;
			$shade_g = 0;
			$shade_b = 0;
			for ($i = -2; $i < 3; $i++ ){
				for ($j = -2; $j < 3; $j++){
					$color_index = ImageColorAt($im, $x+$i, $y+$j);
					$colors = ImageColorsForIndex($im, $color_index);
					$shade_r = $shade_r + $colors['red'];
					$shade_g = $shade_g + $colors['green'];
					$shade_b = $shade_b + $colors['blue'];
				}
			}
			$shade_r = $shade_r / 25;
			$shade_b = $shade_b / 25;
			$shade_g = $shade_g / 25;
			
			ImageSetPixel($im, $x, $y, ImageColorClosest($im,$shade_r, $shade_g, $shade_b));
		}
	}
	
//	ImagePsText($im, "$text",$font, 35, $face_color, $background_color, 20, 44);
	ImageTTFText($im, 26, 0, 20, 44, $face_color, $fontname, $text);

	ImagePng($im, $filename);
}


function usage($script_name)
{
	
	print "USAGE : $script_name    text    file_name    colors \n";
	print "   text      - Text to be printed \n";
	print "   file_name - File name for the picture \n";
	print "   colors    - String for the colors. Possible options: \n";
	print "               *  blue -  Blue \n";
	print "               *  green - Green \n";
	print "               *  red   - Red \n";
	print "               *  brown - Brown \n";
	print "               *  cyan  - Cyan \n";
	print "               *  lila  - Lila \n";	
	print "\nEXAMPLE:  $script_name \"My Home Page\" my_home.png green\n\n";
	
	
}

//if ($argc !=3 && $argc != 4){
//	usage($argv[0]);
//}else{
//	$text = $argv[1];
//	$filename = $argv[2];
//	if ($argc==4)
//		$colorscheme = $argv[3];
//	else
//		$colorscheme = 'ogreen';

//	create_image($text, $colorscheme, $filename);

//Header("Content-type: image/png");
$text = $HTTP_POST_VARS['text'];
$colorscheme = $HTTP_POST_VARS['colors'];;
$colorscheme = $colorscheme[0];
$filename = get_picture_name();
create_image($text, $colorscheme, "/tmp/".$filename);
echo("<img src=\"tmp/$filename\">");
?>
