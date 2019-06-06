<?php
/*
    HTML macros
*/
HTML::macro('pages', function( $data )
{
    if( $data ) {
        print "<ul class='clearfix'>" . "\r\n";
        foreach( $data as $val ) {
            if( ! is_array( $val->subpages ) ) print '<li><a href="/'. $val->slug. '">' . $val->name . '</a>' . "\r\n";
            else {
                print '<li><a href="/'. $val->slug. '">' . $val->name . '</a>' . "\r\n";
                HTML::pages( $val->subpages );
            }
        }
        print '</ul>' . "\r\n";
    }
});


HTML::macro('cat_tree', function( $data, $selected_category, $level = 0, $nbsp = '' )
{
    if( $level > 0 )
        for( $i = 0; $i < $level; $i++ )
            $nbsp .= '&nbsp;&nbsp;&nbsp;&nbsp;';
            
    if( $data )
        print "<option value='0'></option>";
        foreach( $data as $val ) {
            if( $val->id == $selected_category ) $selected = 'selected';
            else $selected = '';

                print '<option ' . $selected . ' value="' . $val->id . '">' . $nbsp . $val->name . '</option>';
            if( is_array( $val->subcategories ) ) HTML::cat_tree( $val->subcategories, $selected_category, $level+1, $nbsp );
        }

});