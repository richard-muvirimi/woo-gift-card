<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get an array list of all available dimensions
 * @return array|Dimension
 */
class DimensionsInstaller {

    public static function Install() {
	$dimensions = DimensionsInstaller::getTemplateSizes();

	foreach ($dimensions as $dimension) {
	    $args = array(
		"slug" => $dimension[0]
	    );

	    //insert term into db
	    $term = wp_create_term($dimension[1], "wgc-template-dimension", $args);

	    if (!is_wp_error($term)) {
		$term_id = $term["term_id"];

		//add term meta
		add_term_meta($term_id, "wgc-dimension-value1", $dimension[2]);
		add_term_meta($term_id, "wgc-dimension-value2", $dimension[3]);
		add_term_meta($term_id, "wgc-dimension-unit", $dimension[4]);
	    }
	}
    }

    private static function getTemplateSizes() {
	$dimensions = array();

	$dimensions[] = array("custom", "Custom", null, null, "mm");
	$dimensions[] = array("us_letter", "US Letter", 8.5, 11, "in");
	$dimensions[] = array("us_legal", "US Legal", 8.5, 14, "in");
	$dimensions[] = array("us_executive", "US Executive", 7.2, 10.5, "in");
	$dimensions[] = array("cse", "CSE", 462, 649, "pt");
	$dimensions[] = array("us_#10_envelope", "US #10 Envelope", 4.1, 9.5, "in");
	$dimensions[] = array("dl_envelope", "DL Envelope", 110, 220, "mm");
	$dimensions[] = array("ledger/tabloid", "Ledger/Tabloid", 11, 17, "in");
	$dimensions[] = array("banner", "Banner", 60, 468, "px");

	$dimensions[] = array("icon16*16", "Icon 16 * 16", 16, 16, "px");
	$dimensions[] = array("icon32*32", "Icon 32 * 32", 32, 32, "px");
	$dimensions[] = array("icon48*48", "Icon 48 * 48", 48, 48, "px");

	$dimensions[] = array("businesscard(iso7810)", "Business Card (ISO 7810)", 54, 85.6, "mm");
	$dimensions[] = array("businesscard(us)", "Business Card (US)", 2, 305, "in");
	$dimensions[] = array("businesscard(europe)", "Business Card (Europe)", 55, 85, "mm");
	$dimensions[] = array("businesscard(auz/nz)", "Business Card (Aus/NZ)", 54, 90, "mm");

	$dimensions[] = array("arch_a", "Arch A", 9, 12, "in");
	$dimensions[] = array("arch_b", "Arch B", 12, 18, "in");
	$dimensions[] = array("arch_c", "Arch C", 18, 24, "in");
	$dimensions[] = array("arch_d", "Arch D", 24, 36, "in");
	$dimensions[] = array("arch_e", "Arch E", 36, 48, "in");
	$dimensions[] = array("arch_e1", "Arch E1", 30, 42, "in");

	//series based
	$a_series = DimensionsInstaller::wgc_getTemplateSeries("a", 841, 1189, 0, 10);
	$b_series = DimensionsInstaller::wgc_getTemplateSeries("b", 1000, 1414, 0, 10);
	$c_series = DimensionsInstaller::wgc_getTemplateSeries("c", 917, 1297, 0, 10);
	$d_series = DimensionsInstaller::wgc_getTemplateSeries("d", 545, 771, 1, 7);
	$e_series = DimensionsInstaller::wgc_getTemplateSeries("e", 400, 560, 3, 6);

	/**
	 * Filters the template dimension list.
	 *
	 * @since 1.0
	 *
	 * @param array   $dimensions     The dimension list
	 */
	return apply_filters("wgc_template_dimensions", array_merge($dimensions, $a_series, $b_series, $c_series, $d_series, $e_series));
    }

    private static function wgc_getTemplateSeries($series, $value1, $value2, $min = 0, $max = 1) {
	$dimensions = array();

	//offset by doubling
	$value1 *= 2;

	//loop through adding values
	for ($index = $min; $index <= $max; $index++) {
	    $id = $series . $index;

	    //bisect value one and set valuetwo
	    $dimension = array($id, strtoupper($id), intval(floor($value1 / 2)), $value2, "mm");
	    $dimensions[] = $dimension;

	    $value2 = $dimension[2];
	    $value1 = $dimension[3];
	}

	return $dimensions;
    }

}
