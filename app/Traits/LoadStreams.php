<?php

namespace App\Traits;

use App\Integrations\Twitch;

define('TWITCH_PAGE_SIZE', 100);

trait LoadStreams
{
    public static function loadStreams(
        bool $isFollowed, int $totalRecords = 100, array $output = [], string $cursor = "", bool $fetchAll = false
    )
    {
        try {
            $remaining = $totalRecords - count($output);
            if ($remaining <= 0) {
                return $output;
            }
            $page_size = ($remaining <= TWITCH_PAGE_SIZE) ? $remaining : TWITCH_PAGE_SIZE;
            $response = Twitch::loadStreams($page_size, $cursor);

            $content = $response->json();
            if (!empty($content['data'])) {
                $count_unique = 0;
                foreach ($content['data'] as $datum) {
                    $stream_id = sprintf("%s-%s", $datum['user_id'], $datum['game_id']);
                    if (!empty($stream_id) && !isset($output[$stream_id])) {
                        $datum['stream_id'] = $stream_id;
                        $output[$stream_id] = $datum;
                        $count_unique++;
                    }
                }
            }

            $row_count = count($output);
            if (($row_count < $totalRecords || $fetchAll) && isset($content['pagination']) && !empty($content['pagination']['cursor'])) {
                $cursor = $content['pagination']['cursor'];
                $output = self::loadStreams($isFollowed, $totalRecords, $output, $cursor, $fetchAll);
            }

            return $output;

        } catch (\Exception $exception) {
        }
    }
}
