<?php

namespace Whiteki\SimpleApiResponse\Exceptions;

use Exception;
use Whiteki\SimpleApiResponse\ExceptionReport;
use App\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\Response|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        $accepts = $request->getAcceptableContentTypes();
        if (in_array('application/json', $accepts)) {
            // 将方法拦截到自己的ExceptionReport
            $reporter = ExceptionReport::make($exception);
            if ($reporter->shouldReturn()) {
                return $reporter->report();
            }
            if (env('APP_DEBUG')) {
                //开发环境，则显示详细错误信息
                return parent::render($request, $exception);
            } else {
                //线上环境,未知错误，则显示500
                return $reporter->prodReport();
            }
        }
        return parent::render($request, $exception);
    }
}
