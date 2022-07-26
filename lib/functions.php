<?php
    /**
     * Get max ky_e_ymd
     * @param array $listKyHis
     * @return int
     */
    function getMaxKyEYMD(array $listKyHis) : int {
        $max = 0;
        $count = count($listKyHis);
        if ( $count == 1 ) {
            $max = strtotime($listKyHis[0]['ky_e_ymd']);
        }
        elseif ( $count > 1 ) {
            foreach ($listKyHis as $kyHis) {
                if (strtotime($kyHis['ky_e_ymd']) > $max) {
                    $max = strtotime($kyHis['ky_e_ymd']);
                }
            }
        }
        return $max;
    };

    /**
     * Check code product
     * @param array $possessedProductsCode
     * @param string $code
     * @return bool
     */
    function checkPossessedProductsCode(array $possessedProductsCode, string $code) : bool {
        foreach ($possessedProductsCode as $productsCode) {
            if ( mb_strpos($code, $productsCode) !== false ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get list serial by name
     * @param array $code
     * @param string $name
     * @return array
     */
    function getListSerialByName(array $listSral, string $name) : array {
        $list = array();
        foreach ( $listSral as $item ) {
            if ( strpos($item, $name) === false ) {
                continue;
            }
            $list[] = $item;
        }
        return $list;
    }
?>