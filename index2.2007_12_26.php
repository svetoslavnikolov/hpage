<?php
/*
 *  FILE     : index.php
 *  ABSTRACT : This file is the implementation of the program 
 *             "H-PaGe", which means "Home Page Generator"
 *
 *  VERSION   : C-2, 29 Aug 2003 by Svetoslav Nikolov
 *              C-4, 04 Nov 2003 by Svetoslav Nikolov
 *              C-5  16 Dec 2003 by Svetoslav Nikolov
 *              C-6  30 Jan 2003 by Svetoslav Nikolov
 *              C-7  05 May 2003 by Svetoslav Nikolov
 *
 */

global $Language;
global $Charset;
global $Title;

global $doc_root;         // Root directory for all Web pages
global $rel_path;         // Path of current web site
global $script_filename;  // Full path to script + name of script
global $script_path;      // Full path to script
global $home_dir;
global $full_home_dir;
global $server_name;      // Web address corresponding to "$doc_root"
global $query_string;     // The required page

global $directory;
global $page;
global $action;
global $show_date;
global $css_link;
global $css_print_link;
global $page_look;

global $upperleft_html;
global $upperright_html;
global $middleleft_html;
global $middleright_html;
global $bottom_text;

global $top_menu_counter;
global $left_menu_counter;
global $left_menu_level;
global $bottom_menu_counter;
global $html_sym;
global $home_text;
global $page_icon;
global $sorting;
global $keywords;
global $require_password;
global $securePagesDir;
global $allowed_users;
global $allowed;
global $print_icon;


$require_password = false;
$allowed_users = Array();
$allowed = true;

$securePagesDir = '/home/sn/2www/phpSecurePages/';

$keywords="H-PaGe";
$sorting = 'time';
$top_menu_counter=0;
$left_menu_counter=0;
$bottom_menu_counter=0;
$use_title=0;              // Use a global title (or local)
$substitute = 0;           // Substitute special characters
$config_sym = array("\*","_");
$html_sym = array("&#8226;","<br>");
$show_date = 0;               // Whether to show "last updated"
$bottom_text="";
$page_icon="http://www.oersted.dtu.dk/personal/sn/hpage/.pictures/hpage_icon.ico";
$print_icon="http://www.oersted.dtu.dk/personal/sn/hpage/.pictures/printer.gif";

$home_text="Home";
$bottom_text="Created with <a class=\"banner\" href=\"http://www.oersted.dtu.dk/personal/sn/hpage/\">H-Page</a>\n";


$Language = "EN";
$Charset = "ISO-8859-1";
$Title = "DTU-Webpage";

$doc_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'] . "/";
if (strlen($HTTP_SERVER_VARS['SCRIPT_URL'])>0)
	$rel_path = $HTTP_SERVER_VARS['SCRIPT_URL'];
elseif(strlen($HTTP_SERVER_VARS['SCRIPT_URL'])>0)
	$rel_path = $HTTP_SERVER_VARS['REDIRECT_URL'];
else
    $rel_path = $HTTP_SERVER_VARS['SCRIPT_NAME'];



$script_filename = $HTTP_SERVER_VARS['SCRIPT_FILENAME'];
$script_path = pathinfo($script_filename);
$script_path = $script_path['dirname'];
$server_name = $HTTP_SERVER_VARS['SERVER_NAME'];
$query_string = $HTTP_SERVER_VARS['QUERY_STRING'];
$css_link = "http://www.oersted.dtu.dk/personal/sn/styles/green.css";
$css_print_link = "http://www.oersted.dtu.dk/personal/sn/styles/print.css";

$show_date = 0;
$page_look = "dtu";

$upperleft_html="<img height=20 src=\"http://oersted.dtu.dk/images/billede_tv.gif\" width=151 border=0 alt=\"design element\">";
$upperright_html="<a href=\"http://www.dtu.dk/index_e.htm\"><img height=20 alt=\"DANMARKS TEKNISKE UNIVERSITET\" src=\"http://www.oersted.dtu.dk/images/dtulogo_e_new.gif\" width=600 border=0></a>";
$middleleft_html="<a href=\"http://www.oersted.dtu.dk/gallery/image0104big.jpg\" target=\"_blank\"><img src=\"http://oersted.dtu.dk/gallery/image0104.jpg\" width=151 height=72 alt=\"Image 104\" border=\"0\"></a>";
$middleright_html="<a href=\"http://www.oersted.dtu.dk/\"><img height=72 src=\"http://www.oersted.dtu.dk/images/orsted-dtu.gif\" width=600 border=0 alt=\"Oersted DTU\"></a>";



/*
 *  Find out exactly how the home directory is related to the 
 *  directory of the web site, and how this is translated into
 *  links 
 */
$site_home_dir=realpath($script_path);
$home_dir_pieces = split("/", $site_home_dir);
$full_dir_pieces = split("/", $script_path);

$no_pieces = 0;
$ii = count($home_dir_pieces)-1;
$jj = count($full_dir_pieces)-1;

while($ii > -1 && $jj>-1 ){
	if($home_dir_pieces[$ii]!=$full_dir_pieces[$jj]) break;
	$ii --; $jj--;
}

$home_dir = "";
$full_home_dir = "";

for ($k = 0; $k <= $jj; $k++){
	$full_home_dir .= $full_dir_pieces[$k] . "/";
}

for ($k = 0; $k <= $ii; $k++){
	$home_dir .= $home_dir_pieces[$k] . "/";
}

if ($rel_path[strlen($rel_path)-1]!='/'){
	$rel_path = pathinfo($rel_path);
	$rel_path = $rel_path['dirname'] . "/";
}

/*********************************************************************
 Adds users which are allowed to login
 *********************************************************************/
function allow_users()
{
	global $require_password;
	global $allowed_users;
	global $allowed;
	
	$require_password = true;	
	$no_users = count($allowed_users);
	$no_args = func_num_args();
	for ($i = 0; $i<$no_args; $i++){
		$allowed_users[$i+$no_users] = func_get_arg($i);
	}
	$allowed = false;
}


/*********************************************************************
	Sets the case of whether to substitute or not special symbols
 *********************************************************************/
function get_page_name($directory, $page)
{

	if (strlen($page)==0){
		if (file_exists($directory   .  "index.php")) $page="main.html";
		else if (file_exists($directory . "main.html")) $page="main.html";
		else if (file_exists($directory . "main.htm"))  $page="main.htm";
		else if (file_exists($directory . "main.txt"))  $page="main.txt";
		else if (file_exists($directory . "main.php"))  $page="main.php";
		else if (file_exists($directory . "main.fifo")) $page="main.fifo";
		else if (file_exists($directory . "main.table")) $page="main.table";
		else $page = " ";
	}
	return $page;
}


/*********************************************************************
	Sets the case of whether to substitute or not special symbols
 *********************************************************************/
function set_substitute($choice)
{
	global $substitute;
	$substitute = ('true' == $choice);
}

/*********************************************************************
	add keywords
 *********************************************************************/
function add_keywords($text)
{
	global $keywords;
	$keywords = $keywords . "," . $text;
}

/*********************************************************************
	Sets the type of sorting when menu is created from directories
 *********************************************************************/
function set_sorting($choice)
{
	global $sorting;
	$sorting = $choice;
}

/*********************************************************************
 FUNCTION : print_header
 ABSTRACT : prints the necessary header information for the browser
 *********************************************************************/
function print_header($css_link)
{
	global $Language;
	global $Charset;
	global $Title;
	global $use_title;
//	global $css_link;
	global $page_icon;
	global $rel_path;         // Path of current web site
	global $server_name;      // Web address corresponding to "$doc_root"
	global $query_string;     // The required page
   global $keywords;
	
	//header("HTTP-Version: 4.01");
 	//header("Charset: iso-8859-1");
	//header("Content-Language: $Language");
	
 
	echo("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n");
	echo("<html lang=\"$Language\">\n");
	echo("<head>\n");
	echo("  <title>$Title</title>\n");
	echo("  <link rel=\"shortcut icon\" href=\"$page_icon\" type=\"image/x-icon\">\n");
	echo("  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=$Charset\">\n");
	echo("  <meta name=\"keywords\" content=\"$keywords\" >\n");
	echo("  <link rel=\"stylesheet\" href=\"$css_link\" type=\"text/css\">\n");
	echo("</head>\n");
	big_comment("http://" . $server_name . $rel_path . "?" . $query_string);
	echo("\n<body>\n");
}




/*********************************************************************
 *********************************************************************/
function print_footer()
{
	echo("</body>\n");
	echo("</html>\n");
}

/*********************************************************************
 *********************************************************************/
function big_comment($comment)
{
  echo("\n<!-- ************************************************************************ -->");
  echo("\n<!-- $comment -->");
  echo("\n<!-- ************************************************************************ -->\n");
}

/*********************************************************************
 *********************************************************************/
function small_comment($comment)
{
  echo("<!-- $comment -->\n");
}



/*********************************************************************
 *********************************************************************/
 
function add_bottom_text($text)
{
	global $bottom_text;
	$bottom_text = $text;
}


/*********************************************************************
 *********************************************************************/
 
function set_page_icon($text)
{
	global $page_icon;
	$page_icon = $text;
}

/**********************************************************************
  FUNCTION: add_image - Add a image to a given position. 
  INPUTS:  $position upperleft, upperright, middleleft, middleright,
                     lowerleft, lowerright, menu_line
				$imgdesc - Description
				$imglink - Link
				$imgalt - Alternative text			
 **********************************************************************/
function add_image($position, $imgfile, $imgalt="defaultalt", $imglink="") 
{
  $ximage_html=$position."_html";
  global $$ximage_html;
  
  /* Changed to accomodate "menu_line"
   * What is exported is a variable which 
	* has a name equal to that given in the "add_image"
	* Then this variable contains only the path to the image.
   */
	
  $picture_name = $position;
  global $$picture_name;
  if ( substr($imgfile,0,2) == "~/"){
		//$imgfile = $SCRIPT_URI_PATH . substr($imgfile,1,strlen($imgfile)-1);
		$imgfile = $HOME_URL_PATH . substr($imgfile,1,strlen($imgfile)-1);
	}
	
	$$picture_name=$imgfile;


  if ( ! $imglink == "" ) $$ximage_html="<a href=\"".$imglink."\">";
  else $$ximage_html="";
  
  $$ximage_html.="<img border=0 src=\"" . $imgfile . "\" ";
  $$ximage_html.="     alt=\"".$imgalt."\">";
  if ( ! $imglink == "" ) $$ximage_html.="</a>";
}




/**********************************************************************
 FUNCTION : sub_sym 
 ABSTRACT : Substitutes the symbols from $str which can be found
            in $config_sym with the corresponding symbols from $html_sym
 **********************************************************************/
function sub_sym($str)
{
  global $config_sym;
  global $html_sym;
  global $substitute;

  reset($config_sym);
  reset($html_sym);

  if ($substitute) {
    while ( (list(,$patchar)=each($config_sym)) AND
				(list(,$replchar)=each($html_sym)) ) {
       $str = ereg_replace($patchar,$replchar,$str);
    }
  }
  return $str;
}



/********************************************************************
 FUNCTION : add_left_submenu
 INPUTS   : $level - At what leve we want to add the submenu
            $name  - What name to see on the screen
				$place - Name of a directory.
				$content - A name of an HTML file.
 ********************************************************************/
function add_left_submenu($level, $name, $directory, $page="")
{
	global $left_menu_link;
	global $left_menu_topic;
	global $left_menu_name;
	global $left_menu_path;
	global $left_menu_counter;
	global $left_menu_level;
	
   global $HTTP_SERVER_VARS;
   global $PHP_SELF;
	

  // First check if the directory is a ROOT for a new web site with index.php
  
  if (strlen(trim($page))==0 && strlen($directory)!=0){
		if(substr($directory,0,7)!='http://'){  // If not a real web site
			if (file_exists("./" . $directory . "/index.php")){
			   $home_path = (realpath($doc_root) . "/");
				$current_path = realpath($script_path);
				$directory = realpath("./". $directory) . "/";
				$directory = $full_home_dir . substr($directory, strlen($home_dir), strlen($directory)-strlen(home_dir));
				$directory = "http://" . $server_name . "/" . substr($directory, strlen($doc_root), strlen($directory)-strlen(doc_root));
			}
		}
  }
  
  
  if (strlen($directory)>0)
	  if($directory[strlen($directory)-1]!='/'){
  			$directory .= "/";
	  }
  
	 
    if ($left_menu_counter == 0){
		$name = "<font color=\"yellow\">add_left_submenu()<br>Error. No previous Menus</font> <br>".$name ;
	 }
	 
	 if ($level > $left_menu_level[$left_menu_counter-1]+1){
		$name = "<font color=\"yellow\">add_left_submenu()<br>Error.Too big LEVEL</font> <br>".$name ;
	 }
	
	 /*
	  *  Find of which previous menu item, this is a submenu.
	  */
	 
	 $parent = $left_menu_counter - 1;
	 $no_sub_items = 0;
	 
	 while($left_menu_level[$parent]!=($level-1) && ($parent>-1)){
		if ($left_menu_level[$parent]==$level){
			 $no_sub_items ++;
		}
		$parent --;
	 }
	 if ($left_menu_level[$parent]==$level){
		 $parent --;
		 $no_sub_items ++;
	 }
	 
	 $menu_path = $left_menu_path[$parent];
	 $menu_path = $menu_path . $no_sub_items . "/";
	 $name = substr("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",0,$level*18) . $name;
    
	 $left_menu_name[$left_menu_counter]= $name;
	 if (substr($directory,0,7)=='http://' || substr($directory,0,8)=='https://' || substr($directory,0,7)== 'mailto:' || strlen($directory)==0){
	    $left_menu_link[$left_menu_counter]=$directory;
	 }else{
	    $left_menu_link[$left_menu_counter]=$SCRIPT_NAME ."?" . $directory;
	 }
 
    $left_menu_topic[$left_menu_counter]=$page;
	 $left_menu_path[$left_menu_counter]= $menu_path; 
	 $left_menu_level[$left_menu_counter]= $level;
	 
    $left_menu_counter++;
	
}


/*********************************************************************
  FUNCTION: add_menu
  INPUTS: $position - Which menu to add to? - "left", "top", "bottom"
          $name  - Name to appear on the screen
			 $directory - Directory name. If it starts with "http://", then
			          this is an absolute location
			 $page - This is a name of a file in $directory.
			 $newwin - Flag showing whether the link will be in a new
			           window
 *********************************************************************/
function add_menu($position, $name, $directory="", $page="", $newwin=0)
{

  $xmenu_html=$position."_menu_html";

  global $top_menu_counter;
  global $left_menu_counter;
  global $bottom_menu_counter;
  
  global $top_menu_name;
  global $top_menu_link;
  global $top_menu_topic;
  global $top_menu_newwin;
  
  global $left_menu_name;
  global $left_menu_link;
  global $left_menu_topic;
  global $left_menu_newwin;
  global $left_menu_level;
  global $left_menu_path;
  
 
  global $bottom_menu_name;
  global $bottom_menu_link;
  global $bottom_menu_topic;
  global $bottom_menu_newwin;

  global $script_filename;
  global $script_path;
  global $server_name;
  global $doc_root;
  global $home_dir;
  global $full_home_dir;
  
  $name = sub_sym($name);
	

  // First check if the directory is a ROOT for a new web site with index.php
  
  if (strlen(trim($page))==0 && strlen($directory)!=0){
		if(substr($directory,0,7)!='http://' && substr($directory,0,8)!='https://'){  // If not a real web site
			if (file_exists("./" . $directory . "/index.php")){
			   $home_path = (realpath($doc_root) . "/");
				$current_path = realpath($script_path);
				$directory = realpath("./". $directory) . "/";
				$directory = $full_home_dir . substr($directory, strlen($home_dir), strlen($directory)-strlen(home_dir));
				$directory = "http://" . $server_name . "/" . substr($directory, strlen($doc_root), strlen($directory)-strlen(doc_root));
			}
		}
  }
  
  
  if (strlen($directory)>0)
	  if($directory[strlen($directory)-1]!='/' && substr($directory,0,7)!= 'mailto:'){
  			$directory .= "/";
	  }
  
 
	 if (substr($directory,0,7)!='http://' && substr($directory,0,8)!='https://' && substr($directory,0,7)!= 'mailto:'){
	 	$page = get_page_name($directory, $page);
	 }
 
  if ( $position=="top" ) {
    $top_menu_name[$top_menu_counter]=$name;
	 if (substr($directory,0,7)=='http://' || substr($directory,0,7)== 'mailto:' || substr($directory,0,8)=='https://' ){
	    $top_menu_link[$top_menu_counter]=$directory;
	 }else{
	    $top_menu_link[$top_menu_counter]= "?" . $directory;
	 }
    $top_menu_topic[$top_menu_counter]=$page;
    $top_menu_newwin[$top_menu_counter] = $newwin;

    $top_menu_counter++;

  } elseif ( $position=="left" ) {
    $left_menu_name[$left_menu_counter]=$name;
	 if (substr($directory,0,7)=='http://' || substr($directory,0,7)== 'mailto:' || substr($directory,0,8)=='https://'){
	    $left_menu_link[$left_menu_counter]=$directory;
	 }else{
	    $left_menu_link[$left_menu_counter]= "?" . $directory;
	 }
	
    $left_menu_topic[$left_menu_counter]=$page;
    $left_menu_newwin[$left_menu_counter] = $newwin;
	 $left_menu_level[$left_menu_counter]= 0;
    $left_menu_counter++;
	 $left_menu_path[$left_menu_counter-1] = ($left_menu_counter + 1) . "/";
  } elseif ( $position=="bottom" ) {
    $bottom_menu_name[$bottom_menu_counter]=$name;
	 if (substr($directory,0,7)=='http://' || substr($directory,0,7)== 'mailto:' || substr($directory,0,8)=='https://'){
	    $bottom_menu_link[$bottom_menu_counter]=$directory;
	 }else{
	    $bottom_menu_link[$bottom_menu_counter]="?" . $directory;
	 }
    $bottom_menu_topic[$bottom_menu_counter]=$page;
    $bottom_menu_newwin[$bottom_menu_counter] = $newwin;

    $bottom_menu_counter++;
  }
  
}


/*******************************************************************************
 fill_menu_dirs - Fill in the menu structure based on directories
 INPUTS  : $directory - The top dir from which it will start
           $level - The level in the menu. Notice that in the beginning
			           it must be "-1", corresponding to "Home"
 *******************************************************************************/
function fill_menu_dirs($path="", $directory="./", $level=-1)
{
	
	global $left_menu_counter;
	global $server_name;
	global $rel_path;
	global $sorting;
	
	if ($level==-1 && $left_menu_counter > 0) return;
	
	$dirnames = array();
	$times = array();
	
	$no_subdirs = 0;
	$page = get_page_name($path . $directory,'');
	

	if (file_exists($path . $directory . 'index.php') && $level>-1){
		if ($path=='./') $path = '';
		$path = 'http://' . $server_name . $rel_path . $path;
		$page = '';
	}

	if ($directory[0]!='.'){
		if ($level == 0){
			add_menu('left',   substr($directory,0,strlen($directory)-1) , $path . $directory, $page);
		}else{
			add_left_submenu($level, substr($directory,0,strlen($directory)-1) , $path .  $directory, $page);
		}
	}


	if ((substr($path,0,7)=='http://') || (substr($path,0,8)=='https://')) return;

	$top_dir_handle = opendir($path . $directory);
	while(false != ($file=readdir($top_dir_handle))){
		if (is_dir($path . $directory . $file) && $file[0]!="."){
			$dirnames[$no_subdirs] = $file;
			//echo("Checking time on :" . $path . $directory . $file . "<br>\n");
			$times[$no_subdirs] = filectime( $path . $directory . $file);
			$no_subdirs ++;
		}
	}
	
	closedir($top_dir_handle);
	
	/*
	 * Sort the file names according to a criterium 
	 */

	if ($sorting=='time'){	
		$flag = 1;
		while($flag == 1){
			$flag = 0;
			for($ii=0; $ii < $no_subdirs-1; $ii ++){
				if ($times[$ii]>$times[$ii+1]){
					$tmp = $times[$ii+1];
					$times[$ii+1] = $times[$ii];
					$times[$ii] = $tmp;

					$tmp = $dirnames[$ii+1];
					$dirnames[$ii+1] = $dirnames[$ii];
					$dirnames[$ii] = $tmp;
					$flag = 1;
				}
			}
		}
	}elseif($sorting=='alpha'){
		$flag = 1;
		while($flag == 1){
			$flag = 0;
			for($ii=0; $ii < $no_subdirs-1; $ii ++){
				if (strcmp($dirnames[$ii], $dirnames[$ii+1])>0){
					$tmp = $times[$ii+1];
					$times[$ii+1] = $times[$ii];
					$times[$ii] = $tmp;
					$tmp = $dirnames[$ii+1];
					$dirnames[$ii+1] = $dirnames[$ii];
					$dirnames[$ii] = $tmp;
					$flag = 1;
				}
			}

		}
	}
	
	for ($ii = 0; $ii < $no_subdirs; $ii++){
		fill_menu_dirs( $path .  $directory ,  $dirnames[$ii] . '/', $level+1);
	}
}




/*******************************************************************************
  start_menu_dtu()

	Create a table for the menu.
	
	|
	|+--------------------+
	|| +----------------+ |
	|| |   Home         | |           Here is the displayed content fom
	|| |  ----------    | |           main.html
	
	
   Create a menu item [Home]. If there is no "top_menu", display the 
	"Home" item in the side menu. Otherwise "Home" is displayed in the 
	top menu.
 *******************************************************************************/
 
function start_menu_dtu()
{
  global $menu_line;
  global $top_menu_counter;
  global $home_text;
  global $server_name;
  global $rel_path;
  global $menu_index;

  	if ($menu_index==-1){
		$classname="current";
	}else{
		$classname="navigation";
	}

  big_comment("Top and left menus");
  
  echo("<td valign=\"top\" width=\"151\" class=\"leftmenu\">");
  echo("<table align=\"center\" width=\"95%\" class=\"leftmenu\">\n");
  echo("<tr><td class=\"leftmenu\">");
  $line_width=0.9*145;
  echo("<table align=\"center\" width=\"100%\">\n");
 
  //if (!file_exists("def.top_menu")){
  
  if ($top_menu_counter == 0){
    echo("<tr>\n");
    echo("<td class=\"leftmenu\">");
	 echo("<br>");
    echo("<a  class=\"$classname\" href=\"http://$server_name" . $rel_path. "\"> $home_text </a>\n");
    echo("<img src=\"$menu_line\" width=\"$line_width\" height=1 alt=\"Menu Line\">");
    echo("</td>\n");
	 echo("</tr>\n");
  }
}

/*******************************************************************************
	end_menu_dtu()
	   Finish a side menu. This function is complementary to start_menu()
		
   |
	|| |  menu_item     | |           Here is the displayed content fom
	|| |  ----------    | |           main.html
	|| +----------------+ |
	|+--------------------+
	
 *******************************************************************************/
function end_menu_dtu()
{
	echo("</table>\n");
	echo("</td></tr>\n");
	echo("</table>\n");
	echo("<br><p>");
	echo("</td>");
	
}
 
 
 
 /*******************************************************************************
  FUNCTIPON : menu_subpath
  ABSTRACT  : Returns the subpath to a certain level
 *******************************************************************************/
function menu_subpath($menu_path, $level)
{
	$subpath = "";

	$path_parts = split("/",$menu_path);
	$no_parts = count($path_parts);
	
	for($ii = 0; $ii<=$level && $ii<=$no_parts; $ii++ ){
		$subpath .= $path_parts[$ii] . "/" ;
	}
	return $subpath;
} 


/*******************************************************************************
  make_left_menu_dtu()
  		Create the submenu from the contents of the file "def.menu"
		
 *******************************************************************************/
function make_left_menu_dtu()
{
  global $menu_line;
  global $leftmenu_html;
  global $left_menu_counter;
  global $left_menu_name;
  global $left_menu_level;
  
  global $left_menu_link;
  global $left_menu_topic;
  global $left_menu_level;
  global $left_menu_path;
  global $level;
  global $menu_index;
  global $menu_name;
  
   $line_width=145*0.9;
	$ii = 0;
	
	if ($menu_index>-1){
		$menu_path = $left_menu_path[$menu_index];
	}else{
		$menu_path = '1/';
	}


	$show  = array(0);
	
	for ($ii = 0; $ii < $left_menu_counter; $ii++){
		if (($left_menu_level[$ii] == 0) ||
		    ($left_menu_level[$ii] <= $level && menu_subpath($left_menu_path[$ii],$left_menu_level[$ii]-1)==menu_subpath($menu_path,$left_menu_level[$ii]-1))
		    ||($left_menu_level[$ii]==($level+1) && menu_subpath($left_menu_path[$ii],$level)==menu_subpath($menu_path,$level))){
			 	$show[$ii] = 1;
		}else{
			   $show[$ii] = 0;
		}
	}
	
	for ($ii = 0; $ii < $left_menu_counter; $ii++){
		$next_show == 0;
		for ($jj = $ii+1; $jj < $left_menu_counter; $jj++){
			if ($show[$jj]==1){
		 		$next_show[$ii] = $jj;
				break;
			}
		}
	}
	
	$ii = 0;
	
	while ($ii < $left_menu_counter){
	
		if (strlen(trim($left_menu_topic[$ii]))>0){
			$left_menu_link[$ii]=$left_menu_link[$ii] . $left_menu_topic[$ii];
		}

		if ($show[$ii]==1){
			if ($ii==$menu_index && $menu_name=='left'){
				$classname="current";
			}else{
				$classname="navigation";
			}
			print("<tr>\n"); print("<td class=\"leftmenu\">\n");
			if (strlen(trim($left_menu_name[$ii]))>0){
				if(strlen($left_menu_link[$ii])>0){
					print("<a class=\"$classname\" href=\"$left_menu_link[$ii]\" ");
					if ($left_menu_newwin[$ii] == 1) print (" target=\"_blank\" ");
					print(">" . $left_menu_name[$ii] . " </a><br>");
					if (($ii == $left_menu_counter-1 || $left_menu_level[$ii]==0) || 
					    ($left_menu_level[$ii]==$level-1 && $left_menu_level[$next_show[$ii]]==$level) ||
					    ($left_menu_level[$ii]==$level && $left_menu_level[$next_show[$ii]]==$level+1) ||
						 ($left_menu_level[$next_show[$ii]]<$left_menu_level[$ii])){
						// $ln_width = $line_width*(1-0.05*$left_menu_level[$ii]);
						 $ln_width = $line_width;
					print("<img  src=\"$menu_line\" width=\"$ln_width\" height=1 alt=\"Menu Line\">");  // Put \n for larger space
					}
				}else{
					print("<a class=\"$classname\">" . $left_menu_name[$ii] . "<br>");
					print("<img src=\"$menu_line\" width=\"$line_width\" height=1 alt=\"Menu Line\">");  // Put \n for larger space
				}
			}else{
				print("<br>\n");
			}
			print("</td>");
			print("</tr>\n");
		}
		$ii = $ii + 1;
	}
	
}
 
/*******************************************************************************
	make_horiz_menu($name, $new_row)
		Create a horizontal menu. The menu is either a top menu, or a bottom menu.
		
	$name - The name of the file from which to read the menu definitions.
	        Usually this is either "def.top_menu" or "def.bottom_menu"
  
  $new_row - Flag showing whehter to add a new row. If this is a top menu,
             then a new row must be added - below the pictures. 
				 The new row has two columns - one of 151 pixels and one of 600 pixels.
				 The column with the 151 pixels will contain the link "Home".
				 
				 If this is a bottom menu, there is no need to add a new row ($new_row == 0).

 For a top menu:
 
 |                                                                            
 | +---------------+---------------+-----------------+-----------------+ |
 | | +------------+|               |                 |                 | |
 | | | Home       || menu_item[0]  |  menu_item[1]   | ....            | |
 | | +------------+|               |                 |                 | |
 | +---------------+---------------+-----------------+-----------------+ |
 |                                                                       |
 
 
 For a bottom menu:
 
 |                                                                            
 |                -+---------------+-----------------+-----------------+ |
 | Here stays      |               |                 |                 | |
 | ll_picture      | menu_item[0]  |  menu_item[1]   | ....            | |
 |                 |               |                 |                 | |
 |               --+---------------+-----------------+-----------------+ |
 |                                                                       |
 
 *******************************************************************************/
function make_horiz_menu($name, $new_row)
{
	global $top_menu_link;
	global $top_menu_name;
	
	global $top_menu_counter;
	global $top_menu_topic;
	global $top_menu_newwin;
	
	global $bottom_menu_link;
	global $bottom_menu_name;
	global $bottom_menu_newwin;
	
	global $bottom_menu_counter;
	global $bottom_menu_topic;
	global $home_text;
	global $server_name;
	global $rel_path;
	global $menu_index;
	global $menu_name;
	
	
  if ( $name == "top" ) {
    $menu_counter=$top_menu_counter;
    $mmenu_name=$top_menu_name;
    $menu_link=$top_menu_link;
    $menu_content=$top_menu_topic;
	 $menu_newwin=$top_menu_newwin;
	 
  } else {
    $menu_counter=$bottom_menu_counter;
    $mmenu_name=$bottom_menu_name;
    $menu_link=$bottom_menu_link;
    $menu_content=$bottom_menu_topic;
	 $menu_newwin=$bottom_menu_newwin;
  }
	
	if ($menu_index==-1){
		$classname="current";
	}else{
		$classname="navigation";
	}
  if ($new_row != 0){
  		$menu_height = 35;
		$menu_class = '"topmenu"';
		echo("<td valign=\"top\" width=\"151\" height=\"$menu_height\">\n");
		echo(" <table  cellpadding=0 cellspacing=0>\n");
			echo(" <tr><td valign=\"middle\" class=\"topmenu\" width=150 height=34>");
			echo("<a  class=\"$classname\" href=\"http://$server_name" . $rel_path . "\">&nbsp;&nbsp;&nbsp;&nbsp;$home_text</a><br>");
			echo("</td></tr>\n");
		echo(" </table>");
		echo("</td>");
	}else{
		$menu_height = 72;
		$menu_class = '"bottommenu"';
	}
	
	echo("<td width=\"600\" height=$menu_height class=\"upper\" align=\"center\" valign=\"top\">\n");
	echo(" <table border=\"0\" align=\"center\" cellpadding=0 cellspacing=0>\n");
	echo("  <tr valign = \"middle\">\n");

	$col_width=600/($menu_counter);
	$ii = 0;
	while ($ii < $menu_counter){
		if (strlen(trim($menu_content[$ii]))>0){
		   //SN_ $menu_link[$ii]=$menu_link[$ii] . '/'. $menu_content[$ii];
			$menu_link[$ii]=$menu_link[$ii] . $menu_content[$ii];
		}
		if ($ii==$menu_index && $menu_name==$name){
				$classname="current";
			}else{
				$classname="navigation";
			}

		print("<td height=$menu_height class=$menu_class width=\"$col_width\" align=\"center\" valign=\"middle\">");
		print("<a class=\"$classname\" href=\"$menu_link[$ii]\" ");
		if ($menu_newwin[$ii]==1) print(" target=\"_blank\" ");
		print(">". $mmenu_name[$ii] . "</a>");
		$ii++;
		print("</td>\n");
	}
	echo("  </tr>\n");
	echo(" </table>\n");
	
	echo("</td>\n");
	echo("</tr>\n");
	if ($new_row!=0){
		echo("<tr>"); small_comment("End of top menu");
	}

}

/********************************************************************************
 ********************************************************************************/
function make_menus_dtu()
{
  global $top_menu_counter; 
  global $left_menu_counter; 
  global $menu_line;
  global $query_string;
  global $print_icon;
     
	$line_width = 140*0.9;
   big_comment("Start of top and left menus");
	if ($top_menu_counter > 0){
		make_horiz_menu("top", 1);
	}
	

	if ($left_menu_counter>0) {
		start_menu_dtu();
		make_left_menu_dtu();    // Create a menu from a definition file
	}else{
		start_menu_dtu();
	}
	
	
	print("<tr>\n"); print("<td class=\"menu\">");
	print("<br>");
	print("</td></tr>")	;
	print("<tr>\n"); print("<td class=\"menu\">");
	print("<a class=\"navigation\" href=\"?$query_string|print\"><img border=0 align=\"left\" src=\"$print_icon\">Print Version</a><br>");
	print("<img src=\"$menu_line\" width=\"$line_width\" height=1 alt=\"menu line\">");  // Put \n for larger space
	print("</td>");
	print("</tr>\n");

	end_menu_dtu();
}

 
/********************************************************************************
 FUNCTION : read_text_file
 ABSTRACT : This function reads a text file. The HTML tags are
            converted to normal text symbols.
 ********************************************************************************/

function read_text_file($fn)
{
	$norun = 0;
	$fn = preg_replace("|^[/\.]+|", '', $fn);


	// Check for errors.
	if(!$fn) {
		echo "<i>You need to specify a file to list.</i>";
	} elseif(!is_readable($fn)) {
		echo "<i>Cannot read $fn.</i>";
	} else {
		// Read the file and output, with HTML specials converted.
		$in = fopen($fn, 'r');
		if(!$in) {
			echo "<i>Cannot open $fn.</i>";
		} else {
			// Test for the listing blocker.
			$line = fgets($in, 40);
			if($line == "<!--NOLIST-->\n" || $line == "<?php //NOLIST\n") {
				echo "<i>Cannot list $fn.</i>";
			} else {
				echo "<pre><tt>";
				while(TRUE) {
					// Get rid of password.
					$line = preg_replace('/(pg_connect\s*\(' .
												'[^(]*password\s*=\s*)([a-zA-Z0-9_\-+=@%&]*)/',
                                    '$1XXXX', $line);
               echo htmlspecialchars($line);
					if(feof($in)) break;
					$line = fgets($in, 1024);
            }
            echo "</tt></pre>\n";
			}
		}
	}
}


/***********************************************************************
	function read_file_tables()
	
	This function is specialized for reading text files which consist
	primarily of one or more tables.
	
	The text file is read AS IS. In other words it is shown as a normal
	HTML file. 

	The only difference 	is when we want to display a table. 
	A table starts with 
	<hpage_table>
	and ends with
	</hpage_table>
	
	Every new row is on a new line
	Every new cell is separated from the other cell with "&&"
	
	Example:
	
<h1>This is a calender</h1>


<hpage_table>
 No    &&     Dato   &&     Emne    &&      Litteratur      &&  Øvelse
 1     &&   15 Sep   &&   That is so cool   && Whenever     &&  That is
 2     &&   25 Dec   &&   What the hell     && That is cool &&  This is something really long. I do not know what is going to happen because of it.
 3     &&   25 Dec   &&   What the hell     && That is cool &&  Cool
 4     &&   25 Dec   &&   What the hell     && That is cool &&  That is
 5     &&   25 Dec   &&   What the hell     && That is cool &&  What the hell

</hpage_table>

<p><br>
	
 ***********************************************************************/
function read_file_tables($in_file_name)
{
		$in_file = fopen($in_file_name, "r");
		$in_table = 0;
		$in_body = 0;
		$dark = 0;
		
		while(!feof($in_file)){
			$line = fgets($in_file, 4096);
			$line = trim($line);

			if (strlen($line)>0){
				$pieces = explode("&&",$line);
				if (strcmp($line,"<hpage_table>")==0){
					echo("<table cellpaddding=2 cellspacing=0 border=\"1px\" bordercolor= \"#aaaaaa\" width=\"100%\" >\n");
					$in_table= 1;
					$in_body = 0;
				}else if (strcmp($line,"</hpage_table>")==0){
					echo("</table>\n");
					$in_table= 0;
					$in_body = 0;
				}else	if ($in_table == 1){
					if ($in_body == 0){
						echo("<tr bgcolor=\"#444444\">\n");
						$in_body = 1;
						$dark = 1;
					   $start_tag = "<td> <font color=\"#ffffff\">";
					   $end_tag = "</font></td>";
					}else{
						if ($dark == 1){
						    echo("<tr height=\"40px\" bgcolor=\"#cfcfcf\">\n");
							 $start_tag = "<td> <font color=\"#000000\">";
							 $end_tag = "</font></td>";
					   }else{
						   echo ("<tr height=\"40px\" bgcolor=\"#ffffff\">\n");
							 $start_tag = "<td> <font color=\"#000000\">";
							 $end_tag = "</font></td>";
						}
					}
					
					for($ii = 0; $ii < count($pieces); $ii++){
						echo ($start_tag . trim($pieces[$ii]) . $end_tag);
					}
					
					$dark= !$dark;
					echo("</tr>\n");
				}else{
					printf($line .  "\n");
				}
			}
		}

} 




/***********************************************************************
 ***********************************************************************/
function make_page_top_dtu()
{
 global $upperleft_html;
 global $upperright_html;
 global $middleleft_html;
 global $middleright_html;

  // Draw outer-most table for border
  big_comment("Decorations on top");
  echo("<table width=753 border=0 cellpadding=0 cellspacing=0 class=\"border\"> \n");
  echo("  <tr><td valign=bottom align=center width=751>\n");
  
  // Draw the internal table 
  echo("  <table align=\"center\" class=\"border\" width=\"751\" border=0 cellspacing=0 cellpadding=0>\n");
  
  /* Put the top row of images */
  echo("    <tr> <!-- Top row of images -->\n");	

  echo("      <td  valign=top width=151 height=20 class=\"upper\">");
  echo $upperleft_html;
  echo("</td>\n");

  echo ("      <td width=600 valign=top height=20 class=\"upper\" align=left>");
  echo  $upperright_html;
  echo("</td>\n"); 
  echo("    </tr><!-- End of top row of images -->\n");
  


	
	/*  Start the second row  */
  echo("\n\n");
  echo("    <tr><!-- Second row of images -->\n");

  echo("      <td valign=\"top\" height=72 width=151 class=\"upper\">");
  echo $middleleft_html;
  echo("</td>\n");

  echo("      <td width=600 valign=\"top\" height=72 class=\"upper\">");
  echo $middleright_html;
  echo("</td>\n");
  echo("    </tr> <!-- End of second row of images -->\n");
	
	/* Start the third row */
	echo("\n\n<!-- Third Row ---------------------------------------------->\n");
	echo("<tr>\n");

}

/***********************************************************************
 ***********************************************************************/
function make_page_bottom_dtu()
{

	global $last_modified;
	global $lowerleft_html;
	global $bottom_menu_counter;
	global $show_date;
	global $bottom_text;
	global $web_address;

	
 	big_comment("Bottom of the page");	
	/* Put the lower left image */
  echo ("<tr class=banner><td width=\"151\"  class=\"banner\" >");
  if ( isset( $lowerleft_html ) ) {
    echo $lowerleft_html;
  } else {
    echo "&nbsp;";
  }
  echo("</td>");

/*  Make the lower right menu or text*/

  if ($bottom_menu_counter > 0 ) {
    make_horiz_menu("bottom", 0);
  }else{
	  echo("<td align=\"center\" width=600 class=\"bottom_menu\" >");small_comment("bottom text");
	  echo ("    <table width=\"92%\" class=\"banner\"> \n");
	  echo ("      <tr><td class=\"banner\" align=\"left\" valign=\"middle\">\n");
	  echo $bottom_text;
	  echo("\n     </td></tr>\n");
	  echo("    </table>\n");
	  echo("</td>\n"); small_comment("End fo Bottom text");
  }	
 
	echo("  </table><!-- Inner table-->");
	echo("</td></tr>\n");
	echo("</table>\n");
}



/***********************************************************************
 ***********************************************************************/
function read_fifo($fifo_name)
{
	/*
	 * Open the file, which describes how many items there are in a FIFO
	 * and what is the base file name.
	 */
	$dir = dirname($fifo_name) . "/";
	
	$fd = fopen($fifo_name,"r");
   $max_items = 1;
   $head = 1;
   $tail = 1;
   while(!feof($fd)){
        $buffer = fgets($fd, 8192);
         parse_str($buffer);
  }
  fclose($fd);
  
  
  /*
   *  Before displaying the FIFO, it is possible to 
	*  to display some more information.
   */
	$page_description = $dir . $page_description;
	if (strlen(trim($page_description))>0){
		if (file_exists(trim($page_description))){
			readfile(trim($page_description));
		}
	}
	
	/*
	 *  Set some initial conditions
	 */
	 

	$current = $tail;
	$base_name =  $dir .  trim($base_name) . ".";
   $number = 1;
 	
	while ($current != $head){
		$file_name = $base_name . sprintf("%d", $current);
		if (file_exists($file_name)){
			$fd = fopen($file_name, "r");
			while (!feof($fd)){
				$buffer = fgets($fd, 2*8194);
				parse_str($buffer);
			}
			fclose($fd); 
                    
			echo "<table align = \"center\" class=\"contents\" width = \"80%\">\n";
			echo "<tr><td width = \"100%\">\n";
				
				/* Display the title of the item */
				echo "<table width=550 border=\"0\">\n";
				echo "<tr>"; echo "<td>";

				echo "<table width=549 class=\"border\"> ";
				echo("<tr class=\"border\">");
				echo "<td width=20 class=\"border\">";
				echo "<b>$number </b>|";
				echo "</td>\n";
                     
				echo "<td>";
				echo $date;
				echo "</td>\n";
				echo "<td width=440>\n";
				echo "|&nbsp;&nbsp;<b>$title</b>";
				echo "</td>";
				echo("</tr>");
				echo "</table>\n";
				
			echo "</td>";
			echo "</tr>\n";
			
			/* Display the text of the fifo item */
			echo "<tr> <td width = \"90%\">\n";
				echo "<table width=549 class=\"border\">";
					echo "<tr><td class=\"printer\">";
          		readfile($dir . trim($news_text));
					echo "</td></tr>";
					echo "</table>";
					echo "</td>";
					echo "</tr>";
				echo "</table>";
			echo "</td></tr></table><p>\n";
		}
		$current = $current + 1;
		if ($current>$max_items-1) $current = 1;
		$number = $number + 1;
	}
}


/***********************************************************************
 ***********************************************************************/
function show_page_normal()
{
	global $show_date;
	global $script_filename;
	global $directory;
	global $page;
	global $page_look;
	global $rel_path;
	global $css_link;
	global $server_name;
	global $rel_path;
	
   global $allowed;
	global $require_password;
	
	print_header($css_link);

	switch ($page_look){
		case "dtu": 
		default:
			make_page_top_dtu();
			make_menus_dtu();
			echo("<td class=\"contents\" valign=\"top\" width=\"100%\">\n");
			small_comment("A new table with one cell, in which the user file is shown");
			echo("<table width=\"92%\" align=\"center\" >\n");
			echo("<tr valign=\"top\">\n");
			echo("<td  width=\"91%\" class=\"contents\">\n");
			break;
	};

	if (!$allowed){
	   print "You must log in to view this page";
   	print_footer();
		return;
	}
	
 /*
	if ($require_password){
		global $auth_userid;
		print ("<p>Welcome : " . GetRealname( $auth_userid ));
		print ("<a href=\"http://$server_name$rel_path?|logout\"> LOGOUT </a><br></p>\n");
	}
	*/
	
	$page = get_page_name($directory, $page);
	

	$ext = pathinfo($page);
	$ext = trim($ext['extension'] . " ");

	if(file_exists($directory . $page)){
		//echo("<td class=\"conents\">\n");
		big_comment("Start of user file : " . $directory . $page);

		$last_modified = filemtime($directory . $page);
		switch($ext){
			case "html":
			case "htm" :
							readfile($directory . $page);
							break;
			case "php" :
						  include $directory . $page;
						  break;
			case "fifo":
							read_fifo($directory . $page);
							break;
			case "table":
							read_file_tables($directory . $page);
							break;
			case "txt" :
			case ""    :
							read_text_file($directory . $page);
							break;
						
		}
		echo("</td></tr></table></td></tr>\n");
		big_comment("End of user file : " . $directory . $page);

	}else{
		$last_modified = filemtime($script_filename);
		echo("Page is under construction <br>\n");
  		echo("</td>\n</tr>\n</table></td></tr>\n");
	}

	switch ($page_look){
		case "dtu": 
		default:
			make_page_bottom_dtu();		 
			break;
	};

	
	if( $directory=="./"){
		print($rel_path);
	}else{
		print($rel_path . $directory);
	}
	print($page . "<br>\n");

	if ($show_date==1){
		$last_date = date("D,  d-M-Y",$last_modified);
		$last_time = date("G:i",$last_modified);
		echo("Last updated: $last_time on $last_date\n");
	}
	

	print_footer();
}



/***********************************************************************
 ***********************************************************************/
function show_print_page()
{
	global $show_date;
	global $script_filename;
	global $directory;
	global $page;
	global $page_look;
	global $rel_path;
	global $server_name;
	global $css_print_link;

	print_header($css_print_link);

	switch ($page_look){
		case "dtu": 
		default:
			echo("<table width=\"100%\" align=\"center\" >\n");
			echo("<tr valign=\"top\">\n");
			echo("<td  width=\"91%\" class=\"printer\">\n");
			break;
	};


	$page = get_page_name($directory, $page);

	$ext = pathinfo($page);
	$ext = trim($ext['extension'] . " ");

	if(file_exists($directory . $page)){
		//echo("<td class=\"conents\">\n");
		big_comment("Start of user file : " . $directory . $page);

		$last_modified = filemtime($directory . $page);
		switch($ext){
			case "html":
			case "htm" :
							readfile($directory . $page);
							break;
			case "php" :
						  include $directory . $page;
						  break;
			case "fifo":
							read_fifo($directory . $page);
							break;
			case "table":
							read_file_tables($directory . $page);
							break;
			case "txt" :
			case ""    :
							read_text_file($directory . $page);
							break;
						
		}
		echo("</td></tr></table>\n");
		big_comment("End of user file : " . $directory . $page);

	}else{			
		$last_modified = filemtime($script_filename);
		echo("Page is under construction <br>\n");
		echo("</td>\n</tr>\n</table>\n");
	}

	switch ($page_look){
		case "dtu": 
		default:
		break;
	};

	print ("<hr>\n");
	if( $directory=="./"){
		print("http://" . $server_name . $rel_path);
	}else{
		print("http://" . $server_name . $rel_path . $directory);
	}
	print($page . "<br>\n");

	if ($show_date==1){
		$last_date = date("D,  d-M-Y",$last_modified);
		$last_time = date("G:i",$last_modified);
		echo("Last updated: $last_time on $last_date\n");
	}

	print_footer();
}




/*********************************************************************
  FUNCTION : set_title
  ABSTRACT : Sets a global title for the whole web site. 
             If this function is NOT used, then each page should
				 provide its own title.
 *********************************************************************/

function set_title($title)
{
  global $Title;
  $Title=$title;
}


function set_style($style)
{
 global $css_link;
 $css_link=$style;
}

/*********************************************************************
  FUNCTION  set_home_text
  ABSTRACT  Changes the text which is shown the "Home" item.
				
 *********************************************************************/
function set_home_text($text)
{
	global $home_text;
	$home_text = $text;
}



/*********************************************************************
  FUNCTION  show_updated
  ABSTRACT  Determines whether to show the "Last Updated" text on the 
            bottom of the page.
				
 *********************************************************************/
function show_updated($option='false')
{
	global $show_date;
	$show_date = ($option=='true');
}


/*********************************************************************
  FUNCTION  get_level
  ABSTRACT  Determines the level at which the link is
				
 *********************************************************************/
function get_level()
{
	global $rel_path;
	global $directory;
	global $page;
	global $full_file_name;
	
 	global $left_menu_link;
	global $left_menu_topic;
	global $left_menu_name;
	global $left_menu_path;
	global $left_menu_counter;
	global $left_menu_level;

	global $top_menu_counter;
 	global $top_menu_link;
	global $top_menu_topic;
	global $top_menu_name;

	global $level;
	global $menu_index;
	global $menu_name;
	
	$page = get_page_name($directory, $page);
	
	if( $directory=="./"){
		$full_file_name = "";
	}else{
		$full_path = $directory;
	}
	$full_path .= $page;
	
	$full_path = trim($full_path);
	
	$level = 0;
	$menu_index = -2;
	if(($directory=="." || strlen($directory)==0) && (substr($page,0,4)=="main")){
		$menu_index = -1;
	}
	
	for ($ii=0; $ii < $left_menu_counter; $ii ++)
	{
		$menu_link = $left_menu_link[$ii];
		if ($menu_link[0]=='?'){
			$menu_link = substr($menu_link,1,strlen($menu_link)-1);
			if( $menu_link=="./"){
				$menu_link_path = "";
			}else{
				$menu_link_path = $menu_link;
			}
			
			if (strlen(trim($left_menu_name[$ii]))>0){
				$menu_link_path .= $left_menu_topic[$ii];
				if (!strcmp(realpath(trim($menu_link_path)),realpath($full_path))){
					$level = $left_menu_level[$ii];
					$menu_index = $ii;
					$menu_name = 'left';
					break;
				}
			}
		}
	}
	
	if ($menu_index==-2){
		for ($ii=0; $ii < $top_menu_counter; $ii ++)
		{
			$menu_link = $top_menu_link[$ii];
			if ($menu_link[0]=='?'){
				$menu_link = substr($menu_link,1,strlen($menu_link)-1);
				if( $menu_link=="./"){
					$menu_link_path = "";
				}else{
					$menu_link_path = $menu_link;
				}
			
				$menu_link_path .= $top_menu_topic[$ii];
				if (!strcmp(realpath(trim($menu_link_path)),realpath($full_path))){
					$menu_index = $ii;
					$menu_name = 'top';
					break;
				}
			}
		}
	};
	



	if ($menu_index==-2){
		for ($ii=0; $ii < $bottom_menu_counter; $ii ++)
		{
			$menu_link = $bottom_menu_link[$ii];
			if ($menu_link[0]=='?'){
				$menu_link = substr($menu_link,1,strlen($menu_link)-1);
				if( $menu_link=="./"){
					$menu_link_path = "";
				}else{
					$menu_link_path = $menu_link;
				}
			
				$menu_link_path .= $bottom_menu_topic[$ii];
				if (!strcmp(realpath(trim($menu_link_path)),realpath($full_path))){
					$menu_index = $ii;
					$menu_name = 'bottom';
					break;
				}
			}
		}
	};
	
	if ($full_path == "main.html") $menu_index = -1;

}


/********************************************************************
  Checks whether the user is authorised
 ********************************************************************/

function is_user_authorised($name)
{
	global $allowed;
	global $allowed_users;
	
	//echo "is_user_authorised" . "<br>" ;
	$no_allowed = count($allowed_users);
	for($i=0; $i < $no_allowed; $i++){
		if ($name == $allowed_users[$i]){
		   $allowed = true;
			break;
		}
	}
	
	return $allowed;
} 

/**********************************************************************
  check_login() - Checks whether the user has logged in. If not,
      it displays a message box for the login. Then it tries to 
		connect to the mail servers to verify the password.
 **********************************************************************/

function check_login()
{
   global $PHP_AUTH_USER;
   global $second_log;
 	global $PHP_AUTH_PW;
	global $allowed;
	global $SERVER_PORT;


   // If we don't have cookie or a password, ask the browser to ask for a
	// password.

	if(!$second_log && !isset($PHP_AUTH_USER)) {
 	   header("WWW-Authenticate: Basic realm=\"Test Realm\"");
		header("HTTP/1.0 401 Unauthorized");
		echo "What, you think you can get in here without a password?";
		exit;
	}

	// Check the permission situation.
	if(!$second_log && isset($PHP_AUTH_USER)) {
		 // Gave password, no cookie.  Check user & password.
	    // $mbox = @imap_open("{oersted.oersted.dtu.dk:143}INBOX", "$login_username", "$password" );
		  $mbox = @imap_open("{oersted.oersted.dtu.dk:143}INBOX", "$PHP_AUTH_USER", "$PHP_AUTH_PW" );
        //$mbox = imap_authenticate( $login_username, $password, "oersted.oersted.dtu.dk" );
        if ($mbox) {
           imap_close($mbox);                      
			  setcookie('logged_in', '1', time() + 7 * 24 * 60 * 60);
        } else{
		     header("WWW-Authenticate: Basic realm=\"Test Realm\"");
			  header("HTTP/1.0 401 Unauthorized");
			  echo "Sorry, your login is not valid.";
			exit;
	   }
   }

}


/**********************************************************************
 **********************************************************************/
function login()
{
	global $server_name;
	global $rel_path;
	global $PHP_AUTH_USER;
	global $OldUser;
	
	setcookie("PHP_AUTH_USER","");
	setcookie("PHP_AUTH_PW","");
	echo "<form action=\"$servername$rel_path\" METHOD=POST>\n"; 
	echo "<input type=\"hidden\" name=\"SeenBefore\" value=\"1\">\n"; 
	echo "<input type=\"hidden\" name=\"OldAuth\" value=\"$PHP_AUTH_USER\">\n"; 
	echo "<input type=\"hidden\" name=\"require_password\" value=\"1\">\n"; 
	echo("<input type=\"text\" name=\"OldUser\" value=\"$PHP_AUTH_USER\"></a>");
	echo "<input type=\"submit\" value=\"Re Authenticate\">\n"; echo "</form></p>\n";
	echo("</form>\n");
	
}


/*********************************************************************
  This function retrieves the publications provided a link
 *********************************************************************/

function retrieve_publication($url, $unique_start="<!-- start -->", $unique_end="<!-- end -->") {
      
		$handle = fopen ("$url", "rb");
		do {
		    $data = fread($handle, 8192);
		    if (strlen($data) == 0) {
		        break;
			    }
		    $fd .= $data;
		} while(true);
		fclose ($handle);
		
		if ($fd) 
		{
			$start= strpos($fd, "$unique_start"); 
			/* echo "start found at " . $start . "<br>"; */
			$finish= strpos($fd, "$unique_end"); 
			/* echo "finish found at " . $finish . "<br>"; */
			$length= $finish-$start;
			$code=Substr($fd, $start, $length).$unique_end;
		}
		
		$code=str_replace("../views","/publications/views",$code);
		return $code;
}

 
/**********************************************************************
 function create_pubdb_link($user_id=0, $type="all", $year="", $title)

  $user_id - User Id in the publication database
  
  $type: The type of publications. A string with one of the following:
          all  - All
          journal - Journal papers
		    conference - Conference papers
		    book - Book
		    msc- MSc Thesis
		    phd - PhD Thesis
		    report - Technical report
		    notes - Lecture note
		    software - Software
		    misc -  Misc
		    patent - Patent
		    dsc - Doctor thesis
			 
	$year - for which year the publication must be. 
	$title  - The title to stay on top of the page
	
	This function returns a link to the publication database
 **********************************************************************/
function create_pubdb_link($user_id=0, $type="all", $year="", $title="Publications")
{
	$cmd="full_view";
	$css="http://server.oersted.dtu.dk/personal/sn/snstyles/pub_blue.css";
	$order="year";
	$fmt="html";
	$b = 1;
	$e=1;
	$f = 1;  // For a link to a full view

	$trans = get_html_translation_table(HTML_ENTITIES); 
	$title = strtr($title, $trans);
	$trans = get_html_translation_table(HTML_SPECIALCHARS); 
	$title = strtr($title, $trans);
	$trans = Array(" "=> '%20');
	$title = strtr($title, $trans);
 	switch ($type){
		case "all": $type = 0; break;
		case "journal": $type=1; break;
		case "conference": $type=2; break;
		case "book": $type=3; break;
		case "msc": $type=4; break;
		case "phd": $type=5; break;
		case "report": $type=6; break;
		case "notes": $type=7; break;
		case "software": $type=8; break;
		case "misc": $type=9; break;
		case "patent": $type=10; break;
		case "dsc": $type=11; break;
		default:
		     $type=0;
	}

   if ($year=="all") $year="";
	
	$dblink = "http://oersted.dtu.dk/publications/personal/showbasket.php?";
	$dblink .= 'cmd=' . $cmd;
	$dblink .= '&id=' . $user_id;
	$dblink .= '&title=' . $title;
	$dblink .= '&header=';
	$dblink .= '&footer=';
	$dblink .= '&css=' . $css;
	$dblink .= '&type=' . $type;
	$dblink .= '&year=' . $year;
	$dblink .= '&fmt=' . $fmt;
	$dblink .= '&order=' . $order;
	$dblink .= '&f=' . $f;
	$dblink .= '&b=' . $b;
	$dblink .= '&e=' . $e;
	return $dblink;	
}


/**********************************************************************
 function pubdb_link($user_id=0, $type="all", $year="", $title="Publications" , $link_text)
    This function prints a link to the publication database
	 
  $user_id - User Id in the publication database
  
  $type: The type of publications. A string with one of the following:
          all  - All
          journal - Journal papers
		    conference - Conference papers
		    book - Book
		    msc- MSc Thesis
		    phd - PhD Thesis
		    report - Technical report
		    notes - Lecture note
		    software - Software
		    misc -  Misc
		    patent - Patent
		    dsc - Doctor thesis
			 
	$year - for which year the publication must be. 
	$title - The title to stay on top of the link page
	$link_text - The text that should stay in the link
 **********************************************************************/

function pubdb_link($user_id=0, $type="all", $year="", $title="Publications", $link_text="Publications")
{

   $url = create_pubdb_link($user_id, $type, $year, $title);
   print("<a href=\"$url\">$link_text</a>");
}


/*********************************************************************
 function pubdb_link($user_id=0, $type="all", $year="", $title)
    This function prints the text contained in the publication 
	 database.
	 
  $user_id - User Id in the publication database
  
  $type: The type of publications. A string with one of the following:
          all  - All
          journal - Journal papers
		    conference - Conference papers
		    book - Book
		    msc- MSc Thesis
		    phd - PhD Thesis
		    report - Technical report
		    notes - Lecture note
		    software - Software
		    misc -  Misc
		    patent - Patent
		    dsc - Doctor thesis
			 
	$year - for which year the publication must be. 
	$title - The title on the page with publications
 *********************************************************************/
function pubdb_page($user_id=0, $type="all", $year="", $title="Publications")
{

   
	$url = create_pubdb_link($user_id, $type, $year, $title);
	//print(retrieve_publication($url));
	print("<iframe width=100% height=500px frameborder=0 marginWidth=0 marginHeight=0 src=\"$url\"></iframe>");

}


/**********************************************************************
 *
 *         MAIN PROGRAM 
 *
 **********************************************************************/


$vars = split("\|",$query_string);

$topic = $vars[0];
if (!$action){
	$action = $vars[1];
}


if (strlen($topic)==0){
	$topic = "./";
}
$directory = pathinfo($topic);

if(is_dir($topic)){
	$directory = $topic;
	$page = "";
}else{
	$page = $directory['basename'];
	$directory = $directory['dirname'] . "/";
}


if(file_exists("web.config")){
	include "web.config";
}

if(file_exists($directory . "web.addconfig")){
	include $directory . "web.addconfig";
}

fill_menu_dirs();
get_level();

global $menu_index;
global $menu_name;

/*
if ($action=="login"){
   header("WWW-Authenticate: Basic realm=\"$action\"");
   header("HTTP/1.0 401 Unauthorized");
	setcookie("action","checklogin");
	exit;
}

if ($action=="checklogin"){
	setcookie("action","");
	check_login();
	$link = "https://".$server_name . $rel_path . "?" . $directory . $page;
   header ("Location: $link");
}

if ($action=='logout'){
	setcookie('logged_in', '0', time());
	setcookie('second_log', '1', time());
	$link = "https://".$server_name . $rel_path . "?" . $directory . $page;
   header ("Location: $link");
	echo "Logged out <br>";
}
if ($require_password){
	if ( $SERVER_PORT != 443 ) {
		$link = "https://".$server_name . $rel_path . "?" . $query_string;
		if (!$logged_in) setcookie("action","login");
 	   header ("Location: $link");
		exit;
	}
	
	if ($logged_in!="1"){
		setcookie("action","login");
		$link = "https://".$server_name . $rel_path . "?" . $directory . $page;
 	   header ("Location: $link");
		exit;
	}else{
		is_user_authorised($PHP_AUTH_USER);
	}
}
*/

if ($require_password){
	if ( $SERVER_PORT != 443 ) {
		$link = "https://".$server_name . $rel_path . "?" . $query_string;
		if (!$logged_in) setcookie("action","login");
 	   header ("Location: $link");
		exit;
	}
	check_login();
	if ($logged_in) is_user_authorised($PHP_AUTH_USER);
}

if ($action=="print"){
	show_print_page();
}else{
	show_page_normal();
}


?>
