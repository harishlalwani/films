<?php
/**
 * Country
 *
 * PHP version 5
 *
 * @category Model 
 * 
 */

class FilmDetail extends AppModel{
	/**
	 * Model name
	 * @var string
	 * @access public
	 */
	var $name = 'FilmDetail';
	/**
	 * Behaviors used by the Model
	 *
	 * @var array
	 * @access public
	 */
    
	public function GetData() {
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
        */
         
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
        */

        $linkurl   = FULL_BASE_URL.'/admin/business/';
        $aColumns = array( "film_details.id", "title", "release_year", "locations.name", "fun_facts", "production_company", "distributor", "director", "writer", "actor_1", "actor_2", "actor_3", "latitude", "longitude");
                 
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";
         
        /* DB table to use */
        $sTable = "film_details";
         
        App::uses('ConnectionManager', 'Model');
        $dataSource = ConnectionManager::getDataSource('default');
         
        /* Database connection information */
        $gaSql['user']       = $dataSource->config['login'];
        $gaSql['password']   = $dataSource->config['password'];
        $gaSql['db']         = $dataSource->config['database'];
        $gaSql['server']     = $dataSource->config['host'];
         
         
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP server-side, there is
        * no need to edit below this line
        */
         
        /*
         * Local functions
        */
        function fatal_error ( $sErrorMessage = '' )
        {
            header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
            die( $sErrorMessage );
        }
         
         
        /*
         * MySQL connection
        */
        if ( ! $gaSql['link'] = mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
        {
            fatal_error( 'Could not open connection to server' );
        }
         
        if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
        {
            fatal_error( 'Could not select database ' );
        }
         
         
        /*
         * Paging
        */
        $sLimit = "";
        if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
        {
            $sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
                    intval( $_GET['iDisplayLength'] );
        }
         
         
        /*
         * Ordering
        */
        $sOrder = "";
        if ( isset( $_GET['iSortCol_0'] ) )
        {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
            {
                if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
                {
                    $sOrder .= "".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".
                        ($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
                }
            }
         
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
            {
                $sOrder = "";
            }
        }
         
         
        /*
         * Filtering
        * NOTE this does not match the built-in DataTables filtering which does it
        * word by word on any field. It's possible to do here, but concerned about efficiency
        * on very large tables, and MySQL's regex functionality is very limited
        */
        $sWhere = "";
        if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
        {
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                $sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }
         
        /* Individual column filtering */
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
            {
                if ( $sWhere == "" )
                {
                    $sWhere = "WHERE ";
                }
                else
                {
                    $sWhere .= " AND ";
                }
                $sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
            }
        }

        
         
         
        /*
         * SQL queries
        * Get data to display
        */

        $join = " join locations on locations.id = film_details.location_id ";

        

        $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS ".implode(", ", $aColumns)."
            FROM   $sTable
            $join
            $sWhere
            $sOrder
            $sLimit
            ";
         /*echo $sQuery;
         exit; */  
        $rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
         
        /* Data set length after filtering */
        $sQuery = "
    SELECT FOUND_ROWS()
";
        $rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
        $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
        $iFilteredTotal = $aResultFilterTotal[0];
         
        /* Total data set length */
        $sQuery = "
    SELECT COUNT(`".$sIndexColumn."`)
            FROM   $sTable
            ";
        $rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
        $aResultTotal = mysql_fetch_array($rResultTotal);
        $iTotal = $aResultTotal[0];
         
         
        /*
         * Output
        */
        $output = array(
                "sEcho" => intval($_GET['sEcho']),
                "iTotalRecords" => $iTotal,
                "iTotalDisplayRecords" => $iFilteredTotal,
                "aaData" => array()
        );
         
        while ( $aRow = mysql_fetch_array( $rResult ) )
        {
            $row = array();
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                
                if ( $aColumns[$i] != ' ' )
                {
                    /* General output */
                    $row[] = utf8_encode($aRow[ $i ]);
                }
            }
            $output['aaData'][] = $row;
        }
         
        return $output;
    }
	
}