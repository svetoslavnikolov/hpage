	ITC proudly presents the ITC Typeface Library of classic and
innovative type designs in PostScript Type 1 and TrueType formats featuring
the FONTEK type library.  We are also proud to present our new DesignFonts
line, collections of spot illustrations in font format. These high-quality
fonts can be used with any Windows or DOS application and can be printed on
any PostScript printer, TrueType printer, or high-resolution imagesetter.

	These typefaces have been carefully prepared by ITC to meet the
highest technical and creative standards, thus ensuring that the integrity
of the original type design is maintained both on-screen and on the printed
page. Our Type 1 fonts are hinted and fully compatible with Adobe Type
Manager (ATM), and our TrueType fonts have been constructed and hinted in
accordance with Windows TrueType format. Each ITC typeface contains an
extensive, character set, which may include non-standard characters such as
ligatures and alternates. In addition, the character metrics and extensive
kerning pairs for each font have been carefully constructed to allow for
precise character spacing.

	ITC is in the vanguard of type innovation. In our quest to meet
the ongoing need for new typestyles and to successfully anticipate the
changing trends in type usage, new typefaces are added to our library each
year. These fonts are produced by ITC's Type Studio in conjunction with
internationally-acclaimed designers. As new typefaces are developed, they
will be added to the FONTEK range, creating an indispensable resource for
graphic designers.

	This User's Guide explains how to install the PostScript and
TrueType versions of your font. It assumes that you are familiar with basic
DOS or Windows operations, such as clicking and dragging, using menus, and
moving icons. If you are unfamiliar with any of these operations, please
refer to your DOS or Windows User's Guide.

Windows Installation

Diskette Contents

	On your disk you will find both PostScript Type 1 and
TrueType versions of the font. It is only necessary to install one version.
We recommend that you install the PostScript Type 1 font if you primarily
use Adobe Type Manager software and print to a PostScript device. If you
primarily use TrueType fonts, we recommend that you install the TrueType font.

LIC_AGMT.TXT (ITC License Agreement). 
This text file contains specific information regarding your use of this
product. 

README.TXT (this User's Guide)

REGCARD.PDF 
This card has been written in Adobe Acrobat PDF format. (Should you not have
the Adobe Acrobat reader it can be downloaded for free from numerous sites 
including www.adobe.com.) Please return the registration card promptly so we
can provide you with proper technical support. 

NAME_ALP.TXT & NAME_NUM.TXT.
These text files coordinate the numbering of certain font files and their 
specific font names. Refer to the FONTEK FILE NAMING CONVENTIONS
section in this document for more specifics.

"stylename".pdf. (This file is contained on "DesignFont" products only)
This file will provide you with the keystrokes necessary to access any of the
characters contained in this font. The file has been written in Adobe Acrobat PDF
format. (Should you not have the Adobe Acrobat reader it can be downloaded for 
free from numerous sites including www.adobe.com.) 


Minimum Hardware and Software Requirements

TrueType:

Any PC running MS-DOS 3.1 (or later), Microsoft Windows 3.1 (or later), 
and Adobe Type Manager

At least 640K of memory

Any TrueType-compatible printer or other Windows-supported printer.

PostScript Type 1:

Any PC running MS-DOS 3.1 (or later), Microsoft Windows 3.0 (or later), 
and Adobe Type Manager

At least 640K of memory

Any PostScript Type 1-compatible printer, HP LaserJet Series II or
III, or other Windows-supported printer.

Before Installing

Before installing your ITC typefaces, we recommend that you:

1.	Check this README.TXT file for any last minute information concerning
        this typeface or its installation.

2.	Make sure you have the appropriate system software installed.

3.	Read the software licensing agreement that came with this typeface.

4.	Make backup copies of the disk.


	The ITC TrueType font uses the extension "TTF" in the filename
to distinguish it from PostScript versions of the same typeface and to make
TrueType font identification easier.

Installing TrueType Fonts - Windows 98, Windows 95:

1. Click "Start" on the Taskbar.

2. Select Settings, Control panel.

3. Double-click the Fonts folder.

4. Select "Install New Font" from the file menu.

5. Select the appropriate drive (typically A:) from the pop-up menu.

6. The font(s) to be loaded will appear in the "List of Fonts" dialog box. 
   Select the intended font to be installed.

7. Click "OK" or press Return. 

Installing TrueType Fonts - Windows 3.1:

1. From the Program Manager, double-click on the Main icon.

2. Double-click on the Control Panel icon.

3. Double-click on the Fonts icon.
   A list of installed TrueType fonts displays.

4. Click on the Add button.

5. From the drive list, select the drive containing the ITC typeface(s)
   you want to install. A list of available TrueType fonts is displayed.

6. Click on the font you want to install and then click on the OK
   button. The Windows Font Installer copies the outline font to the
   specified directories. 

7. Click on the Close button when installation is complete.


	To install PostScript outline fonts (also known as printer fonts)
for Windows applications, you will use Adobe Type Manager (ATM).
Adobe Type Manager automatically generates the type you see on screen
so you do not have to install separate screen fonts (also known as
bitmap fonts).

Note: If you want to install PostScript Type 1 fonts for Windows
applications and you do not have Adobe Type Manager, follow the
instructions for "Installing Outline Fonts" for DOS installations below.

Installing Outline Fonts

Installing PostScript Type 1 fonts:

1. From the Program Manager, double-click on the Main icon.

2. Double-click on the Adobe Type Manager Control Panel icon.
   A list of installed PostScript Type 1 fonts displays.

3. Click on the Add button.

4. Verify the target directories for the PostScript outline font and
   font metric files.

5. From the directories list, select the drive containing the ITC
   typeface(s) you want to install. A list of available PostScript Type
   1 fonts displays.

6. Click on the font you want to install and then click on the Add
   button. Adobe Type Manager copies the outline font to the
   specified directories.

7. Click on the Exit button and restart windows when installation is
   complete.

Using Outline Fonts

	Once TrueType fonts are installed, they are available automatically
for use in your Windows applications.

	Once PostScript fonts are installed, they may or may not be
available automatically, depending on the application you are using.
PostScript fonts will be available for Windows applications such as
Microsoft Word for Windows, Harvard Graphics, and Freelance Graphics. For
other applications, you will need to complete font installation from within
the application. For example:

	For Windows Ventura Publisher, Adobe Type Manager installs the new
fonts in Ventura's ENVIRON.WID file. If you use a custom width file, you
must merge the ENVIRON.WID file with your custom width file.

	For Windows Ventura Publisher, if you are using a PostScript printer
and a version of ATM earlier than 2.0, you must edit WIN.INI so that the
new typefaces will download automatically. See "Installing Printer Font
Information for Windows Ventura Publisher" below. 

	For application-specific instructions, you can also refer to the 
application's user documentation.

Note: A PC icon appears to the left of a font name in the font menu of any
application using PostScript Type 1 fonts; "TT" appears to the left of a
font name in the font menu of any application using TrueType fonts.

Installing Printer Font Information for Windows Ventura Publisher

	To manually enter the name and location of the printer fonts that
are not resident in your printer in WIN.INI:

1. Use NOTEPAD or another ASCII text editor to open the WIN.INI file
   found in the directory in which Windows is installed.

2. Locate the PostScript printer section of the WIN.INI file. For
   example, scroll to the section that looks similar to[PostScript,LPT1]
   (where LPT1 will be replaced by the printer port set for your printer).

3. Locate the lines that describe the new fonts and add the location of
   the .PFB file in the form: ,pathname\font.pfb. For example:

   softfont10=c:\psfonts\pfm\71849___.pfm,c:\psfonts\71849___.pfb

4. Exit from the editor and restart Windows.

Downloading Outline Fonts

	Windows automatically downloads fonts to your PostScript printer,
but you can choose to manually download fonts to speed printing. To
manually download outline fonts to either a parallel or serial printer for
Windows applications, you can use the Adobe PostScript Windows downloader,
Windown. The Windown utility runs within the Windows environment and
modifies the Windows system file (WIN.INI) to work with your printer fonts.
For more information on using Windown, refer to the Adobe Type Manager
User's Guide.

NOTICE TO TRUE TYPE USERS (Windows 3.1)

Due to the highly complex designs of some Fontek Fonts and DesignFonts, it
is recommended that an outline threshold value be inserted in the True Type
section of the WIN.INI file. This value may vary depending on your specific
equipment and software. For example, when outputting to a Hewlett Packard
LaserJet III a value of 64 is required for optimum results. 

To manually enter the outline threshold of TrueType fonts in WIN.INI:

1. Use NOTEPAD or another ASCII text editor to open the WIN.INI file
   found in the directory in which Windows is installed.

2. Locate the TrueType section of the WIN.INI file. For example, scroll
   to the section that looks like [TrueType].

3. Insert the following line in the form: outlinethreshold=64

4. Exit from the editor.

5. Restart Windows.

FONTEK FILE NAMING CONVENTIONS

To copy or delete font files, as well as to use the PCSEND downloader
program, you need to know how to identify fonts by filename.

Some of the filenames in the ITC PC Type Library consist of 11 characters:
five characters followed by three underscores, followed by a three-
character extension. The first five characters of the outline printer
font, True Type font, and font metrics files for a given typeface are
always the same; their extensions are different.

For example, the following filenames are font files for the Aquitaine
Initials typeface:

	71678___.PFB	Type 1 outline font
	71678___.TTF	True Type font
	71678___.AFM	Font Metric file
	71678___.PFM	Microsoft Windows metrics file
	71678___.INF	Font information file for DOS 

Refer to the file "NAME_ALP.TXT", if appropriate, that accompanied the
typeface to determine specific typeface names and their corresponding
PC filenames.


TECHNICAL SUPPORT
Should you need any assistance installing or using ITC typefaces, please 
e-mail our technical support staff at support@itcfonts.com or call 
203-380-9335.

CUSTOMER SERVICE
For more information about ITC products, please e-mail us at 
info@itcfonts.com or call ITC at 203-380-9335

For more information about the ITC typeface library, including the award-
winning Fontek display typeface collection, please check our Web site at
http://www.itcfonts.com.


ITC is a registered trademark of International Typeface Corporation. 
Fontek is a registered trademark and DesignFonts is a trademark of Agfa
Monotype Corporation.All other brand and product names are trademarks or
registered trademarks of their respective holders.

International Typeface Corporation
200 Ballardvale Steet
Wilmington, MA 01887
E-mail: info@itcfonts.com
www.itcfonts.com

RPT 5/2000

