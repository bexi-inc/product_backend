<?

class Chrome
{
	public $Browser = "google-chrome";
	public $WebPage;
	public $Windows_Width = 0;
	public $Widnows_Height = 0;


	function Navigate($WebPage)
	{
		$this->WebPage = $WebPage;
	}

	function SetWindowsSize($width, $height)
	{
		$this->Windows_Width=$width;
		$this->Widnows_Height = $height;
	}

	function SaveScreenShot($path)
	{
		$cmd ="";
		if (!empty($path))
		{
			$pinfo = pathinfo($path);	
			$path =  $pinfo['dirname'];
			$file =  $pinfo['filename']."png";
			$cmd ="cd " . $path . " && ";
		}else
		{
			$file = $path;
			$path = "";
		}

		$cmd .= $this->Browser . "  --headless --disable-gpu --screenshot --no-sandbox ";
		if ($this->Widnows_Height>0 && $this->Windows_Width>0)
		{
			$cmd.= " --window-size=".$this->Windows_Width.",".$this->Widnows_Height." ";
		}

		$cmd.= " ".$this->WebPage;
		echo $cmd;
		exec($cmd);
		echo "mv ".$path."/screenshot.png ".$path."/".$file;

		exec("mv ".$path."/screenshot.png ".$path."/".$file)
	}
}

?>