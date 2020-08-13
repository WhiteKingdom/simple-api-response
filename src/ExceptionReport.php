<?php

namespace Whiteki\SimpleApiResponse;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ExceptionReport
{
    use ApiResponse;

    /**
     * @var Throwable
     */
    public $exception;
    /**
     * @var Request
     */
    public $request;

    /**
     * @var
     */
    protected $report;

    /**
     * ExceptionReport constructor.
     * @param Request $request
     * @param Throwable $exception
     */
    public function __construct(Request $request, Throwable $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    /**
     * @var array
     */
    //当抛出这些异常时，可以使用我们定义的错误信息与HTTP状态码
    //可以把常见异常放在这里
    public $doReport = [
        AuthenticationException::class       => ['未授权', 401],
        ModelNotFoundException::class        => ['该模型未找到', 404],
        AuthorizationException::class        => ['没有权限', 403],
        ValidationException::class           => [],
        UnauthorizedHttpException::class     => ['未登录或登录状态失效', 422],
        NotFoundHttpException::class         => ['没有找到该页面', 404],
        MethodNotAllowedHttpException::class => ['访问方式不正确', 405],
        QueryException::class                => ['查询参数错误', 400],
        MassAssignmentException::class       => ['批量分配异常', 422],
        ThrottleRequestsException::class     => ['太多请求', 429],
    ];

    public function register($className, callable $callback)
    {
        $this->doReport[$className] = $callback;
    }

    /**
     * @return bool
     */
    public function shouldReturn()
    {
        foreach (array_keys($this->doReport) as $report) {
            if ($this->exception instanceof $report) {
                if ($this->exception->getCode() && $report !== QueryException::class) {
                    $this->doReport[$report] = [$this->exception->getMessage(), $this->exception->getCode()];
                }
                $this->report = $report;
                return true;
            }
        }

        return false;
    }

    /**
     * @param Throwable $e
     * @return static
     */
    public static function make(Throwable $e)
    {
        return new static(\request(), $e);
    }

    /**
     * @return mixed
     */
    public function report()
    {
        if ($this->exception instanceof ValidationException) {
            $message = [];
            $errors = $this->exception->errors();
            array_walk_recursive($errors, function ($v) use (&$message) {
                $message[] = $v;
            });
            return $this->failed($message, $this->exception->status);
        }
        $message = $this->doReport[$this->report];
        return $this->failed($message[0], $message[1]);
    }

    public function prodReport()
    {
        return $this->failed('服务器错误', '500');
    }
}
