=== Daniel's DropDowns ===
Contributors: danielsummers
Tags: categories, archives, dropdowns
Requires at least: 2.0
Tested up to: 2.9
Stable tag: trunk

This plugin extends the category and archive lists by providing a dropdown
(select) box that auto-navigates, or with a button or link to activate the
selection.

== Description ==

Daniel's DropDowns extends the standard WordPress category and archive template
tags.  It provides a category dropdown that uses the standard WordPress link
tags for its targets, so it will work with the standard permalinks, or any
form of "pretty" permalinks.  The archive dropdown is a lot simpler, as that
template tag will create option entries with the proper links.

Dropdowns can also include buttons or links to activate the current selection,
or (as of version 2) be rendered as auto-navigating select boxes.  Also, as of
version 2, you can specify the text that will appear in the link or on the
button.

NOTE FOR 1.0 USERS - As of version 2, the CSS parameters have been removed, as
each item can be uniquely identified without it, and will use the CSS of the
theme if none is specified.  There are notes in the plug-in that explain how to
write CSS to address these elements, and also explain the (now 2) parameters for
each tag.

NEW FOR 2.0.1 - There is now a "Select Category" option in the category
dropdown, and it is the default.  This allows the auto-navigation to work
properly for the first category in the list.

NEW FOR 2.1 - The "Select Category" option was not correctly displayed in the
2.5-series, due to an extra space in the category dropdown output.

== Installation ==

Upload "daniels\_dropdowns.php" to your wp-content/plugins directory.  It can
then be activated through the WordPress Plugin Administration page, and the
desired template tag(s) added to your theme as desired.  The tags and their
parameters are detailed in the documentation at the top of the plugin.
