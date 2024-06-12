<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Support\Markdown;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Faker\Provider\ar_EG\Text;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Forms\Set;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Product Information')->schema([
                        Forms\Components\TextInput::make('name')->label('Name')
                        ->required()
                        ->placeholder('Enter the product name')
                        ->maxLength(255)
                        ->live(onBlur:true)
                        ->afterStateUpdated(function (Set $set, $state) {
                            $set('slug', Str::slug($state));
                        }),
                        
                        Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->dehydrated()

                        ->disabled(),
                        
                        MarkdownEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->placeholder('Enter the product description')
                            ->required()
                            ->fileAttachmentsDirectory('products'),
                    ])->columns(2),

                    Section::make('Images')->schema([
                        FileUpload::make('images')
                            ->label('Images')
                            ->multiple()
                            ->maxFiles(5)
                            ->reorderable()
                            ->directory('products')
                    ])

                ])->columnSpan(2),

            
            Group::make()->schema([
                Section::make('Price')->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('Price')
                        ->required()
                        ->placeholder('Enter the product price')
                        ->prefix('$')
                        ->numeric()
                ]),
                Section::make('Associations')->schema([
                    Select::make('category_id')
                        ->label('Category')
                        ->searchable()
                        ->preload()
                        ->relationship('category', 'name')
                        ->required(),
                
                    Select::make('brand_id')
                        ->label('Brand')
                        ->searchable()
                        ->preload()
                        ->relationship('brand', 'name')
                        ->required()
                ]),
                Section::make('Status')->schema([
                    Toggle::make('in_stock')
                        ->required()
                        ->label('In Stock')
                        ->default(true),
                    
                    Toggle::make('is_active')
                        ->required()
                        ->label('Is Active')
                        ->default(true),
                    
                    Toggle::make('on_sale')
                        ->required()
                        ->label('On Sale')
                        ->default(false),

                    Toggle::make('is_featured')
                        ->required()
                        ->label('Is Featured')
                        ->default(false),
                    
                ])
                
            ])->columnSpan(1),
            
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                TextColumn::make('name')
                    ->searchable()
                    ->label('Name'),
                
                TextColumn::make('price')
                    ->sortable()
                    ->prefix('$')
                    ->label('Price'),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Is Active')
                    ->sortable()
                    ->boolean(),
                IconColumn::make('in_stock')
                    ->label('In Stock')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('on_sale')
                    ->label('On Sale')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Is Featured')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->sortable(),

            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name'),

                Filter::make('is_featured')
                    ->toggle()
                    ->label('Is Featured'),
                Filter::make('is_active')
                    ->toggle()
                    ->label('Is Active'),
                Filter::make('in_stock')
                    ->toggle()
                    ->label('In Stock'),
                Filter::make('on_sale') 
                    ->toggle()
                    ->label('On Sale'),

            ])->filtersFormMaxHeight('350px')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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