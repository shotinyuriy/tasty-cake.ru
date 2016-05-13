<?php
	const home_href = "http://tasty-cake.ru/";

	class StringUtils {
		const menu_img_dir = 'menu_img';

		public static function convert( $var, $type ) {
			switch( $type ) {
				case 'int':
					return ( int ) strip_tags( $var );
				break;
				case 'double':
					return ( double ) strip_tags( $var );
				break;
				case 'string':
					$string = ( string ) trim( htmlspecialchars( $var ) );
					$string = strlen( $string ) > 0 ? $string : null;
					return $string;
				break;
				case 'money':
					$var = round( $var, 2 );
					$string = ( string )$var;
					$string = str_replace(".", ",", $string);
					$string = $string." руб.";
					return $string;
				break;
				default:
					return null;
				break;
			}
		}



		public static function rus2translit( $string ) {

		    $converter = array(

		        'а' => 'a',   'б' => 'b',   'в' => 'v',

		        'г' => 'g',   'д' => 'd',   'е' => 'e',

		        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',

		        'и' => 'i',   'й' => 'y',   'к' => 'k',

		        'л' => 'l',   'м' => 'm',   'н' => 'n',

		        'о' => 'o',   'п' => 'p',   'р' => 'r',

		        'с' => 's',   'т' => 't',   'у' => 'u',

		        'ф' => 'f',   'х' => 'h',   'ц' => 'c',

		        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',

		        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',

		        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',



		        'А' => 'A',   'Б' => 'B',   'В' => 'V',

		        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',

		        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',

		        'И' => 'I',   'Й' => 'Y',   'К' => 'K',

		        'Л' => 'L',   'М' => 'M',   'Н' => 'N',

		        'О' => 'O',   'П' => 'P',   'Р' => 'R',

		        'С' => 'S',   'Т' => 'T',   'У' => 'U',

		        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',

		        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',

		        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',

		        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',

		    );

		    return strtr( $string, $converter );

		}

		public static function str2id( $str ) {

		    // переводим в транслит

		    $str = self::rus2translit( $str );

		    // в нижний регистр

		    $str = strtolower( $str );

		    // заменям все ненужное нам на "-"

		    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);

		    // удаляем начальные и конечные '-'

		    $str = trim($str, "-");

		    return $str;

		}

		public static function str2url( $str ) {

		    // переводим в транслит

		    $str = self::rus2translit( $str );

		    // в нижний регистр

		    $str = strtolower( $str );

		    // заменям все ненужное нам на "-"

		    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);

		    // удаляем начальные и конечные '-'

		    $str = trim($str, "-");

		    return $str;

		}
	}

	class FileUtils {

		public static function saveFile( $id, $folder = 'menu-img' ) {
			$file_results = "";

			$image_url = null;
			if( $_FILES["image_url"]["error"] == 4 ) {
				// no file
			} elseif( $_FILES["image_url"]["error"] > 0 ) {
				$file_results .= 'No file Uploaded or invalid file';
				$file_results .= 'Error Code: '.$_FILES["image_url"]["error"];
			} else {
				$file_results .= 'Upload: '.$_FILES["image_url"]["name"]."<br>"
					.'Type: '.$_FILES["image_url"]["type"].'<br>'
					.'File Size: '. round( $_FILES["image_url"]["size"] / 1024, 2 ) .' Килобайт <br>'
					.'Tmp Name: '.$_FILES["image_url"]["tmp_name"];

				$ext = ".jpg";

				$match = preg_match('/\..{1,5}$/', $_FILES["image_url"]["name"], $found);

				echo 'matches: '.$match;
				if( isset( $found ) ) {
					$ext = $found[0];
					echo $ext;
				}

				$filename = $_FILES["image_url"]["tmp_name"];
				$destination = $_SERVER[ 'DOCUMENT_ROOT' ]."/$folder/".$id.$ext;

				$file_results .= 'Расширение '.$ext.'<br>';
				$file_results .= 'Файл перемещен в '.$destination.'<br>';

				move_uploaded_file($filename, $destination);

				$image_url = $id.$ext;
			}

			echo $file_results;

			return $image_url;
		}
	}

	class NavigationUtils {

        public static function print_pagination($page_count, $page_id, $href)
        { ?>
            <div class='row'>
                <div class="col-lg-12">
                        <ul class="pagination">
                            <? for ($i = 1; $i <= $page_count; $i++) {
                                if ($i == $page_id) { ?>
                                    <li class="active">
                                        <a href="#"><?= $i ?></a>
                                    </li>
                                <? } else { ?>
                                    <li>
                                        <a href='<?= $href ?>?pageId=<?= $i ?>'><?= $i ?></a>
                                    </li>
                                <? }
                            } ?>
                        </ul>
                </div>
            </div>
        <? }
	}
?>