<?php

// Пространство имен для ресурса товаров
namespace App\Filament\Resources;

// Импорт классов
use App\Filament\Resources\ProductResource\Pages; // Страницы ресурса товаров
use App\Models\Category; // Модель категории
use App\Models\Product; // Модель товара
use Filament\Forms; // Компоненты форм
use Filament\Forms\Form; // Класс формы
use Filament\Resources\Resource; // Базовый класс ресурса
use Filament\Tables; // Компоненты таблиц
use Filament\Tables\Table; // Класс таблицы
use Illuminate\Support\Str; // Вспомогательный класс для строк

/**
 * Класс ресурса для управления товарами в админ-панели
 */
class ProductResource extends Resource
{
    // Модель, связанная с ресурсом
    protected static ?string $model = Product::class;
    
    // Иконка в навигации
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    // Название в навигационном меню
    protected static ?string $navigationLabel = 'Товары';
    
    // Название модели в единственном числе
    protected static ?string $modelLabel = 'Товар';
    
    // Порядок сортировки в навигации
    protected static ?int $navigationSort = 1;

    /**
     * Определяет форму создания/редактирования товара
     */
    public static function form(Form $form): Form
    {
        return $form->schema([

            // Секция основной информации
            Forms\Components\Section::make('Основная информация')
                ->schema([
                    // Поле названия товара
                    Forms\Components\TextInput::make('name')
                        ->label('Название')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, Forms\Set $set) =>
                            $set('slug', Str::slug($state) . '-' . time())), // Генерация уникального slug

                    // Поле URL-идентификатора
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug (URL)')
                        ->required()
                        ->unique(ignoreRecord: true), // Уникальность

                    // Выбор категории
                    Forms\Components\Select::make('category_id')
                        ->label('Категория')
                        ->relationship('category', 'name') // Связь с категорией
                        ->preload() // Предзагрузка
                        ->searchable() // Поиск
                        ->required(),

                    // Описание товара
                    Forms\Components\Textarea::make('description')
                        ->label('Описание')
                        ->rows(4)
                        ->columnSpanFull(), // На всю ширину
                ])->columns(2), // Две колонки

            // Секция загрузки изображения
            Forms\Components\Section::make('Изображение')
                ->schema([
                    // Компонент загрузки файла
                    Forms\Components\FileUpload::make('image')
                        ->label('Фото товара')
                        ->image() // Только изображения
                        ->disk('public') // Диск для хранения
                        ->directory('products') // Папка products
                        ->visibility('public') // Публичный доступ
                        ->maxSize(2048) // Макс. размер 2MB
                        ->acceptedFileTypes([ // Разрешенные типы
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/webp',
                        ])
                        ->previewable(false) // Без предпросмотра
                        ->afterStateHydrated(fn($component) => $component->state([])) // Сброс фото
                        ->columnSpanFull(),
                ]),

            // Секция цены и склада
            Forms\Components\Section::make('Цена и склад')
                ->schema([
                    // Цена товара
                    Forms\Components\TextInput::make('price')
                        ->label('Цена (₽)')
                        ->numeric() // Числовое значение
                        ->prefix('₽') // Префикс с символом рубля
                        ->required(),

                    // Старая цена
                    Forms\Components\TextInput::make('old_price')
                        ->label('Старая цена (₽)')
                        ->numeric()
                        ->prefix('₽'),

                    // Количество на складе
                    Forms\Components\TextInput::make('stock')
                        ->label('Кол-во на складе')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    // Статус активности
                    Forms\Components\Toggle::make('is_active')
                        ->label('Активен (виден на сайте)')
                        ->default(true), // По умолчанию активен
                ])->columns(2), // Две колонки
        ]);
    }

    /**
     * Определяет таблицу со списком товаров
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(10) // По 10 записей на странице
            ->paginationPageOptions([10, 25, 50]) // Варианты пагинации
            ->modifyQueryUsing(fn($query) => $query->with('category')) // Загрузка категории
            ->columns([
                // ID товара
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                // Изображение товара
                Tables\Columns\ImageColumn::make('image')
                    ->label('Фото')
                    ->disk('public')
                    ->width(80) // Ширина 80px
                    ->height(60) // Высота 60px
                    ->defaultImageUrl('https://via.placeholder.com/80x60?text=No+Image'), // Заглушка

                // Название товара
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable()
                    ->limit(30), // Ограничение длины

                // Категория товара
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория')
                    ->badge() // В виде бейджа
                    ->color('gray'),

                // Цена с форматированием
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->formatStateUsing(
                        fn($state) =>
                        number_format((float) $state, 0, '.', ' ') . ' ₽' // Формат "1 234 ₽"
                    )
                    ->sortable(),

                // Наличие на складе
                Tables\Columns\TextColumn::make('stock')
                    ->label('Склад')
                    ->sortable()
                    ->color(
                        fn($record) =>
                        $record->stock > 0 ? 'success' : 'danger' // Зеленый если есть, красный если нет
                    ),

                // Статус активности
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(), // Иконка да/нет

                // Дата создания
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Добавлен')
                    ->dateTime('d.m.Y') // Формат даты
                    ->sortable(),
            ])
            ->filters([
                // Фильтр по категории
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Категория')
                    ->relationship('category', 'name')
                    ->preload(),

                // Фильтр по статусу
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Статус')
                    ->trueLabel('Активные')
                    ->falseLabel('Скрытые'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // Кнопка редактирования
                Tables\Actions\DeleteAction::make(), // Кнопка удаления
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), // Массовое удаление
                ]),
            ])
            ->defaultSort('created_at', 'desc'); // Сортировка по дате (новые сверху)

    }

    /**
     * Определяет страницы ресурса и их маршруты
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'), // Список товаров
            'create' => Pages\CreateProduct::route('/create'), // Создание товара
            'edit' => Pages\EditProduct::route('/{record}/edit'), // Редактирование
        ];
    }
}