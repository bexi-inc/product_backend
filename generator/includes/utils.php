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

	   // print_r($filters);
	    try
	    {

	   	 	$data = Crew\Unsplash\Photo::random($filters);
	     
	    	$contenido=substr_replace($contenido,$data->urls['custom'],$pos,$pos2-$pos);
	    } catch (Crew\Unsplash\Exception $e) {
	      //writeErrorLogEntry(basename(__FILE__,'.php'),__LINE__,$e);
	    }
	    $pos++;
	}
	return $contenido;
}

?>