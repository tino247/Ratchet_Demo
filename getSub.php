<?php
    // post.php ???
    // This all was here before  ;)
    while(true){
        fscanf(STDIN, "%s %s %s", $category, $tile, $article);
        $entryData = array(
        'category' => $category
      , 'title'    => $tile
      , 'article'  => $article
      , 'when'     => time()
    );
    // var_dump($entryData);die;
    // $pdo->prepare("INSERT INTO blogs (title, article, category, published) VALUES (?, ?, ?, ?)")
    //     ->execute($entryData['title'], $entryData['article'], $entryData['category'], $entryData['when']);

    // This is our new stuff
    var_dump($entryData);
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:6666");

    $socket->send(json_encode($entryData));
    
    }
    
?>