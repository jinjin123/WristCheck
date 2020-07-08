<?php
namespace Drupal\naviteam_twig\TwigExtension;
use Drupal\image\Entity\ImageStyle; 
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
Use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\Core\Datetime;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Database\Schema;
use Drupal\user\Entity\User;

class NaviteamTwig extends \Twig_Extension {

/**
* Generates a list of all Twig filters that this extension defines.
*/
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('substr_count', array($this, 'substrCount')),
            new \Twig_SimpleFilter('substr', array($this, 'substrString')),
            new \Twig_SimpleFilter('preg_replace', array($this, 'pregReplace')),
            new \Twig_SimpleFilter('str_replace', array($this, 'strReplace')),
            new \Twig_SimpleFilter('str_replace_position', array($this, 'StrReplacePosition')),
            new \Twig_SimpleFilter('str_replace_first', array($this, 'strReplaceFirst')),
            new \Twig_SimpleFilter('image_style_url', array($this, 'imageStyle')),
            new \Twig_SimpleFilter('image_create_url', array($this, 'imageUrl')),
            new \Twig_SimpleFilter('print_r', array($this, 'printerObj')),
            new \Twig_SimpleFilter('node_load', array($this, 'nodeLoad')),
            new \Twig_SimpleFilter('explode', array($this, 'explodeStr')),
            new \Twig_SimpleFilter('concatenation', array($this, 'concatenationStr')),
            new \Twig_SimpleFilter('get_uri_file', array($this, 'getURI')),
            new \Twig_SimpleFilter('get_list_term', array($this, 'getListTerm')),
            new \Twig_SimpleFilter('get_path_param', array($this, 'getPathParam')),
            new \Twig_SimpleFilter('trim_text', array($this, 'trimText')),
            new \Twig_SimpleFilter('trim_words', array($this, 'TrimWords')),
            new \Twig_SimpleFilter('arg', array($this, 'getArg')),
            new \Twig_SimpleFilter('strpos', array($this, 'strPos')),
            new \Twig_SimpleFilter('clean_string', array($this, 'cleanString')),
            new \Twig_SimpleFilter('tonumber', array($this, 'strToNumber')),
            new \Twig_SimpleFilter('mod', array($this, 'modNumber')),
            new \Twig_SimpleFilter('strip_tags', array($this, 'stripTags')),
            new \Twig_SimpleFilter('implode', array($this, 'implode')),
            new \Twig_SimpleFilter('term_load', array($this, 'termLoad')),
            new \Twig_SimpleFilter('user_load', array($this, 'userLoad')),
            new \Twig_SimpleFilter('text_link', array($this, 'getTextLink')),
            new \Twig_SimpleFilter('strtotime', array($this, 'getStrtotime')),
            new \Twig_SimpleFilter('embedyoutube', array($this, 'embedYoutube')),
            new \Twig_SimpleFilter('htmlencode', array($this, 'htmlencode')),
            new \Twig_SimpleFilter('unescape', array($this, 'unescape')),
        );
    }
    public function htmlencode($value)
    {
        return htmlentities($value);
    }
    public function unescape($value)
    {
        return html_entity_decode($value);
    }
    public function ExtractNumber($str)
    {
        preg_match_all('!\d+!', $str, $matches);
        return $matches;
    }
    public function views_embed_view($viewName, $viewId = 'default')
    {
        return views_embed_view($viewName, $viewId);
    }
    public function get_image_size($path, $att = 'height')
    {
        $data = getimagesize($path);
        $width = $data[0];
        $height = $data[1];
        if($att == 'width'){
            return $width;
        } else return $height;
    }
    public function get_list_term($vid)
    {
        $terms = \Drupal::service('entity_type.manager')
        ->getStorage("taxonomy_term")
        ->loadTree($vid, $parent = 0, $max_depth = NULL, $load_entities = FALSE);
        return $terms;
    }

    public function NextPreviousPost($nid, $nav = 'next', $nodetype) {
        $node = \Drupal\node\Entity\Node::load($nid);
        $created_time = $node->getCreatedTime();
        $query = \Drupal::entityQuery('node');
        if ($nav === 'next') {
            $comparison_opperator = '>';
            $sort = 'ASC';
        }
        elseif ($nav === 'prev') {
            $comparison_opperator = '<';
            $sort = 'DESC';
        }
        $next = $query->condition('created', $created_time, $comparison_opperator)
        ->condition('status', 1)
        ->condition('type', $nodetype)
        ->sort('created', $sort)
        ->range(0, 1)
        ->execute();
        //If this is not the youngest (or oldest) node
        if (!empty($next) && is_array($next)) {
            $next = array_values($next);
            $next = $next[0];
            $options = ['absolute' => TRUE];
            $link = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $next], $options);
        } else $link = '';
        return $link;
    }
    public function GetNodes($nodetype, $count, $offset = 0, $sort = 'DESC') {
        $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->sort('created', $sort)
        ->condition('type', $nodetype)
        ->range($offset, $count);
        $nids = $query->execute();
        return $nids;
    }
    public function StatisticsGet($nid)
    {
        if(db_table_exists('node_counter')) {
            $totalcount = $query = \Drupal::database()->select('node_counter', 'ncounter')
            ->fields('ncounter',['totalcount'])
            ->condition('nid', $nid)
            ->range(0, 1)
            ->execute()->fetchField();
            if(empty($totalcount)) $totalcount = 0;
        } else $totalcount = 'Not enabled module statistics';
       
        return $totalcount;
    }
    public function strlen($string)
    {
        return strlen($string);
    }
    public function pathinfo($path)
    {
        return pathinfo($path);
    }
    public function KeyArray($arr, $value)
    {
        $n = count($arr);
        for ($i=0; $i < $n; $i++) { 
            if($value == $arr[$i]) {
                $key = $i;
                break;
            } else $key = FALSE;
        }
        return $key;
    }

    public function GetDefaultImage($entity_type_id, $bundle, $field_name)
    {
        $field = \Drupal\field\Entity\FieldConfig::loadByName($entity_type_id, $bundle, $field_name);
        $default_image = $field->getSetting('default_image');
        $file = \Drupal::entityManager()->loadEntityByUuid('file', $default_image['uuid']);
        if($file) {
            $fileuri = $file->getFileUri();
        } else $fileuri = 0;
        return $fileuri;
    }
    public function StrReplacePosition($string, $old, $new, $position = 1) {
        $old_c = '/'.preg_quote($old, '/').'/';
        $str = preg_replace($old_c, $new, $string, $position);
        if($position > 1) {
            $new = '/'.preg_quote($new, '/').'/';
            for ($i=1; $i < $position; $i++) { 
                $str = preg_replace($new, $old, $str, 1);
            }
        }
        return  $str;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('views_embed_view', array($this, 'views_embed_view')),
            new \Twig_SimpleFunction('get_list_term', array($this, 'get_list_term')),
            new \Twig_SimpleFunction('next_prev_post', array($this, 'NextPreviousPost')),
            new \Twig_SimpleFunction('get_nodes', array($this, 'GetNodes')),
            new \Twig_SimpleFunction('statistics_get', array($this, 'StatisticsGet')),
            new \Twig_SimpleFunction('get_image_size', array($this, 'get_image_size')),
            new \Twig_SimpleFunction('extract_number', array($this, 'ExtractNumber')),
            new \Twig_SimpleFunction('strlen', array($this, 'strlen')),
            new \Twig_SimpleFunction('pathinfo', array($this, 'pathinfo')),
            new \Twig_SimpleFunction('node_position', array($this, 'node_position')),
            new \Twig_SimpleFunction('node_url_http', array($this, 'node_url_http')),
            new \Twig_SimpleFunction('get_key_arr', array($this, 'KeyArray')),
            new \Twig_SimpleFunction('get_default_image', array($this, 'GetDefaultImage')),
            new \Twig_SimpleFunction('str_replace_position', array($this, 'StrReplacePosition')),
            new \Twig_SimpleFunction('get_image_fid', array($this, 'GetImageFid')),
            new \Twig_SimpleFunction('term_field', array($this, 'termField')),
        ];
    }

    /**
    * Gets a unique identifier for this Twig extension.
    */
    public function getName() {
        return 'naviteam_twig.twig_extension';
    }

    /**
    * Replaces all instances of "animal" in a string with "plant".
    */
    // public static function strReplace($string) {
    //     return preg_replace('#[0-9 ]*#', '', $string);
    // }

    // public static function nodeLoad($nid) {
    //     return node_load($nid);
    // }
    public static function modNumber($nid, $n) {
        $s = $nid % $n;
        return ($s);
    }
     public static function substrString($string, $start = 0, $length =0) {
        return substr($string, $start, $length);
    }
    public static function GetImageFid($fid) {
    	$file = \Drupal\file\Entity\File::load($fid);
		$path = $file->getFileUri();
        return $path;
    }
    public static function node_url_http($nid) {
        $options = ['absolute' => TRUE];
        $url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $nid], $options);
        $url = $url->toString();
        return $url;
    }
    public static function node_position($nid,  $sort ='DESC') {
        $node = \Drupal\node\Entity\Node::load($nid);
        $nodetype = $node->getType();
        $query = \Drupal::entityQuery('node');
        if ($nav === 'ASC') {
            $comparison_opperator = '>';
            $sort = 'ASC';
        }
        elseif ($nav === 'DESC') {
            $comparison_opperator = '<';
            $sort = 'DESC';
        }
        $next = $query->condition('type', $nodetype)
        ->condition('status', 1)
        ->sort('created', $sort)
        ->execute();
        //If this is not the youngest (or oldest) node
        if (!empty($next) && is_array($next)) {
            $next = array_values($next);
            $n = count($next);
            for ($i=0; $i < $n; $i++) { 
                if($next[$i] == $nid) $counter = $i + 1;
            }
        } else $counter = 0;
        return $counter;
    }
    public static function stripTags($string, $tags ='') {
        if(empty($tags)) {
            return strip_tags($string);
        } else return strip_tags($string, $tags);
    }
    public static function strToNumber($string) {
        return (int) $string;
    }
    public static function cleanString($string) {
        return preg_replace('/[^0-9]/', '', $string);
    }
    public static function getArg($current_path, $n) {
        $path_args = explode('/', $current_path);
        return $path_args[$n];
    }
    public static function strPos($string, $find, $start = 0) {
        $s = strpos($string, $find, $start);
        return $s;
    }
    public static function trimText($input, $length, $ellipses = true, $strip_html = true) {
        if ($strip_html) {
            $input = strip_tags($input);
        }
        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }
        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);
        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= '...';
        }
        return $trimmed_text;
    }
    public static function TrimWords($string, $wordsreturned, $ellipses = true)
    {
      $retval = $string;
      $string = preg_replace('/(?<=\S,)(?=\S)/', ' ', $string);
      $string = str_replace("\n", " ", $string);
      $array = explode(" ", $string);
      if (count($array)<=$wordsreturned)
      {
        $retval = $string;
      }
      else
      {
        array_splice($array, $wordsreturned);
        if($ellipses) {
            $retval = implode(" ", $array)." ...";
        } else $retval = implode(" ", $array);
      }
      return $retval;
    }
    public static function explodeStr($string, $separator) {
        return explode($separator, $string);
    }
    public static function concatenationStr($string1, $string2, $separator = '') {
        $string = $string1.$separator.$string2;
        return  $string;
    }
    public static function substrCount($string, $sub) {
        return substr_count($string, $sub);
    }
    public static function printerObj($string) {
        return print_r($string);
    }
    public static function strReplace($string, $old, $new) {
        return str_replace($old, $new, $string);
    }
    public static function pregReplace($subject, $pattern, $replacement) {
        $pattern = '/'.preg_quote($pattern, '/').'/';
        return preg_replace($pattern, $replacement, $subject);
    }
    public static function strReplaceFirst($string, $from, $to, $n = 1) {
        $from = '/'.preg_quote($from, '/').'/';
        //$string = render($string);
        return preg_replace($from, $to, $string, $n);
    }
    public static function imageStyle($uri, $image_style) {
        $url = ImageStyle::load($image_style)->buildUrl($uri);
        return $url;
    }
    public function getURI($fid) {
        $file = File::load($fid);
        $path = $file->getFileUri();
        return $path;
    }
    public static function imageUrl($uri) {
        $url = file_create_url($uri);
        return $url;
    }
    public static function getPathParam($string) {
        if(isset($_GET[$string])) {
            $param = $_GET[$string];
        } else $param = '';
        return $param;
    }
    public static function getListTerm($volname) {
        $vids = Vocabulary::loadMultiple();
        $arr = '';
        foreach ($vids as $vid) {
            if ($vid->label() == $volname) {
                $container = \Drupal::getContainer();
                $terms = $container->get('entity.manager')->getStorage('taxonomy_term')->loadTree($vid->id());
                if (!empty($terms)) {
                    foreach($terms as $term) {
                        $arr .= '<div data-filter=".cat_'.$term->tid.'" class="cbp-filter-item">';
                        $arr .= $term->name.'</div>';
                    }
                }
                break;
            }
        }
        return $arr;
    }
    public static function nodeLoad($id) {
        $node = \Drupal\node\Entity\Node::load($id);
        return $node;
    }   
    public static function implode($separator = '', $arr) {
        return implode($separator, $arr);
    }
    public static function termLoad($tid) {
        $term = Term::load($tid);
        return $term;
    }
    public static function termField($tid, $field) {
        $term = Term::load($tid);
        return $term->get($field)->value;
    }
    public static function userLoad($uid) {
        $user = \Drupal\user\Entity\User::load($uid);
        return $user;
    }
    public static function getTextLink($string, $n = 1) {
        preg_match('/<a[^>]*>(.*?)<\/a>/i', $string, $matches);
        return $matches[$n];
    }
    public static function getStrtotime($date) {
        return strtotime($date);
    }
    public static function embedYoutube($url, $showinfo = 1, $controls = 1) {
        $vid = substr($url, strpos($url, '?v=') + 3 );
        if($showinfo == 0 and $controls == 0) {
            $src = $vid . '?showinfo=0&amp;controls=0';
        } elseif($showinfo == 0 and $controls == 1) {
            $src = $vid . '?showinfo=0';
        } elseif($showinfo == 1 and $controls == 0) {
            $src = $vid . '?controls=0';
        } else {
            $src = $vid;
        }
        $string = '<iframe frameborder="0" allowfullscreen="" src="http://www.youtube.com/embed/'.$src.'"></iframe>';
        return $string;
    }
}
?>