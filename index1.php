<script language="JavaScript">
	function printPage(){
		if (window.print){
			window.print();
		} 
	}
</script>

<?php	

/*
 * FILE     : index.php
 *
 * ABSTRACT :  
 *
 * The functions from this file can be used to create a web page like 
 *  this:
 *
 *  +-------------+---------------------------------------------+
 *  | ul_picture  | ur_picture                                  |
 *  +-------------+---------------------------------------------+
 *  | ml_picture  | mr_picture                                  |
 *  |             |                                             |
 *  +-------------+---------------------------------------------+
 *  |             |     def.top_menu                            |
 *  |+------------+---------------------------------------------+
 *  |             |                                             |
 *  |  menu       | Contents of the page. Filled  from:         |
 *  |             |  * Several files if "def.fifo" existst      |
 *  |             |  * $item/$topic.html, if var $topic exists. |
 *  |             |  * $item/main.html, if main.html eixts      |
 *  |             |                                             |
 *  +-------------+---------------------------------------------+
 *  | ll_picture  |  def.bottom_menu  OR  def.bottom_text       |
 *  +-------------+---------------------------------------------+
 *
 *
 *
 * PEOPLE:  Svetoslav Nikolov, SN
 *          Claus Sørensen, CS
 *          Jørgen Arendt Jensen, JAJ
 *          Allan Jørgensen, AJ
 *          Mogens Yndal Pedersen, MYP
 *          Henrik Laursen, HL
 *
 * VERSION HISTORY:
 *   Revision A, June 2002, SN. 
 *               The programs works fully
 *
 *   Revision B, 29 Nov 2002, CS. 
 *               The setup is read from a single setup file as 
 *               suggested by JAJ. A number of features present in
 *               Revision A are missing. New CSS.
 *
 *   Revision B-1 29 Nov 2002, SN,
 *               Fixed part of the CSS, new images supported in web.config, 
 *
 *   Revision B-2 9 Dec 2002 SN,
 *               New behaviour of the function "add_menu", fixes in CSS,
 *               various bug fixes. 
 *         The program works again as in Revision A.
 *
 *   Revision B-3 16 Dec 2002, SN
 *                Problem with spaces around images fixed - bug detected by MYP
 *                No line if a menu item is empty. Change suggested by AJ
 *
 *   Revision B-4 17 Dec 2002, AJ, SN
 *                Functions for substituting special characters was added by AJ
 *                Problems with titles on pages fixed by SN
 *                If there is no upper-right image, then the title of the page
 *                printed in large font size.
 *
 *   Revision B-5, 18 Dec 2002,  SN
 *                Added "Last Updated text"
 *                Modifications to the BODY part of the CSS. 
 *                Font-family, size, color.
 *                Wrote a utility for making the titles using PHP
 *                New function "show_updated()" in "web.config"
 *                Added the possibility for a "bottom text", 
 *                which appears instead of the bottom menu.
 *                Added automatic printing from the left menu.
 *                Added "web.addconfig" allowing to have different
 *                setting for different $item(s)
 *
 *   Revision B-6, 19 Dec 2002, SN
 *                Now if the menu specifies a path to a directory, in which
 *                there is a "index.php", the address which is formed is 
 *                a full address to that PHP script.
 *
 *   Revision B-7 03 Jan 2003, SN
 *                The script now detects Netscape 4.x. In this case, the outer
 *                most table has a borderwidth of "1".
 *                Fixed a problem for the display of a horizontal menu. In 
 *                Netscape 4.x, the default value for a table border is "1".
 *                Now it is explicitly set to 0.
 *
 *   Revision B-8 07 Jan 2003, SN
 *                Now the script reads also "*.txt" files. For example
 *                one can have a "main.txt" file, instead of main.html
 *                file. The contents of main.txt will be displayed
 *                within a pair of "<pre>" and "</pre>" tags.
 *
 *   Revision B-8 07 Jan 2003, SN
 *                Modified the CSS files. Now the links change their 
 *                color when the cursor moves over them. 
 *	               The following CSS styles exist:
 *                red.css, green.css, blue.css, 
 *	               lila.css, brown.css, cyan.css
 *                Added support for "*.txt" files. If a "main.txt" 
 *                exists, or the topic refers to a ".txt" file, 
 *	               then the contents of  that file is read within a 
 *	               pair of "&lt;pre&gt;" and "&lt;/pre&gt;" tags.
 *	    
 *
 *   Revision B-9 14 Jan 2003, SN
 *                Added support for global paths, starting with "~/"
 *                Added forms for creating an Upper- and Middle- Right images.
 *
 *   Revision B-10 15 Jan 2003, SN
 *               Added a new command "Set Home text".
 *
 *   Revision B-11 15 Jan 2003, SN
 *               Text files are parsed for HTML tags.
 *
 *   Revision B-12 15 Jan 2003, SN
 *               Added support for sub-menus.
 *
 *   Revision B-13 21 Jan 2003, SN
 *               Bug fixes in the submenu structures
 *     
 */


# Reset variables
$top_menu_counter=0;
$left_menu_counter=0;
$bottom_menu_counter=0;
$use_title=0;              // Use a global title (or local)
$substitute = 0;           // Substitute special characters
$config_sym = array("\*","_");
$html_sym = array("&#8226;","<br>");
$show_date = 0;               // Whether to show "last updated"
$bottom_text="";

$home_text="Home";

# Define functions

global $menu_line;       // Contains the picture for the menu
global $ll_picture_;          // Contains lower-left picture
global $ll_alt_;              // Contains lower-left alternative text
global $ll_link_;             // Contains lower-left picture link
global $topmenu_html;
global $leftmenu_html;
global $use_title;
global $substitute;
global $config_sym;
global $html_sym;
global $last_modified;
global $show_date;
global $bottom_text;
                              // Reason: read def.decorations once -
										 // when the top of the page is displayed.





global $SCRIPT_PATH;
global $SCRIPT_URI_PATH;
global $HOME_URL_PATH;

/*********************************************************************
 *
 * print_header()  Read the Cascaded Style Sheet definition
 *
 *********************************************************************/
function print_header(){

  global $selected_style;
  global $homepagetitle;
  global $use_title;
  global $HTTP_ENV_VARS;
  global $HTTP_SERVER_VARS;
  global $DOCUMENT_ROOT;
  
  global $SCRIPT_URI_PATH;
  global $SCRIPT_PATH;
  global $HOME_URL_PATH;
   
  echo"<HEAD>\n";
  if ($use_title){
		echo"<title>".$homepagetitle."</title>\n";
	}
	
  $selected_style = trim($selected_style);
  
  if (file_exists($selected_style)){
		echo "<link rel=\"stylesheet\" href=\"";
		echo $selected_style;
		echo "\" type=\"text/css\">";
  } else if (file_exists($DOCUMENT_ROOT."/".$selected_style.".css")) {
		echo "<link rel=\"stylesheet\" href=\"";
		echo "/".$selected_style.".css";
		echo "\" type=\"text/css\">";
  } else if (file_exists("/oerstedstyles.css")) {
		echo "<link rel=\"stylesheet\" href=\"";
		echo "/oerstedstyles.css";
		echo "\" type=\"text/css\">";
	}else if ( substr($selected_style,0,2) == "~/"){
		//$selected_style = $SCRIPT_URI_PATH . substr($selected_style,1,strlen($selected_style)-1);
	 $selected_style = $HOME_URL_PATH . substr($selected_style,1,strlen($selected_style)-1);
		echo "<link rel=\"stylesheet\" href=\"";
		echo $selected_style;
		echo "\" type=\"text/css\">";
	}else if ( substr($selected_style,0,1) == "/"){
		echo "<link rel=\"stylesheet\" href=\"";
		echo $selected_style;
		echo "\" type=\"text/css\">";
	
  } else { 
  
?>
<style>
BODY{
    background-color: #336666;
    color: #aadddd;
   font-size: 12px; 
   font-family: Arial, Helvetica, sans-serif
}

/* Ordinary header 1 */
h1 {
     color: #336666; 
     font-size: 21px; 
     font-family: Arial, Helvetica, sans-serif; 
     font-weight: bold;  
     margin-top:0px;  
     margin-bottom:10px}
/* Ordinary header 2 */
h2 {
     color: #336666; 
     font-size: 17px; 
     font-family: Arial, Helvetica, sans-serif; 
     font-weight: bold;
     margin-top:5px;  
     margin-bottom:0px}
/* Ordinary header 3 */
h3 {
     color: #336666; 
     font-size: 14px; 
     font-family: Arial, Helvetica, sans-serif  ; 
     font-weight: bold;  
     margin-top:5px;  
     margin-bottom:0px}
/* Header content */
h4 {
     color: #336666; 
     font-size: 13px; 
     font-family: Arial, Helvetica, sans-serif  ;  
     font-weight: normal;  
     margin-top:0px;  
     margin-bottom:0px}
/* Image text */     
h5 {
     color: #000000; 
     font-size: 12px; 
     font-family: Arial, Helvetica, sans-serif;
     font-weight: normal;  
     margin-top:3px;
     margin-bottom:4px}
p,address {
     color: #000000; 
     font-style: normal; 
     font-size: 14px; 
     font-family: Arial, Helvetica, sans-serif; 
     margin-top:0px;  
     margin-bottom:10px}
a {
     color: #336666; 
     text-decoration: underline}
ul,ol,li {
     color: #000000; 
     font-size: 14px; 
     font-family: Arial, Helvetica, sans-serif}
small {
     color: #000000; 
     font-family: Arial, Helvetica, sans-serif}
a.small {
     color: #336666; 
     font-size: 12px; 
     font-family: Arial, Helvetica, sans-serif}
/* Ordinary header 1 */
h1.tbox {
     color: #ffffff; 
     font-size: 21px; 
     font-family: Arial, Helvetica, sans-serif; 
     font-weight: bold;  
     margin-top:0px;  
     margin-bottom:10px}
/* Ordinary header 2 */
h2.tbox {
     color: #ffffff; 
     font-size: 17px; 
     font-family: Arial, Helvetica, sans-serif; 
     font-weight: bold;
     margin-top:5px;  
     margin-bottom:0px}
/* Ordinary header 3 */
h3.tbox {
     color: #ffffff; 
     font-size: 14px; 
     font-family: Arial, Helvetica, sans-serif  ; 
     font-weight: bold;  
     margin-top:5px;  
     margin-bottom:0px}
/* Header content */
h4.tbox {
     color: #ffffff; 
     font-size: 13px; 
     font-family: Arial, Helvetica, sans-serif  ;  
     font-weight: normal;  
     margin-top:0px;  
     margin-bottom:0px}
/* Image text */     
h5.tbox {
     color: #ffffff; 
     font-size: 12px; 
     font-family: Arial, Helvetica, sans-serif;
     font-weight: normal;  
     margin-top:3px;
     margin-bottom:4px}
p.tbox,address.tbox {
     color: #ffffff; 
     font-style: normal; 
     font-size: 14px; 
     font-family: Arial, Helvetica, sans-serif; 
     margin-top:0px;  
     margin-bottom:10px}
a.tbox:hover,a.tbox:link,a.tbox:visited,a.tbox:active {
     color: #ffffff; 
     text-decoration: underline}
ul.tbox,ol.tbox,li.tbox {
     color: #ffffff; 
     font-size: 14px; 
     font-family: Arial, Helvetica, sans-serif}
     
table.main {
     border-color: #669999;
     border-style: solid;
     }
.navigation { 
     font-family: Arial, Helvetica, sans-serif;
     font-size: 12px;
     color: #ffffff;
     background-color: #336666; 
     text-decoration: none}
b.navigation {
     font-size: 15px;
     color: #ffffff}
a.navigation:link,a.navigation:visited {
     font-weight: normal;
     color: #ffffff}
a.navigation:hover {
     color: #ffffff; 
     font-weight: bold}
a.navigation:active {
     font-weight: normal;
     color: #ffffff}
.banner {
     font-family: Arial, Helvetica, sans-serif;
     font-size: 15px;
     color: #ffffff;
     background-color: #669999; 
     text-decoration: none}
a.banner:link,a.banner:visited {
     color: #ffffff}
a.banner:hover {
     color: #ffffff;
     font-weight: bold}
a.banner:active {
     color: #ffffff}
.banner2 {
     font-family: Arial, Helvetica, sans-serif;
     font-size: 20px;
     background-color: #669999; 
     color: #ffffff;
     text-decoration: none}
a.banner2:link,a.banner2:visited {
     color: #ffffff}
a.banner2:hover {
     color: #ffffff;
     font-weight: bold}
a.banner2:active {
     color: #ffffff}
.banner3 {
     background-color: #669999; 
     font-family: Arial, Helvetica, sans-serif;
     font-size: 30px;
     color: #ffffff;
     text-decoration: none}
p.banner3 {
     color: #ffffff}
a.banner3:link,a.banner3:visited {
     color: #ffffff}
a.banner3:hover {
     color: #ffffff; 
     font-weight: bold}
a.banner3:active {
     color: #ffffff}
.footer {
     font-family: Arial, Helvetica, sans-serif; 
     font-size: 12px; 
     background-color: #669999; 
     color: #ffffff}
a.footer:link,a.footer:visited {
     color: #ffffff}
a.footer:hover {
     color: #ffffff;
     font-weight: bold}
a.footer:active {
     color: #ffffff}

/* Overskrift which seems to be used in Bulletin only */

.overskrift {
     font-family: Arial, Helvetica, sans-serif; 
     font-size: 14px; 
     color: #336666}
a.overskrift:active,a.overskrift:hover,a.overskrift:link,a.overskrift:visited {
     color: #336666}

.strongtext {
     color: #336666; 
     font-weight: bold; 
     font-size: 14px; 
     font-family: Arial, Helvetica, sans-serif}

/* Old styles which will be deleted when they are not used any more */

.header1 {
	color: #336666; 
     background-color: #ebebeb; 
	font-size: 21px; 
	font-family: Arial, Helvetica, sans-serif; 
	font-weight: bold}
.header2 {
	color: #336666; 
     background-color: #ebebeb; 
	font-size: 17px; 
	font-family: Arial, Helvetica, 
	sans-serif; font-weight: bold}
.header3 {
	color: #336666; 
     background-color: #ebebeb; 
	font-size: 14px; 
	font-family: Arial, Helvetica, 
	sans-serif; font-weight: bold}
.header4 {
	color: #ffffff; 
     background-color: #ebebeb; 
	font-size: 23px; 
	font-family: Arial, Helvetica, 
	sans-serif; font-weight: bold}
.headercontent {
	color: #336666; 
     background-color: #ebebeb; 
	font-size: 13px; 
	font-family: Arial, Helvetica, sans-serif}
.imagetext {
	color: #000000; 
     background-color: #ebebeb; 
	font-size: 12px; 
	font-family: Arial, Helvetica, sans-serif}
.plain {
     color: #000000;
     background-color: #ebebeb;
     text-decoration: none}
	       
</style>
<?

  }
  

  echo"</HEAD>\n";
#	if(file_exists("./" . "css.readme")){
#		readfile("./" . "css.readme");
#	}
#	print("<body>");
}


/******************************************************************
  
   Start a table.  Put the top pictures in it.
 
  +---------------------------------------------------------------+
  | +-------------+---------------------------------------------+ |
  | | ul_picture  | ur_picture                                  | |
  | +-------------+---------------------------------------------+ |
  | | ml_picture  | mr_picture                                  | |
  | |             |                                             | |
  | +-------------+---------------------------------------------+ |
  +-                                                             -+
 
 ******************************************************************/
function make_page_top(){

	/*
	 *   First read a file describing the decoration on the page
	 */
	
  	global $menu_line;
	
#  	global $ll_picture_;
#  	global $ll_alt_;
#  	global $ll_link_;

 global $upperleft_html;
 global $upperright_html;
 global $middleleft_html;
 global $middleright_html;
 global $homepagetitle;	
 global $HTTP_SERVER_VARS;
 
	


#	if (file_exists("./def.decorations")){
#		$fd = fopen("./def.decorations", "r");
#		while(!feof($fd)){
#			$buffer = fgets($fd, 8192);
#			parse_str($buffer);
#		}
#		fclose($fd);
#	}
	
	 
#	$border = trim($border);
#	$cellPadding = trim($cellPadding);
#	$cellSpacing = trim($cellSpacing);
#	$bordercolor=trim($bordercolor);
#
#	$ul_link = trim($ul_link);
#	$ur_link = trim($ur_link);
#	$ml_link = trim($ml_link);
#	$mr_link = trim($mr_link);	
#
#	
#$menu_line = trim($menu_line1);
#	$ll_picture_ = trim($ll_picture);
#	$ll_alt_ =  trim($ll_alt);
#	$ll_link_ = trim($ll_link);
	
	/*
	 *
	 * Start by creating the table
	 *
	 */

 $browser = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
  
  eregi("\((.*)\)",$browser, $v);
  echo"<body>";
  // Detect if Netscape 4 is present or not.
  $a = split(";", $v[1]);
  $compatible = (trim($a[0]) == "compatible");
  $mozilla4 = (trim(substr($browser,0,9))=="Mozilla/4");
  
  if ($compatible==1 || $mozilla4!=1){
	  echo("<table width=753 cellpadding=0 cellspacing=0 class=\"border\"> \n");
  }else{
	  echo("<table border=\"1\" width=753 cellpadding=0 cellspacing=0 class=\"border\"> \n");
  }
  echo(" <tr><td valign=bottom align=center width=751>\n");
  echo("  <table align=\"center\" class=\"border\" valign=\"top\" width=\"751\" border=0 cellspacing=0 cellpadding=0> \n");
/* Put the upper left image */
  echo ("<tr><td  valign=top width=151 height=20 class=\"upper\">");
  if ( isset( $upperleft_html ) ) {
    echo $upperleft_html;
  } else {
    echo "<img height=20 src=\"/images/billede_tv.gif\" \n";
    echo "     width=151 border=0 alt=\"design element\">";
  }
  echo("</td>\n");

/*Put the upper right image*/
  echo ("<td width=600 valign=top height=20 class=\"upper\" align=left>");
  if ( isset( $upperright_html ) ) {
    echo $upperright_html;
  } else {
    echo "<a href=\"http://www.dtu.dk/index_e.htm\"><img height=20 \n";
    echo "   alt=\"DANMARKS TEKNISKE UNIVERSITET\" \n";
    echo "   src=\"/images/dtulogo_e_new.gif\" \n";
    echo "   height=20 width=600 border=0></a>";
  }
  echo("</td></tr>\n"); // !!!

/*  Start the second row  */
  echo("<tr>");
  echo(" <td valign=\"top\" height=72 width=151 class=\"upper\">");
  if ( isset( $middleleft_html ) ) {
    echo $middleleft_html;
  } else {
    echo "<img src=\"/images/lille_bygning.gif\" \n";
    echo "     width=151 height=72 alt=\"Small building\">";
  }
  echo("</td>\n");


  if ( isset( $middleright_html ) ) {
    echo("<td width=600 valign=\"top\" height=72 class=\"upper\">");
    echo $middleright_html;
  } else {
	   echo("<td width=600 align=\"left\" valign=\"bottom\" height=72 class=\"upper\">");
  		echo("<h1>&nbsp;&nbsp;<font color=\"white\">$homepagetitle</font></h1><br>\n");

    //echo "<a href=\"http://www.oersted.dtu.dk/\"><img height=72 src=\"/images/orsted-dtu.gif\" \n";
    //echo "     width=600 border=0 alt=\"Oersted DTU\"></a>";
  }
		  
  echo("</td></tr>\n");
	
	/* Start the third row */
	echo("<tr>\n");

}







/*******************************************************************************
   make_page_bottom()
     
  Finish the page. Place the lower-left picture. Create a horizontal bottom menu,
  or fill in botom text. Close all tables.
  
  +--                                                           --+
  | +-------------+---------------------------------------------+ |
  | | ll_picture  |  def.bottom_menu  OR  def.bottom_text       | |
  | +-------------+---------------------------------------------+ |
  +---------------------------------------------------------------+
 *******************************************************************************/
function make_page_bottom()
{
	
#	global $ll_picture_;
#	global $ll_alt_;
#	global $ll_link_;

	global $last_modified;
	global $lowerleft_html;
	global $bottom_menu_counter;
	global $show_date;
	global $bottom_text;
	global $web_address;
	
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
  }else if(strlen(trim($bottom_text))>0){
  	echo("<td width=600 class=\"banner\" >");
	echo($bottom_text);
  }else {
	  echo ("<td width=600 class=\"banner\" >");
	  echo("Page automatically created by HPaGe (Home Page Generator).<br>\n");
	  echo("&copy; 2002, Svetoslav Nikolov \n");
	  echo("&lt;<a class=\"banner\" \n");
	  echo("       href=\"mailto:sn@oersted.dtu.dk\">sn@oersted.dtu.dk</a>&gt;\n");
  }	
  echo("   </td></tr>\n");
  echo("  </table>");
  echo(" </td></tr>\n");
  echo("</table>\n<br>");
//  $last_modified = time($last_modified);
	if ($show_date){
	  $last_date = date("d-M-Y (D)",$last_modified);
	  echo("Last updated: $last_date\n");
	}

	global $PHP_SELF;
	global $HTTP_ENV_VARS;
	global $HTTP_SERVER_VARS;
	
	echo("</body>\n");
}







/*******************************************************************************
  create_link_text($level,$items, $file)
      Create  a text for a hyper link. 
		$items - This is the directory name broken into segments:
  		         F.x.  /home/sn/items is:
					     $items[0]='home',  $items[1]='sn', $items[2]='items'
			      This is the item selected from a link. 
					File is the name of a file from the current directory
					(at the given level). 
					
     $level - In which level of a subdirectory we are.
	  
	  $file - Current name in the directory which is currently being listed.
 *******************************************************************************/
function create_link_text($level,$items, $file)
{
	if ($items[0]=="Home"){
		print("This is home");
	}
	
	$name = $items[0];
	$level_txt = "";
	$i = 1;



	while ($i <= $level){
		$name = $name . "/" . $items[$i];
		$level_txt = $level_txt . "&nbsp;&nbsp;";
		$i++;
	}

	$name = $name . "/" . $file;
	if (file_exists($name . "/index.php"))
		return "<a  class=\"navigation\" href=\"$name/index.php\">" . $level_txt . $file . " </a>";
	else
		return "<a  class=\"navigation\" href=\"index.php?item=$name\">" . $level_txt . $file . " </a>";
}
 


/*******************************************************************************
  start_menu()

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
 
function start_menu()
{
  global $menu_line;
  global $top_menu_counter;
  global $home_text;
  
  echo("<td valign=\"top\" width=\"151\" class=\"leftmenu\">");
  echo("<table align=\"center\" width=\"95%\" class=\"leftmenu\">\n");
  echo("<tr><td class=\"leftmenu\">");
  $line_width=0.9*140;
  echo("<table align=\"center\" width=\"100%\">\n");
  echo("<tr>\n");
  //if (!file_exists("def.top_menu")){
  
  if ($top_menu_counter == 0){
    echo("<td class=\"leftmenu\">");
	 echo("<br>");
    echo("<a  class=\"navigation\" href=\"index.php?item=.\"> $home_text </a>\n");
    echo("<img src=\"$menu_line\" width=\"$line_width\" height=1>");
    echo("</td>");
  }
}



/*******************************************************************************
	end_menu()
	   Finish a side menu. This function is complementary to start_menu()
		
   |
	|| |  menu_item     | |           Here is the displayed content fom
	|| |  ----------    | |           main.html
	|| +----------------+ |
	|+--------------------+
	
 *******************************************************************************/
function end_menu()
{
	echo("</table>\n");
	echo("</td></tr>\n");
	echo("</table>\n");
	echo("<br><p>");
	echo("</td>");
	
}
 
 
 
 
 
/*******************************************************************************
	make_menu_dirs()
	   Fill in the contents of a menu from the directory structure.
		
 *******************************************************************************/
function make_menu_dirs($level, $item){
	
global $menu_line;
	$top_dir_handle = opendir('.');
	
	$no_submenus = 0;
		
	if (strlen(trim($item))==0){
		$item = ".";
	}
	
	$items=explode("/", $item);
	$no_levels = sizeof($items);
	$line_width=0.9*140;
	
	while(false != ($file=readdir($top_dir_handle))){
		if (is_dir($file) && $file[0]!="."){
			$no_submenus = $no_submenus + 1;
			print("<tr>\n"); print("<td class=\"leftmenu\">");
			print(create_link_text($level, $items, $file));
			if ($level==0){
				print("<img src=\"$menu_line\" width=\"$line_width\" height=1>");  // Put \n for larger space
			}
			print("</td>");
			print("</tr>\n");
			if ($level<$no_levels){
				if ($file==$items[$level+1]){
					chdir($file);
					make_menu_dirs($level+1, $item);   // Recursively call itself- make sub-menus.
					chdir("..");
				}
			}
		}
	}
	if ($level>0 && $no_submenus>0){
		print("<tr>\n"); print("<td class=\"leftmenu\">");
		print("<img src=\"$menu_line\" width=\"$line_width\" height=1>");  // Put \n for larger space
		print("</td></tr>\n");
	}
	closedir($top_dir_handle);
}



/*******************************************************************************
 *******************************************************************************/
function menu_subpath($menu_path, $level)
{
	$subpath = "";

	$path_parts = split("/",$menu_path);
	$no_parts = count($path_parts);
	
	for($ii = 0; $ii<=$level && $ii<=$path_parts; $ii++ ){
		$subpath .= $path_parts[$ii] . "/" ;
	}
	return $subpath;
} 



/*******************************************************************************
  make_menu_file()
  		Create the submenu from the contents of the file "def.menu"
		
 *******************************************************************************/
function make_menu_file()
{
  global $menu_line;
  global $leftmenu_html;
  global $left_menu_counter;
  global $left_menu_name;
  global $left_menu_link;
  global $left_menu_topic;
  global $level;
  global $menu_path;
  global $left_menu_level;
  global $left_menu_path;
	
	start_menu();
	$line_width=10*0.9;
	
	$expanded = 0;
	$ii = 0;
	
	while ($ii < $left_menu_counter){
		if (($left_menu_level[$ii] == 0) ||
		    ($left_menu_level[$ii] <= $level && menu_subpath($left_menu_path[$ii],$left_menu_level[$ii]-1)==menu_subpath($menu_path,$left_menu_level[$ii]-1))
		    ||($left_menu_level[$ii]==$level+1 && menu_subpath($left_menu_path[$ii],$left_menu_level[$ii]-1)==menu_subpath($menu_path,$left_menu_level[$ii]-1))){
			
			if ((strlen(trim($left_menu_topic[$ii]))>0) && (strlen($left_menu_link)>0)){
				$left_menu_link[$ii]=$left_menu_link[$ii] . '&' . 'topic=' . $left_menu_topic[$ii];
			}
			
			if ($left_menu_level[$ii] > 0){
				 $expanded = 1;
			}else{
				if ($expanded == 1){
					$expanded=0;
					print("<tr>\n"); print("<td class=\"leftmenu\">\n");
					print("<img src=\"$menu_line\" width=\"$line_width\" height=1>"); 
					print("</td>");
  			   	print("</tr>\n");
				}
			}
			if (strlen($left_menu_link[$ii])>0 && substr($left_menu_link[$ii],0,7)!= 'http://' && substr($left_menu_link[$ii],0,7)!= 'mailto:' ){
				$left_menu_link[$ii].= '&' . 'level=' . $left_menu_level[$ii] . '&' . 'menu_path=' . $left_menu_path[$ii];
			}
			print("<tr>\n"); print("<td class=\"leftmenu\">\n");
			if (strlen(trim($left_menu_name[$ii]))>0){
				if(strlen($left_menu_link[$ii])>0){
					print("<a class=\"navigation\" href=\"$left_menu_link[$ii]\">" . $left_menu_name[$ii] . "</a><br>");
					if ($left_menu_level[$ii]==0){
						print("<img src=\"$menu_line\" width=\"$line_width\" height=1>");  // Put \n for larger space
					}
				}else{
					print("<font color=\"white\">" . $left_menu_name[$ii] . "</font><br>");
					print("<img src=\"$menu_line\" width=\"$line_width\" height=1>");  // Put \n for larger space
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
	//global $top_menu_content;
	global $top_menu_topic;
	
	global $bottom_menu_link;
	global $bottom_menu_name;
	
	global $bottom_menu_counter;
	//global $bottom_menu_content;
	global $bottom_menu_topic;
	global $home_text;
	
  if ( $name == "top" ) {
    $menu_counter=$top_menu_counter;
    $menu_name=$top_menu_name;
    $menu_link=$top_menu_link;
    $menu_content=$top_menu_topic;
  } else {
    $menu_counter=$bottom_menu_counter;
    $menu_name=$bottom_menu_name;
    $menu_link=$bottom_menu_link;
    $menu_content=$bottom_menu_topic;
  }

  if ($new_row != 0){
  		$menu_height = 35;
		$menu_class = '"topmenu"';
		echo("<td border=\"0\" valign=\"top\" width=\"151\" height=\"$menu_height\">\n");
		echo(" <table border=\"0\" cellpadding=0 cellspacing=0>\n");
			echo(" <tr><td valign=\"center\" class=\"topmenu\" width=150 height=34>");
			echo("<a  class=\"navigation\" href=\"index.php?item=.\">&nbsp;&nbsp;&nbsp;&nbsp;$home_text</a><br>");
			echo("</td></tr>\n");
		echo(" </table>");
		echo("</td>");
	}else{
		$menu_height = 72;
		$menu_class = '"bottommenu"';
	}
	
	echo("<td width=\"600\" height=$menu_height class=\"upper\" align=\"center\" valign=\"top\">\n");
	echo(" <table border=\"0\" height=$menu_height valign=\"center\" align=\"center\" cellpadding=0 cellspacing=0>\n");
	echo("  <tr valign = \"center\" height=\"100%\">\n");
#	$fd = fopen($name, "r");
#	while(!feof($fd)){
#		$buffer = fgets($fd, 8192);
#		parse_str($buffer);
#	}
#	
#	fclose($fd);
	
#	$no_menu_items = sizeof($menu_name);
	$col_width=600/($menu_counter);
	$ii = 0;
	while ($ii < $menu_counter){
		if (strlen(trim($menu_content[$ii]))>0){
			$menu_link[$ii]=$menu_link[$ii] . '&amp;' . 'topic=' . $menu_content[$ii];
		}

		print("<td height=100% class=$menu_class width=\"$col_width\" align=\"center\" valign=\"center\">");
		print("<a class=\"navigation\" href=\"$menu_link[$ii]\">" . $menu_name[$ii] . "</a>");
		$ii++;
		print("</td>\n");
	}
	echo("  </tr>\n");
	echo(" </table>\n");
	
	echo("</td>\n");
	echo("</tr>\n");

}







/*******************************************************************************
	create_menus($item)
	
	Create the top_menu.
	Create the side_menu. The side menu is created either from a file "def.menu"
	or from the directory structure.
			
 *******************************************************************************/
function create_menus($item, $topic="")
{
  global $top_menu_counter; 
  global $left_menu_counter; 
  global $menu_line;
  global $SCRIPT_NAME;
  
  $line_width = 140*0.9;
  
	if ($top_menu_counter > 0){
		make_horiz_menu("top", 1);
	}
	
#  if (file_exists("def.menu")){

#echo "\n<!-- left=".$left_menu_counter." -->\n";

  if ($left_menu_counter>0) {
		make_menu_file();    // Create a menu from a definition file
	}else{
		start_menu();
		make_menu_dirs(0, $item);
	}
	
	
	print("<tr>\n"); print("<td class=\"menu\">");
	print("<br>");
	print("</td></tr>")	;
	print("<tr>\n"); print("<td class=\"menu\">");
	print("<a class=\"navigation\" href=\"$web_address$SCRIPT_NAME?item=$item&topic=$topic&action=print\">Print</a><br>");
	print("<img src=\"$menu_line\" width=\"$line_width\" height=1>");  // Put \n for larger space
	print("</td>");
	print("</tr>\n");

	end_menu();
}






/********************************************************************************
	display_fifo()
  If the current directory contains a FIFO of files display them.
  Definition found in "def.fifo"
  The files are organized as a circular buffer. 
 ********************************************************************************/
function display_fifo()
{
	/*
	 * Open the file, which describes how many items there are in a FIFO
	 * and what is the base file name.
	 */
	$fd = fopen("./def.fifo","r");
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
	
	if (strlen(trim($page_description))>0){
		if (file_exists(trim($page_description))){
			readfile(trim($page_description));
		}
	}
	
	/*
	 *  Set some initial conditions
	 */
	$current = $tail;
	$base_name = trim($base_name) . ".";
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
                    
			echo "<table valign=\"top\" align = \"center\" class=\"contents\" width = 80%>\n";
			echo "<tr width = \"100%\"><td>\n";
				
				/* Display the title of the item */
				echo "<table width=550 border=\"0\">\n";
				echo "<tr>"; echo "<td>";

				echo "<table width = 549 class=\"border\"> ";
				echo("<tr class=\"border\">");
				echo "<td width = 20 class=\"border\">";
				echo "<b>$number </b></font>|";
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
			echo "<tr> <td width = 90%>\n";
				echo "<table width = 549 class=\"border\">";
					echo "<tr><td class=\"printer\">";
          		readfile(trim($news_text));
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
            echo "</tt></pre>";
			}
		}
	}
}


/********************************************************************************
	Display the contents of the page:
	
	1. Check if $item/$topic.html or $item/$topic.htm exists. If so display.
	2. Else check if $item/main.html exists. If so display.
	3. Else if $item/def.fifo exists display, else show nothing.
	
	          Here stay top_menu, or pictures on the top of the page
	         --+-------------------------------------------------------+
    Here is   | +--------------------------------------------------+  |
	 the       | |  Read file main.html in this table               |  |
	 menu 	  | |                                                  |  |
	           | +--------------------------------------------------+  |
				--+-------------------------------------------------------+
				
 ********************************************************************************/
function display_contents($item, $topic, $action="")
{
	global $PHP_SELF;
	global $HTTP_ENV_VARS;
	global $HTTP_SERVER_VARS;
   global $last_modified;
	
	if (strlen(trim($item))==0){
		$item = ".";
	}

	if($action=='print'){
		echo("<td valign=\"top\" class=\"printer\">\n");
	}else{
		echo("<td valign=\"top\" class=\"contents\">\n");
	}
	
	echo("<table width=92% align=\"center\" >\n");
	echo("<tr valign=\"top\">\n");
	
	if($action=='print'){
		echo("<td width=91% class=\"printer\">\n");
	}else{
		echo("<td  width=91% class=\"contents\">\n");
	}
	
	
	$cur_dir = realpath("./");
	chdir($item);
	
	if (strlen(trim($topic))>0){
	   if (file_exists($topic)){
			readfile($topic);
			$last_modified = filemtime($topic);

		}else if (file_exists($topic . ".html")){
			readfile($topic . ".html");
			$last_modified = filemtime($topic . ".html");

		}else if(file_exists($topic . ".htm")){
			readfile($topic . ".htm");
			$last_modified = filemtime($topic . ".htm");		
		}else if(file_exists($topic . ".txt")){
			read_text_file($topic . ".txt");
			$last_modified = filemtime($topic . ".txt");		
		}
	}else{
		if(file_exists("./main.html")){
			readfile("./main.html");
			$last_modified = filemtime("./main.html");
		}else if(file_exists("./main.htm")){
			readfile("./main.htm");
			$last_modified = filemtime("./main.htm");		
		}else if(file_exists("./main.txt")){
			read_text_file("./main.txt");
			$last_modified = filemtime("./main.txt");		
					
		}else if(file_exists("./def.fifo")){
			$last_modified = filemtime("./def.fifo");
			display_fifo();
		}
	}
	chdir($cur_dir);

	echo("</td>\n");
	echo("</tr>\n");
	echo("</table>\n");
	echo("</td>\n");
}


/*********************************************************************
  FUNCTION : set_title
  ABSTRACT : Sets a global title for the whole web site. 
             If this function is NOT used, then each page should
				 provide its own title.
 *********************************************************************/

function set_title($title)
{
  global $homepagetitle;
  global $use_title;
  
  $homepagetitle=$title;
  $use_title = 1;
}


/*********************************************************************
	Sets the case of whether to substitute or not special symbols
 *********************************************************************/
function set_substitute($choice)
{
	global $substitute;
	$substitute = ('true' == $choice);
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
function add_left_submenu($level, $name, $place, $content="")
{
	global $left_menu_link;
	global $left_menu_topic;
	global $left_menu_name;
	global $left_menu_path;
	global $left_menu_counter;
	global $left_menu_level;
	
   global $HTTP_SERVER_VARS;
   global $PHP_SELF;
	
	
  $name = sub_sym($name);
  if (strlen(trim($content))==0 && strlen($place)!=0){
		if(substr($place,0,7)!='http://'){
			if (file_exists("./" . $place . "/index.php")){
				$path_parts = pathinfo($PHP_SELF);
				$web_addr = $HTTP_SERVER_VARS['HTTP_HOST'] . $path_parts['dirname'];
				$place = "http://" . $web_addr . "/" . $place . "/index.php?item=.";
			}
		}
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
	 
	 while($left_menu_level[$parent]>=$level && ($parent>-1)){
	 	$parent --;
		if ($left_menu_level[$parent]==$level){
			$no_sub_items ++;
		}
	 }
	 
	 $menu_path = $left_menu_path[$parent];
	 $menu_path = $menu_path . $no_sub_items . "/";
	 $name = substr("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",0,$level*18) . $name;
    
	 $left_menu_name[$left_menu_counter]= $name;
	 if (substr($place,0,7)=='http://' || substr($place,0,7)== 'mailto:' || strlen($place)==0){
	    $left_menu_link[$left_menu_counter]=$place;
	 }else{
	    $left_menu_link[$left_menu_counter]=$SCRIPT_NAME ."?item=" . $place;
	 }
 
    $left_menu_topic[$left_menu_counter]=$content;
	 $left_menu_path[$left_menu_counter]= $menu_path; //$left_menu_counter . "";
	 $left_menu_level[$left_menu_counter]= $level;
	 
    $left_menu_counter++;
	
}


/*********************************************************************
  FUNCTION: add_menu
  INPUTS: $position - Which menu to add to? - "left", "top", "bottom"
          $name  - Name to appear on the screen
			 $place - Directory name. If it starts with "http://", then
			          this is an absolute location
			 $content - This is a name of a file in $place.
 *********************************************************************/
function add_menu($position, $name, $place, $content="")
{

  $xmenu_html=$position."_menu_html";
  global $topmenu_html;
  global $leftmenu_html;
  global $bottom_menu_html;
  global $top_menu_counter;
  global $left_menu_counter;
  global $bottom_menu_counter;
  global $top_menu_name;
  global $top_menu_link;
  global $top_menu_topic;
  
  global $left_menu_name;
  global $left_menu_link;
  global $left_menu_topic;
  global $left_menu_path;
  global $left_menu_level;
  
  global $bottom_menu_name;
  global $bottom_menu_link;
  global $bottom_menu_topic;
  global $HTTP_SERVER_VARS;
  global $PHP_SELF;
  
  $name = sub_sym($name);
  if (strlen(trim($content))==0 && strlen($place)!=0){
		if(substr($place,0,7)!='http://'){
			if (file_exists("./" . $place . "/index.php")){
				$path_parts = pathinfo($PHP_SELF);
				$web_addr = $HTTP_SERVER_VARS['HTTP_HOST'] . $path_parts['dirname'];
				$place = "http://" . $web_addr . "/" . $place . "/index.php?item=.";
			}
		}
  }
  
  if ( $position=="top" ) {
    $top_menu_name[$top_menu_counter]=$name;
	 if (substr($place,0,7)=='http://' || substr($place,0,7)== 'mailto:'){
	    $top_menu_link[$top_menu_counter]=$place;
	 }else{
	    $top_menu_link[$top_menu_counter]=$SCRIPT_NAME ."?item=" . $place;
	 }
    $top_menu_topic[$top_menu_counter]=$content;
    $top_menu_counter++;

  } elseif ( $position=="left" ) {
    $left_menu_name[$left_menu_counter]=$name;
	 if (substr($place,0,7)=='http://' || substr($place,0,7)== 'mailto:' || strlen(trim($place))==0){
	    $left_menu_link[$left_menu_counter]=$place;
	 }else{
	    $left_menu_link[$left_menu_counter]=$SCRIPT_NAME ."?item=" . $place;
	 }
    $left_menu_topic[$left_menu_counter]=$content;
	 $left_menu_path[$left_menu_counter]= $left_menu_counter . "/";
	 $left_menu_level[$left_menu_counter]= 0;
    $left_menu_counter++;
  } elseif ( $position=="bottom" ) {
    $bottom_menu_name[$bottom_menu_counter]=$name;
	 if (substr($place,0,7)=='http://' || substr($place,0,7)== 'mailto:'){
	    $bottom_menu_link[$bottom_menu_counter]=$place;
	 }else{
	    $bottom_menu_link[$bottom_menu_counter]=$SCRIPT_NAME ."?item=" . $place;
	 }
    $bottom_menu_topic[$bottom_menu_counter]=$content;
    $bottom_menu_counter++;
  }
}


/*********************************************************************
 *********************************************************************/
 
function add_bottom_text($text)
{
	global $bottom_text;
	$bottom_text = $text;
}

/**********************************************************************
  FUNCTION: add_image - Add a image to a given position. 
  INPUTS:  $position upperleft, upperright, middleleft, middleright,
                     lowerleft, lowerright, menu_line
				$imgdesc - Description
				$imglink - Link
				$imgalt - Alternative text			
 **********************************************************************/
function add_image($position, $imgfile, $imgdesc="defaultdesc", $imglink="", $imgalt="defaultalt") 
{
  $ximage_html=$position."_html";
  global $$ximage_html;
  global $SCRIPT_URI_PATH;
  global $HOME_URL_PATH;
  
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
  		
  if ( $imgdesc == "defaultdesc" ) $imgdesc = "Image: ".basename($imgfile);

  if ( $imgalt=="defaultalt" ) $imgalt = $imgdesc;

  if ( ! $imglink == "" ) $$ximage_html="<a href=\"".$imglink."\">";
  else $$ximage_html="";
  
  $$ximage_html.="<img border=0 src=\"".$imgfile."\" desc=\"".$imgdesc."\"\n";
  $$ximage_html.="     alt=\"".$imgalt."\">";
  if ( ! $imglink == "" ) $$ximage_html.="</a>";
  echo "<!-- add_image(".$ximage_html.") -->\n";
}



function set_style($style)
{
 global $selected_style;
 $selected_style=$style;
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
  FUNCTION  set_home_text
  ABSTRACT  Changes the text which is shown the "Home" item.
				
 *********************************************************************/
function set_home_text($text)
{
	global $home_text;
	$home_text = $text;
}




/*********************************************************************
  FUNCTION: print_content
  ABSTRACT: Printer friendly format. Only the contents. 
            No other tricks.
 *********************************************************************/
function print_contents($item, $topic)
{
	global $PHP_SELF;
	global $HTTP_ENV_VARS;
	global $HTTP_SERVER_VARS;
	
	if (strlen(trim($item))==0){
		$item = ".";
	}

	$web_addr = "http://" . $HTTP_SERVER_VARS['HTTP_HOST'];
	$web_addr .= $PHP_SELF;
	$web_addr .= "?item=" . $item . "&topic=" . $topic;
	
	echo("<table width=640 align=\"left\" class=\"border\" >\n");
	echo("<tr  align=\"center\">\n");
	echo("<td width=91% align=\"left\" class=\"contents\">");
	echo("&nbsp;&nbsp; <a class=\"contents\" href=\"$web_addr\">$web_addr</a>");
	echo("</td></tr>");
	display_contents($item, $topic,'print');	
	echo("</tr>\n");
	echo("<tr  align=\"center\">\n");
	echo("<td width=91% align=\"left\" class=\"contents\">");
	echo("&nbsp;&nbsp; <a class=\"contents\" href=\"$web_addr\"> [ Back ] </a>");
	echo("&nbsp;&nbsp; <a class=\"contents\" href=\"javascript:printPage()\"> [ Print ] </a>");
	echo("</td>");
	echo("</tr>\n");	
	echo("</table>\n");
	echo("</body>\n");
	echo("<script language=\"JavaScript\">javascript:printPage()</script>\n");
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>

<?
#require_once("./functions.php");

/*******************************************************************************
 *
 *  This is the entry point into the PHP main program;
 *
 *  The  program gets two parameters: 'item' and 'topic'.
 *
 *  'item' is essentially a directory name. 
 *
 *  'topic' is a name of a file in that directory. If there is no
 *          topic, then a file with a name "main.html" from that directory 
 *          is displayed. There are certain rules as what is shown:
 *          
 *         if there is no topic, then the directory is checked for a file
 *         'def.fifo', which displayes a series of files. If there i no
          'def.fifo' file, then the directory is searched for a file 
 *          with a name "main.html"
 *******************************************************************************/


$path_parts = pathinfo($HTTP_SERVER_VARS['SCRIPT_FILENAME']);
$SCRIPT_PATH     = $path_parts['dirname'];

$path_parts = pathinfo($HTTP_SERVER_VARS['SCRIPT_URI']);
$SCRIPT_URI_PATH = $path_parts['dirname'];

if ($path_parts['basename']!='index.php'){
	$SCRIPT_URI_PATH .= "/" . $path_parts['basename'];
}


$HOME_URL_PATH = "/";
$path_parts = split("/",$SCRIPT_URI_PATH);

$ii = 0;
foreach($path_parts as $path_part){
	if ($path_part == 'personal'){
		$HOME_URL_PATH .= 'personal/' . $path_parts[$ii+1] . "/";
		break;
	}
	$ii ++;
}


if (file_exists("web.config")) {
  include "web.config";
} else {
  echo "\n\n<!-- <br><b>You need a \"web.config\" file to make it work properly!</b></br> -->\n\n";
}

if (file_exists("./" . $item ."/web.addconfig")) {
  include "./$item/web.addconfig";
}

print_header();         // Read the CSS.README file. Define the colors

if (count($HTTP_GET_VARS)>0){
	$action = $HTTP_GET_VARS['action'];    // Get the directory name
}else{
	$action="";
}


/*
if (count($HTTP_GET_VARS)>0){
	$item = $HTTP_GET_VARS['item'];    // Get the directory name
}


if (count($HTTP_GET_VARS)>1){
	$topic = $HTTP_GET_VARS['topic'];   // Get the file name
}

if (count($HTTP_GET_VARS)>2){
	$level = $HTTP_GET_VARS['level'];   // Get the file name
}

*/

parse_str($HTTP_GET_VARS);

if ($action=="print"){
	print_contents($item, $topic);
}else{
	make_page_top();        // Display the pictures on the top of the page:
	create_menus($item, $topic);                   // Create top and side menus
	display_contents($item, $topic);       // Read the main.html or whatever file.
	
	make_page_bottom();                    // Put the pictures on the bottom.
                                       // Create bottom menu, or bottom text
};
?>

