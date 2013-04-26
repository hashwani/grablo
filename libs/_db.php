<?php

function sql_escape($arg) {
	return addslashes($arg);
}

function sql_open() {
	global $SQL_HOST, $SQL_DB, $SQL_USER, $SQL_PASS;
		
	if (!@mysql_connect($SQL_HOST, $SQL_USER, $SQL_PASS)) {
		$msg = mysql_error();
		die("Cannot connect to database server (Reason: $msg)");
	}
	if (!@mysql_select_db($SQL_DB)) {
		$msg = mysql_error();
		die("Cannot select db (Reason: $msg)");
	}
	return true;
}

function sql_exec_va($args) {
	global $sql_query;

	$query = $args[0];
	$i = 1;
	$n = count($args);

	$a = explode("%", $query);
	$r = "";
	if (!empty($a)) foreach ($a as $p) {
		$c = $p[0];
		if ($c != "s" && $c != "u" && $c != "d" && $c != "f") {
			$r .= "%";
			if ($c == "P") $p = substr($p, 1);
			$r .= $p;
			continue;
		}
		if ($i >= $n) die("FATAL: not enough arguments to SQL query ($query_code: $query)");
		$arg = $args[$i++];
		switch ($c) {
			case "s": $r .= "'" . sql_escape($arg) . "'"; break;
			case "u": $r .= $arg; break;
			case "d": $r .= (int)$arg; break;
			case "f": $r .= (float)$arg; break;
		}
		$r .= substr($p, 1);
	}
	$query = substr($r, 1);

	$sql_query = $query;
	return @mysql_query($query);
}

function sql_query_va($args) {
	global $sql_query;

	if (!($r = sql_exec_va($args))) {
		$msg = mysql_error();
		die("Query failed (query: $sql_query, reason: $msg)");
	}
	return $r;
}

function sql_query($query) {
	$args = func_get_args();
	return sql_query_va($args);
}

function sql_exec($query) {
	$args = func_get_args();
	return sql_exec_va($args);
}

function sql_row($result) {
	return mysql_fetch_row($result);
}


function sql_rows($result) {
	return mysql_num_rows($result);
}

function sql_fetch($query) {
	$args = func_get_args();
	$r = sql_query_va($args);
	$a = sql_row($r);
	return $a[0];
}

function sql_row_hash($result) {
	return mysql_fetch_array($result);
}

function sql_fetch_hash($query) {
	$args = func_get_args();
	$r = sql_query_va($args);
	return sql_row_hash($r);
}

/*
 * options:
 * table - table name
 * cols - columns to select
 * left_join - table to left join and on clause
 * where - where clause
 * order_by - order by clause
 * limit - limit for the selection of records
 *
 */
function sql_select($options) {
	//echo "SELECT ".$options["cols"]." FROM ".$options["table"].(empty($options["left_join"])?"":" LEFT JOIN ".$options["left_join"]).(empty($options["where"])?"":" WHERE ".$options["where"]).(empty($options["order_by"])?"":" ORDER BY ".$options["order_by"]).(empty($options["limit"])?"":" LIMIT ".$options["limit"]);
	$result = sql_query("SELECT ".$options["cols"]." FROM ".$options["table"].(empty($options["left_join"])?"":" LEFT JOIN ".$options["left_join"]).(empty($options["where"])?"":" WHERE ".$options["where"]).(empty($options["order_by"])?"":" ORDER BY ".$options["order_by"]).(empty($options["limit"])?"":" LIMIT ".$options["limit"])) or die(msql_error());
	for($rows=array();$row=mysql_fetch_assoc($result);$rows[]=$row);
	return $rows;
}

/*
 * options:
 * query - query to be executed
 *
 */
function sql_select_query($query) {
	for($rows=array(), $result = sql_query($query);$row=mysql_fetch_assoc($result);$rows[]=$row);
	return $rows;
}

function sql_insert1($query) {
	$args = func_get_args();
	sql_query_va($args);
	return sql_insert_id();
}
/*
 * table - table name
 * data - associative array containing data to be inserted
 *
 */
function sql_insert($options) {
	$cols_vals = array_to_sql_insert_params($options["data"]);
	$sql = "INSERT INTO `". $options["table"] ."` (". $cols_vals[0] .") VALUES (". $cols_vals[1] .")";
	mysql_query($sql) or die("Error executing query: " . mysql_error());
	return mysql_insert_id();
}

/*
 * table - table name
 * data - associative array containing data to be inserted
 * where - where clause
 *
 */
function sql_update($options) {
	$fields = array_to_sql_update_params($options["data"]);
	//echo "UPDATE `". $options["table"] ."` SET ". $fields .(empty($options["where"])?"":" WHERE ".$options["where"]);
	mysql_query("UPDATE `". $options["table"] ."` SET ". $fields .(empty($options["where"])?"":" WHERE ".$options["where"])) or die(mysql_error());
	return mysql_affected_rows();
}

/*
 * table - table name
 * where - where clause
 *
 */
function sql_delete($options) {
	mysql_query("DELETE FROM `". $options["table"] ."` " . (empty($options["where"])?"":" WHERE " . $options["where"])) or die(mysql_error());
	return mysql_affected_rows();
}

/*
 * table - table name
 *
 */
function sql_show_columns($table) {
	$result = mysql_query("SHOW COLUMNS FROM sometable");	
	for($rows=array();$row=mysql_fetch_assoc($result);$rows[]=$row);
	return $rows;
}

function sql_insert_id() {
	return mysql_insert_id();
}

function sql_free($r) {
	return mysql_free_result($r);
}

function array_to_sql_insert_params($rows) {
	$cols = "";
	$vals = "";
	$comma = false;
	foreach($rows as $key => $value) {
		if($comma) {
			$cols .= ", ";
			$vals .= ", ";
		} else {
			$comma = true;
		}
		$cols .= "`$key`";
		$vals .= "'" . mysql_real_escape_string($value) . "'";
	}
	return array($cols, $vals);
}

function array_to_sql_update_params($rows) {
	$fields = "";
	$comma = false;
	foreach($rows as $key => $value) {
		if($comma) {
			$fields .= ", ";
		} else {
			$comma = true;
		}
		if($value[0] == '`') {
			$fields .= "`$key`= $value";
		} else {
			$fields .= "`$key`='" . mysql_real_escape_string($value) . "'";
		}
	}
	return $fields;
}
?>