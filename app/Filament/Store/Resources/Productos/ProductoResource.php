<?php

namespace App\Filament\Store\Resources\Productos;

use App\Models\Producto;
use BackedEnum;
use Filament\Actions\DeleteAction as ActionsDeleteAction;
use Filament\Actions\EditAction as ActionsEditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'pro_nombre';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pro_codigo')
                    ->label('Código')
                    ->required(),

                TextInput::make('pro_nombre')
                    ->label('Nombre')
                    ->required(),

                TextInput::make('pro_marca')
                    ->label('Marca')
                    ->required(),

                Textarea::make('pro_detalles')
                    ->label('Descripción')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('pro_precio_prove')
                    ->label('Precio de proveedor')
                    ->required()
                    ->numeric()
                    ->prefix('$'),

                TextInput::make('pro_precio_venta')
                    ->label('Precio de venta')
                    ->required()
                    ->numeric()
                    ->prefix('$'),

                Select::make('pro_estado')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ])
                    ->default(1)
                    ->required(),

                Hidden::make('pro_fk_tienda')
                    ->default(fn() => session('store_tienda_id'))
                    ->required(),

                Select::make('pro_fk_categoria')
                    ->label('Categoría')
                    ->relationship(
                        name: 'categoria_producto',
                        titleAttribute: 'cat_nombre',
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Toggle::make('registrar_stock')
                    ->label('Registrar stock')
                    ->helperText('Activa esta opción si deseas llevar control de inventario para este producto.')
                    ->default(false)
                    ->live()
                    ->columnSpanFull(),

                TextInput::make('stock_inicial')
                    ->label('Stock inicial')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->visible(fn ($get) => $get('registrar_stock'))
                    ->required(fn ($get) => $get('registrar_stock')),

                TextInput::make('stock_minimo')
                    ->label('Stock mínimo')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->visible(fn ($get) => $get('registrar_stock'))
                    ->required(fn ($get) => $get('registrar_stock')),

                FileUpload::make('foto_producto')
                    ->label('Foto del producto')
                    ->image()
                    ->disk('public')
                    ->directory('fotoproductos')
                    ->visibility('public')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_principal')
                    ->label('Foto')
                    ->disk('public')
                    ->visibility('public'),

                TextColumn::make('pro_nombre')
                    ->label('Nombre')
                    ->searchable(),

                TextColumn::make('pro_marca')
                    ->label('Marca')
                    ->searchable(),

                TextColumn::make('pro_precio_prove')
                    ->label('Precio de proveedor')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('pro_precio_venta')
                    ->label('Precio de venta')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('pro_estado')
                    ->label('Estado')
                    ->formatStateUsing(fn($state) => $state ? 'Activo' : 'Inactivo')
                    ->sortable(),

                TextColumn::make('tienda.tie_nombre')
                    ->label('Tienda')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('categoria_producto.cat_nombre')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                ActionsEditAction::make(),
                ActionsDeleteAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        $tiendaId = session('store_tienda_id');

        if (! $tiendaId) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        $pertenece = $user->tiendas()
            ->where('tie_id', $tiendaId)
            ->exists();

        if (! $pertenece) {
            abort(403, 'La tienda seleccionada no te pertenece.');
        }

        return parent::getEloquentQuery()
            ->where('pro_fk_tienda', $tiendaId);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Store\Resources\Productos\Pages\ListProductos::route('/'),
            'create' => \App\Filament\Store\Resources\Productos\Pages\CreateProducto::route('/create'),
            'edit' => \App\Filament\Store\Resources\Productos\Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
