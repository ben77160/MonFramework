<?= $renderer->render('header', ['title' => $slug]) ?>

<h1>Bienvenue sur l'article</h1>
<ul>
    <li><a href="<?= $router->generateUri('blog.show', ['slug' => 'zazeaz0-7aze']);?>">Article 1</a></li>
    <li>Article 1</li>
    <li>Article 1</li>
    <li>Article 1</li>
    <li>Article 1</li>
    <li>Article 1</li>
</ul>
<?= $renderer->render('footer')?>
