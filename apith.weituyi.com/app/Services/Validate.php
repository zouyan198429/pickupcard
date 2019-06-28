<?php
namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
/**
 * 验证类
 *
 */
//defined('InShopNC') or exit('Access Invalid!');
Class Validate{
    /**
     * 存放验证信息
     *
     * @var array
     */
    public $validateparam = array();
    /**
     * 验证规则
     *
     * @var array
     */
    private $validator = array(
        "email"=>'/^([.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\\.[a-zA-Z0-9_-])+$/',
        "phone"=>'/^(([0-9]{2,3})|([0-9]{3}-))?((0[0-9]{2,3})|0[0-9]{2,3}-)?[1-9][0-9]{6,7}(-[0-9]{1,4})?$/',
        "mobile"=>'/^1[0-9]{10}$/',
        "url"=>'/^http:(\\/){2}[A-Za-z0-9]+.[A-Za-z0-9]+[\\/=?%-&_~`@\\[\\]\':+!]*([^<>\"\"])*$/',
        "currency"=>'/^[0-9]+(\\.[0-9]+)?$/',
        "number"=>'/^[0-9]+$/',
        "zip"=>'/^[0-9][0-9]{5}$/',
        "qq"=>'/^[1-9][0-9]{4,8}$/',
        "integer"=>'/^[-+]?[0-9]+$/',
        "integerpositive"=>'/^[+]?[0-9]+$/',
        "double"=>'/^[-+]?[0-9]+(\\.[0-9]+)?$/',
        "doublepositive"=>'/^[+]?[0-9]+(\\.[0-9]+)?$/',
        "english"=>'/^[A-Za-z]+$/',
        "englishsentence"=>'/^[A-Za-z ]+$/',//英文语句[可以有空格]
        "englishnumber"=>'/^[A-Za-z0-9]+$/',//英文数字
        "chinese"=>'/^[\x80-\xff]+$/',
        "username"=>'/^[\\w]{3,}$/',
        "nochinese"=>'/^[A-Za-z0-9_-]+$/',
        'datatime'=>'/^\\d{4}[-](0?[1-9]|1[012])[-](0?[1-9]|[12][0-9]|3[01])(\\s+(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9]):(0?[0-9]|[1-5][0-9]))?$/',//匹配时间格式为2012-02-16或2012-02-16 23:59:59前面为0的时候可以不写
    );
    /**
     * 设置验证规则
     * @param array $validator
     */
    public function setValidator($validator = array()){
        $this->validator=$validator;
    }
    /**
     * 获得验证规则
     */
    public function getValidator(){
        return $this->validator;
    }
    /**
     * 获得验证规则,传递给控制器或视图使用;主要是给下标前面加 regex_
     */
    public function getValidatorArr(){
        $re_regex_arr = array();
        $validator_arr = $this->getValidator();
        foreach($validator_arr as $k=>$v){
            $re_regex_arr['regex_'.$k]=$v;
        }
        return $re_regex_arr;
    }

    /**
     * 验证数组中的值
     *
     * <code>
     * //使用示例
     * <?php
     *  require("commonvalidate.class.php");
     *	$a = new CommonValidate();
     *	$a->setValidate("344d",true,"","不可以为空");
     *	$a->setValidate("fdsfsfd",true,"Email","请填写正确的EMAIL");
     *	echo $a->validate();
     *
     *  //显示结果：
     *  请填写正确的EMAIL
     * ? >
     * </code>
     *
     * @param
     * @return string 字符串类型的返回结果
     */
    public function validate(){
        if (!is_array($this->validateparam)){//不是数组，则返回 false
            return false;
        }
        foreach($this->validateparam as $k=>$v){//遍历参数
            $judgeVal = $v['input'];

            if (!isset($v['require']) || $v['require'] == ""){//必填为空，则必填 require 赋值为 false
                $v['require'] = false;
            }
            // 判断是否必填
            if ($this->bn_is_empty($judgeVal) && $v['require'] == "true"){// $judgeVal == ""  [必填但是值已经为空]值为空，且必填时, 给 result 赋值 false ,否则为 true
                $this->validateparam[$k]['result'] = false;
            }else{
                $this->validateparam[$k]['result'] = true;
            }
            // 不为空，判断具体验证
            if ($this->validateparam[$k]['result'] && $judgeVal != ""){//result 值为true 且有值，才进行判断
                $temValidator = $v['validator'] ?? '';
                $temValidator = strtolower($temValidator);//validator 的值转为小写
                switch($temValidator){//根据validator进行判断
                    case "custom": //正则验证 validator=“custom”  regexp=""
                        $this->validateparam[$k]['result'] = $this->check($judgeVal,$v['regexp']);
                        break;
                    case "compare"://比较 validator=“compare”  operator="比较符" to="被比较值"
                        if ($v['operator'] != ""){//比较符不为空
                            eval("\$result = '" . $judgeVal . "'" . $v['operator'] . "'" . $v['to'] . "'" . ";" );
                            $this->validateparam[$k]['result'] = $result;
                        }
                        break;
                    case "length"://判断长度  validator=“length”  min="最小值" max="最大值"
                        //判断编码取字符串长度
                        $input_encode = mb_detect_encoding($judgeVal,array('UTF-8','GBK','ASCII',));//获得字符的编码
                        $input_length = mb_strlen($judgeVal,$input_encode);//获得长度
                        if (intval($v['min']) >= 0 && intval($v['max']) > intval($v['min'])){//最小值>=0 且 最大值>最小值
                            $this->validateparam[$k]['result'] = ($input_length >= intval($v['min']) && $input_length <= intval($v['max']));
                        }
                        else if (intval($v['min']) >= 0 && intval($v['max']) <= intval($v['min'])){//最小值>=0 且 最大值<=最小值 ，按 等于最小值(==)判断
                            $this->validateparam[$k]['result'] = ($input_length == intval($v['min']));
                        }
                        break;

                    case "range"://范围 validator=“range” min="最小值" max="最大值"
                        if (intval($v['min']) >= 0 && intval($v['max']) > intval($v['min'])){//最小值>=0 且 最大值>最小值
                            $this->validateparam[$k]['result'] = (intval($judgeVal) >= intval($v['min']) && intval($judgeVal) <= intval($v['max']));
                        }
                        else if (intval($v['min']) >= 0 && intval($v['max']) <= intval($v['min'])){//最小值>=0 且 最大值<=最小值 ，按 等于最小值(==)判断
                            $this->validateparam[$k]['result'] = (intval($judgeVal) == intval($v['min']));
                        }else if( intval($v['max']) > intval($v['min'])){// 最大值>最小值
                            $this->validateparam[$k]['result'] = (intval($judgeVal) >= intval($v['min']) && intval($judgeVal) <= intval($v['max']));
                        }
                        break;
                    default://默认 validator="" $this->validator的下标值
                        $selValidator = $this->validator[$temValidator] ?? '';
                        $this->validateparam[$k]['result'] = $this->check($judgeVal,$selValidator);
                }
            }
        }
        $error = $this->getError();
        $this->validateparam = array();//清空
        return $error;
    }

    /**
     * 正则表达式运算
     *
     * @param string $str 验证字符串
     * @param string $validator 验证规则
     * @return bool 布尔类型的返回结果 true成功 false失败
     */
    private function check($str='',$validator=''){
        if ($str != "" && $validator != ""){//不为空,且 规则不为空
            if (preg_match($validator,$str)){//验证成功
                return true;
            }else{//失败
                return false;
            }
        }
        return true;
    }

    /**
     * 需要验证的内容
     *
     * @param array $validateparam array("input"=>"","require"=>"","validator"=>"","regexp"=>"","operator"=>"","to"=>"","min"=>"","max"=>"",message=>"")
     * input要验证的值
     * require是否必填，true是必填false是可选
     * validator验证的类型:
     * 其中Compare，Custom，Length,Range比较特殊。
     * Compare是用来比较2个字符串或数字，operator和to用来配合使用，operator是比较的操作符(==,>,<,>=,<=,!=)，to是用来比较的字符串；
     * Custom是定制验证的规则，regexp用来配合使用，regexp是正则表达试；
     * Length是验证字符串或数字的长度是否在一顶的范围内，min和max用来配合使用，min是最小的长度，max是最大的长度，如果不写max则被认为是长度必须等于min;
     * Range是数字是否在某个范围内，min和max用来配合使用。
     * 值得注意的是，如果需要判断的规则比较复杂，建议直接写正则表达式。
     *
     * 单个加入要验证的内容
     * $validateparam 一个一维数组,格式:array("input"=>$_POST["ac_name"], "require"=>"true", "message"=>$lang['article_class_add_name_null']),//分类名称不能为空
     * @return void
     */
    public function setValidate($validateparam){
        $validateparam["result"] = true;
        $this->validateparam = array_merge($this->validateparam,array($validateparam));
    }

    /**
     * 得到验证的错误信息
     *
     * @param
     * @return string 字符串类型的返回结果
     */
    private function getError(){
        foreach($this->validateparam as $k=>$v){
            if ($v['result'] == false){//返回错误提示
                return $v['message'];
            }
        }
        return null;
    }
    /**
     *
     * @brief 是否为 ""、NULL、FALSE、array()、var $var、未定义; 以及没有任何属性的对象都将被认为是空的
     * 、0、"0" 认为是不为空
     * @details
     * @param string $record 需要判断的数据
     * @return boolean  为空返回true,非空返回false
     *
     */
    private function bn_is_empty($record){
        if(!isset($record)){//变量不存在
            //echo '变量不存在';
            return TRUE;
        }
        //为空，排除是0的情况
        if(empty($record)){
            if(($record === 0) || ($record === '0')){
                // echo '变量为0';
                return FALSE;
            }else{
                // echo '变量为空';
                return TRUE;
            }
        }else{
            //echo '变量不为空';
            return FALSE;
        }
    }
}