<?php
/*
    обновления......
    Необходимо доработать класс рассылки Newsletter, что бы он отправлял письма 
    и пуш нотификации для юзеров из UserRepository. 
    
    За отправку имейла мы считаем вывод в консоль строки: "Email {email} has been sent to user {name}"
    За отправку пуш нотификации: "Push notification has been sent to user {name} with device_id {device_id}"
    
    Так же необходимо реализовать функциональность для валидации имейлов/пушей:
    1) Нельзя отправлять письма юзерам с невалидными имейлами
    2) Нельзя отправлять пуши юзерам с невалидными device_id. Правила валидации можете придумать сами.
    3) Ничего не отправляем юзерам у которых нет имен
    4) На одно и то же мыло/device_id - можно отправить письмо/пуш только один раз
    
    Для обеспечения возможности масштабирования системы (добавление новых типов отправок и новых валидаторов), 
    можно добавлять и использовать новые классы и другие языковые конструкции php в любом количестве
*/
class Newsletter
{
    public function send(): void
    {
        $users = new UserRepository();
        $users_email = $users->createUsersEmailArray();
        $users_device = $users->createUsersDeviceArray();

        foreach ($users_email as $user => $email){
            echo "<script>
                        console.log('Email {{$email}} has been sent to user {{$user}}');
                  </script>";
        }
        foreach ($users_device as $user => $device_id){
            echo "<script>
                        console.log('Push notification has been sent to user {{$user}} with device_id {{$device_id}}');
                  </script>";
        }
    }
}

class UserRepository
{
    public function getUsers(): array
    {
        return [
            [
                'name' => 'Ivan',
                'email' => 'ivan@test.com',
                'device_id' => 'Ks[dqweer4'
            ],
            [
                'name' => 'Peter',
                'email' => 'peter@test.com'
            ],
            [
                'name' => 'Mark',
                'device_id' => 'Ks[dqweer4'
            ],
            [
                'name' => 'Nina',
                'email' => '...'
            ],
            [
                'name' => 'Luke',
                'device_id' => 'vfehlfg43g'
            ],
            [
               'name' => 'Zerg',
               'device_id' => ''
            ],
            [
               'email' => '...',
               'device_id' => ''
            ]
        ];    
    }

    /*
     * запрос к бд удобней и при большом колечестве пользователей сработает быстрей
     * */

    public function createUsersDeviceArray ()
    {
        $device_users = [];
        foreach ($this->getUsers() as $user) {
            if (isset($user['name']) && !empty($user['name']) && !empty($user['device_id'])){ // проверку сделал просто на пустоту тк не знаком с push(не приходилось)
                $device_users[$user['name']] = $user['device_id'];
            }
        }

        if ($device_users){
            return array_unique($device_users);
        }

        return false;

    }

    public function createUsersEmailArray ()
    {
        $email_users = [];
        foreach ($this->getUsers() as $user) {
            if (isset($user['name']) && !empty($user['name']) && filter_var($user['email'], FILTER_VALIDATE_EMAIL)){
                $email_users[$user['name']] = $user['email'];
            }
        }

        if ($email_users){
            return array_unique($email_users);
        }

        return false;
    }
}

/**
Тут релизовать получение объекта(ов) рассылки Newsletter и вызов(ы) метода send()
$newsletter = //... TODO
$newsletter->send();
...
*/

$newsletter = new Newsletter();
$newsletter->send();

echo "<div class='wrapper'>
  <div class='pulse'>Result in console :)</div>";

echo "<style>
* {
  margin: 0;
  padding: 0;
}
 
 
.pulse {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 150px;
  height: 150px;
  color:gray;
  background: white;
  border-radius: 50%;
  animation: radial-pulse 1s infinite;
}
 
.wrapper {
  width: 100%;
  height: 100vh;
  background-color: #D04746;
  display: flex;
  align-items: center;
  justify-content: center;
}
 
@keyframes radial-pulse {
  0% {
    box-shadow: 0 0 0 0px rgba(0, 0, 0, 0.5);
  }
 
  100% {
    box-shadow: 0 0 0 40px rgba(0, 0, 0, 0);
  }
}
</style>";

?>


