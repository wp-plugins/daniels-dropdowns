<?php
/*
Plugin Name: Daniel's DropDowns
Plugin URI: http://www.djs-consulting.com/linux/blog/category/programming/wordpress/plug-ins
Description: Extends the WordPress category and archive lists by providing a dropdown and associated button or link.
Version: 1.0
Author: Daniel J. Summers
Author URI: http://www.djs-consulting.com/linux/blog 

This plug-in provides 2 template tags.

  - daniels_category_dropdown() - This puts a category dropdown and button at
the specified point in the template.
  - daniels_archive_dropdown() - This puts an archive dropdown and button at
the specified point in the template.

For both tags, following are the parameters...
 - $sButtonOrLink - This will display a button, unless the string 'link' is
passed in this parameter.
 - $sSelectClass - This will be used as the CSS class for the <select> element
(dropdown list).  It defaults to 'ddd_select' - you can put CSS for that class
in your stylesheet, or pass another class name in the template tag.
 - $sButtonClass - This will be used as the CSS class for the <button> element.
It defaults to 'ddd_select' - you can put CSS for that class in your stylesheet,
or pass another class name in the template tag.  (If the "link" parameter is
specified, this parameter has no effect.

*/

/**
 * Category DropDown.
 * 
 * This places a category dropdown list and button in the template at the
 * place where the tag is found.  It uses the WordPress template tag
 * "get_category_link" to obtain the link, so it should work for both standard
 * and "pretty" URLs.
 * 
 * NOTE: This creates a form called 'ddd_category_form'.  If you wish, you can
 * place a #ddd_category_form entry in the CSS file for the theme and define
 * a separate style for it. 
 *
 * Usage: <?php daniels_category_dropdown(); ?>
 *    or  <?php daniels_category_dropdown ( 'link' ); ?>
 */
function daniels_category_dropdown (
  $sButtonOrLink = 'button', $sSelectClass = 'ddd_select',
  $sButtonClass = 'ddd_button' ) {
	
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
<form id="ddd_category_form" action="" style="text-align:center;">
	<script type="text/javascript">
	var aLink = new Array(<?php echo ( $iMaxCat ); ?>);
<?php
	// Create an array of category links.
	foreach ( $aCategories as $iThisCat ) {
		echo( "aLink[$iThisCat] = '" . get_category_link ( $iThisCat ) . "';\n" );
	} ?>
	function goCat() {
		window.location =
				aLink[document.getElementById('cat')[document.getElementById('cat').selectedIndex].value]; 
	}
	</script>
	<div style="text-align:center;">
		<?php
	// Use the "wp_dropdown_categories" template tag to do the select box. 
	$cats = wp_dropdown_categories ( "class=$sSelectClass&orderby=name&show_count=1&hierarchical=1&echo=0" );
	// Blank out the counts for categories with "0" posts.
	echo ( str_replace ( '(0)', '', $cats ) ); ?>
		<br />
<?php
	if ($sButtonOrLink == 'link') { ?>
		<a href="javascript:void();" onclick="goCat();">View Category</a>
<?php
	}
	else { ?>
		<button class="<?php echo ( $sButtonClass ); ?>" type="button"
			style="margin-top:5px;" onclick="goCat();">View Category</button>
<?php
	}
?>	</div>
</form>
<?php
}

/**
 * Archive DropDown.
 * 
 * This places an archive dropdown list and button in the template at the
 * place where the tag is found.  It uses the WordPress template tag
 * "get_archives", which actually builds the necessary infrastructure (a
 * select box where the value of each item is the link), so it will work with
 * both standard and "pretty" URLs.
 * 
 * NOTE: This creates a form called 'ddd_archive_form'.  If you wish, you can
 * place a #ddd_archive_form entry in the CSS file for the theme and define
 * a separate style for it. 
 *
 * Usage: <?php daniels_archive_dropdown(); ?>
 *    or  <?php daniels_archive_dropdown ( 'link' ); ?>
 */
function daniels_archive_dropdown ( 
  $sButtonOrLink = 'button', $sSelectClass = 'ddd_select',
  $sButtonClass = 'ddd_button' ) {
?>
<form id="ddd_archive_form" action="">
	<script type="text/javascript">
	function goArc() {
		if (document.getElementById('selArchive').selectedIndex > 0) {
			window.location = 
					document.getElementById('selArchive')[document.getElementById('selArchive').selectedIndex].value;
		} 
	}
	</script>
	<div style="text-align:center;">
		<select class="<?php echo ( $sSelectClass ); ?>" id="selArchive" style="margin-top:5px;">
			<option value="">&mdash; Select Month &mdash;</option>
			<?php get_archives ( '', '', 'option', '', '', 1 ); ?>
		</select><br />
<?php
	if ($sButtonOrLink == 'link') { ?>
		<a href="javascript:void();" onclick="goArc();">View Archive</a>
<?php
	}
	else { ?>
		<button class="<?php echo ( $sButtonClass ); ?>" type="button" style="margin-top:5px;"
			onclick="goArc();">View Archive</button>
<?php
	}
?>	</div>
</form>
<?php
}

?>