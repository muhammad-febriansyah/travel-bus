<?php

namespace App\Filament\Resources\HeroSlides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Slide')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Perjalanan Nyal'),

                        TextInput::make('subtitle')
                            ->label('Sub Judul')
                            ->maxLength(255)
                            ->placeholder('Contoh: 24/7 Customer Service'),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Deskripsi singkat tentang slide ini'),

                        TextInput::make('badge_text')
                            ->label('Teks Badge')
                            ->maxLength(255)
                            ->placeholder('Contoh: #1 Layanan Travel Terpercaya'),

                        FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->disk('public')
                            ->directory('hero-slides')
                            ->maxSize(5048)
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Tombol Aksi')
                    ->schema([
                        TextInput::make('primary_button_text')
                            ->label('Teks Tombol Utama')
                            ->maxLength(255)
                            ->placeholder('Contoh: Lihat Rute Perjalanan'),

                        TextInput::make('primary_button_url')
                            ->label('URL Tombol Utama')
                            ->maxLength(255)
                            ->placeholder('Contoh: #routes'),

                        TextInput::make('secondary_button_text')
                            ->label('Teks Tombol Kedua')
                            ->maxLength(255)
                            ->placeholder('Contoh: Hubungi Kami'),

                        TextInput::make('secondary_button_url')
                            ->label('URL Tombol Kedua')
                            ->maxLength(255)
                            ->placeholder('Contoh: #contact'),
                    ])
                    ->columns(2),

                Section::make('Rating & Pengaturan')
                    ->schema([
                        TextInput::make('rating_text')
                            ->label('Teks Rating')
                            ->maxLength(255)
                            ->placeholder('Contoh: Kepuasan Pelanggan'),

                        TextInput::make('rating_value')
                            ->label('Nilai Rating')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(5)
                            ->step(0.1)
                            ->placeholder('Contoh: 4.9'),

                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Urutan slide dari kecil ke besar'),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Hanya slide aktif yang ditampilkan'),
                    ])
                    ->columns(2),
            ]);
    }
}
