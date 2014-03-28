<?php 
/**
 * 二叉树
 *
 * @usage
 *  +BIGIN_EXAMPLE
 *  //...
 *  $str = 'ABC##D##E#F##';
 *  $Cont = new Cont($str);
 *  $BiTree = new BiTree();
 *  $BiTree -> createBiTree($Cont);
 *  $root = $BiTree -> root();
 *  echo "<pre>";
 *  print_r($root);
 *  echo "</pre>";
 *  $BiTree -> preOrderTraverse();
 *  
 *  $node = $BiTree -> find('E');
 *  $parent = $BiTree -> parent($node);
 *  $lS = $BiTree -> rightSibling($lC);
 *  echo "<pre>";
 *  print_r($lS);
 *  echo "</pre>";
 *  $child = new Node('NEWCHILD');
 *  $flag = $BiTree -> insertChild($lS,0,$child);
 *  if($flag) {
 *      echo "<pre>";
 *      print_r($lS);
 *      echo "</pre>";
 *  }
 *  $flag = $BiTree -> deleteChild($c,1);
 *  echo "<pre>";
 *  print_r($lS);
 *  echo "</pre>";
 *
 *  //...
 *  +END_EXAMPLE
 *
 * @issue
 *  1.该程序主要通过递归实现遍历，运行效率相对较低。
 *    如有需要可通过使用栈和队列的方式替换递归遍历。
 *  2.方法createBitree()仅供使用字符串建立二叉树。
 *    中间使用到cont类，模拟终端字符输入。 
 *  3.方法find()可以根据实际需要扩展。
 * 
 * @author wang.delong
 * @since 20140326
 */
class BiTree {
    //根
    private $root;
    //临时节点
    private $node;
    //是否继续递归
    private $flag = true;
    
    public function __construct() {
	$this->initBiTree();
    }
    
    /**
     * @desc 构造空二叉树
     */
    public function initBiTree() {
        $this->root = null;
    }

    /**
     * @desc 销毁二叉树
     */
    public function destroyBiTree() {
	unset($this->root);
    }

    
    /**
     * @desc 按先序构造二叉树
     */
    public function createBiTree(&$Cont) {
	$this->_createBiTree($Cont);
    }
    
    protected function _createBiTree(&$Cont) {	
	//根据模拟输入类获取一个字符
	$char = $Cont->getChar();
	if(empty($char)) {return;}

	if($char == '#') {
	    //根据标记定位置
	    $node = null;
	} else {
	    //先序创建
	    $node = new Node($char);
	    $node->lChild = $this->_createBiTree($Cont);
	    $node->rChild = $this->_createBiTree($Cont);	
	}

	return $this->root = $node;
    }


    /**
     * @desc 清空二叉树
     * @param Node $node
     * @return void
     */
    public function clearBiTree() {
	if(isset($this->root)) {
	    unset($this->root);
	    $this->initBiTree();
	}
    }

    /**
     * @desc 判断二叉树是否为空
     * @return void
     */
    public function biTreeEmpty() {
	if(isset($this->root)) {
	    return $this->root == null;
	}
    }

    /**
     * @desc 返回二叉树的深度
     * @return integer
     */
    public function biTreeDepth() {
	if(isset($this->root)) {
	    return $this->_biTreeDepth($this->root);
	}
    }
    
    protected function _biTreeDepth($node) {
	if($node == null) {
	    return 0;
	}
	$lDepth = $this->_biTreeDepth($node->lChild);
	$rDepth = $this->_biTreeDepth($node->rChild);
	return $lDepth>$rDepth ? ($lDepth+1) : ($rDepth+1);
    }

    /**
     * @desc 获取根
     */
    public function root() {
	return isset($this->root) ? $this->root : null;
    }

    /**
     * @desc 获取节点的值
     * @param Node $node
     * @return mixed
     */
    public function value($node) {
	if(isset($this->root)) {
	    return $node->data;
	} else {
	    return null;
	}
    }

    /**
     * @desc 查找节点
     * @param mixed $data
     * @return Node
     */
    public function find($data) {
	if(isset($this->root)) {
	    $this->search($this->root,$data);
	    $node = $this->getNode();
	    $this->clearNode();
	    $this->flag = true;
	    return $node;
	} else {
	    return null;
	}
    }

    /**
     * @desc 查找节点
     * @param Node $node 初始节点
     * @param mixed $data 
     * @return Node
     */
    protected function search($node,$data) {
	if($node != null && $this->flag == true) {
	    if($node->data == $data) {
		$this->flag = false;
		$this->setNode($node);
	    } 
	    $this->search($node->lChild,$data);
	    $this->search($node->rChild,$data);
	}
    }

    /**
     * @desc 给节点赋值
     */
    public function assign(&$node,$data) {
	$node->data = $data;
    }

    /**
     * @desc 返回节点双亲
     * @param Node $node
     * @return Node
     */
    public function parent($node) {
	$this->_parent($this->root,$node);
	$node = $this->getNode();
	$this->clearNode();
	$this->flag = true;
	return $node;
    }

    protected function _parent($parent,$node) {
	if($parent != null && $this->flag == true) {
	    if($parent->lChild == $node || $parent->rChild == $node) {
		$this->flag = false;
		$this->setNode($parent);
	    } 
	    $this->_parent($parent->lChild,$node);
	    $this->_parent($parent->rChild,$node);
	}
    }

    /**
     * @desc 返回节点左孩子
     * @param Node $node
     * @return Node
     */
    public function leftChild($node) {
	return $node->lChild;
    }

    /**
     * @desc 返回节点右孩子
     * @param Node $node
     * @return Node
     */
    public function rightChild($node) {
	return $node->rChild;
    }

    /**
     * @desc 返回节点左兄弟
     */
    public function leftSibling($node) {
	$lSibling = $this->leftChild($this->parent($node));
	if($lSibling == $node) {
	    return null; 
	} else {
	    return $lSibling;
	}
    }

    /**
     * @desc 返回节点右兄弟
     */
    public function rightSibling($node) {
	$rSibling = $this->rightChild($this->parent($node));
	if($rSibling == $node) {
	    return null;
	} else {
	    return $rSibling;
	}
    }

    /**
     * @desc 插入子树
     * @param Node $node 
     * @param int $lr 0:插入左子节点 1:插入右子节点
     * @param Node $child 子节点
     * @return boolean
     */
    public function insertChild($node,$lr,$child) {
	if($lr==0 && $node->lChild==null) {
	    $node->lChild = $child;
	    return true;
	} elseif($lr==1 && $node->rChild==null) {
	    $node->rChild = $child;
	    return true;
	} else {
	    return false;
	}
    }

    /**
     * @desc 删除子树
     * @param Node $node 
     * @param int 0:删除左子节点 1:删除右子节点
     * @return boolean
     */
    public function deleteChild($node,$lr) {
	if($lr == 0) {
	    $node->lChild = null;
	    return true;
	} elseif($lr == 1) {
	    $node->rChild = null;
	    return true;
	} else {
	    return false;
	}
    }

    /**
     * @desc 先序遍历
     * @return void
     */
    public function preOrderTraverse() {
	if(isset($this->root)) {
	    $this->_preOrderTraverse($this->root);
	}
    }

    protected function _preOrderTraverse($node) {
	if($node != null) {
	    $this->visit($node->data);
	    $this->_preOrderTraverse($node->lChild);
	    $this->_preOrderTraverse($node->rChild);
	}
    }

    /**
     * @desc 中序遍历
     * @return void 
     */
    public function inOrderTraverse() {
	if(isset($this->root)) {
	    $this->_inOrderTraverse($this->root);
	}
    }
    
    protected function _inOrderTraverse($node) {
	if($node != null) {
	    $this->_inOrderTraverse($node->lChild);
	    $this->visit($node->data);
	    $this->_inOrderTraverse($node->rChild);
	}
    }
    

    /**
     * @desc 后序遍历
     * @return void
     */
    public function postOrderTraverse() {
	if(isset($this->root)) {
	    $this->_postOrderTraverse($this->root);
	}
    }

    protected function _postOrderTraverse($node) {
	if($node != null) {
	    $this->_postOrderTraverse($node->lChild);
	    $this->_postOrderTraverse($node->rChild);
	    $this->visit($node->data);
	}
    }

    /**
     * @desc 层序遍历
     */
    public function levelOrderTraverse() {
	if(isset($this->root)) {
	    $depth = $this->biTreeDepth();
	    for($i=0;$i<$depth;$i++) {
		$this->_levelOrderTraverse($this->root,$i);
	    }
	}
    }

    protected function _levelOrderTraverse($root,$level) {
	if($root==null || $level<0) {
	    return;
	}
	if($level==0) {
	    $this->visit($root->data);
	}
	if($root->lChild != null) {
	    $this->_levelOrderTraverse($root->lChild,$level-1);
	} 
	if($root->rChild != null) {
	    $this->_levelOrderTraverse($root->rChild,$level-1);
	}
    } 

    /**
     * @desc 格式化输出
     * @param mixed 需要输出的数据
     * @return void
     */
    private function visit($data) {
	echo "<pre>";print_r($data);echo "</pre>";
    }

    private function getNode() {
	return $this->node;
    }

    private function setNode($node) {
	$this->node = $node;
    }

    private function clearNode() {
	$this->node = null;
    }


    public function __destruct() {
	$this->destroyBiTree();
    }

}


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


/**
 * 二叉树节点
 *
 * @usage 
 *   $node = new Node($data);
 *
 * @author wang.delong
 * @since 20140327
 */
class Node {
    
    public $data;    
    public $lChild = null;  
    public $rChild = null;  
    
    public function __construct($data=null) {
	$this->data = $data;
    }
}

?>