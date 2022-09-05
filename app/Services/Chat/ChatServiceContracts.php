<?php
declare(strict_types=1);

namespace App\Services\Chat;

use App\Http\Requests\Api\Chats\StoreMessageRequest;

interface ChatServiceContracts
{
    /**
     * Send the message to another user
     *
     * @param int $chatRoomId
     * @param StoreMessageRequest $request
     * @return void
     */
    public function sendMessage(int $chatRoomId, StoreMessageRequest $request): void;

    public function downloadFileAsAZip($file): string;

    public function downloadAllFileAsAZip($files): string;
}
