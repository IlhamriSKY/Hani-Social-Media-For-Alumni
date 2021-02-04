<?php

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

	$TEXT = array();
	$SEX = array("Male" => 0, "Female" => 1);

    $TEXT['lang-code'] = "ru";
    $TEXT['lang-name'] = "Русский";

    $TEXT['main-page-welcome'] = "Публикуйте сообщения и картинки с помощью My Social Network!";

    $TEXT['main-page-about'] = "My Social Network - это социальная сеть с помощью которой Вы можете делиться интересными новостями, картинками и видеороликами с YouTube со своими друзьями и всеми людьми.";

    $TEXT['main-page-prompt-app'] = "My Social Network идет, куда вы идете, так что вы можете создавать посты и общаться с друзьями в любое время, в любом месте. Оставайтесь на связи с друзьями с мобильного приложения My Social Network. Доступно для Android.";

    $TEXT['mode-demo'] = "Режим демо-версии. Изменения не будут сохранены.";

    $TEXT['topbar-users'] = "Пользователи";

    $TEXT['topbar-stats'] = "Статистика";

    $TEXT['topbar-signin'] = "Вход";

    $TEXT['topbar-friends'] = "Друзья";

    $TEXT['topbar-logout'] = "Выйти";

    $TEXT['topbar-signup'] = "Регистрация";

    $TEXT['topbar-settings'] = "Настройки";

    $TEXT['topbar-support'] = "Поддержка";

    $TEXT['topbar-profile'] = "Профиль";

    $TEXT['topbar-likes'] = "Уведомления";

    $TEXT['topbar-search'] = "Поиск";

    $TEXT['topbar-main-page'] = "Главная";

    $TEXT['topbar-wall'] = "Главная";

    $TEXT['topbar-messages'] = "Сообщения";

    $TEXT['footer-about'] = "о сайте";

    $TEXT['footer-terms'] = "правила";

    $TEXT['footer-contact'] = "контакты";

    $TEXT['footer-support'] = "поддержка";

    $TEXT['page-main'] = "Главная";

    $TEXT['page-users'] = "Пользователи";

    $TEXT['page-hashtags'] = "Хештеги";

    $TEXT['page-terms'] = "Правила";

    $TEXT['page-about'] = "О сайте";

    $TEXT['page-language'] = "Выбирете язык";

    $TEXT['page-support'] = "Поддержка";

    $TEXT['page-restore'] = "Восстановление пароля";

    $TEXT['page-restore-sub-title'] = "Введите email, на который зарегистрирована страница.";

    $TEXT['page-signup'] = "регистрация";

    $TEXT['page-login'] = "Войти";

    $TEXT['page-wall'] = "Новости";

    $TEXT['page-blacklist'] = "Черный список";

    $TEXT['page-messages'] = "Сообщения";

    $TEXT['page-stream'] = "Лента";

    $TEXT['page-following'] = "Читает";

    $TEXT['page-friends'] = "Друзья";

    $TEXT['page-followers'] = "Подписчики";

    $TEXT['page-posts'] = "Записи";

    $TEXT['page-search'] = "Поиск";

    $TEXT['page-profile-report'] = "Жалоба";

    $TEXT['page-profile-block'] = "Блокировать";

    $TEXT['page-profile-upload-avatar'] = "Сменить фото";

    $TEXT['page-profile-upload-cover'] = "Сменить обложку";

    $TEXT['page-profile-report-sub-title'] = "Профили, на которые есть жалобы, проверяются модератором и могут быть блокированы, если они нарушают наши правила";

    $TEXT['page-profile-block-sub-title'] = "не сможет писать комментарии к вашим постам и отправлять Вам личные сообщения, и вы не будите получать уведомлений от";

    $TEXT['page-post-report-sub-title'] = "Посты, на которые есть жалобы, проверяются модератором и могут быть удалены, если они нарушают наши правила";

    $TEXT['page-likes'] = "Люди, которым это нравиться";

    $TEXT['page-services'] = "Сервисы";

    $TEXT['page-services-sub-title'] = "Соедините My Social Network с аккаунтом в социальной сети";

    $TEXT['page-prompt'] = "регистрация или вход";

    $TEXT['page-settings'] = "Настройки";

    $TEXT['page-profile-settings'] = "Профиль";

    $TEXT['page-profile-password'] = "Сменить пароль";

    $TEXT['page-notifications-likes'] = "Уведомления";

    $TEXT['page-profile-deactivation'] = "Деактивировать профиль";

    $TEXT['page-profile-deactivation-sub-title'] = "Покидаете нас?<br>Помните, что Вы всегда можете вернуться назад, введя свой логин и пароль на странице входа. Мы будем скучать без Вас!";

    $TEXT['page-error-404'] = "Страница не найдена";

    $TEXT['label-location'] = "Местоположение";
    $TEXT['label-facebook-link'] = "Страница Facebook";
    $TEXT['label-instagram-link'] = "Страница Instagram";
    $TEXT['label-status'] = "О себе";

    $TEXT['label-error-404'] = "Запрашиваемая страница не найдена.";

    $TEXT['label-account-disabled'] = "Пользователь отключил свой аккаунт.";

    $TEXT['label-account-blocked'] = "Аккаунт заблокирован администратором.";

    $TEXT['label-account-deactivated'] = "Аккаунт не активирован.";

    $TEXT['label-reposition-cover'] = "Обложку можно двигать";

    $TEXT['label-or'] = "или";

    $TEXT['label-and'] = "и";

    $TEXT['label-signup-confirm'] = "При нажатии Регистрация, Вы соглашаетесь с нашими";

    $TEXT['label-likes-your-post'] = "понравилась ваша запись.";

    $TEXT['label-login-to-like'] = "Вы должны быть зарегистрированы, чтобы оценивать записи.";

    $TEXT['label-login-to-follow'] = "Вы должны иметь аккаунт, чтобы подпичываться на пользователей.";

    $TEXT['label-empty-my-wall'] = "Вы ничего не публиковали.";

    $TEXT['label-empty-wall'] = "У этого пользователя нет записей.";

    $TEXT['label-empty-page'] = "Здесь пусто.";

    $TEXT['label-empty-questions'] = "Это ссылка на твой профиль. Поделись ее со своими друьями.";

    $TEXT['label-empty-list'] = "Список пуст.";

    $TEXT['label-empty-feeds'] = "Здесь Вы будите видеть записи Ваших друзей.";

    $TEXT['label-search-result'] = "Результаты поиска";

    $TEXT['label-search-empty'] = "Ничего не найдено.";

    $TEXT['label-search-prompt'] = "Искать пользователей по логину.";

    $TEXT['label-thanks'] = "Ура!";

    $TEXT['label-post-missing'] = "Запись удалена.";

    $TEXT['label-post-deleted'] = "Записи была удалена.";

    $TEXT['label-posts-privacy'] = "Настройки для Ваших записей";

    $TEXT['label-comments-allow'] = "Я разрешаю комментировать свои записи";

    $TEXT['label-messages-privacy'] = "Настройки сообщений";

    $TEXT['label-messages-allow'] = "Принимать сообщения от всех пользователей";

    $TEXT['label-settings-saved'] = "Настройки сохранены.";

    $TEXT['label-password-saved'] = "Пароль изменен.";

    $TEXT['label-profile-settings-links'] = "А также";

    $TEXT['label-photo'] = "Фото";

    $TEXT['label-username'] = "Логин";

    $TEXT['label-fullname'] = "Полное имя";

    $TEXT['label-services'] = "Сервисы";

    $TEXT['label-blacklist'] = "Черный список";

    $TEXT['label-blacklist-desc'] = "Смотреть черный список";

    $TEXT['label-profile'] = "Профиль";

    $TEXT['label-email'] = "Email";

    $TEXT['label-password'] = "Пароль";

    $TEXT['label-old-password'] = "Текущий пароль";

    $TEXT['label-new-password'] = "Новый пароль";

    $TEXT['label-change-password'] = "Сменить пароль";

    $TEXT['label-facebook'] = "Facebook";

    $TEXT['label-prompt-follow'] = "Вы должны быть зарегистрированы, чтобы подписаться на этого пользователя.";

    $TEXT['label-prompt-like'] = "YВы должны быть зарегистрированы, чтобы оценивать записи.";

    $TEXT['label-placeholder-post'] = "Опубликуйте...";

    $TEXT['label-placeholder-message'] = "Написать сообщение...";

    $TEXT['label-img-format'] = "Максимально 3 Mb. JPG, PNG";

    $TEXT['label-message'] = "Сообщение";

    $TEXT['label-subject'] = "Тема";

    $TEXT['label-support-message'] = "В чем Ваша проблема?";

    $TEXT['label-support-sub-title'] = "Мы рады слышать Вас! ";

    $TEXT['label-profile-report-reason-1'] = "Спам.";

    $TEXT['label-profile-report-reason-2'] = "Грубое поведение.";

    $TEXT['label-profile-report-reason-3'] = "Порнография.";

    $TEXT['label-profile-report-reason-4'] = "Поддельный профиль.";

    $TEXT['label-profile-report-reason-5'] = "Пиратство.";

    $TEXT['label-like-user'] = "пользователю";

    $TEXT['label-mylike-user'] = "другому";

    $TEXT['label-like-peoples'] = "пользователям";

    $TEXT['label-mylike-peoples'] = "другим";

    $TEXT['label-mylike'] = "Вам";

    $TEXT['label-like'] = "это понравилось";

    $TEXT['label-success'] = "Успешно";

    $TEXT['label-password-reset-success'] = "Новый пароль успешно установлен!";

    $TEXT['label-verify'] = "проверен";

    $TEXT['label-account-verified'] = "Подлинный профиль";

    $TEXT['label-true'] = "true";

    $TEXT['label-false'] = "false";

    $TEXT['label-state'] = "account status";

    $TEXT['label-stats'] = "Статистика";

    $TEXT['label-id'] = "Id";

    $TEXT['label-count'] = "Count";

    $TEXT['label-repeat-password'] = "восстановить пароль";

    $TEXT['label-category'] = "Категория";

    $TEXT['label-from-user'] = "от";

    $TEXT['label-to-user'] = "для";

    $TEXT['label-reason'] = "Причина";

    $TEXT['label-action'] = "Действие";

    $TEXT['label-warning'] = "Внимание!";

    $TEXT['label-connected-with-facebook'] = "Подключить к Facebook";

    $TEXT['label-authorization-with-facebook'] = "Авторизация через Facebook.";

    $TEXT['label-services-facebook-connected'] = "Вы успешно связали свой аккаунт с Facebook!";

    $TEXT['label-services-facebook-disconnected'] = "Вы удалили связь с Facebook.";

    $TEXT['label-services-facebook-error'] = "Ваш аккаунт Facebook уже связан с другим аккаунтом.";

    $TEXT['action-login-with'] = "Войти через";

    $TEXT['action-signup-with'] = "Регистрация через";
    $TEXT['action-delete-profile-photo'] = "Удалить фото";
    $TEXT['action-delete-profile-cover'] = "Удалить обложку";
    $TEXT['action-change-photo'] = "Сменить фото";
    $TEXT['action-change-password'] = "Сменить пароль";

    $TEXT['action-more'] = "Показать еще";

    $TEXT['action-next'] = "Далее";

    $TEXT['action-another-post'] = "Создать еще одну запись";

    $TEXT['action-add-img'] = "Добавить картинку";

    $TEXT['action-remove-img'] = "Удалить картинку";

    $TEXT['action-close'] = "Закрыть";

    $TEXT['action-go-to-conversation'] = "Перейти к беседе";

    $TEXT['action-post'] = "Запись";

    $TEXT['action-remove'] = "Удалить";

    $TEXT['action-report'] = "Жалоба";

    $TEXT['action-block'] = "Блокировать";

    $TEXT['action-unblock'] = "Разблокировать";

    $TEXT['action-follow'] = "Подписаться";

    $TEXT['action-unfollow'] = "Отписаться";

    $TEXT['action-change-cover'] = "Сменить обложку";

    $TEXT['action-change'] = "Изменить";

    $TEXT['action-change-image'] = "Выбрать картинку";

    $TEXT['action-edit-profile'] = "Редактировать профиль";

    $TEXT['action-edit'] = "Редактировать";

    $TEXT['action-restore'] = "Восстановить";

    $TEXT['label-for-followers'] = "Только для подписчиков";

    $TEXT['action-deactivation-profile'] = "Деактивировать профиль";

    $TEXT['action-connect-profile'] = "Подключиться к социальным сетям";

    $TEXT['action-connect-facebook'] = "Подключиться к Facebook";

    $TEXT['action-disconnect'] = "Разорвать связь";

    $TEXT['action-back-to-default-signup'] = "Обычная регистрация";

    $TEXT['action-back-to-main-page'] = "На главную";

    $TEXT['action-back-to-previous-page'] = "Вернуться назад";

    $TEXT['action-forgot-password'] = "Забыли пароль?";

    $TEXT['action-full-profile'] = "Смотреть полный профиль";

    $TEXT['action-delete-image'] = "Удалить картинку";

    $TEXT['action-send'] = "Отправить";

    $TEXT['action-cancel'] = "Отмена";

    $TEXT['action-upload'] = "Загрузить";

    $TEXT['action-search'] = "Поиск";

    $TEXT['action-change'] = "Изменить";

    $TEXT['action-save'] = "Сохранить";

    $TEXT['action-login'] = "Войти";

    $TEXT['action-signup'] = "Регистрация";

    $TEXT['action-join'] = "Регистрация";

    $TEXT['action-forgot-password'] = "Забыли пароль?";

    $TEXT['msg-loading'] = "Загрузка...";

    $TEXT['msg-post-sent'] = "Ваша запись опубликована.";

    $TEXT['msg-login-taken'] = "Имя пользователя уже занято.";

    $TEXT['msg-login-incorrect'] = "Имя пользователя - не верный формат.";

    $TEXT['msg-fullname-incorrect'] = "Полное имя - не верный формат.";

    $TEXT['msg-password-incorrect'] = "Пароль - не верный формат.";

    $TEXT['msg-password-save-error'] = "Не верный формат нового пароля.";

    $TEXT['msg-email-incorrect'] = "Email не верный ормат.";

    $TEXT['msg-email-taken'] = "Пользователь с таким Email уже зарегистрирован.";

    $TEXT['msg-email-not-found'] = "Пользователь с таким Email не найден в базе данных.";

    $TEXT['msg-reset-password-sent'] = "Ссылка на восстановление пароля выслана на Ваш email.";

    $TEXT['msg-error-unknown'] = "Ошибка. Повторите позже.";

    $TEXT['msg-error-authorize'] = "Не верный логин или пароль.";

    $TEXT['msg-error-deactivation'] = "Не верный пароль.";

    $TEXT['placeholder-users-search'] = "Искать пользователя по логину.";

	$TEXT['ticket-send-success'] = 'В ближайшее время Вы получите ответ на свой email.';

	$TEXT['ticket-send-error'] = 'Заполните все поля.';






    $TEXT['action-go-to-post'] = "Перейти к записи";
    $TEXT['label-follow-your'] = "подписался на Вас";
    $TEXT['label-full-profile'] = "Полный профиль";

    $TEXT['label-placeholder-comment'] = "Комментарий...";
    $TEXT['action-show-all'] = "Показать все";
    $TEXT['action-comment'] = "Комментировать";
    $TEXT['label-comments-prompt'] = "Оставлять комментарии могут только зарегистрированные пользователи.";
    $TEXT['label-comments-disallow'] = "Пользователь запретил комментировать свои записи.";
    $TEXT['label-new-comment'] = "написал комментарий.";

    $TEXT['label-image-upload-description'] = "Поддерживается JPG, PNG or GIF files.";
    $TEXT['label-cover-upload-description'] = "Выбирете уникальную обложку для своего профиля.<br>Поддерживается JPG or PNG files.";
    $TEXT['label-photo-upload-description'] = "Загрузите свое настоящие фото, чтобы друзья могли Вас узнать.<br>Поддерживается JPG or PNG files.";
    $TEXT['action-select-file-and-upload'] = "Выбрать файл";

     $TEXT['fb-linking'] = "Подключиться к Facebook";

    $TEXT['label-social-search'] = "Ваш аккаунт не подключен к Facebook.";
    $TEXT['label-social-search-not-found'] = "Мы не нашли Ваших друзей по Facebook";

    $TEXT['sidebar-profile'] = "Моя Страница";
    $TEXT['sidebar-friends'] = "Мои Друзья";
    $TEXT['sidebar-messages'] = "Мои Сообщения";
    $TEXT['sidebar-news'] = "Мои Новости";
    $TEXT['sidebar-settings'] = "Мои Настройки";
    $TEXT['sidebar-favorites'] = "Мне Понравилось";

    $TEXT['page-favorites'] = "Избранное";

    $TEXT['label-search-hashtag-prompt'] = "Искать посты по хештегу.";

    $TEXT['label-gender'] = "Пол";
    $TEXT['label-birth-date'] = "Дата рождения";

    $TEXT['gender-unknown'] = "Не указан";
    $TEXT['gender-male'] = "Мужской";
    $TEXT['gender-female'] = "Женский";

    $TEXT['month-jan'] = "Январь";
    $TEXT['month-feb'] = "Февраль";
    $TEXT['month-mar'] = "Март";
    $TEXT['month-apr'] = "Апрель";
    $TEXT['month-may'] = "Май";
    $TEXT['month-june'] = "Июнь";
    $TEXT['month-july'] = "Июль";
    $TEXT['month-aug'] = "Август";
    $TEXT['month-sept'] = "Сентябрь";
    $TEXT['month-oct'] = "Октябрь";
    $TEXT['month-nov'] = "Ноябрь";
    $TEXT['month-dec'] = "Декабрь";

    $TEXT['label-likes'] = "Мне нравится";

    $TEXT['sidebar-stream'] = "Лента";
    $TEXT['sidebar-popular'] = "Популярное";

    $TEXT['page-popular'] = "Популярное";

    $TEXT['label-new-reply-to-comment'] = "ответил на Ваш комментарий.";
    $TEXT['action-reply'] = "Ответить";

    $TEXT['action-share'] = "Поделиться";
    $TEXT['action-share-post'] = "Поделиться";
    $TEXT['label-share-options'] = "Поделиться";
    $TEXT['label-share-where'] = "Выберите аудиторию";
    $TEXT['label-share-on-wall'] = "Друзья и подписчики";
    $TEXT['label-share-on-wall-desc'] = "Поделиться записью с Вашими друзьями и подписчиками";
    $TEXT['label-share-add-comment'] = "Ваш комментарий";

    $TEXT['label-prompt-repost'] = "Вы должны быть зарегистрированным пользователем чтобы сделать репост.";
    $TEXT['label-repost-error'] = "Контент недоступен.";


    $TEXT['page-gallery'] = "Галерея";
    $TEXT['action-add-photo'] = "Добавить";
    $TEXT['label-add-photo'] = "Выберите фотографию, чтобы добавить ее в галерею";
    $TEXT['sidebar-gallery'] = "Моя Галерея";
    $TEXT['label-photos'] = "Фотографии";

    $TEXT['sidebar-groups'] = "Мои Группы";
    $TEXT['page-groups'] = "Сообщества";
    $TEXT['label-groups'] = "Сообщества";
    $TEXT['label-my-groups'] = "Мои Группы";
    $TEXT['label-managed-groups'] = "Управление";
    $TEXT['action-create-group'] = "Создать сообщество";
    $TEXT['label-group-search-prompt'] = "Искать сообщество по названию.";

    $TEXT['label-group-fullname'] = "Название";
    $TEXT['label-group-username'] = "Адрес страницы (Короткое имя)";
    $TEXT['label-group-status'] = "Описание сообщества";
    $TEXT['label-group-location'] = "Местоположение";
    $TEXT['label-group-web-page'] = "Веб-сайт";
    $TEXT['label-group-category'] = "Тематика сообщества";
    $TEXT['label-group-date'] = "Дата основания";
    $TEXT['label-group-privacy'] = "Настройки приватности";
    $TEXT['label-group-allow-comments'] = "Все члены сообщества имеют право оставлять записи на стене";
    $TEXT['label-group-allow-posts'] = "Все члены сообщества имеют право оставлять комментарии к сообщениям";

    $TEXT['label-group-name-error'] = "Имя (Короткое имя) уже занято или некорректно";
    $TEXT['label-group-fullname-error'] = "Полное имя сообщества должно содержать минимум 2 символа";

    $TEXT['group-category_0'] = "Активный отдых";
    $TEXT['group-category_1'] = "Культура и искусство";
    $TEXT['group-category_2'] = "Авто/мото";
    $TEXT['group-category_3'] = "Красота и мода";
    $TEXT['group-category_4'] = "Бизнес";
    $TEXT['group-category_5'] = "Кино";
    $TEXT['group-category_6'] = "Кулинария";
    $TEXT['group-category_7'] = "Знакомство и общение";
    $TEXT['group-category_8'] = "Дизайн и графика";
    $TEXT['group-category_9'] = "Образование";
    $TEXT['group-category_10'] = "Электроника и бытовая техника";
    $TEXT['group-category_11'] = "Развлечения";
    $TEXT['group-category_12'] = "Эротика";
    $TEXT['group-category_13'] = "Эзотерика";
    $TEXT['group-category_14'] = "Дом и семья";
    $TEXT['group-category_15'] = "Финансы";
    $TEXT['group-category_16'] = "Продукты питания";
    $TEXT['group-category_17'] = "Игры";
    $TEXT['group-category_18'] = "Товары и услуги";
    $TEXT['group-category_19'] = "Здоровье";
    $TEXT['group-category_20'] = "Увлечения и хобби";
    $TEXT['group-category_21'] = "Обустройство и ремонт";
    $TEXT['group-category_22'] = "Юмор";
    $TEXT['group-category_23'] = "Промышленность";
    $TEXT['group-category_24'] = "Страхование";
    $TEXT['group-category_25'] = "ИТ (компьютеры и софт)";
    $TEXT['group-category_26'] = "Литература";
    $TEXT['group-category_27'] = "Мобильная связь и интернет";
    $TEXT['group-category_28'] = "Музыка";
    $TEXT['group-category_29'] = "Новости и СМИ";
    $TEXT['group-category_30'] = "Домашние животные";
    $TEXT['group-category_31'] = "Фото";
    $TEXT['group-category_32'] = "Политика";
    $TEXT['group-category_33'] = "Недвижимость";
    $TEXT['group-category_34'] = "Религия";
    $TEXT['group-category_35'] = "Наука и техника";
    $TEXT['group-category_36'] = "Безопасность";
    $TEXT['group-category_37'] = "Общество, гуманитарные науки";
    $TEXT['group-category_38'] = "Спорт";
    $TEXT['group-category_39'] = "Телевидение";
    $TEXT['group-category_40'] = "Путешествия";
    $TEXT['group-category_41'] = "Работа";

    // For version 1.8

    $TEXT['page-guests'] = "Гости";
    $TEXT['sidebar-guests'] = "Мои Гости";

    // For version 1.9

    $TEXT['page-nearby'] = "Люди рядом";

    // For version 2.1

    $TEXT['page-balance'] = "Баланс";
    $TEXT['page-balance-desc'] = "Кредиты - универсальная валюта. С помощью кредитов вы можете оплатить подарки и другие функции.";
    $TEXT['action-get-credits'] = "Получить кредиты";
    $TEXT['label-credits'] = "Кредиты";
    $TEXT['label-balance'] = "У Вас:";
    $TEXT['page-gifts'] = "Подарки";
    $TEXT['label-new-gift'] = "сделал подарок.";
    $TEXT['action-view'] = "смотреть";
    $TEXT['dlg-select-gift'] = "Выберите подарок";
    $TEXT['page-send-gift'] = "Отправить подарок";
    $TEXT['label-placeholder-gift'] = "Комментарий к подарку...";

    // For version 2.6

    $TEXT['label-image-missing'] = "Фотография отсутсвует.";

    $TEXT['label-image-deleted'] = "Фотография удалена.";

    $TEXT['label-images-privacy'] = "Настройка приватности для фотографий";

    $TEXT['label-images-comments-allow'] = "Я разрешаю комментировать мои фотографии";

    $TEXT['action-go-to-photo'] = "Смотреть фото";

    $TEXT['label-likes-your-photo'] = "понравилось ваше фото.";

    // For version 3.1

    $TEXT['label-missing-account'] = "У Вас еще нет аккаунта?";
    $TEXT['label-existing-account'] = "У Вас уже есть аккаунт?";
    $TEXT['label-errors-title'] = "Ошибка. Читайте ниже:";
    $TEXT['label-signup-sub-title'] = "Создайте аккаунт и присоединяйтесь к нашему сообществу прямо сейчас!";

    $TEXT['label-friends-search'] = "Найти";
    $TEXT['label-friends-search-sub-title'] = "Найти моих друзей";

    $TEXT['label-notifications-sub-title'] = "Здесь вы можете видеть уведомления о лайках, подписках, комментариях, подарках.";
    $TEXT['label-friends-sub-title'] = "Здесь вы можете увидеть список людей, на которых вы подписались.";
    $TEXT['label-guests-sub-title'] = "В этой секции показаны пользователи, которые посещали Ваш профиль.";
    $TEXT['label-messages-sub-title'] = "Эта Секция отражает ваши диалоги с другими пользователями социальной сети.";
    $TEXT['label-gallery-sub-title'] = "Добавляйте больше фотографии и изображения!";
    $TEXT['label-settings-main-section-title'] = "Основные";
    $TEXT['label-settings-main-section-sub-title'] = "Введите свое имя, ваш пол, дату рождения и т.д.";
    $TEXT['label-settings-password-sub-title'] = "Введите свой старый пароль, а затем новый пароль.";
    $TEXT['label-settings-deactivation-sub-title'] = "Введите свой текущий пароль.";

    $TEXT['nav-communities'] = "Сообщества";
    $TEXT['nav-profile'] = "Профиль";
    $TEXT['nav-friends'] = "Друзья";
    $TEXT['nav-messages'] = "Сообщения";
    $TEXT['nav-notifications'] = "Уведомления";
    $TEXT['nav-search'] = "Поиск";
    $TEXT['nav-settings'] = "Настройки";
    $TEXT['nav-logout'] = "Выход";
    $TEXT['nav-news'] = "Новости";

    $TEXT['nav-popular'] = "Популярное";
    $TEXT['nav-favorites'] = "Мне нравится";
    $TEXT['nav-guests'] = "Гости";
    $TEXT['nav-gallery'] = "Галерея";
    $TEXT['nav-home'] = "Главная";

    $TEXT['label-profile-report-block'] = "Что-то не так с этим аккаунтом? Сообщите нам!";

    // For version 3.4

    $TEXT['action-add-to-friends'] = "Добавить в друзья";
    $TEXT['action-remove-from-friends'] = "Удалить из друзей";
    $TEXT['action-accept-friend-request'] = "Принять запрос в друзья";
    $TEXT['action-cancel-friend-request'] = "Отклонить запрос в друзья";

    $TEXT['label-notify-request-to-friends'] = "хочет добавить Вас в друзья";

    $TEXT['action-accept'] = "Принять";
    $TEXT['action-reject'] = "Отклонить";

    $TEXT['label-error-permission'] = "Информация доступна только для друзей этого пользователя.";

    $TEXT['label-privacy'] = "Приватность";

    $TEXT['label-allow-show-friends'] = "Показывать список друзей только друзям";
    $TEXT['label-allow-show-photos'] = "Показывать фотографии только друзям";
    $TEXT['label-allow-show-videos'] = "Показывать видео только друзям";
    $TEXT['label-allow-show-gifts'] = "Показывать подарки только друзям";
    $TEXT['label-allow-show-info'] = "Показывать информацию профиля только друзям";

    $TEXT['label-allow-show-friends-desc'] = "Друзья";
    $TEXT['label-allow-show-photos-desc'] = "Фотографии";
    $TEXT['label-allow-show-videos-desc'] = "Видео";
    $TEXT['label-allow-show-gifts-desc'] = "Подарки";
    $TEXT['label-allow-show-info-desc'] = "Информация профиля";

    // For version 3.5

    $TEXT['label-for-friends'] = "Только для друзей";

    // For version 3.7

    $TEXT['page-referrals'] = "Рефералы";

    $TEXT['page-referrals-label-id'] = "Ваш User Id:";
    $TEXT['page-referrals-label-hint'] = "Приглашайте друзей и получайте Кредиты!";
    $TEXT['page-referrals-label-hint2'] = "Рефералы это люди которые зарегистрировались по Вашему приглашению.";

    $TEXT['label-user-id'] = "User ID";
    $TEXT['label-signup-invite'] = "User ID кто пригласил Вас (не обязательно)";

    // For version 4.1

    $TEXT['label-seen'] = "просмотрено";

    // For version 4.4

    $TEXT['page-news-description'] = "Здесь вы можете читать посты своих друзей и сообществ.";
    $TEXT['page-stream-description'] = "Здесь вы можете читать все посты из социальной сети.";
    $TEXT['page-favorites-description'] = "В этом разделе вы можете видеть посты, которые вам понравились раньше.";
    $TEXT['page-popular-description'] = "Здесь отображаются самые популярные посты.";

    $TEXT['page-communities'] = "Сообщества";
    $TEXT['page-communities-description'] = "Сообщества за которыми вы следуете.";
    $TEXT['page-managed-communities'] = "Управление";
    $TEXT['page-managed-communities-description'] = "Сообщества которые вы создали и управляете ими.";
    $TEXT['page-search-communities'] = "Поиск Сообществ";
    $TEXT['page-search-communities-description'] = "Введите имя сообщества для поиска";
    $TEXT['page-create-communities'] = "Создать новое сообщество";
    $TEXT['page-create-communities-description'] = "Заполните все необходимые поля, чтобы создать ваше сообщество.";

    $TEXT['label-community-verified'] = "Проверенное сообщество";
    $TEXT['label-community-followers'] = "Подписчики";

    $TEXT['tab-search-users'] = "Люди";
    $TEXT['tab-search-communities'] = "Сообщества";
    $TEXT['tab-search-facebook'] = "Facebook";
    $TEXT['tab-search-hashtags'] = "Хештеги";
    $TEXT['tab-search-nearby'] = "Люди рядом";

    $TEXT['tab-search-users-description'] = "Ищете друзей и знакомых! Введите имя, логин, email, страну или город для начала поиска.";
    $TEXT['tab-search-communities-description'] = "Введите имя сообщества для поиска.";
    $TEXT['tab-search-facebook-description'] = "Ищете здесь друзей и знакомых, с которыми вы дружите на Facebook.";
    $TEXT['tab-search-hashtags-description'] = "Введите хештег для поиска постов.";
    $TEXT['tab-search-nearby-description'] = "Люди, которые находятся в близости от Вас.";

    $TEXT['page-notifications'] = "Уведомления";
    $TEXT['page-notifications-description'] = "Здесь вы можете увидеть уведомления о лайках, друзьях, комментариях, подарках ...";

    $TEXT['action-go-to-chat'] = "Перейти в чат";

    // For version 4.5

    $TEXT['hint-item-android-version'] = "Опубликовано с приложения для Android";
    $TEXT['hint-item-ios-version'] = "Опубликовано с приложения для iOS";

    $TEXT['search-filters-show'] = "Показать расширенные фильтры поиска";
    $TEXT['search-filters-hide'] = "Спрятать расширенные фильтры поиска";
    $TEXT['search-filters-gender'] = "Пол";
    $TEXT['search-filters-all'] = "Все";
    $TEXT['search-filters-male'] = "Мужской";
    $TEXT['search-filters-female'] = "Женский";
    $TEXT['search-filters-online'] = "Онлайн";
    $TEXT['search-filters-active'] = "Последняя активность";
    $TEXT['search-filters-photo'] = "Фотография";
    $TEXT['search-filters-photo-filter'] = "Только с фотографией";
    $TEXT['search-filters-action-search'] = "Поиск";

    $TEXT['search-editbox-placeholder'] = "Введите ключевое слово";

    $TEXT['tab-friends-all'] = "Все Друзья";
    $TEXT['tab-friends-online'] = "Друзья Онлайн";
    $TEXT['tab-friends-inbox-requests'] = "Входящие запросы";
    $TEXT['tab-friends-outbox-requests'] = "Исходящие запросы";

    $TEXT['label-friends-online-sub-title'] = "Здесь вы можете видеть список друзей, которые сейчас в сети.";
    $TEXT['label-friends-inbox-requests-sub-title'] = "Здесь вы можете видеть людей, которые хотят с вам дружить.";
    $TEXT['label-friends-outbox-requests-sub-title'] = "Список людей, которым вы отправили запрос в друзья.";

    $TEXT['label-last-seen'] = "Последний визит";
    $TEXT['label-last-visit'] = "Последнее посещение";
    $TEXT['label-create-at'] = "Время создания";
    $TEXT['label-outbox-request'] = "Исходящий запрос";
    $TEXT['label-inbox-request'] = "Входящий запрос";

    $TEXT['page-friends-requests'] = "Запросы в друзья";
    $TEXT['page-chat'] = "Чат";

    $TEXT['action-clear-all'] = "Удалить все";
    $TEXT['action-clear'] = "Очистить";

    $TEXT['page-welcome'] = "Добро пожаловать";
    $TEXT['page-welcome-sub-title'] = "Спасибо за регистрацию!";

    $TEXT['page-welcome-message-1'] = "Загрузите фото!";
    $TEXT['page-welcome-message-2'] = "Вы можете Загрузите свое фото прямо сейчас!";
    $TEXT['page-welcome-message-3'] = "Фотография отображает вашу уникальность, индивидуальность и стиль.";
    $TEXT['page-welcome-message-4'] = "Загрузка фотографии не является обязательным требованием. Вы можете пропустить этот шаг и загрузить вашу фотографию позднее.";

    $TEXT['action-start'] = "Начать";

    // For version 4.6

    $TEXT['label-updated-profile-photo'] = "обновлена фотография профиля.";
    $TEXT['label-updated-cover-photo'] = "обновлено изображение обложки.";

    $TEXT['page-gdpr'] = "GDPR (Общий регламент по защите данных)"; //GDPR (General Data Protection Regulation) Privacy Rights
    $TEXT['footer-gdpr'] = "GDPR";

    $TEXT['label-cookie-message'] = "Мы используем файлы \"cookies\" на нашем веб-сайте для анализа трафика. Используя веб-сайт, вы соглашаетесь с нашими ";

    $TEXT['label-is-feeling'] = "есть чувство";

    // For version 4.9

    $TEXT['page-privacy'] = "Политика конфиденциальности";

    $TEXT['label-notify-profile-photo-reject'] = "отклонил вашу фотографию профиля.";
    $TEXT['label-notify-profile-photo-reject-subtitle'] = "Загрузите/измените фото профиля для повторного прохождения модерации.";
    $TEXT['label-notify-profile-cover-reject'] = "отклонил вашу обложку профиля.";
    $TEXT['label-notify-profile-cover-reject-subtitle'] = "Загрузите/измените обложку профиля для повторного прохождения модерации.";

    // For version 5.0

    $TEXT['label-comments'] = "Комментарии";
    $TEXT['label-reposts'] = "Репосты";
    $TEXT['label-create-post'] = "Создайте публикацию";
    $TEXT['label-search-filters'] = "Фильтры";
    $TEXT['label-profile-info'] = "Информация";

    $TEXT['action-like'] = "Нравится";
    $TEXT['action-yes'] = "Да";
    $TEXT['action-no'] = "Нет";

    $TEXT['msg-contact-promo'] = "Хотите связаться с %s? Присоединяйтесь!";

    $TEXT['label-allow-show-gallery'] = "Показывать галерею только друзьям";
    $TEXT['label-allow-show-gallery-desc'] = "Галерея";

    $TEXT['label-gallery-privacy'] = "Настройка приватности для галереи";
    $TEXT['label-gallery-comments-allow'] = "Я разрешаю комментировать мои фотографии и видео";

    $TEXT['dlg-confirm-block-title'] = "Заблокировать пользователя";
    $TEXT['msg-block-user-text'] = "Пользователь %s будет добавлен в Ваш черный список. Вы не будете получать от пользователя %s личные сообщения и другие уведомления. Вы подтверждаете свое действие?";

    $TEXT['label-chats'] = "Чаты";

    // For version 5.1

    $TEXT['action-activate'] = "Активировать";
    $TEXT['label-activated'] = "Активировано";

    $TEXT['action-buy-credits'] = "Купить кредиты";
    $TEXT['action-pay'] = "Купить";
    $TEXT['action-stripe-pay'] = "Купить через ";
    $TEXT['label-stripe'] = "Stripe";
    $TEXT['label-payments-for'] = "за";
    $TEXT['label-payments-credits'] = "Credits";
    $TEXT['label-payments-amount'] = "Цена";
    $TEXT['label-payments-description'] = "Описание";
    $TEXT['label-payments-date'] = "Дата";

    $TEXT['label-balance-not-enough'] = "На Вашем балансе недостаточно средств.";

    $TEXT['label-payments-credits-stripe'] = "Покупка кредитов через Stripe";
    $TEXT['label-payments-credits-android'] = "Покупка кредитов в приложении Android";
    $TEXT['label-payments-credits-ios'] = "Покупка кредитов в приложении iOS";
    $TEXT['label-payments-credits-admob'] = "Кредиты за просмотр рекламы";
    $TEXT['label-payments-send-gift'] = "Отправка подарка";
    $TEXT['label-payments-verified-badge'] = "Активация функции Verified Badge";
    $TEXT['label-payments-ghost-mode'] = "Активация функции Ghost Mode";
    $TEXT['label-payments-off-admob'] = "Отключение рекламы Admob в приложении";
    $TEXT['label-payments-registration-bonus'] = "Бонус за регистрацию";
    $TEXT['label-payments-referral-bonus'] = "Бонус за привлечение реферала";

    $TEXT['label-payments-success_added'] = "Кредиты зачислены на ваш баланс";

    $TEXT['label-payments-history'] = "История баланса";

    $TEXT['page-upgrades'] = "Модернизация";
    $TEXT['page-upgrades-desc'] = "Получите больше с активацией дополнительных функций!";

    $TEXT['label-upgrades-verified-badge'] = "Проверенный значок";
    $TEXT['label-upgrades-verified-badge-desc'] = "Покажите другим, что вы являетесь проверенным пользователем с этим значком";
    $TEXT['label-upgrades-ghost-mode'] = "Режим призрака";
    $TEXT['label-upgrades-ghost-mode-desc'] = "Просматривайте профили пользователей анонимно, пользователи не будут знать о Вашем посещении их профиля.";
    $TEXT['label-upgrades-off-admob'] = "Отключить рекламу";
    $TEXT['label-upgrades-off-admob-desc'] = "Надоело смотреть рекламу? Отключить ее!";

    // For version 5.2

    $TEXT['page-market'] = "Маркет";
    $TEXT['page-market-sub-title'] = "Здесь вы можете покупать и продавать товары.";

    $TEXT['page-products'] = "Мои товары";

    $TEXT['market-new-item-dlg-title'] = "Новое объявление";
    $TEXT['market-new-item-ad-title'] = "Заголовок объявления";
    $TEXT['market-new-item-ad-title-placeholder'] = "Введите заголовок вашего объявления";
    $TEXT['market-new-item-ad-desc'] = "Описание объявления";
    $TEXT['market-new-item-ad-desc-placeholder'] = "Сделайте детальное описание вашего продукта или услуги";
    $TEXT['market-new-item-ad-price'] = "Цена (цена в $, должна быть больше нуля)";

    $TEXT['market-new-item-button-title'] = "Создать объявление";
    $TEXT['market-new-item-promo-title'] = "Создайте объявление прямо сейчас!";
    $TEXT['market-new-item-promo-desc'] = "Продавайте и покупайте товары здесь и сейчас!";

    $TEXT['action-contact-seller'] = "Связаться с продавцом";

    $TEXT['action-access-mode-all'] = "Для всех";
    $TEXT['action-access-mode-friends'] = "Друзья";

    // For version 5.3

    $TEXT['action-message'] = "Написать сообщение";

    $TEXT['action-ok'] = "Ok";
    $TEXT['label-messages-not-allowed'] = "%s желает получать приватные сообщения только от друзей.";

    $TEXT['footer-privacy'] = "политика конфиденциальности";

    $TEXT['page-nearby'] = "Люди рядом";
    $TEXT['page-nearby-desc'] = "Смотрите кто рядом с Вами.";

    $TEXT['action-apply'] = "Применить";
    $TEXT['action-allow'] = "Разрешить";

    $TEXT['label-location-request'] = "Предоставьте доступ к Вашим геоданным, чтобы иметь возможность использовать эту функцию.";
    $TEXT['label-location-denied'] = "Разрешите доступ к вашим геоданным в настройках браузера.";
    $TEXT['label-location-unsupported'] = "Простите, но Ваш браузер не поддерживает функции гелокации.";

    $TEXT['label-distance'] = "Расстояние";

    $TEXT['page-privacy-settings'] = "Настройки конфиденциальности";

    $TEXT['label-menu-post'] = "Запись";
    $TEXT['label-menu-item'] = "Запись";
    $TEXT['label-menu-likes'] = "Лайки";
    $TEXT['label-menu-profile'] = "Профиль %s";
    $TEXT['label-menu-gallery'] = "Галерея %s";

    // For version 5.4

    $TEXT['label-chat-empty'] = "Чат пустой.";
    $TEXT['label-chat-empty-promo'] = "Начните переписку! Отправьте сообщение, наклейку, смайлик или картинку!";

    $TEXT['label-select-gift'] = "Выберите подарок";

    $TEXT['action-buy'] = "Купить кредиты";

    $TEXT['label-balance'] = "Баланс";
    $TEXT['label-you-balance'] = "Ваш баланс";

    $TEXT['label-gift-message-promo'] = "Здесь Вы можете добавить комментарий к Вашему подарку...";

    // For version 5.5

    $TEXT['page-explore'] = "Исследовать";

    $TEXT['action-explore'] = "Исследовать";

    $TEXT['main-page-promo-google-app'] = "%s идет, куда вы идете, так что вы можете создавать посты и общаться с друзьями в любое время, в любом месте. Оставайтесь на связи с друзьями с мобильного приложения %s. Доступно для Android.";
    $TEXT['main-page-promo-login'] = "%s - это социальная сеть с помощью которой Вы можете делиться интересными новостями, картинками и видеороликами с YouTube со своими друзьями и всеми людьми.";
    $TEXT['main-page-promo-explore'] = "%s дает Вам способность видеть полную ленту публикаций от пользователей и сообществ, искать людей, товары и сообщества без регистарции! Читайте посты, смотрите видео, ищите инофрмацию и иследуйте социальную сеть!";