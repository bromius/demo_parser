<?php

namespace Parser;

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'Parser.php';

$title = 'Simple content parser';
$parseUrl = 'https://newsblur.com/reader/river_stories';

try {
    $parser = new Parser($parseUrl);
} catch (\Throwable $e) {
    $error = $e->getMessage();
}
?><!DOCTYPE html>
<html lang="ru">
    <head>
        <title><?= $title ?></title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <style>
            h1 {
                font-size: 1.5rem;
                text-transform: uppercase;
                margin: 2rem 0;
            }

            h2 {
                font-size: 1.2rem;
            }

            .logo {
                text-transform: uppercase;
                color: #333;
            }

            .content {
                width: 90%;
                margin: 0 auto;
            }

            .content .article:hover {
                box-shadow: 0 1px 3px #c3c3c3;
            }

            .article {
                margin-bottom: 4rem;
                padding: 1rem;
                cursor: default;
            }

            .article h2 a {
                color: #333;
            }

            .article .article-body {
                margin-top: 1rem;
            }
        </style>
    </head>
    <body>
        <div class="d-flex flex-column flex-md-row align-items-center p-3 mb-3 bg-white border-bottom shadow-sm">
            <a class="logo font-weight-normal" href="/">
                <?= $title ?>
            </a>
            <div class="ml-5 mt-r mr-md-auto">
                Source: <a href="<?= $parseUrl ?>" target="_blank"><?= $parseUrl ?></a>
            </div>
        </div>

        <div class="content">
            <?php if (!isset($error)): ?>
                <h1>Latest news</h1>

                <?php foreach ($parser as $index => $row): ?>
                    <?php if ($index && ($index % 3 == 0)): ?>
                        </div>
                    <?php endif ?>
                    <?php if ($index % 3 == 0): ?>
                            <div class="row">
                    <?php endif ?>

                    <div class="col-4 article">
                        <div class="article-header">
                            <h2>
                                <a href="<?= $row['url'] ?>" target="_blank" title="<?= $row['title'] ?>"><?= $row['title'] ?></a>
                            </h2>
                            <small><?= $row['created'] . ' by <b>' . $row['author'] . '</b>' ?></small>
                        </div>
                        <div class="article-body">
                            <?php if ($row['image']): ?>
                                <img class="float-left mr-3" src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>" title="<?= $row['title'] ?>" width="250" />
                            <?php endif ?>

                            <?php if (strlen($row['content']) > 500): ?>
                                <?= substr($row['content'], 0, 500) ?>
                                <br />
                                <div class="text-center">
                                    <a href="<?= $row['url'] ?>" target="_blank" title="<?= $row['title'] ?>">- read more -</a>
                                </div>
                            <?php else: ?>
                                <?= $row['content'] ?>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <h2>Error</h2>
                <p><?= $error ?></p>
            <?php endif ?>
        </div>
    </body>
</html>