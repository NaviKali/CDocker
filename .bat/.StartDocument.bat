@echo off
title Tank文档启动终端
chcp 65001
cd document/view

:fun_main
    @REM 第三方库名称
    set name = node_modules
    @REM 获取当前路径
    set curdir = %cd%
    @REM 验证是否 npm install ?
    for /f %%i in ('dir /b "%curdir%"') do (
        echo %%i | findstr %name% == null && (
            @REM 如果没有则安装依赖
            npm i
            echo 第三方依赖已安装，请重新启动!
            pause
            goto:eof
        )
        echo %%i | findstr %name% > null && (
            @REM 如果存在第三方库，则启动Vue+Vite框架
            npm run dev
            pause
            goto:eof
        )
    )
    pause
