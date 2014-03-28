/**
 * 模拟逐个字符输入
 *
 * @usage
 *  $Cont = new Cont('ABC##D##E#F##');
 *  $char = $Cont-> getChar();
 *
 * @author wang.delong
 * @since 20140327
 */
class Cont {    
    
    private $str;

    public function __construct($str='') {
	$this->str = $str;
    }

    public function getChar() {
	$char = substr($this->str,0,1);
	$this->str = substr($this->str,1);
	return $char ? $char : '';
    }
}
