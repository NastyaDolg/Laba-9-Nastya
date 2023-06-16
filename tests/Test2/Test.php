<?php

namespace App\Tests\Test2;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Test extends WebTestCase
{
    private array $trueData = [
        'email' => 'nastya_user@mail.ru',
        'password' => '12345678'
    ];
    private array $falseData = [
        'email' => 'nastyaUser@mail.ru',
        'password' => '12345678'
    ];

    public function testIndexPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('NastyaNews');
//        $this->assertSelectorTextContains('h1', 'Hello World');
        $this->assertCount(13, $crawler->filter('.post'));

        $link = $crawler->filter('.post-link')->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleContains('NastyaNews');
    }

    public function testAuthentication(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('.vhod--link')->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleContains('Вход');
        $client->submitForm('Войти', $this->falseData);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('.error', 'Неверно введена почта или пароль');
        $client->submitForm('Войти', $this->trueData);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('NastyaNews');
    }

    public function testAddNew()
    {
        $client = static::createClient();
        $client->request('GET', '/addNew');
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertPageTitleContains('Вход');
        $client->submitForm('Войти', $this->trueData);

        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertPageTitleContains('NastyaNews');

        $link = $crawler->filter('.add__new')->link();
        $client->click($link);
        $uploadedFile = new UploadedFile(
            __DIR__.'/../testFoto/news2.jpg',
            'test.jpg'
        );
        $new_false = [
            'add_news[fotopath]' => ' ',
            'add_news[name]' => 'Машина',
            'add_news[description]' => 'Летающая машина будущего..',
            'add_news[content]' => 'Летающая машина будущего будет представлена в ближайшие 10-15 лет',
        ];
        $client->submitForm('Загрузить', $new_false);
        $this->assertSelectorTextContains('.error', 'Пожалуйста загрузите фото');
        $new_true = [
            'add_news[fotopath]' => $uploadedFile,
            'add_news[name]' => 'Машина',
            'add_news[description]' => 'Летающая машина будущего..',
            'add_news[content]' => 'Летающая машина будущего будет представлена в ближайшие 10-15 лет'
        ];
        $client->submitForm('Загрузить', $new_true);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('NastyaNews');
    }
}
