<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>" ?>

<feed xmlns="http://www.w3.org/2005/Atom">

  <title><?php echo $feed['name'] ?></title>
  <updated><?php echo $feed['entry'][0]['published'] ?></updated>
  <link rel="alternate" type="text/html" href="<?php echo $feed['url'] ?>"/>
  <author>
    <name><?php echo $feed['author']['name'] ?></name>
  </author>
  <id><?php echo $feed['url'] ?></id>

  <?php foreach ($feed['entry'] as $entry): ?>
  <entry>
    <title><?php echo htmlspecialchars($entry['name'], ENT_QUOTES) ?></title>
    <id><?php echo $entry['url'] ?></id>
	<link rel="alternate" type="text/html" href="<?php echo $entry['url'] ?>"/>
    <updated><?php echo $entry['published'] ?></updated>
    <summary><?php echo htmlspecialchars($entry['summary'], ENT_QUOTES) ?></summary>
  </entry>
  <?php endforeach; ?>

</feed>