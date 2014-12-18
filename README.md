Export (plugin for Omeka)
=============================


Summary
-------

This plugin allows administrators the ability to download a ZIP archive containing a
single PDF for each file within a collection that contains transcription text. To be
clear the PDF files are created from single file level transcription data; it does
not merge all pages of an item’s transcription into one pdf. This plugin was designed
specifically for use by the Library of Virginia to extract transcription data for each
file as a PDF to be imported into a digital asset management system adding full text
search capability.

This plugin is meant to be used in conjuction with Scripto and MediaWiki as orginally
configured as a crowd-sourcing transcription tool by the University of Iowa Libraries
(http://diyhistory.lib.uiowa.edu/transcribe/) and later reproduced by the Library of Virginia
(http://www.virginiamemory.com/transcribe/). Visit http://diyhistory.lib.uiowa.edu/code.html
for an overview of the technology stack and implementation information. Visit 
http://www.virginiamemory.com/transcribe/code for the Library of Virginia's changes.

Installation
------------

Save entire Export directory to your plugins directory. 

Lines 3 and 11 within the Export/views/adming/index/index.php file refer to the Library
of Virginia's Omeka installation root "/transcribe" and should be updated to your own
Omeka installation directory.

Ensure your httpd service account has write access to the /plugins/Export/PDF/ directory.

Then install it like any other Omeka plugin.


Usage
-----

The plugin will add a link, "Download PDF file for each collection->item->files 
transcription", to the administrative view of the collection show page. Clicking the 
link, which might take a moment to load for larger collections, will result in a 
download prompt of a ZIP archive. The archive will contain a pdf for each Omeka file 
that contains transcription information from within the collection. The individual PDF 
files will be named using the "original file name" for each Omeka file. Any Omeka 
files that do not contain transcription information will not have a corresponding PDF.


Warning
-------

Use it at your own risk.

It's always recommended to backup your database so you can roll back if needed.


Troubleshooting
---------------

https://github.com/LibraryofVA/plugin-Export


License
-------

This plugin is published under [GNU/GPL][4].

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.


Contact
-------

Current maintainers:
Library of Virginia

Copyright
---------

Copyright Library of Virginia, 2014
