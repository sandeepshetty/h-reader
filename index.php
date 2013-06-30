<?php

	require __DIR__.'/vendor/phpish/app/app.php';
	require __DIR__.'/vendor/phpish/template/template.php';
	require __DIR__.'/vendor/phpish/http/http.php';
	require __DIR__.'/vendor/autoload.php';


	use phpish\app;
	use phpish\template;
	use phpish\http;
	use mf2\Parser;


	app\query('/feed/atom', function($req) {

		if (isset($req['query']['url']))
		{
			$url = $req['query']['url'];
			try
			{
				$response_body = http\request("GET $url", array(), array(), $response_headers);
				$mf2parser = new Parser($response_body);
				$mf2 = $mf2parser->parse();

				$feed = array();
				$feed['url'] = $url;
				$feed['author'] = array();
				$feed['entry'] = array();

				foreach ($mf2['items'] as $item)
				{
					if (in_array('h-feed', $item['type']))
					{
						if (isset($item['name'][0])) $feed['name'] = $item['name'][0];
						else $feed['name'] = $url;

						if (isset($item['properties']['author'][0]))
						{

							if (in_array('h-card', $item['properties']['author'][0]['type']))
							{
								$h_card = $item['properties']['author'][0];

								if (isset($h_card['properties']['name'][0])) $feed['author']['name'] = $h_card['properties']['name'][0];
								else $feed['author']['name'] = 'sdadsa';
							}
						}

						if (isset($item['children']))
						{
							foreach($item['children'] as $entry)
							{
								if (in_array('h-entry', $entry['type']))
								{
									$feed['entry'][] = array(
										'name' => $entry['properties']['name'][0],
										'url' => $entry['properties']['url'][0],
										'published' => $entry['properties']['published'][0],
										'summary' => $entry['properties']['summary'][0]
									);
								}
							}
						}
					}
				}
				return app\response(template\render('feed.atom', compact('feed')), 200, array('content-type'=>'application/atom+xml'));
			}
			catch (http\ResponseException $e)
			{
				return app\response('Error while fetching URL', 500);
			}
		}

		return app\response('Bad Request', 400);

	});


?>