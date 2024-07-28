<?php

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\RouteCollectorProxyInterface as Proxy;
use Takemo101\CmsTool\HookTags;

hook()->on(
    // ActiveThemeRouteRegistered は、選択中テーマのルートが登録された後に呼ばれるフックです。
    // 選択中テーマに設定しているプリセットに応じたルートが事前に登録されます。
    // テーマ独自のルートを登録するためには、このフック処理を使ってください。
    HookTags::ActiveThemeRouteRegistered,
    fn (Proxy $proxy) => $proxy
        // この例では、``/sample`` というパスにGETリクエストがあった場合に、404ページを表示するルートを登録しています。
        ->get(
            '/sample',
            fn (
                ServerRequestInterface $request,
            ) => throw new HttpNotFoundException($request),
        ),
);
