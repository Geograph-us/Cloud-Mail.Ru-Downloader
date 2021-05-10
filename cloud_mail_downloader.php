<?php
	$links_file = "links.txt";
	$download_to_folder = "downloads";

	$proxy = null;
	$proxy_auth = null;
	//$proxy = "u1.p.webshare.io:80";
	//$proxy = "127.0.0.1:8888";
	//$proxy_auth = "pdmvzoam-1:hcecmi79o9ot";

	$delete_input_file_after_download = true;

	// ======================================================================================================== //

	$aria2c = "aria2c";
	$current_dir = dirname(__FILE__);
	$aria2c = pathcombine($current_dir, $aria2c);
	$links = file($links_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	echo "Start create input file for Aria2c Downloader..." . PHP_EOL;
	foreach($links as $link)
	{
		$link = trim($link);
		if(strpos($link, 'http') !== 0) continue;

		$file4aria = pathcombine($current_dir, "input" . time() . ".txt");
		if (file_exists($file4aria)) unlink($file4aria);

		$base_url = "";
		if($files = GetAllFiles($link))
		{
			foreach ($files as $file)
			{
				$line = $file->link . PHP_EOL;
				$line .= "	out=" . $file->output . PHP_EOL;
				$line .= "	referer=" . $link . PHP_EOL;
				$line .= "	dir=" . $download_to_folder . PHP_EOL;

				file_put_contents($file4aria, $line, FILE_APPEND);
			}
			echo "Running Aria2c for download " . count($files) . " files..." . PHP_EOL;
			StartDownload();
			if ($delete_input_file_after_download) @unlink($file4aria);
		}
		else
		{
			die("Can't find any file" . PHP_EOL);
		}
	}

	echo "Done!" . PHP_EOL;

	// ======================================================================================================== //

	class CMFile
	{
		public $link = "";
		public $output = "";

		function __construct($link, $output)
		{
			$this->output = $output;
			$this->link = $link;
		}
	}

	// ======================================================================================================== //

	function GetAllFiles($link, $folder = "")
	{
		global $base_url, $download_to_folder, $current_dir;

		$page = get(pathcombine($link, $folder));
		if ($page === false) { echo "Error $link\r\n"; return false; }
		if (($mainfolder = GetMainFolder($page)) == false) { echo "Cannot get main folder $link\r\n"; return false; }

		if (!$base_url) $base_url = GetBaseUrl($page);

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
				$fileurl = $item["weblink"];
				$file_output = windowsbadpath(pathcombine($mainfolder["name"], $item["name"]));
				$full_path = pathcombine($current_dir, $download_to_folder, $file_output);

				// fix for windows 10
				// reg add "HKLM\SYSTEM\CurrentControlSet\Control\FileSystem" /v LongPathsEnabled /t REG_DWORD /d 1 /f
				if (strlen($full_path) >= 260) echo "WARNING: path too long " . strlen($full_path) . " > 260 chars: " . $full_path;

				$cmfiles[] = new CMFile(pathcombine($base_url, $fileurl), $file_output);
			}
		}

		return $cmfiles;
	}

	// ======================================================================================================== //

	function StartDownload()
	{
		global $aria2c, $file4aria, $proxy, $proxy_auth;

		$command = "\"$aria2c\" --file-allocation=none --max-connection-per-server=10 --split=10 --max-concurrent-downloads=10 --summary-interval=0 --continue --user-agent=\"Mozilla/5.0 (compatible; Firefox/3.6; Linux)\" --input-file=\"$file4aria\"";
		if ($proxy) $command .= " --all-proxy=" . ($proxy_auth ? "$proxy_auth@" : "") . "$proxy";
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
		global $proxy, $proxy_auth;

		$http["method"] = "GET";
		if ($proxy) { $http["proxy"] = "tcp://" . $proxy; $http["request_fulluri"] = true; }
		if ($proxy_auth) $http["header"] = "Proxy-Authorization: Basic " . base64_encode($proxy_auth);
		$options['http'] = $http;
		$options['ssl'] = array
		(
			'verify_peer' => false,
			'verify_peer_name' => false
		);
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
