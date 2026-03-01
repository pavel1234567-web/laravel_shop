<?php

// Пространство имен для ресурса категорий
namespace App\Filament\Resources;

// Импорт классов
use App\Filament\Resources\CategoryResource\Pages; // Страницы ресурса категорий
use App\Models\Category; // Модель категории
use Filament\Forms; // Компоненты форм
use Filament\Forms\Form; // Класс формы
use Filament\Resources\Resource; // Базовый класс ресурса
use Filament\Tables; // Компоненты таблиц
use Filament\Tables\Table; // Класс таблицы
use Illuminate\Support\Str; // Вспомогательный класс для строк

/**
 * Класс ресурса для управления категориями в админ-панели
 */
class CategoryResource extends Resource
{
    // Модель, связанная с ресурсом
    protected static ?string $model = Category::class;
    
    // Иконка в навигации
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    // Название в навигационном меню
    protected static ?string $navigationLabel = 'Категории';
    
    // Название модели в единственном числе
    protected static ?string $modelLabel = 'Категория';
    
    // Порядок сортировки в навигации
    protected static ?int $navigationSort = 2;

    /**
     * Определяет форму создания/редактирования категории
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Поле ввода названия
            Forms\Components\TextInput::make('name')
                ->label('Название')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                    $set('slug', Str::slug($state))), // Автоматическая генерация slug

            // Поле ввода URL-идентификатора
            Forms\Components\TextInput::make('slug')
                ->label('Slug (URL)')
                ->required()
                ->unique(ignoreRecord: true), // Уникальность значения

            // Поле ввода описания
            Forms\Components\Textarea::make('description')
                ->label('Описание')
                ->rows(3)
                ->columnSpanFull(), // На всю ширину
        ]);
    }

    /**
     * Определяет таблицу со списком категорий
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ID категории
                Tables\Columns\TextColumn::make('id')
                    ->label('#')->sortable(),
                
                // Название категории
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')->searchable()->sortable(),
                
                // Slug категории
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')->color('gray'),
                
                // Количество товаров в категории
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Товаров')
                    ->counts('products') // Подсчет связанных товаров
                    ->badge()->color('primary'),
                
                // Дата создания
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')->dateTime('d.m.Y')->sortable(),
            ])
            ->filters([]) // Фильтры не используются
            ->actions([
                Tables\Actions\EditAction::make(), // Кнопка редактирования
                Tables\Actions\DeleteAction::make(), // Кнопка удаления
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), // Массовое удаление
                ]),
            ]);
    }

    /**
     * Определяет страницы ресурса и их маршруты
     */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'), // Список категорий
            'create' => Pages\CreateCategory::route('/create'), // Создание категории
            'edit'   => Pages\EditCategory::route('/{record}/edit'), // Редактирование
        ];
    }
}