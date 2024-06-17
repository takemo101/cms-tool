<?php

namespace Takemo101\CmsTool\Support\Toast;

enum ToastStyle: string
{
    case Success = 'success';
    case Created = 'created';
    case Updated = 'updated';
    case Deleted = 'deleted';
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
            self::Created => '追加しました',
            self::Updated => '更新しました',
            self::Deleted => '削除しました',
            self::Error => 'エラーが発生しました',
        };
    }
}
