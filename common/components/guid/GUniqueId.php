<?php
namespace common\components\guid;
use yii\base\Component;

/**
 * Generate unique id that can be used for primary key
 */
class GUniqueId extends Component {

    /**
     * Using uniqid() function
     * @param string $prefix
     * @param bool $entropy
     * @param bool $hash
     * @return string
     */
    public function basic($prefix = '', $entropy = true, $hash = false) {
        $id = uniqid($prefix, $entropy);
        return $hash ? md5($id) : $id;
    }

    public function short($prefix = '') {
        $id = uniqid(null, true);
        return md5($prefix . $id);
    }
    
    /**
     * Generate unique using current time + IP
     * @return mixed
     */
    public function byTimeAndIp() {
        $timeStamp = date('Ymdhis');
        $ip = $_SERVER['REMOTE_ADDR'];
        $id = "{$timeStamp}-{$ip}";
        return str_replace('.', '', $id);
    }

    /**
     * Generate custom length unique id
     * @param int $length
     * @return string
     */
    public function byLength($length = 10) {
        $id = crypt(uniqid(rand(), 1));
        $id = strip_tags(stripslashes($id));
        $id = str_replace('.', '', $id);
        $id = strrev(str_replace('/', '', $id));
        return substr($id, 0, $length);
    }

    /**
     * Generate XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX style unique id,
     * (8 letters)-(4 letters)-(4 letters)-(4 letters)-(12 letters)
     */
    public function guid() {
        $s = strtoupper(md5(uniqid(rand(), true)));
        return
                substr($s, 0, 8) . '-' .
                substr($s, 8, 4) . '-' .
                substr($s, 12, 4) . '-' .
                substr($s, 16, 4) . '-' .
                substr($s, 20);
    }

}
