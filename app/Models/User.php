<?php

// Пространство имен для моделей приложения
namespace App\Models;

// Импорт классов (закомментирован, т.к. не используется)
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class User extends Authenticatable implements MustVerifyEmail

/**
 * Класс модели пользователя
 * 
 * Расширяет базовый класс Authenticatable для аутентификации
 * Связан с таблицей 'users' в базе данных
 */
// class User extends Authenticatable
{
    /**
     * Использование трейтов:
     * HasFactory - для создания фабрик модели
     * Notifiable - для отправки уведомлений
     * 
     * @use HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory, Notifiable;

    /**
     * Атрибуты, которые можно массово присваивать
     * 
     * @var list<string>
     */
    protected $fillable = [
        'name',      // Имя пользователя
        'email',     // Email пользователя
        'password',  // Пароль (будет хеширован)
        'role',      // Роль пользователя (admin/user)
    ];

    /**
     * Связь с моделью Order (один ко многим)
     * Пользователь имеет много заказов
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class); // Внешний ключ: user_id
    }

    /**
     * Проверка, является ли пользователь администратором
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin'; // true если роль admin
    }

    /**
     * Атрибуты, которые должны быть скрыты при сериализации
     * (например, при преобразовании в JSON/массив)
     * 
     * @var list<string>
     */
    protected $hidden = [
        'password',       // Пароль не должен быть виден
        'remember_token', // Токен запоминания
    ];

    /**
     * Получение атрибутов, которые должны быть приведены к типам
     * Определяет преобразования типов для определенных полей
     * 
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Дата верификации email
            'password' => 'hashed',            // Пароль хранится как хеш
        ];
    }
}