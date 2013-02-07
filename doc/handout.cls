%% handout.cls - Version 1.0
%% LaTeX2e clas file to format handouts for DTU courses

\NeedsTeXFormat{LaTeX2e}
\ProvidesClass{handout}[2002/11/21 v1.0 DTU handout class]
\RequirePackage{palatino}

\RequirePackage{mathptm}
\RequirePackage{epsfig}
\RequirePackage{theorem}
\RequirePackage{float}
\RequirePackage{color}



%\RequirePackage{fancyhdr}\typeout{Using fancier footers than usual.}
\LoadClassWithOptions{article}  % build on standard article class


\DeclareOption{a4paper}{%
   \AtEndOfClass{%
       \textwidth 17cm
       \textheight 23.5cm
       \oddsidemargin -0.5cm
       \evensidemargin -0.5cm
       \topmargin -1cm
       \typeout{a4paper used}
       }
   }
\DeclareOption{a4}{%
   \AtEndOfClass{%
       \textwidth 17cm
       \textheight 23.5cm
       \oddsidemargin -1.5cm
       \evensidemargin -1.5cm
       \topmargin -1cm
       \typeout{a4paper used}
       }
   }

\DeclareOption{authorinfo}{%
   \def\showauthorinfo{1}
   \typeout{ }
   \typeout{No information about the author}
   \typeout{ }
   }

\ProcessOptions\relax      % set margins for a4paper if specified

\newcommand{\ha@authoraddress}{}
\newcommand{\address}[1]{\renewcommand{\ha@authoraddress}{#1}}

\newcommand{\ha@coursename}{}
\newcommand{\course}[1]{\renewcommand{\ha@coursename}{#1}}

%% Space for floats - figures
\renewcommand{\floatpagefraction}{0.9}  % Have a lot of space on page
\renewcommand{\bottomfraction}{0.5}     % Move large figures to the top
\renewcommand{\topfraction}{0.9}        % of the page
\renewcommand{\textfraction}{0.05}      % Allow even small amount of text



%
%  Set up fonts
%
\newlength{\RW}
\setlength{\RW}{1pt}
\newlength{\LogoW}
\setlength{\LogoW}{4.5cm}
\newlength{\TitleW}
\setlength{\TitleW}{12cm}

\newcommand{\ha@HeaderFamily}{\sffamily}
\def\mghrulefill#1{\leavevmode\leaders\hrule\@height #1\hfill\kern\z@}
\newcommand{\ha@doHorizontalRule}{\mghrulefill{\RW}}


\newcommand{\ha@handoutnumber}{}

\newcommand{\handoutnumber}[1]{
   \renewcommand{\ha@handoutnumber}{#1}
}


\newcommand{\ha@release}{}
\newcommand{\version}{}
\newcommand{\ha@logo}{}

\newcommand{\releasename}{Release}
\newcommand{\release}[1]{%
\renewcommand{\ha@release}{\releasename\space\version}%
   \renewcommand{\version}{#1}}

\newcommand{\logo}[1]{
   \renewcommand{\ha@logo}{\epsfig{width=0.98\LogoW, file=#1}}
}

%  Renew the command for making a title
\usepackage{fancyheadings}
\lhead{\ha@coursename}
\rhead{\@title}
\rfoot{\thepage}
\lfoot{\ha@authoraddress}
\cfoot{\@author}


\renewcommand{\maketitle}{%
  %\begin{titlepage}
  \thispagestyle{plain}
  \@ifundefined{pdfinfo}{}{
      \pdfinfo{
        /Author (\@author)
        /Title (\@title)
      }
    }

   \parbox[c]{\LogoW}{\ha@logo}
   \hspace{.4cm}
   \parbox[c]{\TitleW}{
    \let\footnotesize\small
    \let\footnoterule\relax

    \hrule\vspace{-5pt}
    \begin{flushleft}
    \hspace{0.5cm}
    {\sc\bfseries\ha@HeaderFamily\ha@coursename}
    \end{flushleft}\vspace{-5pt}
    \hrule
    % Now place the number of the handout
      {\setlength{\unitlength}{1cm}
       \begin{picture}(14, 0.5)
          \put(9.2,0.1){\parbox[b]{2.8cm}{\centering\fontsize{72}{5}\bf\ha@HeaderFamily \ha@handoutnumber}}
       \end{picture}
     \begin{picture}(14, 3.5)
         \put(0.5,2.5){\parbox[t]{11.5cm}{\rm\huge\bfseries\ha@HeaderFamily \@title \par}}
         \@ifundefined{showauthorinfo}{}{
             \put(0.5,0){\parbox[b]{11.5cm}{\rm\fontsize{12}{16}\bfseries\ha@HeaderFamily \@author \\\rm\fontsize{10}{14}\ha@HeaderFamily \ha@authoraddress \\ \@date \par}}
              % \put(0.5,0){\parbox[b]{11.5cm}{\rm\fontsize{10}{10}\ha@HeaderFamily \@date \par}}
          }
     \end{picture}

    }}
    \@thanks
 % \end{titlepage}
 \vspace{1cm}
 %\ha@doHorizontalRule

 %%% WRITE THE NAME OF THE AUTHOR
 %     \begin{flushright}
 %     {\LARGE\bfseries\ha@HeaderFamily \@author \par}
 %     {\large
 %      \@date \par
 %      {\bfseries\ha@HeaderFamily\ha@authoraddress \par}
 %     }%
 %   \end{flushright}%\par

  \setcounter{footnote}{1}%
  \let\thanks\relax\let\maketitle\relax
  %\gdef\@thanks{}\gdef\@author{}\gdef\@title{}
}



%\pagestyle{fancy}


%%%% useful definitions %%%%
\def\skiplinehalf{\medskip\\}
\def\skipline{\\}
\def\exspace{\vspace{1ex}}


\newcommand{\code}[1]{{\rm\@vobeyspaces\@noligs\def\{{\char`\{}\def\}{\char`\}}\def\~{\char`\~}\def\^{\char`\^}\def\e{\char`\\}\def\${\char`\$}\def\#{\char`\#}\def\&{\char`\&}\def\%{\char`\%}%
\texttt{#1}}}

\newcommand{\bfcode}[1]{\code{\bfseries#1}} % bold-faced code font
\newcommand{\kbd}[1]{\code{#1}}
\newcommand{\samp}[1]{`\code{#1}'}
% This weird definition of \var{} allows it to always appear in roman
% italics, and won't get funky in code fragments when we play around
% with fonts.  This also works directly in math mode.
\newcommand{\var}[1]{%
  \ifmmode%
    \hbox{\normalsize\textrm{\textit{#1\/}}}%
  \else%
    \normalsize\textrm{\textit{#1\/}}%
  \fi%
}
\newcommand{\keyword}[1]{\texttt{\bfseries #1}}
\newcommand{\cfunction}[1]{\texttt{#1}}
\newcommand{\ctype}[1]{\texttt{#1}} % C struct or typedef name
\newcommand{\cdata}[1]{\texttt{#1}} % C variable, typically global
\newcommand{\constant}[1]{\code{#1}}   % manifest constant, not syntactic
\newcommand{\preprocessor}[1]{\code{#1}}  % Pre processor
\newcommand{\logic}[1]{\textsf{#1}}  % Logical function- AND, OR, ...

\newcounter{examplectr}
\setcounter{examplectr}{1}

\newcommand{\ha@reset}{
   \setcounter{examplectr}{1}
}

\renewcommand{\section}{\ha@reset
                        \@startsection{section}{1}{\z@}%
                                    {-3.5ex \@plus -1ex \@minus -.2ex}%
                                    {2.3ex \@plus.2ex}%
                                    {\reset@font\large\bf\ha@HeaderFamily}}
\renewcommand{\subsection}{\@startsection{subsection}{2}{\z@}%
                                    {-3.25ex\@plus -1ex \@minus -.2ex}%
                                    {1.5ex \@plus .2ex}%
                                    {\reset@font\bf\ha@HeaderFamily}}
\renewcommand{\subsubsection}{\@startsection{subsubsection}{3}{\z@}%
                                    {-3.25ex\@plus -1ex \@minus -.2ex}%
                                    {1.5ex \@plus .2ex}%
                                    {\reset@font\bf\ha@HeaderFamily}}%{\reset@font\normalsize\py@HeaderFamily}}
\renewcommand{\paragraph}{\@startsection{paragraph}{4}{\z@}%
                                    {3.25ex \@plus1ex \@minus.2ex}%
                                    {-1em}%
                                    {\reset@font\normalsize\ha@HeaderFamily}}
\renewcommand{\subparagraph}{\@startsection{subparagraph}{5}{\parindent}%
                                    {3.25ex \@plus1ex \@minus .2ex}%
                                    {-1em}%
                                    {\reset@font\normalsize\ha@HeaderFamily}}



\floatstyle{ruled}

{ \theoremstyle{break}
  \theoremheaderfont{\rm\bfseries\ha@HeaderFamily}
  \theorembodyfont{\itshape\small}
  \newtheorem{Example}{Example}
}


\newenvironment{example}[1]{
  \vspace{16pt}\hrule\vspace{10pt}
  \begin{Example}
  {#1}
  \end{Example}
}{
 \vspace{10pt}
 \hrule
 \vspace{16pt}
}


{ \theoremstyle{break}
  \theoremheaderfont{\rm\bfseries\ha@HeaderFamily}
  \newtheorem{Listing}{Listing}
}

\usepackage{longtable}

\newenvironment{listing}{
   \begin{longtable}[t]{|p{0.98\linewidth}|}
   \hline
}{
   \hline
   \end{longtable}
}

\newcommand{\T}[0]{{\color{white}.}}

\newcommand{\Ta}[0]{{\color{white}...}}
\newcommand{\Tb}[0]{{\color{white}......}}
\newcommand{\Tc}[0]{{\color{white}.........}}
\newcommand{\Td}[0]{{\color{white}............}}
\newcommand{\Te}[0]{{\color{white}...............}}
