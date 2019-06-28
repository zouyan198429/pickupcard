<?php
namespace App\Exceptions;
use App\Services\Request\API\Sites\APIRunBuyRequest;
use App\Services\Common;
use Throwable;
class ExportException extends \Exception
{
    protected $message;
    protected $code;
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = $message;
        $this->code = $code;
    }

    public function report()
    {
        // 这里自定义发生异常发生时要额外做的事情
        // 比如发邮件通知管理员
        //
    }

    public function render()
    {
        // 这里需要给浏览器或者API返回必要的通知信息
        // 可以是json 结构, 一般是针对API调用的
        // 也可以渲染一个网页, 一般是针对浏览器访问的页面
        // 也可以直接重定向到其他网页
        // return response()->json(['status' => 200, 'message' => $this->message], 503);
        // return errorJson($this->message, null ,['code' => $this->code]);
        if(isAjax() || in_array($this->code,[2,3])){
            return ajaxDataArr(0, ['code' => $this->code], $this->message);
        }else{
            $server = $_SERVER;
            $httpHost = $server['HTTP_HOST'] ?? '';
            return redirect(Common::urlRedirect($httpHost, 2));
            // return redirect('login');
        }
        // return ajaxDataArr(0, ['code' => $this->code], $this->message);
    }
}