<?

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
	    } catch (Crew\Unsplash\Exception $e) {
	      //writeErrorLogEntry(basename(__FILE__,'.php'),__LINE__,$e);
	    }
	    $pos++;
	}


	while ( ( $pos = strpos( $contenido, "%bg_img", $pos ) ) !== false ) {
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

	   	 	//print_r($data);
	     	//echo $data->user['name'];
	     	//$contenido=substr_replace($contenido,' ',$pos2 + 2 ,0);
	    	$contenido=substr_replace($contenido,$data->urls['custom'],$pos,$pos2-$pos);

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
	    } catch (Crew\Unsplash\Exception $e) {
	      //writeErrorLogEntry(basename(__FILE__,'.php'),__LINE__,$e);
	    }
	    $pos++;
	}
	return $contenido;
}

?>