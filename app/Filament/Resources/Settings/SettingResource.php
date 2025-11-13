<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\EditSettings;
use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | \UnitEnum | null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $modelLabel = 'Pengaturan Website';

    protected static ?int $navigationSort = 999;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('Informasi Website')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Informasi Dasar')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Nama Website')
                                            ->placeholder('Contoh: Travel Bisnis')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        TextInput::make('email')
                                            ->label('Email')
                                            ->placeholder('Contoh: info@travelbisnis.com')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('phone')
                                            ->label('Nomor Telepon')
                                            ->placeholder('Contoh: 081234567890')
                                            ->tel()
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('address')
                                            ->label('Alamat')
                                            ->placeholder('Contoh: Jl. Raya No. 123, Jakarta')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Textarea::make('google_maps_embed')
                                            ->label('Google Maps Embed URL')
                                            ->placeholder('Contoh: https://www.google.com/maps/embed?pb=...')
                                            ->helperText('Paste URL embed dari Google Maps. Buka Google Maps → Bagikan → Sematkan peta → Salin HTML, ambil bagian src="..."')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        TextInput::make('keyword')
                                            ->label('Kata Kunci (SEO)')
                                            ->placeholder('Contoh: travel, bus, sewa bus, rental bus')
                                            ->helperText('Pisahkan dengan koma untuk multiple keyword')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Textarea::make('description')
                                            ->label('Deskripsi Website')
                                            ->placeholder('Contoh: Layanan travel dan rental bus terpercaya...')
                                            ->helperText('Deskripsi untuk SEO (maks 160 karakter)')
                                            ->maxLength(500)
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),

                                Section::make('Logo Website')
                                    ->schema([
                                        FileUpload::make('logo')
                                            ->label('Logo')
                                            ->image()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('settings')
                                            ->maxSize(4096)
                                            ->helperText('Format: JPG, PNG. Maksimal 4MB')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make('Hero Section')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make('Konten Hero')
                                    ->schema([
                                        TextInput::make('hero_badge')
                                            ->label('Badge Hero')
                                            ->placeholder('Contoh: #1 Layanan Travel Terpercaya')
                                            ->maxLength(255)
                                            ->helperText('Teks badge yang ditampilkan di atas judul')
                                            ->columnSpanFull(),

                                        TextInput::make('hero_title')
                                            ->label('Judul Hero')
                                            ->placeholder('Contoh: Perjalanan Nyaman, Aman & Terpercaya')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Textarea::make('hero_subtitle')
                                            ->label('Subjudul Hero')
                                            ->placeholder('Contoh: Layanan travel dan rental bus dengan armada modern...')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        FileUpload::make('hero_image')
                                            ->label('Gambar Hero')
                                            ->image()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('hero')
                                            ->maxSize(4096)
                                            ->helperText('Format: JPG, PNG. Maksimal 4MB. Ukuran disarankan: 1200x800px')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Statistik Hero')
                                    ->description('Tampilkan statistik di hero section')
                                    ->schema([
                                        Repeater::make('hero_stats')
                                            ->label('Statistik')
                                            ->schema([
                                                TextInput::make('number')
                                                    ->label('Angka')
                                                    ->placeholder('Contoh: 10000')
                                                    ->required(),
                                                TextInput::make('suffix')
                                                    ->label('Suffix')
                                                    ->placeholder('Contoh: +')
                                                    ->maxLength(5),
                                                TextInput::make('label')
                                                    ->label('Label')
                                                    ->placeholder('Contoh: Penumpang')
                                                    ->required(),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make('Features')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make('Fitur & Layanan')
                                    ->description('Kelola fitur dan layanan yang ditampilkan')
                                    ->schema([
                                        Repeater::make('features')
                                            ->label('Daftar Fitur')
                                            ->schema([
                                                TextInput::make('icon')
                                                    ->label('Icon (Lucide)')
                                                    ->placeholder('Contoh: Shield, Clock, Star')
                                                    ->helperText('Nama icon dari Lucide React')
                                                    ->required(),
                                                TextInput::make('title')
                                                    ->label('Judul')
                                                    ->placeholder('Contoh: Keamanan Terjamin')
                                                    ->required(),
                                                Textarea::make('description')
                                                    ->label('Deskripsi')
                                                    ->placeholder('Contoh: Armada dilengkapi dengan asuransi...')
                                                    ->rows(2)
                                                    ->required(),
                                                TextInput::make('rating')
                                                    ->label('Rating')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->maxValue(5)
                                                    ->step(0.1)
                                                    ->placeholder('4.8'),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Link Social Media')
                                    ->description('Kelola link social media Anda')
                                    ->schema([
                                        TextInput::make('facebook_url')
                                            ->label('Facebook')
                                            ->url()
                                            ->placeholder('https://facebook.com/username')
                                            ->prefixIcon('heroicon-o-link')
                                            ->columnSpanFull(),

                                        TextInput::make('instagram_url')
                                            ->label('Instagram')
                                            ->url()
                                            ->placeholder('https://instagram.com/username')
                                            ->prefixIcon('heroicon-o-link')
                                            ->columnSpanFull(),

                                        TextInput::make('twitter_url')
                                            ->label('Twitter / X')
                                            ->url()
                                            ->placeholder('https://twitter.com/username')
                                            ->prefixIcon('heroicon-o-link')
                                            ->columnSpanFull(),

                                        TextInput::make('whatsapp_number')
                                            ->label('WhatsApp')
                                            ->tel()
                                            ->placeholder('628123456789 (tanpa +)')
                                            ->helperText('Format: 628xxx tanpa tanda + atau spasi')
                                            ->prefixIcon('heroicon-o-phone')
                                            ->columnSpanFull(),

                                        TextInput::make('youtube_url')
                                            ->label('YouTube')
                                            ->url()
                                            ->placeholder('https://youtube.com/@username')
                                            ->prefixIcon('heroicon-o-link')
                                            ->columnSpanFull(),

                                        TextInput::make('tiktok_url')
                                            ->label('TikTok')
                                            ->url()
                                            ->placeholder('https://tiktok.com/@username')
                                            ->prefixIcon('heroicon-o-link')
                                            ->columnSpanFull(),

                                        TextInput::make('linkedin_url')
                                            ->label('LinkedIn')
                                            ->url()
                                            ->placeholder('https://linkedin.com/company/name')
                                            ->prefixIcon('heroicon-o-link')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => EditSettings::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
