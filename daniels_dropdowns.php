<?php
/*
Plugin Name: Daniel's DropDowns
Plugin URI: http://www.djs-consulting.com/linux/blog/category/programming/wordpress/plug-ins
Description: Extends the WordPress category and archive lists by providing a dropdown and associated button or link.
Version: 2.0.1
Author: Daniel J. Summers
Author URI: http://www.djs-consulting.com/linux/blog 

This plug-in provides 2 template tags.

  - daniels_category_dropdown() - This puts a category dropdown (and button or
link) at the specified point in the template.
  - daniels_archive_dropdown() - This puts an archive dropdown (and button or
link) at the specified point in the template.

For both tags, there are the following 2 parameters...
 - $sNavigationType - This parameter controls how the user navigates.
   - 'button' (or blank) is the default, and provides a button.
   - 'link' provides a text link.
   - 'auto' makes the dropdown list auto-navigate when the user selects an item
in that list.
 - $sText - This is the text of the button or link.  It defaults to "View [x]",
where [x] is either "Category" or "Archive".

To control styling, both dropdowns add a category of "ddd_[x]_form", where [x]
is either "category" or "archive".  This allows for CSS definition by each form,
for both forms together, or, if none is specified, it will take the styling from
the theme.

For example, if you wanted all aspects of the category form to have 10pt font,
you could do something like this...

#ddd_category_form select, #ddd_category_form button, #ddd_category_form a {
   font-size: 10pt;
}

For this reason, the CSS parameters to the dropdowns have been removed.
(Besides, there was a bug in the category dropdown that caused the parameter to
not work properly anyway.)  Feel free to contact me if the CSS is giving you a
problem.
*/

/**
 * Category DropDown.
 * 
 * This places a category dropdown list (and button or link) in the template at
 * the place where the tag is found.  It uses the WordPress template tag
 * "get_category_link" to obtain the link, so it should work for both standard
 * and "pretty" URLs.
 * 
 * Usage:
 * <?php if ( function_exists( 'daniels_category_dropdown' ) ) daniels_category_dropdown([paramters]); ?>
 *  (Note: Since this plug-in does not use "hooks," this is necessary so that
 *  the page will still display if the plug-in is disabled.  If you're not going
 *  to ever disable the plug-in, you don't need the "if" portion.)
 *
 * @param $sNavigationType 'auto', 'link', or 'button' (default).
 * @param $sText The text of the button or link.
 * @access public
 */
function daniels_category_dropdown (
  $sNavigationType = 'button', $sText = 'View Category' ) {
	
	// Determine the highest category ID - this is used to initialize the
	// JavaScript array.
	$aCategories = get_all_category_ids ( );
	$iMaxCat = 0;
	foreach ( $aCategories as $iThisCat ) {
		if ( $iMaxCat < $iThisCat ) {
			$iMaxCat = $iThisCat;
		}
	}
	
	$iMaxCat++;
?>
<form id="ddd_category_form" action="">
	<script type="text/javascript">
	var aLink = new Array ( <?php echo ( $iMaxCat ); ?> );
<?php
	// Create an array of category links.
	foreach ( $aCategories as $iThisCat ) {
		echo ( "aLink[$iThisCat] = '" . get_category_link ( $iThisCat ) . "';\n" );
	} ?>
	function goCat() {
		var elGato = document.getElementById('cat');
		if (elGato.selectedIndex > 0) {
			window.location = aLink[elGato[elGato.selectedIndex].value];
		}
	}
	</script>
	<div style="text-align:center;">
		<?php
	// Use the "wp_dropdown_categories" template tag to do the select box, and
	// blank out the zero counts. 
	$cats = str_replace ( '(0)', '', 
		wp_dropdown_categories (
			"class=$sSelectClass&orderby=name&show_count=1&hierarchical=1&echo=0" ) );
	
	// Add a "Select Category" option.
	$cats = str_replace ( "class=''>",
		"class=''><option value=''>&mdash; Select Category &mdash;</option>",
		$cats);
	if ( !strpos ( $cats, 'selected="selected"' ) ) {
		// Another category is not selected, so make the "Select Category"
		// option the default.
		$cats = str_replace ( "value=''>", "value='' selected='selected'>", 
			$cats );
	}
	
	if ( $sNavigationType == 'auto' ) {
		echo str_replace ( '<select', '<select onchange="goCat();"', $cats );
	}
	else {
		echo $cats; ?>
		<br />
<?php
		if ($sNavigationType == 'link') { ?>
		<a href="javascript:void();" onclick="goCat();"><?php echo $sText; ?></a>
<?php
		}
		else { ?>
		<button type="button" style="margin-top:5px;"
			onclick="goCat();"><?php echo $sText; ?></button>
<?php
		}
	}
?>	</div>
</form>
<?php
}

/**
 * Archive DropDown.
 * 
 * This places an archive dropdown list (and button or link) in the template at
 * the place where the tag is found.  It uses the WordPress template tag
 * "get_archives", which actually builds the necessary infrastructure (a
 * select box where the value of each item is the link), so it will work with
 * both standard and "pretty" URLs.
 * 
 * Usage:
 * <?php if ( function_exists( 'daniels_archive_dropdown' ) ) daniels_archive_dropdown([paramters]); ?>
 *  (Note: Since this plug-in does not use "hooks," this is necessary so that
 *  the page will still display if the plug-in is disabled.  If you're not going
 *  to ever disable the plug-in, you don't need the "if" portion.)
 *
 * @param $sButtonOrLink 'auto', 'link', or 'button' (default).
 * @param $sText The text of the button or link.
 * @access public
 */
function daniels_archive_dropdown ( 
  $sNavigationType = 'button', $sText = 'View Archive' ) {
?>
<form id="ddd_archive_form" action="">
	<script type="text/javascript">
	function goArc() {
		var selArc = document.getElementById('selArchive');
		if (selArc.selectedIndex > 0) {
			window.location = selArc[selArc.selectedIndex].value;
		} 
	}
	</script>
	<div style="text-align:center;">
		<select <?php if ( $sNavigationType == 'auto' ) { ?>onchange="goArc();"<?php } ?>
			id="selArchive" style="margin-top:5px;">
			<option value="">&mdash; Select Month &mdash;</option>
			<?php get_archives ( '', '', 'option', '', '', 1 ); ?>
		</select>
<?php
	if ( $sNavigationType != 'auto' ) { ?>
		<br />
<?php
		if ( $sNavigationType == 'link' ) { ?>
		<a href="javascript:void();" onclick="goArc();"><?php echo $sText; ?></a>
<?php
		}
		else { ?>
		<button  type="button" style="margin-top:5px;"
			onclick="goArc();"><?php echo $sText; ?></button>
<?php
		}
	}
?>	</div>
</form>
<?php
}
?>