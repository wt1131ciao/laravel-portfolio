<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $tickets = [
            [
                'name'        => 'Spring Music Festival 2026 - 一般チケット',
                'description' => '春の野外音楽フェスティバル。国内外のアーティストが多数出演。芝生エリアでお楽しみいただけます。',
                'price'       => 8000,
                'stock'       => 200,
                'image_url'   => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Spring Music Festival 2026 - VIPチケット',
                'description' => 'VIPエリア入場権・限定グッズ・バックステージツアー付きプレミアムチケット。',
                'price'       => 25000,
                'stock'       => 20,
                'image_url'   => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'テクノロジーカンファレンス 2026',
                'description' => 'AI・Web3・クラウド技術をテーマにした一日カンファレンス。著名エンジニアによる講演・ハンズオンワークショップ。',
                'price'       => 5000,
                'stock'       => 150,
                'image_url'   => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'コメディライブ Night Vol.12',
                'description' => '人気お笑い芸人によるスペシャルライブ。笑いあり涙ありの夜をお楽しみください。',
                'price'       => 3500,
                'stock'       => 80,
                'image_url'   => null,
                'is_active'   => true,
            ],
            [
                'name'        => '東京アートエキシビション 2026 - 入場券',
                'description' => '現代アートの祭典。国内外50名以上のアーティストの作品を展示。期間限定カタログ付き。',
                'price'       => 1500,
                'stock'       => 0,
                'image_url'   => null,
                'is_active'   => true,
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }
    }
}
