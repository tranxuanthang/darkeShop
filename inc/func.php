<?php
include 'config.php';
function search($default_value=null){
    return '
    <form method="get" action="search.php">
    <input id="keyword" type="text" name="keyword" value="'.$default_value.'" />
    <input class="button-primary" type="submit" name="submit" value="search" />
    </form>';
}

function kichthuoc($size){
    if($size < 1024){
        $kichthuoc = "$size Bytes";
    } elseif($size < 1048576) {
        $kichthuoc = ''.round($size/1024,1).' KB';
    } else {
        $kichthuoc = ''.round($size/1024/1024,1).' MB';
    }
    return $kichthuoc;
}


function chiatrang($pages, $u,$f) {
    if(!isset($f))$f='';
	if($pages <= 7) {
		for($i = 1; $i <= $pages; $i++) {
			if($i == $_GET['page']) echo '<button class="button-small button-primary" disabled>'.$i."</button>"; else
			echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>';
			if($i != $pages) echo ',';
		}
	}
	else
	{
		if($_GET['page'] <= 2 || $_GET['page'] >= $pages-2) {
			if($_GET['page'] == 1 || $_GET['page'] == $pages) {
			for($i = 1; $i <= 2; $i++) {
				if($i == $_GET['page']) echo '<button class="button-small button-primary" disabled>'.$i.'</button>'; else
				echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>';
				if($i != 2) echo ',';
			}
			echo '..';
			for($i = $pages - 1; $i <= $pages; $i++) {
				if($i == $_GET['page']) echo '<button class="button-small button-primary" disabled>'.$i.'</button>'; else
				echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>';
				if($i != $pages) echo ',';
			}
			} else {
			if($_GET['page'] <= 2) {
				for($i = 1; $i <= $_GET['page'] + 1; $i++) {
				if($i == $_GET['page']) echo '<button class="button-small button-primary" disabled>'.$i.'</button>'; else
				echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>';
				if($i != $_GET['page'] + 1) echo ',';
				}
				echo '..';
				for($i = $pages - 1; $i <= $pages; $i++) {
				if($i == $_GET['page']) echo '<button class="button-small button-primary" disabled>'.$i.'</button>'; else
				echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>';
				if($i != $pages) echo ',';
				}
			} else {
				for($i = 1; $i <= 2; $i++) {
				if($i == $_GET['page']) echo '<button class="button-small button-primary" disabled>'.$i.'</button>'; else
				echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>';
				if($i != $_GET['page'] + 1) echo ',';
				}
				echo '..';
				for($i = $_GET['page'] - 1; $i <= $pages; $i++) {
				if($i == $_GET['page']) echo '<button class="button-small button-primary" disabled>'.$i.'</button>'; else
				echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>';
				if($i != $pages) echo ',';
				}
			}
			}
		} else {
			$i = 1;
			if($_GET['page'] - 2 == 1) $p = ','; else $p = ',...,';
			echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>'.$p;
			
			//
			
			for($i = $_GET['page']-1; $i <= $_GET['page']+1; $i++) {
				if($i == $_GET['page']) echo '<button class="button button-small button-primary" disabled>'.$i.'</button>'; else
				echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>';
				if($i != $_GET['page']+1) echo ',';
			}
			echo '..';
			//
			
			$i = $pages;
			echo '<a class="button button-small" href="'.$f.''.$i.'">'.$i.'</a>';
		}
	}
}



function XMLtoArray($XML)
{
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $XML, $vals);
    xml_parser_free($xml_parser);
    // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
    $_tmp='';
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_level!=1 && $x_type == 'close') {
            if (isset($multi_key[$x_tag][$x_level]))
                $multi_key[$x_tag][$x_level]=1;
            else
                $multi_key[$x_tag][$x_level]=0;
        }
        if ($x_level!=1 && $x_type == 'complete') {
            if ($_tmp==$x_tag)
                $multi_key[$x_tag][$x_level]=1;
            $_tmp=$x_tag;
        }
    }
    // jedziemy po tablicy
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_type == 'open')
            $level[$x_level] = $x_tag;
        $start_level = 1;
        $php_stmt = '$xml_array';
        if ($x_type=='close' && $x_level!=1)
            $multi_key[$x_tag][$x_level]++;
        while ($start_level < $x_level) {
            $php_stmt .= '[$level['.$start_level.']]';
            if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
            $start_level++;
        }
        $add='';
        if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
            if (!isset($multi_key2[$x_tag][$x_level]))
                $multi_key2[$x_tag][$x_level]=0;
            else
                $multi_key2[$x_tag][$x_level]++;
            $add='['.$multi_key2[$x_tag][$x_level].']';
        }
        if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
            if ($x_type == 'open')
                $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
            else
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
            eval($php_stmt_main);
        }
        if (array_key_exists('attributes', $xml_elem)) {
            if (isset($xml_elem['value'])) {
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            foreach ($xml_elem['attributes'] as $key=>$value) {
                $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                eval($php_stmt_att);
            }
        }
    }
    return $xml_array;
}

function getimgurimg($imgurl){
    $url = 'https://api.imgur.com/3/image.json';
    $headers = array("Authorization: Client-ID $imgur_client_id");
    $pvars  = array(
    'image' => $imgurl,'type' => 'url'
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
       CURLOPT_URL=> $url,
       CURLOPT_TIMEOUT => 30,
       CURLOPT_POST => 1,
       CURLOPT_RETURNTRANSFER => 1,
       CURLOPT_HTTPHEADER => $headers,
       CURLOPT_POSTFIELDS => $pvars
    ));
    $json_returned = curl_exec($curl); // blank response
    $json_anser = json_decode($json_returned, true);
    //print_r($json_anser) ;
    curl_close ($curl); 
    //print_r($json_anser);
    //echo '<br>'.$json_anser['data']['link'].'<br>';
    if(!empty($json_anser['data']['link']))return $json_anser['data']['link']; else return '';
    



}
?>