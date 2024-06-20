<?php

namespace Takemo101\CmsTool\Support\Toast;

enum ToastStyle: string
{
    case Success = 'success';
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
    case Error = 'error';

    /**
     * Check if it is a success style
     *
     * @return boolean
     */
    public function isSuccessStyle(): bool
    {
        return !$this->isErrorStyle();
    }

    /**
     * Check if it is an error style
     *
     * @return boolean
     */
    public function isErrorStyle(): bool
    {
        return $this === self::Error;
    }

    /**
     * Get toast message
     *
     * @return string
     */
    public function message(): string
    {
        return match ($this) {
            self::Success => '成功しました',
            self::Create => '追加しました',
            self::Update => '更新しました',
            self::Delete => '削除しました',
            self::Error => 'エラーが発生しました',
        };
    }
}
