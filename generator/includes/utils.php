<?

/*
function setImages($contenido,$keywords = ""){
		
	$pos = 0;
	$pos2 = 0;
	while ( ( $pos = strpos( $contenido, "%img", $pos ) ) !== false ) {
	  $pos2 = strpos( $contenido, "%", ($pos + 1) );
	 // echo "The letter 'l' was found at position: $pos<br/>";
	  $imgtag = substr($contenido,$pos+1,$pos2-$pos - 1);
	 // echo $imgtag;
	  $imgdata = explode ("|",$imgtag);
	  //print_r( $imgdata);

	    $filters = [
	            'featured' => true,
	            'w'        => $imgdata[1],
	            'h'        => $imgdata[2],
	            'query'    => $keywords
	    ];

	    //print_r($filters);
	    try
	    {

	   	 	$data = Crew\Unsplash\Photo::random($filters);
	   	} catch (Crew\Unsplash\Exception $e) {
	       $filters = [
	            'featured' => true,
	            'w'        => $imgdata[1],
	            'h'        => $imgdata[2]
	    	];
	    	$data = Crew\Unsplash\Photo::random($filters);
	    }
	    

	   	 	//print_r($data);
	     	//echo $data->user['name'];
	     	$contenido=substr_replace($contenido,' bexi_img_au="'.$data->user['name'].'"  bexi_au_link="'.$data->user["links"]["html"].'" ',$pos2 + 2 ,0);
	    	$contenido=substr_replace($contenido,$data->urls['custom'],$pos,$pos2-$pos);

	    	$tagend = strpos($contenido,">",$pos);
	    	$tagstar = $tagend;

	    	while(substr($contenido, $tagstar,1)!="<")
			{
				$tagstar = $tagstar -1;
			}

			//echo "TAG IMG = ".$tagstar."=>".$tagend;
			$taghtml = substr($contenido, $tagstar,$tagend - $tagstar +1);
			//echo "bexi_img".strpos($taghtml,"bexi_img");
			//echo htmlentities($taghtml);
			$dom=new domDocument;
			libxml_use_internal_errors(true);
			$dom->loadHTML($taghtml);
			libxml_use_internal_errors(false);
			$dom->preserveWhiteSpace = false;

			$tagimg = $dom->getElementsByTagName ("img")->item(0);
			//print_r($tagimg);
			//echo $tagimg->getAttribute('class');
			if (strpos($tagimg->getAttribute('class'),"bexi_img") == false )
			{
				$pos_class=strpos($taghtml,"class");
				$pos_class2 = strpos($taghtml,"'",$pos_class);
				if(!$pos_class2)
				{
					$pos_class2 = strpos($taghtml,'"',$pos_class);
				}
				//echo "add bexi_img";

				$contenido = substr_replace($contenido, "bexi_img ", $tagstar + $pos_class2 + 1  ,0);
			}
	    $pos++;
	}

	$pos=0;

	while ( ( $pos = strpos( $contenido, "%bg_img", $pos ) ) !== false ) {
	  $pos2 = strpos( $contenido, "%", ($pos + 1) );
	 // echo "The letter 'l' was found at position: $pos<br/>";
	  $imgtag = substr($contenido,$pos+1,$pos2-$pos - 1);
	  //echo $imgtag;
	  $imgdata = explode ("|",$imgtag);
	  //print_r( $imgdata);

	    $filters = [
	            'featured' => true,
	            'w'        => $imgdata[1],
	            'h'        => $imgdata[2],
	            'query'    => $keywords
	    ];

	    //print_r($filters);
	    try
	    {

	   	 	$data = Crew\Unsplash\Photo::random($filters);
	   	} catch (Crew\Unsplash\Exception $e) {
	     	$filters = [
	            'featured' => true,
	            'w'        => $imgdata[1],
	            'h'        => $imgdata[2]
	    	];
	    	$data = Crew\Unsplash\Photo::random($filters);
	   		//print_r($e);
	   	}
	   	 	//print_r($data);
	     	//echo $data->user['name'];
	     	//$contenido=substr_replace($contenido,' ',$pos2 + 2 ,0);
	    	$contenido=substr_replace($contenido,$data->urls['full'],$pos,$pos2-$pos + 1);

	    	/*$tagend = strpos($contenido,">",$pos);
	    	$tagstar = $tagend;

	    	while(substr($contenido, $tagstar,1)!="<")
			{
				$tagstar = $tagstar -1;
			}

			//echo "TAG IMG = ".$tagstar."=>".$tagend;
			$taghtml = substr($contenido, $tagstar,$tagend - $tagstar +1);
			//echo "bexi_img".strpos($taghtml,"bexi_img");
			//echo htmlentities($taghtml);
			$dom=new domDocument;
			libxml_use_internal_errors(true);
			$dom->loadHTML($taghtml);
			libxml_use_internal_errors(false);
			$dom->preserveWhiteSpace = false;

			$tagimg = $dom->getElementsByTagName ("img")->item(0);
			//print_r($tagimg);
			//echo $tagimg->getAttribute('class');
			if (strpos($tagimg->getAttribute('class'),"bexi_img") == false )
			{
				$pos_class=strpos($taghtml,"class");
				$pos_class2 = strpos($taghtml,"'",$pos_class);
				if(!$pos_class2)
				{
					$pos_class2 = strpos($taghtml,'"',$pos_class);
				}
				//echo "add bexi_img";

				$contenido = substr_replace($contenido, "bexi_img ", $tagstar + $pos_class2 + 1  ,0);
			}*/

	   // $pos++;
	/*}
	return $contenido;
}*/

function setImages($contenido,$keywords = ""){
		
	$pos = 0;
	$pos2 = 0;
	while ( ( $pos = strpos( $contenido, "%img", $pos ) ) !== false ) {
	  $pos2 = strpos( $contenido, "%", ($pos + 1) );
	 // echo "The letter 'l' was found at position: $pos<br/>";
	  $imgtag = substr($contenido,$pos+1,$pos2-$pos - 1);
	 // echo $imgtag;
	  $imgdata = explode ("|",$imgtag);
	  //print_r( $imgdata);
	    $filters = [
	            'featured' => true,
	            'w'        => $imgdata[1],
	            'h'        => $imgdata[2],
	            'query'    => $keywords
	    ];
	    //print_r($filters);
	    try
	    {
	   	 	$data = Crew\Unsplash\Photo::random($filters);
	   	} catch (Crew\Unsplash\Exception $e) {
	      //writeErrorLogEntry(basename(__FILE__,'.php'),__LINE__,$e);
	   	 	$filters = [
		            'featured' => true,
		            'w'        => $imgdata[1],
		            'h'        => $imgdata[2]
		    ];
		    $data = Crew\Unsplash\Photo::random($filters);
	    }
	    	//print_r($filters);
	   	 	//print_r($data);
	     	//echo $data->user['name'];
	     	$contenido=substr_replace($contenido,' bexi_img_au="'.$data->user['name'].'"  bexi_au_link="'.$data->user["links"]["html"].'" ',$pos2 + 2 ,0);
	    	$contenido=substr_replace($contenido,$data->urls['custom'],$pos,$pos2-$pos+1);
	    	$tagend = strpos($contenido,">",$pos);
	    	$tagstar = $tagend;
	    	while(substr($contenido, $tagstar,1)!="<")
			{
				$tagstar = $tagstar -1;
			}
			//echo "TAG IMG = ".$tagstar."=>".$tagend;
			$taghtml = substr($contenido, $tagstar,$tagend - $tagstar +1);
			//echo "bexi_img".strpos($taghtml,"bexi_img");
			//echo htmlentities($taghtml);
			$dom=new domDocument;
			libxml_use_internal_errors(true);
			$dom->loadHTML($taghtml);
			libxml_use_internal_errors(false);
			$dom->preserveWhiteSpace = false;
			$tagimg = $dom->getElementsByTagName ("img")->item(0);
			//print_r($tagimg);
			//echo $tagimg->getAttribute('class');
			if (strpos($tagimg->getAttribute('class'),"bexi_img") == false )
			{
				$tagimg->setAttribute('class',$tagimg->getAttribute('class')." bexi_img");
				/*$pos_class=strpos($taghtml,"class");
				$pos_class2 = strpos($taghtml,"'",$pos_class);
				if(!$pos_class2)
				{
					$pos_class2 = strpos($taghtml,'"',$pos_class);
				}
				//echo $tagstar."=>".$pos_class2;
				//echo "add bexi_img";
				$contenido = substr_replace($contenido, "bexi_img ", $tagstar + $pos_class2 + 1  ,0);
				*/
				$content = $dom->saveHTML();
			}
	   
	    $pos++;
	}
	$pos=0;
	while ( ( $pos = strpos( $contenido, "%bg_img", $pos ) ) !== false ) {
	  $pos2 = strpos( $contenido, "%", ($pos + 1) );
	 // echo "The letter 'l' was found at position: $pos<br/>";
	  $imgtag = substr($contenido,$pos+1,$pos2-$pos - 1);
	  //echo $imgtag;
	  $imgdata = explode ("|",$imgtag);
	  //print_r( $imgdata);
	    $filters = [
	            'featured' => true,
	            'w'        => $imgdata[1],
	            'h'        => $imgdata[2],
	            'query'    => $keywords
	    ];
	    try
	    {
	   	 	$data = Crew\Unsplash\Photo::random($filters);
	   	print_r($data);
	   	} catch (Crew\Unsplash\Exception $e) {
		     $filters = [
		            'featured' => true,
		            'w'        => $imgdata[1],
		            'h'        => $imgdata[2]
		    ];
		    try
	    	{
		    	$data = Crew\Unsplash\Photo::random($filters);
		    } catch (Crew\Unsplash\Exception $e2) {

		    }
	   }

	    	$contenido=substr_replace($contenido,$data->urls['custom'],$pos,$pos2-$pos + 1);
	    	
	   
	   // $pos++;
	}
	return $contenido;
}

function zipme($source, $destination)
{
    if (!file_exists($source)) {
        echo "source doesn't exist";
        return false;
    } 
     
    if (!extension_loaded('zip')) {
        echo "zip extension not loaded in php";
        return false;
    }
  
    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        echo "failed to create zip file on destination";
        return false;
    }
  
    $source = str_replace('\\', '/', realpath($source));
  
    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
  
        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);
  
            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;
  
            $file = realpath($file);
  
            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
  
    return $zip->close();
}

?>