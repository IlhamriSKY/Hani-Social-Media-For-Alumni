<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

	class language extends db_connect
    {

        private $language;

		public function __construct($dbo = NULL, $language = "en")
        {

			parent::__construct($dbo);

            $this->set($language);

		}

        public function timeAgo($time)
        {

            switch($this->get()) {

                case "id" :  {

                    $titles = array("menit","menit","menit");
                    $titles2 = array("jam","jam","jam");
                    $titles3 = array("hari","hari","hari");
                    $titles4 = array("bulan","bulan","bulan");
                    $about = " lalu";
                    $now = "kurang dari 1 menit lalu";
                    $more_three = "lebih dari tiga tahun yang lalu";
                    break;
                }

                case "ua" :  {

                    $titles = array("хвилину","хвилини","хвилин");
                    $titles2 = array("година","години","годин");
                    $titles3 = array("день","дні","днів");
                    $titles4 = array("місяць","місяці","місяців");
                    $about = " тому";
                    $now = "Тільки що";
                    $more_three = "понад три роки тому";
                    break;
                }

                case "ru" :  {

                    $titles = array("минуту","минуты","минут");
                    $titles2 = array("час","часа","часов");
                    $titles3 = array("день","дня","дней");
                    $titles4 = array("месяц","месяца","місяців");
                    $about = " назад";
                    $now = "Только что";
                    $more_three = "более 3х лет назад";
                    break;
                }

                default :  {

//                    $titles = array("minute","minutes","minutes");
//                    $titles2 = array("hour","hours","hours");
//                    $titles3 = array("day","days","days");
//                    $titles4 = array("month","months","months");
//                    $about = " ago";
//                    $now = "less than a minute ago";

                    $titles = array("m","m","m");
                    $titles2 = array("h","h","h");
                    $titles3 = array("d","d","d");
                    $titles4 = array("month","months","months");
                    $about = " ago";
                    $now = "just now";
                    $more_three = "more than 3 years ago";

                    break;
                }
            }

            $new_time = time();
            $origin_time = $time;
            $time = $new_time - $time;

            if($time < 60) return $now; else
            if($time < 3600) return language::declOfNum(($time-($time%60))/60, $titles).$about; else
            if($time < 86400) return language::declOfNum(($time-($time%3600))/3600, $titles2).$about; else
            if($time < 2073600) return language::declOfNum(($time - ($time % 86400)) / 86400, $titles3).$about; else
            if($time < 62208000) return language::declOfNum(($time - ($time % 2073600)) / 2073600, $titles4).$about; else return $more_three;
        }

        static function declOfNum($number, $titles)
        {
            $cases = array(2, 0, 1, 1, 1, 2);
            return $number.''.$titles[ ($number%100>4 && $number%100<20) ? 2 : $cases[($number%10<5) ? $number%10:5] ];
        }

        static function getLikes($LANG, $result, $type = "post")
        {

            if ( $result['myLike'] && $result['likesCount'] == 1 ) {

                //Вам это понравилось

                return $LANG['label-mylike']." ".$LANG['label-like'];

            } else if ( $result['myLike'] && $result['likesCount'] == 2 ) {

                //Вам и 1 другому это понравилось

                return $LANG['label-mylike']." ".$LANG['label-and']." <a href=\"/{$result['fromUserUsername']}/{$type}/{$result['id']}/people\">1 ".$LANG['label-mylike-user']."</a> ".$LANG['label-like'];

            } else if ( $result['myLike'] && $result['likesCount'] > 2 ) {

                //Вам и 2 другим это понравилось

                return $LANG['label-mylike']." ".$LANG['label-and']." <a href=\"/{$result['fromUserUsername']}/{$type}/{$result['id']}/people\">".--$result['likesCount']." ".$LANG['label-mylike-peoples']."</a> ".$LANG['label-like'];

            } else if ( !$result['myLike'] && $result['likesCount'] > 1 ) {

                //6 пользователям это понравилось

                return "<a href=\"/{$result['fromUserUsername']}/{$type}/{$result['id']}/people\">".$result['likesCount']." ".$LANG['label-like-peoples']."</a> ".$LANG['label-like'];

            } else if (!$result['myLike'] && $result['likesCount'] == 1) {

                //1 пользователю это понравилось

                return "<a href=\"/{$result['fromUserUsername']}/{$type}/{$result['id']}/people\">1 ".$LANG['label-like-user']."</a> ".$LANG['label-like'];

            } else {

                return "";
            }
        }

        public function set($language)
        {

            $this->language = $language;
        }

        public function get()
        {

            return $this->language;
        }
	}

?>