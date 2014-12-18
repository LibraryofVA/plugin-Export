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
configured as a crowd-sourcing transcription by the University of Iowa Libraries
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

First upload items as instructed in the documentation for [CsvImport](http://omeka.org/codex/Plugins/CsvImport). Choose 
the radio button for 'Item' on the main form.

To upload metadata for an item's files, you must first create a csv file for the file-level metadata. Each row represents
one file. Your file should include a column for Original Filename (the full path as entered on your item-level csv file).
If you wish to apply an file order to your item files, include a column called 'Omeka file order'. (CsvImport may or may not
have uploaded your item files in the order you intended.) Upload this csv file through the CsvImport interface, choosing 
'File' on the main form. Map Original Filename to the 'Filename?' checkbox and map all other fields as you normally would.
Do not map 'Omeka file order'. Metadata values will overwrite any existing element texts for file-level metadata.

See sample _item.csv and sample _file.csv for examples of usage.


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

[1]: http://www.omeka.org "Omeka.org"
[2]: https://github.com/omeka/plugin-CsvImport/Issues "GitHub CsvImport"
[3]: https://github.com/saverkamp/plugin-CsvImport/Issues "GitHub CsvImport fork"
[4]: https://www.gnu.org/licenses/gpl-3.0.html "GNU/GPL"
[5]: https://github.com/omeka "CHNM"
[6]: https://github.com/saverkamp "saverkamp"
[7]: https://github.com/Daniel-KM "Daniel_KM"
