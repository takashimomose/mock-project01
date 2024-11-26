<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail // ← MustVerifyEmailインターフェイスを実装
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'address',
        'building',
        'profile_image',
        'email_verified_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * プロフィール画像を保存または更新
     *
     * @param \Illuminate\Http\UploadedFile|null $file
     * @param string|null $existingPath
     * @return string|null
     */
    public function saveProfileImage($file, $existingPath = null)
    {
        if ($file) {
            // 新しい画像を保存し、古い画像を削除（必要なら）
            $path = $file->store('profile_images', 'public');

            // 必要に応じて古い画像を削除
            if ($existingPath && \Storage::disk('public')->exists($existingPath)) {
                \Storage::disk('public')->delete($existingPath);
            }

            return $path;
        }

        // 新しい画像がない場合、既存のパスを保持
        return $existingPath;
    }

    /**
     * プロフィール情報を更新
     *
     * @param array $data
     * @return bool
     */
    public function updateProfile(array $data)
    {
        return $this->update($data);
    }

    // 配送先情報を取得するメソッド
    public function getShippingInfo()
    {
        return $this->select('id', 'name', 'postal_code', 'address', 'building')
            ->first();
    }
}
