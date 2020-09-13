<?php
	$links_file = "links.txt";
	$storage_path = "downloads";

	$file4aria = "input.txt";
	$aria2c = "aria2c";
	$current_dir = dirname(__FILE__);

	// ======================================================================================================== //

	$file4aria = pathcombine($current_dir, $file4aria);
	$aria2c = pathcombine($current_dir, $aria2c);

	if (file_exists($file4aria)) unlink($file4aria);
	$links = file($links_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	echo "Start create input file for Aria2c Downloader..." . PHP_EOL;
	foreach($links as $link)
	{
		$link = trim($link);
		if(strpos($link, 'http') !== 0) continue;
		$base_url = "";
		$id = "";
		if($files = GetAllFiles($link))
		{
			foreach ($files as $file)
			{
				$line = $file->download_link . PHP_EOL;
				$line .= "	out=" . $file->output . PHP_EOL;
				$line .= "	referer=" . $link . PHP_EOL;
				$line .= "	dir=" . $storage_path . PHP_EOL;

				file_put_contents($file4aria, $line, FILE_APPEND);
			}
			echo "Running Aria2c for download " . count($files) . " files..." . PHP_EOL;
			StartDownload();
			@unlink($file4aria);
		}
		else
		{
			die("Can't find any file");
		}
	}

	echo "Done!" . PHP_EOL;

	// ======================================================================================================== //

	class CMFile
	{
		public $name = "";
		public $output = "";
		public $link = "";
		public $download_link = "";

		function __construct($name, $output, $link, $download_link)
		{
			$this->name = $name;
			$this->output = $output;
			$this->link = $link;
			$this->download_link = $download_link;
		}
	}

	// ======================================================================================================== //

	function GetAllFiles($link, $folder = "")
	{
		global $base_url, $id, $storage_path, $current_dir;

		$page = get(pathcombine($link, $folder));
		if ($page === false) { echo "Error $link\r\n"; return false; }
		if (($mainfolder = GetMainFolder($page)) == false) { echo "Cannot get main folder $link\r\n"; return false; }

		if (!$base_url) $base_url = GetBaseUrl($page);
		if (!$id && preg_match('~\/public\/(.*)~', $link, $match)) $id = $match[1];

		$cmfiles = array();
		if ($mainfolder["name"] == "/") $mainfolder["name"] = "";
		foreach ($mainfolder["list"] as $item)
		{
			if ($item["type"] == "folder")
			{
				$files_from_folder = GetAllFiles($link, pathcombine($folder, rawurlencode(basename($item["name"]))));

				if (is_array($files_from_folder))
				{
					foreach ($files_from_folder as $file)
					{
						if ($mainfolder["name"] != "")
						{
							$file->output = $mainfolder["name"] . "/" . windowsbadpath($file->output);
						}
					}
					$cmfiles = array_merge($cmfiles, $files_from_folder);
				}
			}
			else
			{
				$fileurl = pathcombine($folder, rawurlencode($item["name"]));
				// Старые ссылки содержат название файла в id
				if (strpos($id, $fileurl) !== false) $fileurl = "";
				$file_output = windowsbadpath(pathcombine($mainfolder["name"], $item["name"]));
				$full_path = pathcombine($current_dir, $storage_path, $file_output);
				if (strlen($full_path) >= 260) die("ERROR: path too long " . strlen($full_path) . " > 260 chars: " . $full_path);
				$cmfiles[] = new CMFile($item["name"],
									$file_output,
									pathcombine($link, $fileurl),
									pathcombine($base_url, $id, $fileurl));
			}
		}

		return $cmfiles;
	}

	// ======================================================================================================== //

	function StartDownload()
	{
		global $aria2c, $file4aria;
		$command = "\"{$aria2c}\" --file-allocation=none --max-connection-per-server=10 --split=10 --max-concurrent-downloads=10 --summary-interval=0 --continue --user-agent=\"Mozilla/5.0 (compatible; Firefox/3.6; Linux)\" --input-file=\"{$file4aria}\"";
		passthru("{$command}");
	}

	// ======================================================================================================== //

	function GetMainFolder($page)
	{
		if (preg_match('~"folder":\s+(\{.*?\}\s+\]\s+\})\s+\}~s', $page, $match))
		{
			return json_decode($match[1], true);
		}
		else return false;
	}

	// ======================================================================================================== //

	function GetBaseUrl($page)
	{
		if (preg_match('~"weblink_get":.*?"url":\s*"(https:[^"]+)~s', $page, $match)) return $match[1];
		else return false;
	}

	// ======================================================================================================== //

	function get($url)
	{
		$proxy = null; //"127.0.0.1:8888";

		$http["method"] = "GET";
		if ($proxy) { $http["proxy"] = "tcp://" . $proxy; $http["request_fulluri"] = true; }
		$options['http'] = $http;
		$context = stream_context_create($options);
		$body = @file_get_contents($url, NULL, $context);
		return $body;
	}

	// ======================================================================================================== //

	function pathcombine()
	{
		$result = "";
		foreach (func_get_args() as $arg)
		{
			if ($arg !== '')
			{
				if ($result && substr($result, -1) != "/") $result .= "/";
				$result .= $arg;
			}
		}
		return $result;
	}

	// ======================================================================================================== //

	function windowsbadpath($filename)
	{
		$bad = array_merge(
			array_map('chr', range(0,31)),
			array("<", ">", ":", '"', "|", "?", "*"));
		return str_replace($bad, "", $filename);
	}

	// ======================================================================================================== //

?>
