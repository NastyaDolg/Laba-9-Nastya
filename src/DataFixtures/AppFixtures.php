<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\News;
use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $roles_admin = [
            'ROLE_USER',
            'ROLE_ADMIN',
        ];

        $roles_user = [
            'ROLE_USER',
        ];
        
        //Обычный юзер
        $user = new User();
        $user->setApiToken(Uuid::v1()->toRfc4122());
        $password = $this->hasher->hashPassword($user, '12345678');
        $user->setLogin('nastya_user');
        $user->setEmail('nastya_user@mail.ru');
        $user->setRoles($roles_user);
        $user->setPassword($password);

        $manager->persist($user);

        //Админ
        $user = new User();
        $user->setApiToken(Uuid::v1()->toRfc4122());
        $password = $this->hasher->hashPassword($user, 'qwerty1234');
        $user->setLogin('nastya_admin');
        $user->setEmail('nastya_admin@mail.ru');
        $user->setRoles($roles_admin);
        $user->setPassword($password);

        $manager->persist($user);

        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            
            if($i % 3 == 0){
                $news = new News;
                $news->setName('Статистика №'. $i+1);
                $news->setDescription('В Липецкой области в январе за январь ликвидированы 113 предприятий и организаций');
                $news->setContent("Росстат опубликовал некоторые итоги социально-экономического развития регионов в январе. По данным статистики, промышленное производство в Липецкой области упало на 2,7% по сравнению с показателем января 2022 года. В минусе вместе с нашей областью оказалось еще четыре региона России. ЦФО в целом, напротив, показало рост (+3,2%).

В январе промышленники Липецкой области отгрузили товаров на 452 млн рублей, меньше на 18,7, чем в январем 2022-го. По этому показателю лучше нас сработали в Москве, Московской, Белгородской, Владимирской, Воронежской, Тульской и Курской областях. Но если говорить об отношении января к декабрю прошлого года, то у нас случился пусть небольшой, но рост (+1,2% ). Заметим, у единственного субъекта ЦФО. Ближе к нам лишь Белгородская область с ее 93,7% роста к последнему месяцу прошлого года. А хуже всех обстоят дела в Ярославской области с ее абсолютно провальными 22,3%.

В январе этого года в Липецкой области на убой в живом весе сдано 28,4 тысячи тонн скота и птицы (+18% к январю 2022 года), молока надоено 20,4 тысячи тонн (+4,2%), яиц получено 58,4 млн штук (+4,4%). Но даже с небольшим падением по ряду показателей безоговорочный лидер в АПК — Белгородская область, где в январе на убой в живом весе сдано 138,9 тысячи тонн скота и птицы, надой составил 50,4 тысячи тонн, курицы снесли 118,8 млн штук яиц. На втором месте по валу производства продукции животноводства — Воронежская область. По производству мяса и молока наш регион потеснила Курская область.

В Липецкой области в январе ввели в эксплуатацию 85,9 тыс кв.м жилья. По графе «строительство жилых домов» с учетом частных — падение на 6,2% относительно января 2022 года. Меньше, чем по ЦФО с его средним падением на 3,6. Больше нас жилья в физических объемах построили в Москве (996,7 тыс. кв.м) и Подмосковье (1098,2 тыс. кв.м), а также в Брянской (185,8 тыс. кв.м), Воронежской (122 тыс. кв.м), Калужской (174 тыс кв.м), Рязанской (102,7 тыс. кв.м), Тульской (123 тыс. кв.м) и Ярославской областях (105,4 тыс. кв.м). У 12 наших соседей объемы строительства жилья (в процентах) выше по отношению к январю 2022 года.

В январе этого года число хозяйствующих субъектов в регионе уменьшилось с 18 884 до 18 441. Впрочем, прибавку новых предприятий и организаций в ЦФО показали лишь Москва и Смоленская область. Ситуацию ухудшает показатель числа официально ликвидированных организаций в расчете на 1000 существующих: у нас он 6,4 (второй с конца по ЦФО и шестой из наихудших по стране). В Липецкой области в январе ликвидированы 113 организаций. Хуже, чем у нас, дела обстоят в Тульской области, где закрылись 193 предприятия. В Липецкой области, кстати, убыточно каждое четвертое предприятие. По ЦФО доля прибыльных предприятий — 73,9%.

Если говорить о заработной плате, то Росстат заявляет о средних 46 355 рублях (+15%) по Липецкой области в 2022 году. Это — седьмой показатель в ЦФО с его и вовсе заоблачными для липчан 81 380 рублями.

Липчане оставили в магазинах в январе 26,1 млрд рублей — на 5,3% меньше, чем в январе 2022 года. Но по ЦФО падение товарооборота составило даже большую цифру — 10,8%. При этом в Москве и в Московской области товарооборот за год упал на 13,5% и на 13,8% соответственно.

Оборот общественного питания в январе этого года по отношению к январю 2022-го упал в Липецкой области на 2,4% (на рестораны и кафе липчане потратили 822,2 млн рублей). В 10 регионах ЦФО: Москве, Брянской, Владимирской, Воронежской, Костромской, Курской, Орловской, Рязанской, Смоленской и Тамбовской областях в этом январе на общественное питание потратили больше денег, чем в январе прошлого года.

По объему платных услуг в январе этого года к январю прошлого года у Липецкой области — второй наихудший показатель по ЦФО. Мы упали на 4,4% в то время, как наши соседи в целом показали рост в 1,5%.");
                $date = new \DateTime('@'.strtotime('now + 3 hours'));
                $news->setDateLoad($date);
                $news->setViewsNum(0);
                $news->setfotopath('news1.jpg');
                $news->setActive(true);
                $news->setUser($user);
                $manager->persist($news);
            }

            else if($i % 3 == 1){
                $news = new News;
                $news->setName('Мошенничество №'. $i+1);
                $news->setDescription('За сутки жители Липецкой области перевели мошенникам более миллиона рублей');
                $news->setContent("По данным пресс-служба полиции, за сутки мошенники обманули жителей Липецкой области более чем на миллион рублей. Но есть и истории со счастливым концом.

«43-летней ельчанке 2 марта позвонил на сотовый телефон «следователь» и сообщил, на имя женщины пытаются оформить кредит. Чтобы не лишиться денег, женщина должна была перечислить средства на безопасный счёт. Ельчанка сразу же догадалась, что имеет дело с мошенникам, внимательно выслушала сначала «полицейского», затем «банковского специалиста», сняла 485 000 рублей со счёта, но перечислять на указанный мошенниками номер не стала. Она закончила телефонные переговоры и обратилась в дежурную часть елецкой полиции. Позже злоумышленники попытались возобновить с ней общение, но женщина не стала отвечать на их звонки», - сообщает пресс-служба УМВД России по Липецкой области.");
                $date = new \DateTime('@'.strtotime('now + 3 hours'));
                $news->setDateLoad($date);
                $news->setViewsNum(0);
                $news->setfotopath('news2.jpg');
                $news->setUser($user);
                $manager->persist($news);
            }

            else{
                $news = new News;
                $news->setName('Наука №'. $i+1);
                $news->setDescription('Невозможные объекты: в глубоком космосе найдены "разрушители Вселенной"');
                $news->setContent('Эволюцию Вселенной описывает стандартная космологическпя модель ΛCDM (читается как лямбда-си-ди-эм), основанная на Общей теории относительности Эйнштейна.
CDM — это cold dark matter, "холодная темная материя". На нее приходится около 25 процентов Вселенной. В электромагнитном взаимодействии не участвует, поэтому не фиксируется средствами наблюдения, имеющимися у человечества.
Однако у нее есть сила притяжения. Сгустки темной материи сыграли ключевую роль в формировании Вселенной — именно вокруг них группировались атомы видимого вещества, которые в конце концов образовали звезды и галактики. Масса и сложность структуры увеличивались постепенно. Поэтому логично предположить, что молодые галактики были меньше и проще, нежели более зрелые, такие как наш Млечный Путь.
Новейшие телескопы позволили ученым заглянуть в прошлое и проверить эту гипотезу. Известно, что Вселенная расширяется с ускорением под действием силы, противоположной гравитации, — гипотетической темной энергии. Она не способна разрушить галактики, "скрепленные" темной материей, поэтому просто "растаскивает" их в разные стороны.');
                $date = new \DateTime('@'.strtotime('now + 3 hours'));
                $news->setDateLoad($date);
                $news->setViewsNum(0);
                $news->setfotopath('news3.jpg');
                $news->setActive(true);
                $news->setUser($user);                     
                $manager->persist($news);

            }

            $comment = new Comments();
            $comment->setContent("Очень интересная новость)");
            $comment->setDateLoad($date);
            $comment->setUser($user);
            $comment->setNew($news);
            if($i % 3 == 0){
                $flag = false;
            }
            else{
                $flag = true;
            }
            $comment->setActive($flag);

            $manager->persist($comment);
        }
        $manager->flush();
    }
}
