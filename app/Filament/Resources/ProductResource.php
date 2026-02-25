<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Товары';
    protected static ?string $modelLabel = 'Товар';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Основная информация')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Название')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, Forms\Set $set) =>
                            $set('slug', Str::slug($state) . '-' . time())),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug (URL)')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\Select::make('category_id')
                        ->label('Категория')
                        ->relationship('category', 'name')
                        ->preload()
                        // ->options(Category::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\Textarea::make('description')
                        ->label('Описание')
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Изображение')
                ->schema([
                    // ↓ ЗАГРУЗКА КАРТИНКИ
                    Forms\Components\FileUpload::make('image')
                        ->label('Фото товара')
                        ->image()
                        ->disk('public')           // ← явно указать диск
                        ->directory('products')  // хранить в storage/app/public/products
                        ->visibility('public')
                        ->maxSize(2048)              // максимум 2MB
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/webp',
                        ])
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Цена и склад')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('Цена (₽)')
                        ->numeric()
                        ->prefix('₽')
                        ->required(),

                    Forms\Components\TextInput::make('old_price')
                        ->label('Старая цена (₽)')
                        ->numeric()
                        ->prefix('₽'),

                    Forms\Components\TextInput::make('stock')
                        ->label('Кол-во на складе')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Активен (виден на сайте)')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(10) // ← показывать по 10 записей
            ->paginationPageOptions([10, 25, 50])
            ->modifyQueryUsing(fn($query) => $query->with('category'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                // ↓ ПРЕВЬЮ КАРТИНКИ В ТАБЛИЦЕ
                Tables\Columns\ImageColumn::make('image')
                    ->label('Фото')
                    ->disk('public')
                    ->width(80)
                    ->height(60)
                    ->defaultImageUrl('https://via.placeholder.com/80x60?text=No+Image'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->formatStateUsing(
                        fn($state) =>
                        number_format((float) $state, 0, '.', ' ') . ' ₽'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Склад')
                    ->sortable()
                    ->color(
                        fn($record) =>
                        $record->stock > 0 ? 'success' : 'danger'
                    ),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Добавлен')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Категория')
                    ->relationship('category', 'name')
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Статус')
                    ->trueLabel('Активные')
                    ->falseLabel('Скрытые'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}